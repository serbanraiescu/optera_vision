<?php

namespace App\Services;

use App\Models\Service;
use App\Models\Project;
use App\Models\Page;

class SeoService
{
    /**
     * Generate dynamic meta tags dictionary for public layouts.
     */
    public function getMetaTags(mixed $entity = null): array
    {
        $siteName = setting('site.name', 'Optera Vision');
        $defaultTitle = setting('seo.default_title', 'Sisteme Supraveghere Video Câmpulung Moldovenesc');
        $defaultDesc = setting('seo.default_description', 'Sisteme de supraveghere video profesionale în Bucovina.');
        $defaultOgImage = setting('seo.default_og_image') ? asset('storage/' . setting('seo.default_og_image')) : null;

        $title = $defaultTitle;
        $desc = $defaultDesc;
        $ogImage = $defaultOgImage;
        $canonical = url()->current();
        $noindex = false;

        if ($entity) {
            if ($entity instanceof Page || $entity instanceof Service || $entity instanceof Project) {
                $title = $entity->meta_title ?: ($entity->title . ' | ' . $siteName);
                $desc = $entity->meta_description ?: $defaultDesc;
                
                // Entity specific featured image or default
                if (isset($entity->featured_image) && $entity->featured_image) {
                    $ogImage = asset('storage/' . $entity->featured_image);
                }

                // Check for noindex directive
                $noindex = isset($entity->noindex) ? (bool) $entity->noindex : false;
            }
        }

        return [
            'title' => $title,
            'description' => $desc,
            'og_image' => $ogImage,
            'canonical' => $canonical,
            'noindex' => $noindex,
        ];
    }

    /**
     * Generate dynamic JSON-LD schemas based on dynamic parameters.
     */
    public function getSchemas(?string $type = null, mixed $entity = null): string
    {
        $schemas = [];

        // 1. Organization Schema
        if (setting('schema.organization_enabled', true)) {
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => setting('company.name', 'Optera Vision'),
                'url' => url('/'),
                'logo' => setting('brand.logo') ? asset('storage/' . setting('brand.logo')) : null,
                'contactPoint' => [
                    '@type' => 'ContactPoint',
                    'telephone' => setting('company.phone', '+40700000000'),
                    'contactType' => 'customer service'
                ]
            ];
        }

        // 2. LocalBusiness Schema
        if (setting('schema.local_business_enabled', true)) {
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'LocalBusiness',
                'name' => setting('company.name', 'Optera Vision'),
                'image' => setting('brand.logo') ? asset('storage/' . setting('brand.logo')) : null,
                'telephone' => setting('company.phone', '+40700000000'),
                'email' => setting('company.email', 'office@opteravision.ro'),
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => setting('company.address', 'Calea Transilvaniei'),
                    'addressLocality' => setting('company.locality', 'Câmpulung Moldovenesc'),
                    'addressRegion' => setting('company.county', 'Suceava'),
                    'addressCountry' => 'RO'
                ],
                'priceRange' => '$$',
                'openingHours' => setting('company.hours', 'Mo-Fr 09:00-18:00')
            ];
        }

        // 3. Service specific schema
        if ($type === 'service' && $entity instanceof Service && setting('schema.service_enabled', true)) {
            $schemas[] = [
                '@context' => 'https://schema.org',
                '@type' => 'Service',
                'serviceType' => 'Instalare Supraveghere Video',
                'provider' => [
                    '@type' => 'LocalBusiness',
                    'name' => setting('company.name', 'Optera Vision')
                ],
                'name' => $entity->title,
                'description' => $entity->short_description
            ];
        }

        return count($schemas) > 0 ? json_encode($schemas, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) : '';
    }
}
