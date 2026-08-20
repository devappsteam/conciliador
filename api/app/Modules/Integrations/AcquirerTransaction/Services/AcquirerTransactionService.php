<?php

namespace App\Modules\Integrations\AcquirerTransaction\Services;

use App\Modules\Integrations\AcquirerTransaction\Models\AcquirerTransaction;
use App\Modules\Integrations\AcquirerTransaction\Repositories\Contracts\AcquirerTransactionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AcquirerTransactionService
{
    public function __construct(protected AcquirerTransactionRepositoryInterface $repository) {}

    public function all(array $relations = []): Collection
    {
        return $this->repository->all($relations);
    }

    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $relations);
    }

    public function findById(int $id, array $relations = []): ?Model
    {
        return $this->repository->findById($id, $relations);
    }

    public function findByUuid(string $uuid, array $relations = []): ?Model
    {
        return $this->repository->findByUuid($uuid, $relations);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $this->repository->update($model, $data);
    }

    public function delete(Model $model): bool
    {
        return $this->repository->delete($model);
    }

    public function restore(Model $model): bool
    {
        return $this->repository->restore($model);
    }


    /**
     * Persiste uma transação processada a partir de uma linha de EDI (ou API) de forma idempotente.
     */
    public function processAndSaveTransaction(array $parsedData): ?AcquirerTransaction
    {
        return DB::transaction(function () use ($parsedData) {
            // Tratamento e sanitização de segurança (PCI-DSS)
            if (!empty($parsedData['card_number'])) {
                $parsedData['card_number_masked'] = $this->maskCardNumber($parsedData['card_number']);
                unset($parsedData['card_number']); // Remove cartão cru da memória imediatamente
            }

            // Validação de Idempotência Financeira (Evita duplicidade em caso de reprocessamento)
            $existing = $this->repository->findByNsu(
                $parsedData['company_id'],
                $parsedData['establishment_code'],
                $parsedData['acquirer_nsu']
            );

            if ($existing) {
                Log::info("Transação ignorada por duplicidade (NSU: {$parsedData['acquirer_nsu']})");
                return $existing;
            }

            // Persistência na camada de Repositório
            return $this->repository->create($parsedData);
        });
    }


    /**
     * Mascara o número do cartão para conformidade PCI-DSS / Pentest.
     * Exemplo: 4111123456781111 -> 411112******1111
     */
    private function maskCardNumber(string $cardNumber): string
    {
        $clean = preg_replace('/\D/', '', $cardNumber);
        $length = strlen($clean);

        if ($length < 13) {
            return '************';
        }

        return substr($clean, 0, 6) . str_repeat('*', $length - 10) . substr($clean, -4);
    }
}
