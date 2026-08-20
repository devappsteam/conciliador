<?php

namespace App\Modules\Integrations\AcquirerTransaction\Enums;

enum ReconciliationStatusEnum: string
{
    case UNMATCHED       = 'unmatched';        // Pendente de conciliação com o ERP
    case MATCHED         = 'matched';          // Conciliado perfeitamente
    case DIVERGENT       = 'divergent';        // Conciliado com divergência (valor/taxa/data)
    case MANUAL_MATCHED  = 'manual_matched';   // Conciliado manualmente pelo operador BPO

    public static function values(): array
    {
        return array_map(fn($status) => $status->value, self::cases());
    }
}
