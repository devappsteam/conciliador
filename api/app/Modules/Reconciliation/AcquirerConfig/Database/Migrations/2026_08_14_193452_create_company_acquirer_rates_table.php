<?php

use App\Modules\Reconciliation\AcquirerConfig\Enums\ProductTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_acquirer_rates', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignUuid('company_acquirer_id')->constrained('company_acquirers')->cascadeOnDelete();

            $table->enum('product_type', ProductTypeEnum::values())->index();
            $table->string('brand')->nullable()->comment('Bandeira (Visa, Master). Null = Todas');

            $table->integer('installment_min')->default(1);
            $table->integer('installment_max')->default(1);

            // Taxas suportam combinações (% + Fixo)
            $table->decimal('rate_percentage', 5, 4)->default(0)->comment('Taxa % MDR');
            $table->decimal('rate_fixed', 10, 4)->default(0)->comment('Taxa Fixa R$ MDR');

            // Versionamento de taxas
            $table->date('effective_date')->comment('Data de vigência da taxa');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_acquirer_rates');
    }
};
