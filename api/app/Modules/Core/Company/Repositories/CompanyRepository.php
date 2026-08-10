<?php

namespace App\Modules\Core\Company\Repositories;

use App\Modules\Core\Company\Enums\Status;
use Illuminate\Database\Eloquent\Collection;

use App\Modules\Core\Company\Models\Company;
use App\Modules\Core\Company\Repositories\Contracts\CompanyRepositoryInterface;
use DevApps\LaravelModulesKit\Support\BaseRepository;

class CompanyRepository extends BaseRepository implements CompanyRepositoryInterface
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

    public function all(array $relations = []): Collection
    {
        return $this->query($relations)->orderBy('corporate_name', 'ASC')->where('status', Status::ACTIVE)->get();
    }
}
