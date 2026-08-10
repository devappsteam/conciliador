<?php

namespace App\Modules\ERP\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ERP\Department\Http\Requests\StoreDepartmentRequest;
use App\Modules\ERP\Department\Http\Requests\UpdateDepartmentRequest;
use App\Modules\ERP\Department\Http\Resources\DepartmentListResource;
use App\Modules\ERP\Department\Http\Resources\DepartmentResource;
use App\Modules\ERP\Department\Services\DepartmentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DepartmentController extends Controller
{
    public function __construct(protected DepartmentService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $departments = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.department.pagination.per_page', 15))
        );

        return DepartmentListResource::collection($departments);
    }

    public function all(): AnonymousResourceCollection
    {
        $departments = $this->service->all();

        return DepartmentListResource::collection($departments);
    }

    public function store(StoreDepartmentRequest $request): JsonResponse
    {
        $department = $this->service->create($request->validated());

        return (new DepartmentResource($department))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): DepartmentResource
    {
        return new DepartmentResource($this->findOrFail($uuid));
    }

    public function update(UpdateDepartmentRequest $request, string $uuid): DepartmentResource
    {
        $department = $this->findOrFail($uuid);
        $updated = $this->service->update($department, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update department.');
        }

        return new DepartmentResource($department->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $department = $this->findOrFail($uuid);
        $deleted = $this->service->delete($department);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete department.');
        }

        return response()->json(status: 204);
    }

    protected function findOrFail(string $uuid)
    {
        $department = $this->service->findByUuid($uuid);

        if (!$department) {
            throw new NotFoundHttpException('Department not found.');
        }

        return $department;
    }
}
