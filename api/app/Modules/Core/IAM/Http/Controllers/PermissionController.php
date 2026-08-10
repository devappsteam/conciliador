<?php

namespace App\Modules\Core\IAM\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\IAM\Http\Resources\PermissionListResource;
use App\Modules\Core\IAM\Http\Resources\PermissionResource;
use App\Modules\Core\IAM\Services\PermissionService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PermissionController extends Controller
{
    public function __construct(protected PermissionService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $permissions = $this->service->paginate(
            perPage: request()->integer('per_page', 15),
            relations: []
        );
        return PermissionListResource::collection($permissions);
    }

    public function all(): AnonymousResourceCollection
    {
        $permissions = $this->service->all(
            relations: []
        );
        return PermissionListResource::collection($permissions);
    }

    public function show(string $uuid): PermissionResource
    {
        return new PermissionResource($this->findOrFail($uuid));
    }

    protected function findOrFail(string $uuid)
    {
        $permission = $this->service->findByUuid(
            uuid: $uuid,
            relations: []
        );

        if (!$permission) {
            throw new NotFoundHttpException('Permission not found.');
        }

        return $permission;
    }
}
