<?php

namespace App\Modules\Integrations\Acquirer\Services;

use App\Modules\Integrations\Acquirer\Repositories\Contracts\AcquirerRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class AcquirerService
{
    public function __construct(protected AcquirerRepositoryInterface $repository)
    {
    }

    public function all(array $relations = []): Collection
    {
        return $this->repository->all($relations);
    }

    public function paginate(int $perPage = 15, array $relations = [], array $filters = []): LengthAwarePaginator
    {
        return $this->repository->paginateWithFilters($filters, $perPage, $relations);
    }

    public function findById(int $id, array $relations = []): ?Model
    {
        return $this->repository->findById($id, $relations);
    }

    public function findByUuid(string $uuid, array $relations = []): ?Model
    {
        return $this->repository->findByUuid($uuid, $relations);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        return $this->repository->update($model, $data);
    }

    public function delete(Model $model): bool
    {
        return $this->repository->delete($model);
    }

    public function toggleStatus(Model $model): bool
    {
        return $this->repository->update($model, ['status' => !$model->status]);
    }

    public function restore(Model $model): bool
    {
        return $this->repository->restore($model);
    }
}
