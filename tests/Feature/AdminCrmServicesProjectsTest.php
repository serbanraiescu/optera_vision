<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Service;
use App\Models\Project;
use App\Models\ProjectImage;
use App\Models\ActivityLog;
use App\Helpers\HtmlSanitizer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AdminCrmServicesProjectsTest extends TestCase
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
     * Test role-based access restrictions for Services and Projects CMS.
     */
    public function test_role_based_access_restrictions_on_services_and_projects()
    {
        $rolesAllowed = [$this->superadmin, $this->admin, $this->operator];

        foreach ($rolesAllowed as $user) {
            $this->actingAs($user)->get(route('admin.services.index'))->assertStatus(200);
            $this->actingAs($user)->get(route('admin.services.create'))->assertStatus(200);
            $this->actingAs($user)->get(route('admin.projects.index'))->assertStatus(200);
            $this->actingAs($user)->get(route('admin.projects.create'))->assertStatus(200);
        }

        // Technicians are completely blocked
        $this->actingAs($this->technician)->get(route('admin.services.index'))->assertStatus(403);
        $this->actingAs($this->technician)->get(route('admin.services.create'))->assertStatus(403);
        $this->actingAs($this->technician)->get(route('admin.projects.index'))->assertStatus(403);
        $this->actingAs($this->technician)->get(route('admin.projects.create'))->assertStatus(403);
    }

    /**
     * Test Services full CRUD, auto-slug locking, XSS Sanitization, cache invalidation, sitemap invalidation, and auditing.
     */
    public function test_services_crud_workflow()
    {
        // Put something in the cache to check invalidation
        Cache::put('active_services_list', ['item1', 'item2']);
        Cache::put('sitemap_xml', '<xml>old sitemap</xml>');

        // 1. Create a service (POST)
        $serviceData = [
            'title' => 'Sistem Supraveghere Video Interior',
            'slug' => 'sistem-video-interior',
            'short_description' => 'Rezumatul serviciului',
            'full_description' => '<div>Instalare camere de supraveghere profesionale.</div><script>alert("hack")</script>',
            'icon_key' => 'home',
            'featured_image' => 'services/interior.jpg',
            'status' => 'published',
            'is_featured' => '1',
            'sort_order' => '3',
            'meta_title' => 'Meta Title Serviciu',
            'meta_description' => 'Meta Description Serviciu',
        ];

        $response = $this->actingAs($this->operator)->post(route('admin.services.store'), $serviceData);
        $response->assertRedirect(route('admin.services.index'));

        // Assert database entry exists with XSS stripped
        $this->assertDatabaseHas('services', [
            'title' => 'Sistem Supraveghere Video Interior',
            'slug' => 'sistem-video-interior',
            'short_description' => 'Rezumatul serviciului',
            'full_description' => '<div>Instalare camere de supraveghere profesionale.</div>',
            'icon_key' => 'home',
            'featured_image' => 'services/interior.jpg',
            'status' => 'published',
            'is_featured' => true,
            'sort_order' => 3,
            'meta_title' => 'Meta Title Serviciu',
            'meta_description' => 'Meta Description Serviciu',
        ]);

        $service = Service::where('slug', 'sistem-video-interior')->firstOrFail();

        // Assert cache got flushed
        $this->assertFalse(Cache::has('active_services_list'));
        $this->assertFalse(Cache::has('sitemap_xml'));

        // Assert audit log exists
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->operator->id,
            'action' => 'service_created',
        ]);

        // Repopulate cache to test updates
        Cache::put('active_services_list', ['item3']);
        Cache::put('sitemap_xml', '<xml>old sitemap</xml>');

        // 2. Edit & Update Service (PUT)
        $updatedData = [
            'title' => 'Sistem Supraveghere Video Interior Edit',
            'slug' => 'sistem-video-interior-editat',
            'short_description' => 'Rezumat actualizat',
            'full_description' => '<p>Instalare camere.</p>',
            'icon_key' => 'shield',
            'featured_image' => 'services/interior_new.jpg',
            'status' => 'draft',
            'sort_order' => '1',
            'meta_title' => 'Meta Edit',
            'meta_description' => 'Meta Desc Edit',
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.services.update', $service->id), $updatedData);
        $response->assertRedirect(route('admin.services.index'));

        // Assert database changes
        $this->assertDatabaseHas('services', [
            'id' => $service->id,
            'title' => 'Sistem Supraveghere Video Interior Edit',
            'slug' => 'sistem-video-interior-editat',
            'short_description' => 'Rezumat actualizat',
            'full_description' => '<p>Instalare camere.</p>',
            'icon_key' => 'shield',
            'featured_image' => 'services/interior_new.jpg',
            'status' => 'draft',
            'is_featured' => false, // unchecked is_featured defaults to false
            'sort_order' => 1,
        ]);

        // Assert cache got flushed again
        $this->assertFalse(Cache::has('active_services_list'));
        $this->assertFalse(Cache::has('sitemap_xml'));

        // Assert audit log for editing
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'action' => 'service_edited',
        ]);

        // Repopulate cache to test deletes
        Cache::put('active_services_list', ['item4']);
        Cache::put('sitemap_xml', '<xml>old sitemap</xml>');

        // 3. Delete Service (DELETE)
        $response = $this->actingAs($this->admin)->delete(route('admin.services.destroy', $service->id));
        $response->assertRedirect(route('admin.services.index'));

        // Assert soft delete
        $this->assertSoftDeleted('services', ['id' => $service->id]);

        // Assert cache got flushed on delete
        $this->assertFalse(Cache::has('active_services_list'));
        $this->assertFalse(Cache::has('sitemap_xml'));

        // Assert audit log for deleting
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'action' => 'service_deleted',
        ]);
    }

    /**
     * Test Projects CRUD, multi-image gallery insertions/reorder/deletion, XSS sanitization, sitemap invalidation, and auditing.
     */
    public function test_projects_crud_workflow()
    {
        Cache::put('sitemap_xml', '<xml>old sitemap</xml>');

        // 1. Create a Project (POST)
        $projectData = [
            'title' => 'Lucrare Pensiune Bucovina',
            'slug' => 'lucrare-pensiune-bucovina',
            'category' => 'Comercial',
            'locality' => 'Câmpulung Moldovenesc',
            'short_description' => 'Scurtă descriere a lucrării',
            'full_description' => '<div>Raport tehnic detaliat.</div><script>alert("hack")</script>',
            'featured_image' => 'projects/pensiune_cover.jpg',
            'status' => 'published',
            'is_featured' => '1',
            'sort_order' => '2',
            'meta_title' => 'Meta Title Proiect',
            'meta_description' => 'Meta Description Proiect',
            'gallery_images' => [
                'projects/pensiune_img1.jpg',
                'projects/pensiune_img2.jpg',
                'projects/pensiune_img3.jpg'
            ]
        ];

        $response = $this->actingAs($this->operator)->post(route('admin.projects.store'), $projectData);
        $response->assertRedirect(route('admin.projects.index'));

        // Assert database entry exists with XSS stripped
        $this->assertDatabaseHas('projects', [
            'title' => 'Lucrare Pensiune Bucovina',
            'slug' => 'lucrare-pensiune-bucovina',
            'category' => 'Comercial',
            'locality' => 'Câmpulung Moldovenesc',
            'short_description' => 'Scurtă descriere a lucrării',
            'full_description' => '<div>Raport tehnic detaliat.</div>',
            'featured_image' => 'projects/pensiune_cover.jpg',
            'status' => 'published',
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        $project = Project::where('slug', 'lucrare-pensiune-bucovina')->firstOrFail();

        // Assert gallery images were synced with correct sort order
        $this->assertCount(3, $project->images);
        $this->assertDatabaseHas('project_images', [
            'project_id' => $project->id,
            'image_path' => 'projects/pensiune_img1.jpg',
            'sort_order' => 0,
        ]);
        $this->assertDatabaseHas('project_images', [
            'project_id' => $project->id,
            'image_path' => 'projects/pensiune_img2.jpg',
            'sort_order' => 1,
        ]);
        $this->assertDatabaseHas('project_images', [
            'project_id' => $project->id,
            'image_path' => 'projects/pensiune_img3.jpg',
            'sort_order' => 2,
        ]);

        // Assert sitemap cache got flushed
        $this->assertFalse(Cache::has('sitemap_xml'));

        // Assert audit log exists
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->operator->id,
            'action' => 'project_created',
        ]);

        // Repopulate cache
        Cache::put('sitemap_xml', '<xml>old sitemap</xml>');

        // 2. Edit & Update Project - modifying, reordering, and deleting gallery images (PUT)
        $updatedData = [
            'title' => 'Lucrare Pensiune Bucovina Modificată',
            'slug' => 'lucrare-pensiune-bucovina-modificata',
            'category' => 'Rezidențial',
            'locality' => 'Vatra Dornei',
            'short_description' => 'Scurtă descriere actualizată',
            'full_description' => '<p>Raport tehnic finalizat.</p>',
            'featured_image' => 'projects/pensiune_cover_new.jpg',
            'status' => 'draft',
            'sort_order' => '5',
            'gallery_images' => [
                'projects/pensiune_img2.jpg', // reordered from original index 1 to new index 0
                'projects/pensiune_img4.jpg', // added new image
                'projects/pensiune_img3.jpg', // reordered from original index 2 to new index 2
                // projects/pensiune_img1.jpg is deleted implicitly since it's not present in this list
            ]
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.projects.update', $project->id), $updatedData);
        $response->assertRedirect(route('admin.projects.index'));

        // Assert project fields updated
        $this->assertDatabaseHas('projects', [
            'id' => $project->id,
            'title' => 'Lucrare Pensiune Bucovina Modificată',
            'slug' => 'lucrare-pensiune-bucovina-modificata',
            'category' => 'Rezidențial',
            'locality' => 'Vatra Dornei',
            'status' => 'draft',
            'is_featured' => false,
            'sort_order' => 5,
        ]);

        // Assert gallery images correctly re-synced, sorted and pruned
        $project = $project->fresh(['images']);
        $this->assertCount(3, $project->images);

        // check order
        $this->assertEquals('projects/pensiune_img2.jpg', $project->images[0]->image_path);
        $this->assertEquals(0, $project->images[0]->sort_order);

        $this->assertEquals('projects/pensiune_img4.jpg', $project->images[1]->image_path);
        $this->assertEquals(1, $project->images[1]->sort_order);

        $this->assertEquals('projects/pensiune_img3.jpg', $project->images[2]->image_path);
        $this->assertEquals(2, $project->images[2]->sort_order);

        // assert original first image was removed from database
        $this->assertDatabaseMissing('project_images', [
            'project_id' => $project->id,
            'image_path' => 'projects/pensiune_img1.jpg',
        ]);

        // Assert sitemap cache got flushed
        $this->assertFalse(Cache::has('sitemap_xml'));

        // Assert audit log for editing
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'action' => 'project_edited',
        ]);

        // Repopulate cache
        Cache::put('sitemap_xml', '<xml>old sitemap</xml>');

        // 3. Delete Project (DELETE)
        $response = $this->actingAs($this->admin)->delete(route('admin.projects.destroy', $project->id));
        $response->assertRedirect(route('admin.projects.index'));

        // Assert soft delete
        $this->assertSoftDeleted('projects', ['id' => $project->id]);

        // Assert sitemap cache got flushed on delete
        $this->assertFalse(Cache::has('sitemap_xml'));

        // Assert audit log for deleting
        $this->assertDatabaseHas('activity_logs', [
            'user_id' => $this->admin->id,
            'action' => 'project_deleted',
        ]);
    }
}
