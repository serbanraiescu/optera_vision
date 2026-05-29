<?php

namespace App\Helpers;

class HtmlSanitizer
{
    /**
     * Recursively strip out dangerous HTML tags and attributes to prevent XSS.
     * Preserves formatting like headings, lists, tables, images, links.
     */
    public static function sanitize(?string $html): ?string
    {
        if (is_null($html)) {
            return null;
        }

        // 1. Remove script tags and their content completely
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);

        // 2. Remove dynamic iframe and object embeds completely
        $html = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $html);
        $html = preg_replace('/<embed\b[^>]*>(.*?)<\/embed>/is', '', $html);
        $html = preg_replace('/<object\b[^>]*>(.*?)<\/object>/is', '', $html);

        // 3. Remove inline "on*" event handlers (e.g. onload, onerror, onclick, etc.)
        $html = preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]*)/is', '', $html);

        // 4. Remove javascript: links and urls
        $html = preg_replace('/href\s*=\s*["\']\s*javascript\s*:[^"\']*["\']/is', 'href="#"', $html);

        return $html;
    }
}
