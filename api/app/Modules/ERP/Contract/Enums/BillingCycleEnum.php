<?php

namespace App\Modules\ERP\Contract\Enums;

enum BillingCycleEnum: string
{
    case MONTHLY    = 'monthly';        // Mensal
    case QUARTERLY  = 'quarterly';      // Trimestral
    case SEMIANNUAL = 'semiannual';     // Semestral
    case ANNUAL     = 'annual';         // Anual

    public function label()
    {
        return match ($this) {
            self::MONTHLY       => 'Mensal',
            self::QUARTERLY     => 'Trimestral',
            self::SEMIANNUAL    => 'Semestral',
            self::ANNUAL        => 'Anual',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
