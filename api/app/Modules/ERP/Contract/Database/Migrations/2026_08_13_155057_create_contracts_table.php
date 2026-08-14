<?php

use App\Modules\ERP\Contract\Enums\BillingCycleEnum;
use App\Modules\ERP\Contract\Enums\ContractStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->uuid('uuid')->unique();
            $table->string('code', 50)->unique();
            $table->enum('status', ContractStatusEnum::values())->default(ContractStatusEnum::PENDING->value);
            $table->enum('billing_cycle', BillingCycleEnum::values())->default(BillingCycleEnum::MONTHLY->value);

            $table->decimal('amount', 15, 2)->default(0)->comment('Valor da assinatura');
            $table->integer('transaction_limit')->nullable()->comment('Limite de transações mensais conciliadas');
            $table->decimal('overage_fee', 10, 4)->default(0)->comment('Taxa por transação excedente');

            $table->date('start_date');
            $table->date('end_date')->nullable()->comment('Nulo = tempo indeterminado');

            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
