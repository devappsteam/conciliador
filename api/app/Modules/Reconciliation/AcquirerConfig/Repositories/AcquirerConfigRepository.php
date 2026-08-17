<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Repositories;

use App\Modules\Reconciliation\AcquirerConfig\Models\AcquirerConfig;
use App\Modules\Reconciliation\AcquirerConfig\Repositories\Contracts\AcquirerConfigRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;

class AcquirerConfigRepository extends BaseRepository implements AcquirerConfigRepositoryInterface
{
    public function __construct(AcquirerConfig $model)
    {
        parent::__construct($model);
    }
}
