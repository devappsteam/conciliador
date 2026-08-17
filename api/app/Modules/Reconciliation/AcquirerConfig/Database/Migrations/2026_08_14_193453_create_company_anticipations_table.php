<?php

use App\Modules\Reconciliation\AcquirerConfig\Enums\AnticipationTypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_anticipations', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignUuid('company_acquirer_id')->constrained('company_acquirers')->cascadeOnDelete();

            $table->enum('anticipation_type', AnticipationTypeEnum::values())->index();

            $table->decimal('rate_percentage_monthly', 5, 4)->default(0)->comment('Taxa % de antecipação ao mês');
            $table->decimal('rate_fixed_per_operation', 10, 4)->default(0)->comment('Taxa fixa cobrada por lote/operação de antecipação');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_anticipations');
    }
};
