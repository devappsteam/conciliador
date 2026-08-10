<?php

namespace App\Modules\ERP\Employee\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ERP\Employee\Http\Requests\StoreEmployeeRequest;
use App\Modules\ERP\Employee\Http\Requests\UpdateEmployeeRequest;
use App\Modules\ERP\Employee\Http\Resources\EmployeeListResource;
use App\Modules\ERP\Employee\Http\Resources\EmployeeResource;
use App\Modules\ERP\Employee\Services\EmployeeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class EmployeeController extends Controller
{
    public function __construct(protected EmployeeService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $employees = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.employee.pagination.per_page', 15)),
            relations: ['department', 'position'],
        );

        return EmployeeListResource::collection($employees);
    }

    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->service->create($request->validated());

        return (new EmployeeResource($employee))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): EmployeeResource
    {
        return new EmployeeResource($this->findOrFail($uuid));
    }

    public function update(UpdateEmployeeRequest $request, string $uuid): EmployeeResource
    {
        $employee = $this->findOrFail($uuid);
        $updated = $this->service->update($employee, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update employee.');
        }

        return new EmployeeResource($employee->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $employee = $this->findOrFail($uuid);
        $deleted = $this->service->delete($employee);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete employee.');
        }

        return response()->json(status: 204);
    }

    protected function findOrFail(string $uuid)
    {
        $employee = $this->service->findByUuid(
            uuid: $uuid,
            relations: ['department', 'position'],
        );

        if (!$employee) {
            throw new NotFoundHttpException('Employee not found.');
        }

        return $employee;
    }
}
