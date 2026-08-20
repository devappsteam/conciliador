<?php

namespace App\Modules\Integrations\Brand\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Integrations\Brand\Http\Requests\StoreBrandRequest;
use App\Modules\Integrations\Brand\Http\Requests\UpdateBrandRequest;
use App\Modules\Integrations\Brand\Http\Resources\BrandResource;
use App\Modules\Integrations\Brand\Services\BrandService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BrandController extends Controller
{
    public function __construct(protected BrandService $service)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $statusFilter = match (request()->query('status')) {
            'active' => true,
            'inactive' => false,
            default => '',
        };

        $brands = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.brand.pagination.per_page', 15)),
            filters: [
                'search' => request()->query('search'),
                'status' => $statusFilter,
            ]
        );

        return BrandResource::collection($brands);
    }

    public function all(): AnonymousResourceCollection
    {
        $brands = $this->service->all();

        return BrandResource::collection($brands);
    }

    public function store(StoreBrandRequest $request): JsonResponse
    {
        $brand = $this->service->create($request->validated());

        return (new BrandResource($brand))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): BrandResource
    {
        return new BrandResource($this->findOrFail($uuid));
    }

    public function update(UpdateBrandRequest $request, string $uuid): BrandResource
    {
        $brand = $this->findOrFail($uuid);
        $updated = $this->service->update($brand, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update brand.');
        }

        return new BrandResource($brand->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $brand = $this->findOrFail($uuid);
        $deleted = $this->service->delete($brand);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete brand.');
        }

        return response()->json(status: 204);
    }

    public function toggleStatus(string $uuid): BrandResource
    {
        $brand = $this->findOrFail($uuid);
        $toggled = $this->service->toggleStatus($brand);

        if (!$toggled) {
            throw new RuntimeException('Failed to toggle brand status.');
        }

        return new BrandResource($brand->refresh());
    }

    protected function findOrFail(string $uuid)
    {
        $brand = $this->service->findByUuid($uuid);

        if (!$brand) {
            throw new NotFoundHttpException('Brand not found.');
        }

        return $brand;
    }
}
