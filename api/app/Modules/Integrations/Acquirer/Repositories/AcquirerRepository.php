<?php

namespace App\Modules\Integrations\Acquirer\Repositories;

use App\Modules\Integrations\Acquirer\Models\Acquirer;
use App\Modules\Integrations\Acquirer\Repositories\Contracts\AcquirerRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

class AcquirerRepository extends BaseRepository implements AcquirerRepositoryInterface
{
    public function __construct(Acquirer $model)
    {
        parent::__construct($model);
    }

    public function all(array $relations = []): Collection
    {
        return $this->query($relations)->orderBy('name')->get();
    }
}
