<?php

namespace App\Enums;

enum QuoteStatus: string
{
    case NEW = 'nou';
    case CONTACTED = 'contactat';
    case EVALUATION_SCHEDULED = 'programare_evaluare';
    case QUOTED = 'ofertat';
    case ACCEPTED = 'acceptat';
    case IN_PROGRESS = 'in_lucru';
    case COMPLETED = 'finalizat';
    case LOST = 'pierdut';

    /**
     * Get the Romanian readable label for the status.
     */
    public function label(): string
    {
        return match($this) {
            self::NEW => 'Nou',
            self::CONTACTED => 'Contactat',
            self::EVALUATION_SCHEDULED => 'Programare Evaluare',
            self::QUOTED => 'Ofertat',
            self::ACCEPTED => 'Acceptat',
            self::IN_PROGRESS => 'În Lucru',
            self::COMPLETED => 'Finalizat',
            self::LOST => 'Pierdut',
        };
    }

    /**
     * Get premium color classes for HSL forest green accents and modern UI themes.
     */
    public function colorClass(): string
    {
        return match($this) {
            self::NEW => 'bg-blue-50 text-blue-800 border border-blue-100',
            self::CONTACTED => 'bg-amber-50 text-amber-800 border border-amber-100',
            self::EVALUATION_SCHEDULED => 'bg-purple-50 text-purple-800 border border-purple-100',
            self::QUOTED => 'bg-indigo-50 text-indigo-800 border border-indigo-100',
            self::ACCEPTED => 'bg-emerald-50 text-emerald-800 border border-emerald-100',
            self::IN_PROGRESS => 'bg-cyan-50 text-cyan-800 border border-cyan-100',
            self::COMPLETED => 'bg-teal-50 text-teal-850 border border-teal-100',
            self::LOST => 'bg-rose-50 text-rose-800 border border-rose-100',
        };
    }
}
