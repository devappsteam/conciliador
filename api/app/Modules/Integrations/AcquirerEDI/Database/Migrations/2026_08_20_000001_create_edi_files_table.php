<?php

use App\Modules\Integrations\AcquirerEDI\Enums\EDIStatusEnum;
use App\Modules\Integrations\AcquirerEDI\Enums\EDITypeEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edi_files', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignUuid('acquirer_id')->constrained('acquirers')->restrictOnDelete();
            $table->foreignUuid('company_id')->nullable()->constrained('companies')->nullOnDelete();
            $table->string('file_name');
            $table->enum('file_type', EDITypeEnum::values())->default(EDITypeEnum::CV->value)->index();
            $table->enum('status', EDIStatusEnum::values())->default(EDIStatusEnum::PENDING->value)->index();
            $table->string('file_hash')->unique();
            $table->timestamp('processed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edi_files');
    }
};
