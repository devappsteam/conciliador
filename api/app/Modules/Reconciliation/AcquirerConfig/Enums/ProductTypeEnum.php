<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Enums;

enum ProductTypeEnum: string
{
    case DEBIT              = 'debit';
    case CREDIT             = 'credit';
    case CREDIT_INSTALLMENT = 'credit_installment';
    case VOUCHER            = 'voucher';
    case PIX                = 'pix';

    public function label(): string
    {
        return match ($this) {
            self::DEBIT                 => 'Débito',
            self::CREDIT                => 'Crédito à Vista',
            self::CREDIT_INSTALLMENT    => 'Crédito Parcelado',
            self::VOUCHER               => 'Voucher (Alimentação/Refeição)',
            self::PIX                   => 'PIX',
        };
    }

    public static function values(): array
    {
        return array_map(
            fn(ProductTypeEnum $enum) => $enum->value,
            self::cases()
        );
    }
}
