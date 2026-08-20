<?php

use App\Modules\Integrations\AcquirerEDI\Enums\EDIStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('edi_file_rows', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->foreignUuid('edi_file_id')->constrained('edi_files')->cascadeOnDelete();
            $table->integer('line_number')->comment('Número sequencial da linha no arquivo');
            $table->text('raw_data');
            $table->enum('status', EDIStatusEnum::values())->default(EDIStatusEnum::PENDING->value)->index();
            $table->text('error_message')->nullable()->comment('Motivo do erro caso status seja error');

            $table->timestamps();

            $table->index(['edi_file_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('edi_file_rows');
    }
};
