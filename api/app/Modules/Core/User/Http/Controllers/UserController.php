<?php

namespace App\Modules\Core\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\User\Http\Requests\StoreUserRequest;
use App\Modules\Core\User\Http\Requests\UpdateUserRequest;
use App\Modules\Core\User\Http\Resources\UserListResource;
use App\Modules\Core\User\Http\Resources\UserResource;
use App\Modules\Core\User\Models\User;
use App\Modules\Core\User\Services\UserService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected UserService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = $this->service->paginate(
            perPage: request()->integer('per_page', 15),
            relations: ['roles:id,uuid,name']
        );

        return UserListResource::collection($users);
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $user = $this->service->create($request->validated());

        return (new UserResource($user))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): UserResource
    {
        $user = $this->findOrFail($uuid);
        $this->authorize('view', $user);

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, string $uuid): UserResource
    {
        $user = $this->findOrFail($uuid);
        $this->authorize('update', $user);

        $updated = $this->service->update($user, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update user.');
        }

        return new UserResource($user->fresh(['roles']));
    }

    public function destroy(string $uuid): JsonResponse
    {
        $user = $this->findOrFail($uuid);
        $this->authorize('delete', $user);

        $deleted = $this->service->delete($user);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete user.');
        }

        return response()->json(status: 204);
    }

    protected function findOrFail(string $uuid)
    {
        $user = $this->service->findByUuid($uuid, relations: ['roles']);

        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        return $user;
    }
}
