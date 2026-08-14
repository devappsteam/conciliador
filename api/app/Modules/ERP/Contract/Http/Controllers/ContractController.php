<?php

namespace App\Modules\ERP\Contract\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\ERP\Contract\Http\Requests\StoreContractRequest;
use App\Modules\ERP\Contract\Http\Requests\UpdateContractRequest;
use App\Modules\ERP\Contract\Http\Resources\ContractResource;
use App\Modules\ERP\Contract\Http\Resources\ContractListResource;
use App\Modules\ERP\Contract\Services\ContractService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ContractController extends Controller
{
    public function __construct(protected ContractService $service)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $contracts = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.contract.pagination.per_page', 15)),
            relations: ['company'],
        );

        return ContractListResource ::collection($contracts);
    }

    public function store(StoreContractRequest $request): JsonResponse
    {
        $contract = $this->service->create($request->validated());

        return (new ContractResource($contract))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): ContractResource
    {
        return new ContractResource($this->findOrFail($uuid));
    }

    public function update(UpdateContractRequest $request, string $uuid): ContractResource
    {
        $contract = $this->findOrFail($uuid);
        $updated = $this->service->update($contract, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update contract.');
        }

        return new ContractResource($contract->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $contract = $this->findOrFail($uuid);
        $deleted = $this->service->delete($contract);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete contract.');
        }

        return response()->json(status: 204);
    }

    protected function findOrFail(string $uuid)
    {
        $contract = $this->service->findByUuid($uuid);

        if (!$contract) {
            throw new NotFoundHttpException('Contract not found.');
        }

        return $contract;
    }
}
