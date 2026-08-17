<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Services;

use Exception;
use Illuminate\Support\Facades\DB;
use App\Modules\Reconciliation\AcquirerConfig\Models\CompanyAcquirer;
use App\Modules\Reconciliation\AcquirerConfig\Repositories\Contracts\CompanyAcquirerRepositoryInterface;

class CompanyAcquirerService
{
    public function __construct(
        protected CompanyAcquirerRepositoryInterface $repository
    ) {}

    /**
     * Cria ou atualiza a configuração comercial de uma adquirente para um cliente.
     *
     * @param string $companyId
     * @param string $acquirerId
     * @param array $data Contém merchant_id, rates (array) e anticipation (array)
     * @return CompanyAcquirer
     * @throws Exception
     */
    public function setupCompanyAcquirer(string $companyId, string $acquirerId, array $data): CompanyAcquirer
    {
        return DB::transaction(function () use ($companyId, $acquirerId, $data) {

            // Cria ou recupera o vínculo principal (Company <-> Acquirer)
            $companyAcquirer = CompanyAcquirer::updateOrCreate(
                [
                    'company_id'  => $companyId,
                    'acquirer_id' => $acquirerId,
                ],
                [
                    'merchant_id' => $data['merchant_id'],
                    'is_active'   => $data['is_active'] ?? true,
                ]
            );

            // Sincroniza a Matriz de Taxas (MDR)
            if (isset($data['rates']) && is_array($data['rates'])) {
                $this->syncRates($companyAcquirer, $data['rates']);
            }

            // Configura a Regra de Antecipação
            if (isset($data['anticipation'])) {
                $this->syncAnticipation($companyAcquirer, $data['anticipation']);
            }

            // Retorna o objeto completo atualizado
            return $this->repository->getWithRatesAndAnticipation($companyAcquirer->id);
        });
    }

    /**
     * Sincroniza (recria/atualiza) as taxas MDR configuradas.
     */
    protected function syncRates(CompanyAcquirer $companyAcquirer, array $rates): void
    {
        $companyAcquirer->rates()->delete();

        $ratesData = array_map(function ($rate) {
            return [
                'product_type'    => $rate['product_type'],
                'brand'           => $rate['brand'] ?? null,
                'installment_min' => $rate['installment_min'] ?? 1,
                'installment_max' => $rate['installment_max'] ?? 1,
                'rate_percentage' => $rate['rate_percentage'] ?? 0,
                'rate_fixed'      => $rate['rate_fixed'] ?? 0,
                'effective_date'  => $rate['effective_date'] ?? now()->toDateString(),
            ];
        }, $rates);

        $companyAcquirer->rates()->createMany($ratesData);
    }

    /**
     * Sincroniza a configuração de antecipação.
     */
    protected function syncAnticipation(CompanyAcquirer $companyAcquirer, array $anticipationData): void
    {
        $companyAcquirer->anticipationConfig()->updateOrCreate(
            ['company_acquirer_id' => $companyAcquirer->id],
            [
                'anticipation_type'        => $anticipationData['anticipation_type'],
                'rate_percentage_monthly'  => $anticipationData['rate_percentage_monthly'] ?? 0,
                'rate_fixed_per_operation' => $anticipationData['rate_fixed_per_operation'] ?? 0,
            ]
        );
    }

    /**
     * Alterna o status (Ativo/Inativo) da configuração.
     */
    public function toggleStatus(string $companyAcquirerId): CompanyAcquirer
    {
        $companyAcquirer = CompanyAcquirer::findOrFail($companyAcquirerId);

        $companyAcquirer->update([
            'is_active' => !$companyAcquirer->is_active
        ]);

        return $companyAcquirer;
    }
}
