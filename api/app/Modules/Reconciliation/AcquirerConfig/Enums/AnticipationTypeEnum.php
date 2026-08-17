<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Enums;

enum AnticipationTypeEnum: string
{
    case AUTOMATIC  = 'automatic';   // Antecipação automática (D+1, D+2)
    case ON_DEMAND  = 'on_demand';   // Antecipação esporádica solicitada pelo cliente
    case NONE       = 'none';        // Não possui antecipação contratada

    public function label(): string
    {
        return match ($this) {
            self::AUTOMATIC => 'Automática',
            self::ON_DEMAND => 'Sob demanda',
            self::NONE => 'Sem antecipação',
        };
    }

    public static function values(): array
    {
        return array_map(fn($case) => $case->value, self::cases());
    }
}
