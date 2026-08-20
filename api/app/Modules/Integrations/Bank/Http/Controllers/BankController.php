<?php

namespace App\Modules\Integrations\Bank\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Integrations\Bank\Http\Requests\StoreBankRequest;
use App\Modules\Integrations\Bank\Http\Requests\UpdateBankRequest;
use App\Modules\Integrations\Bank\Http\Resources\BankResource;
use App\Modules\Integrations\Bank\Services\BankService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use RuntimeException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BankController extends Controller
{
    public function __construct(protected BankService $service)
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $statusFilter = match (request()->query('status')) {
            'active' => true,
            'inactive' => false,
            default => '',
        };

        $banks = $this->service->paginate(
            perPage: request()->integer('per_page', (int) config('modules.bank.pagination.per_page', 15)),
            filters: [
                'search' => request()->query('search'),
                'status' => $statusFilter,
            ]
        );

        return BankResource::collection($banks);
    }

    public function all(): AnonymousResourceCollection
    {
        $banks = $this->service->all();

        return BankResource::collection($banks);
    }

    public function store(StoreBankRequest $request): JsonResponse
    {
        $bank = $this->service->create($request->validated());

        return (new BankResource($bank))
            ->response()
            ->setStatusCode(201);
    }

    public function show(string $uuid): BankResource
    {
        return new BankResource($this->findOrFail($uuid));
    }

    public function update(UpdateBankRequest $request, string $uuid): BankResource
    {
        $bank = $this->findOrFail($uuid);
        $updated = $this->service->update($bank, $request->validated());

        if (!$updated) {
            throw new RuntimeException('Failed to update bank.');
        }

        return new BankResource($bank->refresh());
    }

    public function destroy(string $uuid): JsonResponse
    {
        $bank = $this->findOrFail($uuid);
        $deleted = $this->service->delete($bank);

        if (!$deleted) {
            throw new RuntimeException('Failed to delete bank.');
        }

        return response()->json(status: 204);
    }

    public function toggleStatus(string $uuid): BankResource
    {
        $bank = $this->findOrFail($uuid);
        $toggled = $this->service->toggleStatus($bank);

        if (!$toggled) {
            throw new RuntimeException('Failed to toggle bank status.');
        }

        return new BankResource($bank->refresh());
    }

    protected function findOrFail(string $uuid)
    {
        $bank = $this->service->findByUuid($uuid);

        if (!$bank) {
            throw new NotFoundHttpException('Bank not found.');
        }

        return $bank;
    }
}
