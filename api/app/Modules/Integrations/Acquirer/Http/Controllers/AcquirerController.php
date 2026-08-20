<?php

namespace App\Modules\Integrations\Acquirer\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Integrations\Acquirer\Http\Requests\StoreAcquirerRequest;
use App\Modules\Integrations\Acquirer\Http\Requests\UpdateAcquirerRequest;
use App\Modules\Integrations\Acquirer\Http\Resources\AcquirerResource;
use App\Modules\Integrations\Acquirer\Services\AcquirerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AcquirerController extends Controller
{
    public function __construct(protected AcquirerService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $statusFilter = match (request()->query('status')) {
            'active' => true,
            'inactive' => false,
            default => '',
        };

        $acquirers = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.acquirer.pagination.per_page', 15)),
            filters: [
                'search' => request()->query('search'),
                'status' => $statusFilter,
            ]
        );

        return AcquirerResource::collection($acquirers);
    }

    public function all(): AnonymousResourceCollection
    {
        $acquirers = $this->service->all();

        return AcquirerResource::collection($acquirers);
    }

    public function store(StoreAcquirerRequest $request): JsonResponse
    {
        $acquirer = $this->service->create($request->validated());

        return (new AcquirerResource($acquirer))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): AcquirerResource
    {
        return new AcquirerResource($this->findOrFail($uuid));
    }

    public function update(UpdateAcquirerRequest $request, string $uuid): AcquirerResource
    {
        $acquirer = $this->findOrFail($uuid);
        $updated = $this->service->update($acquirer, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update acquirer.');
        }

        return new AcquirerResource($acquirer->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $acquirer = $this->findOrFail($uuid);
        $deleted = $this->service->delete($acquirer);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete acquirer.');
        }

        return response()->json(status: 204);
    }

    public function toggleStatus(string $uuid): AcquirerResource
    {
        $acquirer = $this->findOrFail($uuid);
        $toggled = $this->service->toggleStatus($acquirer);

        if (!$toggled) {
            throw new RuntimeException('Failed to toggle acquirer status.');
        }

        return new AcquirerResource($acquirer->refresh());
    }

    protected function findOrFail(string $uuid)
    {
        $acquirer = $this->service->findByUuid($uuid);

        if (!$acquirer) {
            throw new NotFoundHttpException('Acquirer not found.');
        }

        return $acquirer;
    }
}
