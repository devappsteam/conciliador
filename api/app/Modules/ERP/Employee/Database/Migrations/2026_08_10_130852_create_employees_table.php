<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('department_id')->constrained('departments');
            $table->foreignId('position_id')->constrained('positions');

            // Identificação
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('preferred_name')->nullable();

            // Dados pessoais
            $table->date('birth_date')->nullable();
            $table->string('gender', 50)->nullable();

            // Documentos
            $table->string('cpf', 20)->unique();
            $table->string('rg', 20)->nullable();
            $table->string('rg_issuer', 50)->nullable();
            $table->string('pis_pasep', 50)->nullable();

            // Contato
            $table->string('email', 255)->unique();
            $table->string('personal_email', 255)->nullable();
            $table->string('phone', 40)->nullable();
            $table->string('mobile_phone', 40)->nullable();

            // Endereço
            $table->string('zip_code', 15)->nullable();
            $table->string('street')->nullable();
            $table->string('number', 50)->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 2)->nullable();
            $table->string('country', 2)->default('BR');

            // Dados profissionais
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();

            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->text('notes')->nullable();
            $table->string('profile_picture')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('employee_contracts', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('contract_type', ['full_time', 'part_time', 'temporary', 'internship'])->default('full_time');
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('base_salary', 15, 2)->nullable();
            $table->enum('salary_type', ['monthly', 'hourly'])->nullable();
            $table->string('work_schedule')->nullable();
            $table->unsignedInteger('weekly_hours')->nullable();
            $table->string('termination_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_documents', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('type', ['cpf', 'rg', 'passport', 'driver_license']);
            $table->string('number')->nullable();
            $table->string('issuer')->nullable();
            $table->date('issued_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->string('file_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employee_bank_accounts', function (Blueprint $table): void {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();

            $table->string('bank_code', 3)->nullable();
            $table->string('bank_name')->nullable();

            $table->string('agency');
            $table->string('agency_digit')->nullable();

            $table->string('account_number');
            $table->string('account_digit')->nullable();

            $table->enum('account_type', ['checking', 'savings', 'investment'])->nullable()->comment('Tipo de conta: checking (corrente), savings (poupança), investment (investimento)');

            $table->string('pix_key')->nullable();
            $table->string('pix_key_type')->nullable();

            $table->boolean('is_primary')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_bank_accounts');
        Schema::dropIfExists('employee_documents');
        Schema::dropIfExists('employee_contracts');
        Schema::dropIfExists('employees');
    }
};
