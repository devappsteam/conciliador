<?php

namespace App\Modules\Integrations\AcquirerTransaction\Enums;

enum ProductTypeEnum: string
{
    case DEBIT               = 'debit';
    case CREDIT_FULL         = 'credit_full';
    case CREDIT_INSTALLMENT  = 'credit_installment';
    case VOUCHER             = 'voucher';
    case PIX                 = 'pix';
    case OTHER               = 'other';


    public static function values(): array
    {
        return array_map(fn($type) => $type->value, self::cases());
    }
}
