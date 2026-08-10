<?php

namespace App\Modules\ERP\Department\Database\Seeders;
use Illuminate\Support\Str;
use App\Modules\ERP\Department\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            [
                'uuid'        => Str::uuid()->toString(),
                'name'        => 'Recursos Humanos',
                'code'        => 'RH',
                'description' => 'Responsável por gerenciar o capital humano da empresa, incluindo recrutamento, treinamento e bem-estar dos funcionários.',
                'is_active'   => true,
            ],
            [
                'uuid'        => Str::uuid()->toString(),
                'name'        => 'Finanças',
                'code'        => 'FIN',
                'description' => 'Responsável por gerenciar as finanças da empresa, incluindo orçamento e contabilidade.',
                'is_active'   => true,
            ],
            [
                'uuid'        => Str::uuid()->toString(),
                'name'        => 'Tecnologia da Informação',
                'code'        => 'TI',
                'description' => 'Responsável pela infraestrutura tecnológica e suporte.',
                'is_active'   => true,
            ],
            [
                'uuid'        => Str::uuid()->toString(),
                'name'        => 'Marketing',
                'code'        => 'MKT',
                'description' => 'Foca na promoção da empresa e de seus produtos/serviços.',
                'is_active'   => true,
            ],
            [
                'uuid'        => Str::uuid()->toString(),
                'name'        => 'Vendas',
                'code'        => 'VND',
                'description' => 'Responsável pelo relacionamento com clientes e processos de vendas.',
                'is_active'   => true,
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
