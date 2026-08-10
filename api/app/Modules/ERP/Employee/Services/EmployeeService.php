<?php

namespace App\Modules\ERP\Employee\Services;

use App\Modules\ERP\Employee\Repositories\Contracts\EmployeeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Throwable;

class EmployeeService
{
    public function __construct(protected EmployeeRepositoryInterface $repository) {}

    public function all(array $relations = []): Collection
    {
        return $this->repository->all($relations);
    }

    public function paginate(int $perPage = 15, array $relations = []): LengthAwarePaginator
    {
        return $this->repository->paginate($perPage, $relations);
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
        if (isset($data['profile_picture']) && $data['profile_picture'] instanceof UploadedFile) {
            $data['profile_picture'] = $data['profile_picture']->store('employees', 'public');
        }
        return $this->repository->create($data);
    }

    public function update(Model $model, array $data): bool
    {
        $oldProfilePicture = $model->profile_picture;
        $newProfilePicture = null;

        if (
            isset($data['profile_picture']) &&
            $data['profile_picture'] instanceof UploadedFile
        ) {
            $newProfilePicture = $data['profile_picture']->store(
                'employees',
                'public'
            );
            $data['profile_picture'] = $newProfilePicture;
        }

        try {
            $updated = $this->repository->update($model, $data);

            if (!$updated) {
                if ($newProfilePicture) {
                    Storage::disk('public')->delete($newProfilePicture);
                }

                return false;
            }

            if (
                $oldProfilePicture &&
                $oldProfilePicture !== $newProfilePicture
            ) {
                Storage::disk('public')->delete($oldProfilePicture);
            }

            return true;
        } catch (Throwable $exception) {
            if ($newProfilePicture) {
                Storage::disk('public')->delete($newProfilePicture);
            }
            throw $exception;
        }
    }

    public function delete(Model $model): bool
    {
        return $this->repository->delete($model);
    }

    public function restore(Model $model): bool
    {
        return $this->repository->restore($model);
    }
}
