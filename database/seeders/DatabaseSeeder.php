<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Setting;
use App\Models\Service;
use App\Models\Page;
use App\Models\PageSection;
use App\Models\ContentBlock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed default superadmin if not exists
        if (!User::where('email', 'admin@optervision.ro')->exists()) {
            User::create([
                'name' => 'Optera SuperAdmin',
                'email' => 'admin@optervision.ro',
                'password' => Hash::make('opteravision2026'),
                'role' => 'superadmin',
            ]);
        }

        // 2. Seed dynamic settings
        $settings = [
            // Site and branding settings
            'site.name' => ['value' => 'Optera Vision', 'group' => 'branding'],
            'site.description' => ['value' => 'Sisteme de supraveghere video profesionale în Câmpulung Moldovenesc și Bucovina.', 'group' => 'branding'],
            'brand.primary_color' => ['value' => '#0F3D24', 'group' => 'branding'], // curator dark green accent
            'brand.secondary_color' => ['value' => '#164E2D', 'group' => 'branding'],
            'brand.accent_color' => ['value' => '#4ADE80', 'group' => 'branding'],
            'brand.logo' => ['value' => null, 'group' => 'branding'],
            'brand.favicon' => ['value' => null, 'group' => 'branding'],
            'brand.footer_logo' => ['value' => null, 'group' => 'branding'],
            'brand.anpc_link' => ['value' => 'https://anpc.ro/', 'group' => 'branding'],
            'brand.sol_link' => ['value' => 'https://ec.europa.eu/consumers/odr/', 'group' => 'branding'],

            // Company information settings
            'company.name' => ['value' => 'Optera Vision S.R.L.', 'group' => 'company'],
            'company.cui' => ['value' => 'RO51234567', 'group' => 'company'],
            'company.reg_number' => ['value' => 'J33/987/2026', 'group' => 'company'],
            'company.address' => ['value' => 'Strada Trandafirilor Nr. 12, Câmpulung Moldovenesc, Suceava, România', 'group' => 'company'],
            'company.phone' => ['value' => '+40 740 123 456', 'group' => 'company'],
            'company.whatsapp' => ['value' => '+40740123456', 'group' => 'company'],
            'company.email' => ['value' => 'office@optervision.ro', 'group' => 'company'],
            'company.schedule' => ['value' => 'Luni - Vineri: 08:00 - 17:00, Sâmbătă: 09:00 - 13:00', 'group' => 'company'],
            'company.served_area' => ['value' => 'Câmpulung Moldovenesc, Suceava, Gura Humorului, Vatra Dornei, Rădăuți și zonele limitrofe din Bucovina.', 'group' => 'company'],

            // SMTP configuration settings
            'smtp.host' => ['value' => 'mail.optervision.ro', 'group' => 'smtp'],
            'smtp.port' => ['value' => '465', 'group' => 'smtp'],
            'smtp.username' => ['value' => 'no-reply@optervision.ro', 'group' => 'smtp'],
            'smtp.password' => ['value' => 'smtp_password_placeholder', 'group' => 'smtp'],
            'smtp.encryption' => ['value' => 'ssl', 'group' => 'smtp'],
            'smtp.from_address' => ['value' => 'no-reply@optervision.ro', 'group' => 'smtp'],
            'smtp.from_name' => ['value' => 'Optera Vision Web', 'group' => 'smtp'],
            'smtp.admin_email' => ['value' => 'admin@optervision.ro', 'group' => 'smtp'],

            // SEO settings
            'seo.default_title' => ['value' => 'Sisteme Supraveghere Video Câmpulung Moldovenesc | Optera Vision', 'group' => 'seo'],
            'seo.default_description' => ['value' => 'Instalare camere de supraveghere video de înaltă rezoluție (interior/exterior) în Câmpulung Moldovenesc, Suceava și Bucovina. Montaj impecabil, service și configurare de pe telefon.', 'group' => 'seo'],
            'seo.default_og_image' => ['value' => null, 'group' => 'seo'],
            'seo.robots_txt' => ['value' => "User-agent: *\nAllow: /\nSitemap: https://optervision.ro/sitemap.xml", 'group' => 'seo'],
            'seo.local_business_schema' => ['value' => '', 'group' => 'seo'],
        ];

        foreach ($settings as $key => $data) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $data['value'], 'group' => $data['group']]
            );
        }

        // 3. Seed services (Active Surveillance ones + Inactive future expansion drafts)
        $services = [
            // Active Services
            [
                'title' => 'Camere supraveghere interior',
                'short_description' => 'Monitorizare completă și discretă a spațiilor interioare.',
                'full_description' => 'Soluții profesionale pentru monitorizarea spațiilor de locuit, birourilor sau halelor comerciale. Utilizăm echipamente cu senzori performanți pentru condiții de lumină scăzută și rezoluții superioare.',
                'icon_key' => 'home',
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'title' => 'Camere supraveghere exterior',
                'short_description' => 'Protecție perimetrală rezistentă la intemperii și night-vision.',
                'full_description' => 'Sisteme de securitate exterioară adaptate pentru rezistență crescută la ploaie, zăpadă și temperaturi extreme. Tehnologii infraroșu avansate sau color-night pentru vizibilitate completă noaptea.',
                'icon_key' => 'shield',
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 2,
            ],
            [
                'title' => 'Sisteme DVR / NVR',
                'short_description' => 'Unități de înregistrare digitale și IP ultra-rapide.',
                'full_description' => 'Configurăm și instalăm recordere digitale (DVR) sau recordere de rețea IP (NVR) cu stocare securizată, criptare avansată și back-up securizat al datelor video.',
                'icon_key' => 'hard-drive',
                'status' => 'published',
                'is_featured' => false,
                'sort_order' => 3,
            ],
            [
                'title' => 'Acces de pe telefon',
                'short_description' => 'Vizualizare în timp real de pe mobil sau tabletă.',
                'full_description' => 'Configurăm conexiunea la internet a sistemului de securitate și aplicațiile mobile dedicate pe sistemele Android sau iOS, permițându-ți să vezi live și să derulezi înregistrările de oriunde din lume.',
                'icon_key' => 'smartphone',
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 4,
            ],
            [
                'title' => 'Cablare și configurare',
                'short_description' => 'Infrastructură cablată curată și mascată estetic.',
                'full_description' => 'Cablare structurată profesională folosind cablu FTP/UTP sau coaxial de înaltă calitate. Configurăm echipamentele de rețea, routerele și switch-urile PoE pentru stabilitate maximă și zero interferențe.',
                'icon_key' => 'cpu',
                'status' => 'published',
                'is_featured' => false,
                'sort_order' => 5,
            ],
            [
                'title' => 'Mentenanță',
                'short_description' => 'Verificări periodice și curățarea senzorilor video.',
                'full_description' => 'Mentenanță tehnică completă pentru sisteme video: curățarea lentilelor, calibrarea unghiurilor, verificarea hard-disk-urilor, update de firmware și back-up preventiv al stocării.',
                'icon_key' => 'tool',
                'status' => 'published',
                'is_featured' => false,
                'sort_order' => 6,
            ],
            [
                'title' => 'Service și upgrade sisteme existente',
                'short_description' => 'Diagnosticare defecțiuni și înlocuire echipamente vechi.',
                'full_description' => 'Dacă ai deja un sistem analogic vechi sau camere care nu se mai văd clar, diagnosticăm problemele și realizăm upgrade-uri la tehnologii IP de ultimă generație păstrând parțial cablarea existentă pentru costuri optimizate.',
                'icon_key' => 'refresh-cw',
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 7,
            ],

            // Future Expansion Services - Draft Status (Hidden publicly)
            [
                'title' => 'Automatizări porți',
                'short_description' => 'Sisteme de automatizare batante sau culisante.',
                'full_description' => 'Automatizări inteligente pentru porți rezidențiale sau industriale, cu control din telecomandă sau direct de pe smartphone.',
                'icon_key' => 'navigation',
                'status' => 'draft',
                'is_featured' => false,
                'sort_order' => 8,
            ],
            [
                'title' => 'Sisteme alarmă',
                'short_description' => 'Protecție anti-efracție cu senzori de mișcare wireless.',
                'full_description' => 'Centrale de alarmă conectate la dispecerat sau auto-apelatoare, cu senzori magnetici, senzori de prezență și sirene acustice de mare putere.',
                'icon_key' => 'bell',
                'status' => 'draft',
                'is_featured' => false,
                'sort_order' => 9,
            ],
            [
                'title' => 'Control acces',
                'short_description' => 'Pontaj electronic și acces securizat cu cartelă sau cod.',
                'full_description' => 'Sisteme de control al accesului pentru birouri și spații comerciale, yale electromagnetice, cititoare de cartelă sau biometrice.',
                'icon_key' => 'key',
                'status' => 'draft',
                'is_featured' => false,
                'sort_order' => 10,
            ],
            [
                'title' => 'Smart home',
                'short_description' => 'Integrare lumini, climatizare și senzori inteligenți.',
                'full_description' => 'Soluții complete de automatizare a locuinței, senzori de inundație, control inteligent al căldurii și luminilor, toate integrate într-o singură aplicație.',
                'icon_key' => 'cloud',
                'status' => 'draft',
                'is_featured' => false,
                'sort_order' => 11,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::updateOrCreate(
                ['title' => $serviceData['title']],
                $serviceData
            );
        }

        // 4. Seed legal CMS templates
        $legalPages = [
            [
                'title' => 'Termeni și condiții',
                'content' => '<h1>Termeni și Condiții</h1><p>Bun venit pe site-ul Optera Vision. Accesarea și utilizarea acestui site implică acceptarea deplină a termenilor și condițiilor noastre...</p><p>Serviciile noastre de supraveghere video sunt realizate în deplină conformitate cu legislația din România...</p>',
                'status' => 'published',
                'type' => 'legal',
            ],
            [
                'title' => 'Politică de confidențialitate / GDPR',
                'content' => '<h1>Politică de Confidențialitate (GDPR)</h1><p>Optera Vision se angajează să protejeze datele dumneavoastră cu caracter personal. Acest document explică modul în care colectăm, prelucrăm și protejăm datele transmise prin formularele noastre...</p>',
                'status' => 'published',
                'type' => 'legal',
            ],
            [
                'title' => 'Politică cookies',
                'content' => '<h1>Politică de utilizare Cookie-uri</h1><p>Site-ul optervision.ro folosește cookie-uri pentru a asigura o experiență optimă de navigare și pentru a analiza traficul...</p>',
                'status' => 'published',
                'type' => 'legal',
            ],
        ];

        foreach ($legalPages as $pageData) {
            Page::updateOrCreate(
                ['title' => $pageData['title']],
                $pageData
            );
        }

        // 5. Seed Homepage Page for Dynamic Block Architecture
        $homePage = Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'title' => 'Homepage',
                'status' => 'published',
                'type' => 'custom',
                'meta_title' => 'Sisteme Supraveghere Video Câmpulung Moldovenesc | Optera Vision',
                'meta_description' => 'Instalare camere de supraveghere video profesionale în Câmpulung Moldovenesc și Bucovina. Montaj curat, service și configurare de pe telefon.',
                'slug_locked' => true,
            ]
        );

        // Clear existing sections under home to re-seed cleanly
        $homePage->sections()->delete();

        // 5a. Hero Section
        $heroSec = PageSection::create([
            'page_id' => $homePage->id,
            'section_key' => 'hero',
            'sort_order' => 1,
        ]);
        ContentBlock::create([
            'page_section_id' => $heroSec->id,
            'block_key' => 'hero_headline',
            'content' => [
                'title' => 'Sisteme de supraveghere video pentru locuințe și afaceri',
                'subtitle' => 'Montaj curat, configurare completă și acces de pe telefon pentru clienți din Câmpulung Moldovenesc și zonele apropiate.',
                'cta_text' => 'Solicită ofertă',
                'phone_text' => 'Sună acum',
            ],
            'sort_order' => 1,
        ]);

        // 5b. Trust Cards Section
        $trustSec = PageSection::create([
            'page_id' => $homePage->id,
            'section_key' => 'trust_cards',
            'sort_order' => 2,
        ]);
        $cards = [
            ['title' => 'Montaj profesionist', 'description' => 'Cablare ordonată, mascată estetic și configurare certificată a echipamentelor.', 'icon' => 'wrench'],
            ['title' => 'Acces mobil', 'description' => 'Vizualizezi în timp real imagini de înaltă rezoluție de pe telefon sau tabletă, de oriunde.', 'icon' => 'smartphone'],
            ['title' => 'Service & mentenanță', 'description' => 'Suport tehnic rapid, diagnosticare și intervenții prompte în caz de defecțiuni.', 'icon' => 'activity'],
            ['title' => 'Upgrade sisteme', 'description' => 'Înlocuire DVR/NVR vechi și integrare de camere noi cu rezoluții superioare.', 'icon' => 'refresh-cw'],
        ];
        foreach ($cards as $idx => $card) {
            ContentBlock::create([
                'page_section_id' => $trustSec->id,
                'block_key' => 'trust_card_item',
                'content' => $card,
                'sort_order' => $idx + 1,
            ]);
        }

        // 5c. Services Preview Heading
        $servicesSec = PageSection::create([
            'page_id' => $homePage->id,
            'section_key' => 'services_preview',
            'sort_order' => 3,
        ]);
        ContentBlock::create([
            'page_section_id' => $servicesSec->id,
            'block_key' => 'section_header',
            'content' => [
                'title' => 'Servicii Profesionale de Supraveghere',
                'subtitle' => 'Soluții personalizate adaptate perfect nevoilor tale',
            ],
            'sort_order' => 1,
        ]);

        // 5d. Local SEO Content Block
        $seoSec = PageSection::create([
            'page_id' => $homePage->id,
            'section_key' => 'local_seo_block',
            'sort_order' => 4,
        ]);
        ContentBlock::create([
            'page_section_id' => $seoSec->id,
            'block_key' => 'seo_text_block',
            'content' => [
                'title' => 'Securitate garantată în Câmpulung Moldovenesc și toată Bucovina',
                'body' => 'Fie că ai nevoie de camere de supraveghere video pentru o locuință privată în Câmpulung Moldovenesc, sau de o infrastructură complexă de monitorizare pentru un spațiu comercial din județul Suceava, Optera Vision îți oferă echipamente DVR/NVR moderne și servicii complete de service, mentenanță și cablare structurată.',
                'link_text' => 'Află mai multe despre noi',
            ],
            'sort_order' => 1,
        ]);

        // 5e. Final CTA Footer Block
        $ctaSec = PageSection::create([
            'page_id' => $homePage->id,
            'section_key' => 'cta_footer',
            'sort_order' => 5,
        ]);
        ContentBlock::create([
            'page_section_id' => $ctaSec->id,
            'block_key' => 'cta_banner',
            'content' => [
                'title' => 'Vrei să îți securizezi proprietatea?',
                'subtitle' => 'Cere o ofertă personalizată sau sună-ne direct pentru o evaluare gratuită la fața locului în Câmpulung Moldovenesc și localitățile învecinate.',
                'button_text' => 'Solicită ofertă gratuită',
            ],
            'sort_order' => 1,
        ]);

        // 6. Seed Projects and Project Images
        $projectsList = [
            [
                'title' => 'Sistem Supraveghere Pensiune Bucovina',
                'category' => 'Comercial',
                'locality' => 'Câmpulung Moldovenesc',
                'short_description' => 'Instalare sistem de monitorizare video IP cu 12 camere 4K și NVR performant pentru o pensiune de top.',
                'full_description' => 'Am proiectat și instalat un sistem de monitorizare complet pentru o pensiune tradițională din Câmpulung Moldovenesc. Sistemul include 12 camere IP de rezoluție 8MP (4K) echipate cu senzori Smart Dual Light care luminează cu lumină caldă albă la detecția mișcării. Cablarea a fost complet mascată în structura de lemn a pensiunii, păstrând estetica intactă. NVR-ul cu 16 canale a fost configurat pentru o stocare securizată de 30 de zile și acces de pe telefoanele administratorilor.',
                'featured_image' => 'assets/projects/project1.jpg',
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 1,
                'meta_title' => 'Sistem Supraveghere Pensiune Câmpulung Moldovenesc | Optera',
                'meta_description' => 'Proiect de monitorizare video realizat la o pensiune turistică din Bucovina. 12 camere IP 4K instalate estetic în lemn.',
            ],
            [
                'title' => 'Monitorizare Video Depozit Materiale',
                'category' => 'Industrial',
                'locality' => 'Suceava',
                'short_description' => 'Sistem de securitate de înaltă rezoluție pentru un depozit industrial cu 8 camere de exterior.',
                'full_description' => 'Upgrade complet de la un sistem analogic defect la un sistem IP modern cu camere ColorVu de 5MP. Am reinstalat cablarea exterioară folosind tuburi de protecție rezistente la UV și am configurat detecția inteligentă a intruziunilor pe perimetrul depozitului din Suceava, trimițând alerte în timp real pe telefon.',
                'featured_image' => 'assets/projects/project2.jpg',
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 2,
                'meta_title' => 'Supraveghere Video Depozit Industrial Suceava | Optera',
                'meta_description' => 'Instalare 8 camere de exterior 5MP cu inteligență artificială pentru monitorizare perimetru depozit industrial.',
            ],
            [
                'title' => 'Sistem Rezidențial Vilă Smart',
                'category' => 'Rezidențial',
                'locality' => 'Vatra Dornei',
                'short_description' => 'Sistem cu 4 camere IP UltraHD montat estetic pe o locuință privată în Vatra Dornei.',
                'full_description' => 'Optera Vision a instalat un sistem de monitorizare rezidențial format din 4 camere IP UltraHD (4MP) pentru securizarea perimetrului unei case private. Echipamentele beneficiază de lentile super-angulare și microfon încorporat. Am realizat configurarea completă pentru accesarea camerelor de pe telefoanele proprietarilor și am securizat stocarea locală pe un HDD dedicat.',
                'featured_image' => 'assets/projects/project3.jpg',
                'status' => 'published',
                'is_featured' => true,
                'sort_order' => 3,
                'meta_title' => 'Sistem Camere Supraveghere Vilă Vatra Dornei | Optera',
                'meta_description' => 'Montaj camere video 4MP UltraHD la o locuință privată în Vatra Dornei. Vizualizare live pe telefon.',
            ]
        ];

        foreach ($projectsList as $projData) {
            $project = \App\Models\Project::updateOrCreate(
                ['title' => $projData['title']],
                $projData
            );

            // Seed dummy gallery images for the project details page
            $project->images()->delete();
            for ($i = 1; $i <= 3; $i++) {
                $project->images()->create([
                    'image_path' => "assets/projects/project_{$project->id}_img{$i}.jpg",
                    'sort_order' => $i
                ]);
            }
        }
    }
}
