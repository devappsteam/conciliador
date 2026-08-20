<?php

namespace App\Modules\Integrations\AcquirerEDI\Enums;

enum EDITypeEnum: string
{
    case CV = 'cv'; // Comprovante de Vendas (RO/CV)
    case CP = 'cp'; // Comprovante de Pagamentos (Extrato Eletrônico)
    case AN = 'an'; // Antecipações
    case OUTROS = 'outros';

    public static function values(): array
    {
        return array_map(fn(self $type) => $type->value, self::cases());
    }
}
