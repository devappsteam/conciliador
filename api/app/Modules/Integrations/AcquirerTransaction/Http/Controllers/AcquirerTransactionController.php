<?php

namespace App\Modules\Integrations\AcquirerTransaction\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Integrations\AcquirerTransaction\Http\Requests\StoreAcquirerTransactionRequest;
use App\Modules\Integrations\AcquirerTransaction\Http\Requests\UpdateAcquirerTransactionRequest;
use App\Modules\Integrations\AcquirerTransaction\Http\Resources\AcquirerTransactionResource;
use App\Modules\Integrations\AcquirerTransaction\Services\AcquirerTransactionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AcquirerTransactionController extends Controller
{
    public function __construct(protected AcquirerTransactionService $service)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $acquirerTransactions = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.acquirer-transaction.pagination.per_page', 15))
        );

        return AcquirerTransactionResource::collection($acquirerTransactions);
    }

    public function store(StoreAcquirerTransactionRequest $request): JsonResponse
    {
        $acquirerTransaction = $this->service->create($request->validated());

        return (new AcquirerTransactionResource($acquirerTransaction))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): AcquirerTransactionResource
    {
        return new AcquirerTransactionResource($this->findOrFail($uuid));
    }

    public function update(UpdateAcquirerTransactionRequest $request, string $uuid): AcquirerTransactionResource
    {
        $acquirerTransaction = $this->findOrFail($uuid);
        $updated = $this->service->update($acquirerTransaction, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update acquirerTransaction.');
        }

        return new AcquirerTransactionResource($acquirerTransaction->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $acquirerTransaction = $this->findOrFail($uuid);
        $deleted = $this->service->delete($acquirerTransaction);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete acquirerTransaction.');
        }

        return response()->json(status: 204);
    }

    protected function findOrFail(string $uuid)
    {
        $acquirerTransaction = $this->service->findByUuid($uuid);

        if (!$acquirerTransaction) {
            throw new NotFoundHttpException('AcquirerTransaction not found.');
        }

        return $acquirerTransaction;
    }
}
