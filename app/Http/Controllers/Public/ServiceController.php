<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Support\Facades\Cache;

class ServiceController extends Controller
{
    /**
     * Display a listing of the active services.
     */
    public function index()
    {
        // Cache the list of active services to prevent database hits
        $services = Cache::remember('active_services_list', 3600, function () {
            return Service::published()->orderBy('sort_order')->get();
        });

        return view('public.services.index', compact('services'));
    }

    /**
     * Display the specified active service page.
     */
    public function show(string $slug)
    {
        $service = Service::published()->where('slug', $slug)->firstOrFail();

        // 1. Compile high-end surveillance specific FAQs related to this service
        $faqs = $this->getFaqsForService($service);

        // 2. Generate JSON-LD Service & FAQ Schema markup
        $schema = $this->generateServiceSchema($service, $faqs);

        return view('public.services.show', compact('service', 'faqs', 'schema'));
    }

    /**
     * Get tailored FAQ lists for video surveillance services.
     */
    protected function getFaqsForService(Service $service): array
    {
        // Custom structured FAQs based on service icon/slug
        switch ($service->icon_key) {
            case 'smartphone':
                return [
                    [
                        'q' => 'Am nevoie de IP static pentru a vedea camerele pe telefon?',
                        'a' => 'Nu mai este necesar un IP static. Unitățile moderne DVR/NVR folosesc tehnologii securizate P2P Cloud, ceea ce înseamnă că vă puteți conecta instantaneu scanând un cod QR direct din aplicația de mobil.'
                    ],
                    [
                        'q' => 'Pot primi notificări dacă se detectează mișcare?',
                        'a' => 'Da, configurăm alerte inteligente push direct pe telefoanele selectate. Sistemele noastre pot diferenția mișcarea frunzelor de cea a oamenilor sau mașinilor, evitând alarmele false.'
                    ]
                ];
            case 'wrench':
            case 'shield':
                return [
                    [
                        'q' => 'Camerele de exterior funcționează pe timp de noapte?',
                        'a' => 'Absolut. Instalăm exclusiv camere echipate cu senzori infraroșu avansați sau iluminatoare Smart Dual Light care oferă vizibilitate clară noaptea pe distanțe între 30 și 80 de metri, color sau alb-negru în funcție de preferințe.'
                    ],
                    [
                        'q' => 'Ce rezoluție recomandați pentru supraveghere exterioară?',
                        'a' => 'Recomandăm o rezoluție minimă de 4MP (2K) sau 8MP (4K) pentru exterior, pentru a permite identificarea fețelor sau a numerelor de înmatriculare în caz de incident.'
                    ]
                ];
            case 'refresh-cw':
            case 'tool':
                return [
                    [
                        'q' => 'Putem folosi cablurile existente de la vechiul sistem?',
                        'a' => 'De cele mai multe ori, da. Putem folosi cablurile coaxiale existente pentru a face upgrade la camere HD de înaltă rezoluție (tehnologie TurboHD/HD-TVI), reducând astfel costurile montajului cu până la 40%.'
                    ],
                    [
                        'q' => 'Cât de des trebuie făcută mentenanța sistemului?',
                        'a' => 'Pentru afaceri și spații comerciale recomandați revizii semestriale, iar pentru locuințe private o curățare și verificare tehnică anuală este suficientă pentru a asigura funcționarea non-stop.'
                    ]
                ];
            default:
                return [
                    [
                        'q' => 'Cât timp se păstrează înregistrările pe hard disk?',
                        'a' => 'Înregistrările se păstrează de regulă între 14 și 30 de zile, în conformitate cu legislația în vigoare. Când spațiul se umple, sistemul șterge automat cele mai vechi zile pentru a face loc noilor înregistrări.'
                    ],
                    [
                        'q' => 'Echipamentele instalate sunt asigurate de garanție?',
                        'a' => 'Da, toate camerele, recorderele și hard disk-urile beneficiază de 24 de luni garanție, iar manopera beneficiază de suport tehnic și garanție completă timp de 12 luni de la data recepției.'
                    ]
                ];
        }
    }

    /**
     * Generate dynamic JSON-LD structured schemas.
     */
    protected function generateServiceSchema(Service $service, array $faqs): string
    {
        $siteUrl = url('/');
        $serviceUrl = url()->current();

        $serviceSchema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $service->title,
            'description' => $service->short_description,
            'provider' => [
                '@type' => 'LocalBusiness',
                'name' => setting('company.name', 'Optera Vision'),
                'image' => $siteUrl . '/assets/logo.png',
                'telephone' => setting('company.phone'),
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => setting('company.address'),
                    'addressLocality' => 'Câmpulung Moldovenesc',
                    'addressRegion' => 'Suceava',
                    'addressCountry' => 'RO'
                ]
            ],
            'areaServed' => [
                '@type' => 'AdministrativeArea',
                'name' => 'Bucovina / Suceava'
            ],
            'url' => $serviceUrl
        ];

        $faqSchema = [];
        if (!empty($faqs)) {
            $faqSchema = [
                '@context' => 'https://schema.org',
                '@type' => 'FAQPage',
                'mainEntity' => []
            ];

            foreach ($faqs as $faq) {
                $faqSchema['mainEntity'][] = [
                    '@type' => 'Question',
                    'name' => $faq['q'],
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['a']
                    ]
                ];
            }
        }

        // Return combined JSON-LD strings
        $html = '<script type="application/ld+json">' . json_encode($serviceSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>' . "\n";
        if (!empty($faqSchema)) {
            $html .= '<script type="application/ld+json">' . json_encode($faqSchema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . '</script>';
        }

        return $html;
    }
}
