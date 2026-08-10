<?php

namespace App\Modules\ERP\Position\Database\Seeders;
use Illuminate\Support\Str;
use App\Modules\ERP\Position\Models\Position;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = [
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Gerente',
                'code' => 'MGR',
                'description' => 'Responsável por supervisionar as operações e gerenciar a equipe.',
                'is_active' => true,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Desenvolvedor',
                'code' => 'DEV',
                'description' => 'Responsável por escrever e manter o código das aplicações.',
                'is_active' => true,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'BPO',
                'code' => 'BPO',
                'description' => 'Responsável por processos de negócios terceirizados, garantindo eficiência e qualidade.',
                'is_active' => true,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Suporte Técnico',
                'code' => 'SUP',
                'description' => 'Responsável por fornecer suporte técnico aos usuários, solucionando problemas e garantindo o funcionamento adequado dos sistemas.',
                'is_active' => true,
            ],
            [
                'uuid' => Str::orderedUuid()->toString(),
                'name' => 'Analista de Dados',
                'code' => 'DATA',
                'description' => 'Responsável por coletar, analisar e interpretar dados para fornecer insights valiosos para a tomada de decisões.',
                'is_active' => true,
            ],
        ];

        foreach ($positions as $position) {
            Position::create($position);
        }
    }
}
