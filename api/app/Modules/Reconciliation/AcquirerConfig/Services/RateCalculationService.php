<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Services;

use Carbon\Carbon;
use Exception;
use App\Modules\Reconciliation\AcquirerConfig\Models\CompanyAcquirer;
use App\Modules\Reconciliation\AcquirerConfig\Enums\ProductTypeEnum;

class RateCalculationService
{
    /**
     * Calcula o valor esperado da taxa MDR para uma transação específica.
     *
     * @param CompanyAcquirer $companyAcquirer A configuração do cliente
     * @param float $transactionAmount Valor bruto da venda
     * @param ProductTypeEnum $productType Tipo do produto (Crédito, Débito, etc)
     * @param int $installments Número de parcelas
     * @param Carbon|string $transactionDate Data da venda para checar a vigência da taxa
     * @return float Valor descontado (MDR) esperado em R$
     * @throws Exception
     */
    public function calculateExpectedMdr(
        CompanyAcquirer $companyAcquirer,
        float $transactionAmount,
        ProductTypeEnum $productType,
        int $installments,
        $transactionDate
    ): float {
        $date = Carbon::parse($transactionDate);

        // 1. Encontra a taxa correta baseada no produto, parcelas e data de vigência
        $applicableRate = $companyAcquirer->rates()
            ->where('product_type', $productType->value)
            ->where('installment_min', '<=', $installments)
            ->where('installment_max', '>=', $installments)
            ->where('effective_date', '<=', $date->toDateString())
            ->orderBy('effective_date', 'desc') // Pega a vigência mais recente aplicável
            ->first();

        if (! $applicableRate) {
            throw new Exception("Nenhuma taxa configurada encontrada para o produto {$productType->label()} em {$installments}x na data {$date->format('d/m/Y')}.");
        }

        // 2. Realiza o cálculo matemático: (Valor * %) + Taxa Fixa
        $percentageDiscount = $transactionAmount * ($applicableRate->rate_percentage / 100);
        $fixedDiscount = $applicableRate->rate_fixed;

        return round($percentageDiscount + $fixedDiscount, 2);
    }

    /**
     * Calcula a taxa de antecipação caso exista (Pode ser expandido futuramente)
     */
    public function calculateExpectedAnticipationFee(CompanyAcquirer $companyAcquirer, float $advancedAmount, int $daysAdvanced): float
    {
        $anticipation = $companyAcquirer->anticipationConfig;

        if (! $anticipation || $anticipation->anticipation_type->value === 'none') {
            return 0.0;
        }

        // Exemplo simplificado de cálculo pro-rata dia (Taxa Mensal / 30 * dias antecipados)
        $dailyRate = ($anticipation->rate_percentage_monthly / 100) / 30;
        $fee = ($advancedAmount * $dailyRate * $daysAdvanced) + $anticipation->rate_fixed_per_operation;

        return round($fee, 2);
    }
}
