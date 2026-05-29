<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportController extends Controller
{
    /**
     * Export all CRM quote requests to a downloadable CSV file.
     * Enforces strict role authorization (technicians blocked).
     */
    public function exportCsv()
    {
        $user = auth()->user();

        // 1. Authorization check
        if ($user->role === 'technician') {
            abort(403, 'Nu aveți permisiunea de a exporta date.');
        }

        // 2. Build filename with current date
        $filename = 'optera_vision_leads_' . date('Y-m-d_H-i-s') . '.csv';

        // 3. Stream CSV generation
        $response = new StreamedResponse(function () {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel Romanian characters support
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Set Header row
            fputcsv($handle, [
                'Nume Client',
                'Telefon',
                'Email',
                'Localitate',
                'Tip Locație',
                'Tip Sistem',
                'Număr Camere',
                'Stadiu CRM',
                'Data Înregistrării',
                'Număr Notițe CRM'
            ]);

            // Query in chunks for high memory efficiency
            QuoteRequest::withCount('notes')
                ->orderBy('created_at', 'desc')
                ->chunk(100, function ($quotes) use ($handle) {
                    foreach ($quotes as $quote) {
                        fputcsv($handle, [
                            $quote->name,
                            $quote->phone,
                            $quote->email,
                            $quote->locality ?: '-',
                            ucfirst($quote->location_type),
                            $quote->is_upgrade ? 'Upgrade' : 'Sistem Nou',
                            $quote->camera_count,
                            $quote->status->label(),
                            $quote->created_at->format('d.m.Y H:i'),
                            $quote->notes_count
                        ]);
                    }
                });

            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);

        return $response;
    }
}
