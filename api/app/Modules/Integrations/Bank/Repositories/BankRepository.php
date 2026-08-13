<?php

namespace App\Modules\Integrations\Bank\Repositories;

use App\Modules\Integrations\Bank\Models\Bank;
use App\Modules\Integrations\Bank\Repositories\Contracts\BankRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class BankRepository extends BaseRepository implements BankRepositoryInterface
{
    public function __construct(Bank $model)
    {
        parent::__construct($model);
    }

    public function all(array $relations = []): Collection
    {
        return $this->query($relations)->orderBy('name')->get();
    }
}
