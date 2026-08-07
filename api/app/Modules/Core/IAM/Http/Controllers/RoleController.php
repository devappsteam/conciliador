<?php

namespace App\Modules\Core\IAM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\IAM\Http\Requests\StoreRoleRequest;
use App\Modules\Core\IAM\Http\Requests\UpdateRoleRequest;
use App\Modules\Core\IAM\Http\Resources\RoleListResource;
use App\Modules\Core\IAM\Http\Resources\RoleResource;
use App\Modules\Core\IAM\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RoleController extends Controller
{
    public function __construct(protected RoleService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $roles = $this->service->paginate(
            perPage: request()->integer('per_page', 15),
            relations: ['permissions']
        );
        return RoleListResource::collection($roles);
    }

    public function store(StoreRoleRequest $request): JsonResponse
    {
        $role = $this->service->create($request->validated());

        return (new RoleResource($role))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): RoleResource
    {
        return new RoleResource($this->findOrFail($uuid));
    }

    public function update(UpdateRoleRequest $request, string $uuid): RoleResource
    {
        $role = $this->findOrFail($uuid);
        $updated = $this->service->update($role, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update role.');
        }

        return new RoleResource($role->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $role = $this->findOrFail($uuid);
        $deleted = $this->service->delete($role);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete role.');
        }

        return response()->json(status: 204);
    }

    protected function findOrFail(string $uuid)
    {
        $role = $this->service->findByUuid(
            uuid: $uuid,
            relations: ['permissions']
        );

        if (!$role) {
            throw new NotFoundHttpException('Role not found.');
        }

        return $role;
    }
}
