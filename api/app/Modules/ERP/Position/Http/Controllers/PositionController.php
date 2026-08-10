<?php

namespace App\Modules\ERP\Position\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ERP\Position\Http\Requests\StorePositionRequest;
use App\Modules\ERP\Position\Http\Requests\UpdatePositionRequest;
use App\Modules\ERP\Position\Http\Resources\PositionResource;
use App\Modules\ERP\Position\Http\Resources\PositionListResource;
use App\Modules\ERP\Position\Services\PositionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PositionController extends Controller
{
    public function __construct(protected PositionService $service) {}

    public function index(): AnonymousResourceCollection
    {
        $positions = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.position.pagination.per_page', 15))
        );

        return PositionListResource::collection($positions);
    }

    public function all(): AnonymousResourceCollection
    {
        $positions = $this->service->all(
            relations: []
        );

        return PositionListResource::collection($positions);
    }

    public function store(StorePositionRequest $request): JsonResponse
    {
        $position = $this->service->create($request->validated());

        return (new PositionResource($position))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): PositionResource
    {
        return new PositionResource($this->findOrFail($uuid));
    }

    public function update(UpdatePositionRequest $request, string $uuid): PositionResource
    {
        $position = $this->findOrFail($uuid);
        $updated = $this->service->update($position, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update position.');
        }

        return new PositionResource($position->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $position = $this->findOrFail($uuid);
        $deleted = $this->service->delete($position);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete position.');
        }

        return response()->json(status: 204);
    }

    protected function findOrFail(string $uuid)
    {
        $position = $this->service->findByUuid($uuid);

        if (!$position) {
            throw new NotFoundHttpException('Position not found.');
        }

        return $position;
    }
}
