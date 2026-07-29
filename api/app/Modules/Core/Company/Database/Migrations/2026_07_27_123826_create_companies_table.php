<?php

use App\Modules\Core\Company\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('document', 30)->unique()->comment('CNPJ');
            $table->string('corporate_name', 255)->comment('Razão Social');
            $table->string('trade_name', 255)->nullable()->comment('Nome Fantasia');
            $table->string('state_registration', 30)->nullable();
            $table->string('municipal_registration', 30)->nullable();
            $table->string('email', 255)->nullable()->comment('E-mail');
            $table->string('phone', 30)->nullable()->comment('Telefone');
            $table->string('street', 255)->comment('Logradouro');
            $table->string('number', 20)->nullable()->comment('Número');
            $table->string('complement', 255)->nullable()->comment('Complemento');
            $table->string('neighborhood', 255)->nullable()->comment('Bairro');
            $table->string('city', 255)->nullable()->comment('Cidade');
            $table->string('state', 2)->nullable()->comment('Estado');
            $table->string('zip_code', 15)->nullable()->comment('CEP');
            $table->string('country', 2)->default('BR')->comment('País');
            $table->enum('status', Status::values())->default(Status::PENDING)->comment('Status da empresa');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['document', 'corporate_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
