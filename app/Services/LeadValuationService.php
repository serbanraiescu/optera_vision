<?php

namespace App\Services;

use App\Models\QuoteRequest;

class LeadValuationService
{
    /**
     * Calculate the estimated project value dynamically on-the-fly.
     * Keeps model thin and database columns unchanged.
     */
    public function calculate(QuoteRequest|array $lead): float
    {
        $cameraCount = is_array($lead) ? intval($lead['camera_count'] ?? 0) : intval($lead->camera_count);
        $locationType = is_array($lead) ? ($lead['location_type'] ?? 'rezidential') : $lead->location_type;

        $value = $cameraCount * 650;
        switch ($locationType) {
            case 'industrial':
                $value += 2500;
                break;
            case 'comercial':
                $value += 1500;
                break;
            case 'public':
                $value += 1000;
                break;
            default: // rezidential
                $value += 500;
                break;
        }

        return (float) $value;
    }
}
