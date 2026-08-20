<?php

namespace App\Modules\Integrations\AcquirerEDI\Enums;

enum EDIStatusEnum: string
{
    case PENDING    = 'pending';        // Arquivo/Linha aguardando processamento
    case PROCESSING = 'processing';     // Sendo lido pelo Job
    case PROCESSED  = 'processed';      // Processado com sucesso
    case ERROR      = 'error';          // Falha ao processar (layout inválido, etc)

    public static function values(): array
    {
        return array_map(fn(self $status) => $status->value, self::cases());
    }
}
