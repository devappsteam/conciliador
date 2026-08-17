<?php

namespace App\Modules\Reconciliation\AcquirerConfig\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Reconciliation\AcquirerConfig\Http\Requests\StoreAcquirerConfigRequest;
use App\Modules\Reconciliation\AcquirerConfig\Http\Requests\UpdateAcquirerConfigRequest;
use App\Modules\Reconciliation\AcquirerConfig\Http\Resources\AcquirerConfigResource;
use App\Modules\Reconciliation\AcquirerConfig\Services\AcquirerConfigService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AcquirerConfigController extends Controller
{
    public function __construct(protected AcquirerConfigService $service)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $acquirerConfigs = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.acquirer-config.pagination.per_page', 15))
        );

        return AcquirerConfigResource::collection($acquirerConfigs);
    }

    public function store(StoreAcquirerConfigRequest $request): JsonResponse
    {
        $acquirerConfig = $this->service->create($request->validated());

        return (new AcquirerConfigResource($acquirerConfig))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): AcquirerConfigResource
    {
        return new AcquirerConfigResource($this->findOrFail($uuid));
    }

    public function update(UpdateAcquirerConfigRequest $request, string $uuid): AcquirerConfigResource
    {
        $acquirerConfig = $this->findOrFail($uuid);
        $updated = $this->service->update($acquirerConfig, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update acquirerConfig.');
        }

        return new AcquirerConfigResource($acquirerConfig->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $acquirerConfig = $this->findOrFail($uuid);
        $deleted = $this->service->delete($acquirerConfig);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete acquirerConfig.');
        }

        return response()->json(status: 204);
    }

    protected function findOrFail(string $uuid)
    {
        $acquirerConfig = $this->service->findByUuid($uuid);

        if (!$acquirerConfig) {
            throw new NotFoundHttpException('AcquirerConfig not found.');
        }

        return $acquirerConfig;
    }
}
