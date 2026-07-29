<?php

namespace App\Modules\Core\Company\Enums;

enum Status: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';

    public static function values(): array
    {
        return array_map(fn ($status) => $status->value, self::cases());
    }

    public static function labels(): array
    {
        return [
            self::PENDING->value => __('Pending'),
            self::ACTIVE->value => __('Active'),
            self::INACTIVE->value => __('Inactive'),
            self::SUSPENDED->value => __('Suspended'),
        ];
    }

    public function label(): string
    {
        return self::labels()[$this->value];
    }
}
