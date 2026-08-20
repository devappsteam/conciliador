<?php

namespace App\Modules\Integrations\AcquirerTransaction\Enums;

enum TransactionStatusEnum: string
{
    case APPROVED     = 'approved';     // Venda Aprovada
    case CANCELLED    = 'cancelled';    // Venda Cancelada / Estornada
    case CHARGEBACK   = 'chargeback';   // Contestação / Chargeback
    case ANTICIPATED  = 'anticipated';  // Transação Antecipada
    case REJECTED     = 'rejected';     // Rejeitada

    public static function values(): array
    {
        return array_map(fn($status) => $status->value, self::cases());
    }
}
