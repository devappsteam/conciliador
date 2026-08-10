<?php

namespace App\Modules\ERP\Employee\Repositories;

use App\Modules\ERP\Employee\Models\Employee;
use App\Modules\ERP\Employee\Repositories\Contracts\EmployeeRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;

class EmployeeRepository extends BaseRepository implements EmployeeRepositoryInterface
{
    public function __construct(Employee $model)
    {
        parent::__construct($model);
    }
}
