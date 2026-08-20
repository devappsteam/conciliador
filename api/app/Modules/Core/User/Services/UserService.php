<?php

namespace App\Modules\Core\User\Services;

use App\Modules\Core\IAM\Models\Role;
use App\Modules\Core\User\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class UserService
{
    public function __construct(protected UserRepositoryInterface $repository)
    {
    }

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
        $roleUuid = Arr::pull($data, 'role_uuid');
        $avatar = Arr::pull($data, 'avatar');
        unset($data['password_confirmation']);

        if ($avatar instanceof UploadedFile) {
            $data['avatar_url'] = $this->storeAvatar($avatar);
        }

        $user = $this->repository->create($data);
        $this->syncRole($user, $roleUuid);

        return $user->load('roles');
    }

    public function update(Model $model, array $data): bool
    {
        $roleUuid = Arr::pull($data, 'role_uuid');
        $avatar = Arr::pull($data, 'avatar');
        unset($data['password_confirmation']);

        if (empty($data['password'])) {
            unset($data['password']);
        }

        if ($avatar instanceof UploadedFile) {
            $data['avatar_url'] = $this->storeAvatar($avatar);
        }

        $updated = $this->repository->update($model, $data);

        if ($roleUuid) {
            $this->syncRole($model, $roleUuid);
        }

        return $updated;
    }

    protected function syncRole(Model $user, ?string $roleUuid): void
    {
        if (!$roleUuid) {
            return;
        }

        $role = Role::where('uuid', $roleUuid)->firstOrFail();
        $user->roles()->sync([$role->id]);
    }

    protected function storeAvatar(UploadedFile $file): string
    {
        $path = $file->store('avatars', 'public');

        return Storage::disk('public')->url($path);
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
