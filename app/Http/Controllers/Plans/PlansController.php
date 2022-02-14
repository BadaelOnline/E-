<?php

namespace App\Http\Controllers\Plans;

use App\Http\Controllers\Controller;
use App\Http\Requests\Plan\PlanRequest;
use App\Service\Plans\PlansService;
use App\Traits\GeneralTrait;

class PlansController extends Controller
{
    use GeneralTrait;
    private $plansService;

    public function __construct(PlansService $plansService)
    {
        $this->plansService = $plansService;
    }

    public function getAll()
    {
        return $this->plansService->getAll();
    }

    public function getById($id)
    {
        return $this->plansService->getById($id);
    }

    public function getByActivity($activity_id)
    {
        return $this->plansService->getByActivity($activity_id);
    }

    public function getTrashed()
    {
        return $this->plansService->getTrashed();
    }

    public function create(PlanRequest $request)
    {
        return $this->plansService->create($request);
    }

    public function update(PlanRequest $request, $id)
    {
        return $this->plansService->update($request, $id);
    }

    public function trash($id)
    {
        return $this->plansService->trash($id);
    }

    public function restoreTrashed($id)
    {
        return $this->plansService->restoreTrashed($id);
    }

    public function delete($id)
    {
        return $this->plansService->delete($id);
    }
}
