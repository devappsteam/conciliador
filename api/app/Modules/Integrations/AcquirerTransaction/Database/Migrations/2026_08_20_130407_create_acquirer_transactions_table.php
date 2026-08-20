<?php

use App\Modules\Integrations\AcquirerTransaction\Enums\ProductTypeEnum;
use App\Modules\Integrations\AcquirerTransaction\Enums\ReconciliationStatusEnum;
use App\Modules\Integrations\AcquirerTransaction\Enums\TransactionStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acquirer_transactions', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();

            // Relacionamentos e Tenant
            $table->foreignUuid('company_id')->constrained('companies')->cascadeOnDelete();
            $table->foreignUuid('company_acquirer_id')->constrained('company_acquirers')->cascadeOnDelete();
            $table->foreignUuid('edi_file_row_id')->nullable()->constrained('edi_file_rows')->nullOnDelete();

            // Identificadores da Adquirente / Ponto de Venda
            $table->string('establishment_code', 50); // Número do PV na Adquirente
            $table->string('acquirer_nsu', 50);       // NSU da Adquirente
            $table->string('authorization_code', 30)->nullable(); // Código de Autorização
            $table->string('tid', 100)->nullable();   // Transaction ID (E-commerce / POS)

            // Dados da Venda
            $table->dateTime('sale_date_time');
            $table->date('payment_expected_date');
            $table->date('payment_real_date')->nullable();

            // Valores Financeiros (Decimal de Precisão)
            $table->decimal('gross_amount', 15, 2);                     // Valor Bruto da Venda
            $table->decimal('net_amount', 15, 2);                       // Valor Líquido a Receber
            $table->decimal('fee_amount', 15, 2)->default(0.00);        // Valor Total de Taxa Cobrado
            $table->decimal('fee_percentage', 8, 4)->default(0.0000);   // Taxa % cobrada pela Adquirente
            $table->decimal('fixed_fee_amount', 15, 2)->default(0.00);  // Taxa Fixa cobrada por transação

            // Parcelamento e Cartão
            $table->unsignedSmallInteger('installment_number')->default(1);
            $table->unsignedSmallInteger('total_installments')->default(1);
            $table->string('card_brand', 30);                               // Visa, Mastercard, Elo, etc.
            $table->string('card_number_masked', 20)->nullable();           // Ex: 411111******1111 (PCI Compliance)
            $table->enum('product_type', ProductTypeEnum::values());        // Enum ProductTypeEnum

            // Enums de Estado
            $table->enum('status', TransactionStatusEnum::values())->default(TransactionStatusEnum::APPROVED->value);
            $table->enum('reconciliation_status', ReconciliationStatusEnum::values())->default(ReconciliationStatusEnum::UNMATCHED->value);


            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['company_id', 'sale_date_time']);
            $table->index(['company_id', 'reconciliation_status']);
            $table->index(['company_id', 'establishment_code', 'acquirer_nsu']);

            // Constraint de Unicidade Nativa no DB contra Duplicidade Financeira
            $table->unique(
                ['company_id', 'establishment_code', 'acquirer_nsu', 'installment_number', 'sale_date_time'],
                'unique_acquirer_transaction_record'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acquirer_transactions');
    }
};
