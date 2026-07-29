<?php

namespace App\Modules\Core\Company\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Core\Company\Http\Requests\StoreCompanyRequest;
use App\Modules\Core\Company\Http\Requests\UpdateCompanyRequest;
use App\Modules\Core\Company\Http\Resources\CompanyResource;
use App\Modules\Core\Company\Services\CompanyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CompanyController extends Controller
{
    public function __construct(protected CompanyService $service)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $companies = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.company.pagination.per_page', 15))
        );

        return CompanyResource::collection($companies);
    }

    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = $this->service->create($request->validated());

        return (new CompanyResource($company))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): CompanyResource
    {
        return new CompanyResource($this->findOrFail($uuid));
    }

    public function update(UpdateCompanyRequest $request, string $uuid): CompanyResource
    {
        $company = $this->findOrFail($uuid);
        $updated = $this->service->update($company, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update company.');
        }

        return new CompanyResource($company->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $company = $this->findOrFail($uuid);
        $deleted = $this->service->delete($company);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete company.');
        }

        return response()->json(status: 204);
    }

    protected function findOrFail(string $uuid)
    {
        $company = $this->service->findByUuid($uuid);

        if (!$company) {
            throw new NotFoundHttpException('Company not found.');
        }

        return $company;
    }
}
