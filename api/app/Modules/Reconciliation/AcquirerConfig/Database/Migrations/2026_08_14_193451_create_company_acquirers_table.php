<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('company_acquirers', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignUuid('company_id')->constrained('companies')->restrictOnDelete();
            $table->foreignUuid('acquirer_id')->constrained('acquirers')->restrictOnDelete();

            $table->string('merchant_id')->comment('Número de afiliação/estabelecimento (EC)');
            $table->boolean('is_active')->default(true);

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['company_id', 'acquirer_id', 'merchant_id'], 'idx_company_acquirer_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('company_acquirers');
    }
};
