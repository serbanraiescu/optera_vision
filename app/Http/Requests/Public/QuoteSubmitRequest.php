<?php

namespace App\Http\Requests\Public;

use App\Services\ActivityLogService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class QuoteSubmitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nume' => 'required|string|max:255',
            'telefon' => 'required|string|max:30',
            'email' => 'required|email|max:255',
            'localitate' => 'required|string|max:255',
            'tip_locatie' => 'required|string|in:rezidential,comercial,industrial,public',
            'tip_serviciu' => 'required|string|in:nou,upgrade,mentenanta',
            'camere' => 'required|integer|min:1|max:100',
            'mesaj' => 'nullable|string|max:1000',
            'gdpr' => 'accepted',
            
            // Honeypot field (hidden from users, must remain empty)
            'website' => 'nullable|string|max:0',
        ];
    }

    /**
     * Configure the validator instance and apply custom anti-spam guards.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $activityLogger = app(ActivityLogService::class);

            // 1. Honeypot check (field named 'website' must be empty)
            if ($this->filled('website')) {
                $activityLogger->log(
                    'spam_blocked',
                    "Honeypot de spam declanșat. Câmpul website a fost completat cu valoarea: '{$this->input('website')}' de la IP {$this->ip()}."
                );

                throw ValidationException::withMessages([
                    'website' => 'Spam detectat. Cererea a fost respinsă.',
                ]);
            }

            // 2. Minimum Submit Time check (requires at least 3 seconds)
            $formLoadedAt = session()->get('quote_form_loaded_at');
            $currentTime = time();

            if (!$formLoadedAt || ($currentTime - $formLoadedAt) < 3) {
                $activityLogger->log(
                    'spam_blocked',
                    "Trimitere prea rapidă a formularului. Timp scurs: " . ($formLoadedAt ? ($currentTime - $formLoadedAt) : 'N/A') . " secunde de la IP {$this->ip()}."
                );

                throw ValidationException::withMessages([
                    'gdpr' => 'Vă rugăm să citiți datele din formular înainte de a trimite (anti-bot).',
                ]);
            }
        });
    }

    /**
     * Custom validation messages in Romanian.
     */
    public function messages(): array
    {
        return [
            'nume.required' => 'Numele complet este obligatoriu.',
            'telefon.required' => 'Numărul de telefon este obligatoriu.',
            'email.required' => 'Adresa de email este obligatorie.',
            'email.email' => 'Adresa de email nu este validă.',
            'localitate.required' => 'Localitatea este obligatorie.',
            'tip_locatie.in' => 'Tipul de locație selectat nu este valid.',
            'tip_serviciu.in' => 'Tipul de proiect selectat nu este valid.',
            'camere.required' => 'Numărul de camere este obligatoriu.',
            'camere.integer' => 'Numărul de camere trebuie să fie un număr valid.',
            'camere.min' => 'Numărul minim de camere este 1.',
            'camere.max' => 'Numărul maxim de camere este 100.',
            'gdpr.accepted' => 'Trebuie să acceptați prelucrarea datelor cu caracter personal.',
        ];
    }
}
