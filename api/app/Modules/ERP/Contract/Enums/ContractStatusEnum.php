<?php

namespace App\Modules\ERP\Contract\Enums;

enum ContractStatusEnum: string
{
    case PENDING    = 'pending';        // Aguardando assinatura/pagamento
    case ACTIVE     = 'active';         // Contrato ativo
    case SUSPENDED  = 'suspended';      // Inadimplência ou bloqueio
    case CANCELED   = 'canceled';       // Cancelado pelo cliente ou empresa
    case EXPIRED    = 'expired';        // Prazo de vigência encerrado

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pendente',
            self::ACTIVE => 'Ativo',
            self::SUSPENDED => 'Suspenso',
            self::CANCELED => 'Cancelado',
            self::EXPIRED => 'Expirado',
        };
    }

    public static function values(): array
    {
        return array_map(fn($status) => $status->value, self::cases());
    }
}
