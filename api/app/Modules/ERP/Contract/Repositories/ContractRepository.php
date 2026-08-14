<?php

namespace App\Modules\ERP\Contract\Repositories;

use App\Modules\ERP\Contract\Models\Contract;
use App\Modules\ERP\Contract\Repositories\Contracts\ContractRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;

class ContractRepository extends BaseRepository implements ContractRepositoryInterface
{
    public function __construct(Contract $model)
    {
        parent::__construct($model);
    }
}
