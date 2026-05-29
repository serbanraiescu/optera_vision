<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Page;
use App\Models\Service;
use App\Models\Project;
use App\Models\Media;
use App\Models\Setting;
use App\Helpers\HtmlSanitizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AdminCrmSettingsTest extends TestCase
{
    use RefreshDatabase;

    protected $superadmin;
    protected $admin;
    protected $operator;
    protected $technician;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Create different test role users
        $this->superadmin = User::create([
            'name' => 'SuperAdmin Test',
            'email' => 'superadmin@optera.ro',
            'password' => bcrypt('password'),
            'role' => 'superadmin',
        ]);

        $this->admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@optera.ro',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $this->operator = User::create([
            'name' => 'Operator Test',
            'email' => 'operator@optera.ro',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        $this->technician = User::create([
            'name' => 'Technician Test',
            'email' => 'tech@optera.ro',
            'password' => bcrypt('password'),
            'role' => 'technician',
        ]);
    }

    /**
     * Test HTML Sanitizer safely strips dangerous scripts and attributes.
     */
    public function test_html_sanitizer_removes_dangerous_tags_and_attributes()
    {
        $dirty = '<div><script>alert("XSS")</script><p onclick="steal()">Hello</p><iframe src="malicious.html"></iframe><a href="javascript:alert(1)">Click</a></div>';
        $clean = HtmlSanitizer::sanitize($dirty);

        $this->assertStringNotContainsString('<script>', $clean);
        $this->assertStringNotContainsString('onclick', $clean);
        $this->assertStringNotContainsString('<iframe>', $clean);
        $this->assertStringNotContainsString('javascript:', $clean);
        $this->assertStringContainsString('<div>', $clean);
        $this->assertStringContainsString('<p>Hello</p>', $clean);
        $this->assertStringContainsString('href="#"', $clean);
    }

    /**
     * Test admin can access settings index and update settings.
     */
    public function test_admin_can_manage_settings_but_operator_restricted_from_branding_system()
    {
        // 1. Admin accesses settings: 200
        $response = $this->actingAs($this->admin)->get(route('admin.settings.index'));
        $response->assertStatus(200);

        // 2. Admin updates company group settings: 302 redirect back
        $updateCompany = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'group' => 'company',
            'company.phone' => '+40 799 999 999',
        ]);
        $updateCompany->assertRedirect();
        $this->assertEquals('+40 799 999 999', setting('company.phone'));

        // 3. Admin updates branding group settings: success
        $updateBranding = $this->actingAs($this->admin)->post(route('admin.settings.update'), [
            'group' => 'branding',
            'brand.primary_color' => '#FF0000',
        ]);
        $updateBranding->assertRedirect();
        $this->assertEquals('#FF0000', setting('brand.primary_color'));

        // 4. Operator tries to update branding settings: 403 Forbidden
        $operatorBrandingFail = $this->actingAs($this->operator)->post(route('admin.settings.update'), [
            'group' => 'branding',
            'brand.primary_color' => '#0000FF',
        ]);
        $operatorBrandingFail->assertStatus(403);

        // 5. Operator updates contact settings: success
        $operatorContactOk = $this->actingAs($this->operator)->post(route('admin.settings.update'), [
            'group' => 'contact',
            'contact.facebook' => 'https://facebook.com/optera',
        ]);
        $operatorContactOk->assertRedirect();
        $this->assertEquals('https://facebook.com/optera', setting('contact.facebook'));
    }

    /**
     * Test admin can clear cache and sitemap.
     */
    public function test_admin_can_clear_cache_and_trigger_sitemap_rebuild()
    {
        // Clear Cache
        $response = $this->actingAs($this->admin)->post(route('admin.system.cache.clear'));
        $response->assertRedirect();

        // Rebuild Sitemap
        $sitemapResponse = $this->actingAs($this->admin)->post(route('admin.system.sitemap.rebuild'));
        $sitemapResponse->assertRedirect();
    }

    /**
     * Test Page CRUD and Duplicate features.
     */
    public function test_pages_management_and_duplication()
    {
        $page = Page::create([
            'title' => 'Pagina Test',
            'slug' => 'pagina-test',
            'content' => '<p>Initial Content</p>',
            'status' => 'published',
            'type' => 'legal',
        ]);

        // Duplicate page
        $duplicateResponse = $this->actingAs($this->admin)->post(route('admin.pages.duplicate', $page->id));
        $duplicateResponse->assertRedirect();

        // A duplicate copy should exist in draft
        $this->assertDatabaseHas('pages', [
            'slug' => 'pagina-test-copie',
            'status' => 'draft',
            'type' => 'legal',
            'parent_id' => $page->id,
        ]);

        // Create page with malicious script gets sanitized
        $storeResponse = $this->actingAs($this->admin)->post(route('admin.pages.store'), [
            'title' => 'Page XSS',
            'slug' => 'page-xss',
            'content' => '<p>Safe Text</p><script>alert("hack")</script>',
            'status' => 'published',
            'type' => 'custom',
        ]);
        $storeResponse->assertRedirect();
        
        $savedPage = Page::where('slug', 'page-xss')->first();
        $this->assertNotNull($savedPage);
        $this->assertEquals('<p>Safe Text</p>', $savedPage->content);
    }

    /**
     * Test SEO cockpit metadata override and noindex triggers.
     */
    public function test_seo_cockpit_can_override_entity_metadata()
    {
        $service = Service::create([
            'title' => 'Serviciu Test',
            'slug' => 'serviciu-test',
            'short_description' => 'Desc',
            'full_description' => 'Full Desc',
            'icon_key' => 'home',
            'status' => 'published',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.seo.update', ['type' => 'service', 'id' => $service->id]), [
            'meta_title' => 'SEO Title Override',
            'meta_description' => 'SEO Description Override',
            'noindex' => '1',
        ]);

        $response->assertRedirect();
        
        $service = $service->fresh();
        $this->assertEquals('SEO Title Override', $service->meta_title);
        $this->assertEquals('SEO Description Override', $service->meta_description);
        $this->assertTrue($service->noindex);
    }

    /**
     * Test dynamic public sitemap and robots.txt behavior.
     */
    public function test_dynamic_sitemap_and_robots()
    {
        Cache::forget('sitemap_xml');

        // Create one indexed page and one noindexed page
        $indexedPage = Page::create([
            'title' => 'Pagina Indexata',
            'slug' => 'pagina-indexata',
            'content' => 'Content',
            'status' => 'published',
            'type' => 'legal',
            'noindex' => false,
        ]);

        $noindexedPage = Page::create([
            'title' => 'Pagina Noindex',
            'slug' => 'pagina-noindex',
            'content' => 'Content',
            'status' => 'published',
            'type' => 'legal',
            'noindex' => true,
        ]);

        // Get Sitemap
        $response = $this->get(route('sitemap'));
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=utf-8');
        $response->assertSee(route('pages.show', 'pagina-indexata'));
        $response->assertDontSee(route('pages.show', 'pagina-noindex'));

        // Get robots.txt
        $robotsResponse = $this->get(route('robots'));
        $robotsResponse->assertStatus(200);
        $robotsResponse->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $robotsResponse->assertSee('User-agent: *');
    }

    /**
     * Test Media uploader constraints and cleanup hooks.
     */
    public function test_media_upload_constraints_and_orphan_proofing()
    {
        Storage::fake('public');

        // 1. Upload valid image (using create mock to bypass local environment lacking GD extension)
        $file = UploadedFile::fake()->create('image.jpg', 10, 'image/jpeg');
        
        $response = $this->actingAs($this->admin)->post(route('admin.media.store'), [
            'file' => $file,
            'folder' => 'general',
        ]);

        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        $mediaId = $response->json('media.id');
        $media = Media::findOrFail($mediaId);

        // Verify file resides in storage
        Storage::disk('public')->assertExists($media->path);
        if ($media->thumbnail_path) {
            Storage::disk('public')->assertExists($media->thumbnail_path);
        }

        // 2. SVG file validation failure
        $svgFile = UploadedFile::fake()->create('vector.svg', 10, 'image/svg+xml');
        $svgResponse = $this->actingAs($this->admin)->post(route('admin.media.store'), [
            'file' => $svgFile,
            'folder' => 'general',
        ]);
        // Laravel's default file validation throws validation redirect on session requests or 302
        $svgResponse->assertStatus(302);

        // 3. Delete media deletes database and removes physical files from storage
        $media->delete();
        Storage::disk('public')->assertMissing($media->path);
        if ($media->thumbnail_path) {
            Storage::disk('public')->assertMissing($media->thumbnail_path);
        }
    }
}
