<?php

namespace App\Http\Controllers\Interaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Interaction\InteractionRequest;
use App\Service\Interaction\InteractionService;
use Illuminate\Http\Request;

class InteractionController extends Controller
{
    Private $InteractionService;

    public function __construct(InteractionService $InteractionService)
    {
        $this->InteractionService=$InteractionService;
    }
    public function get()
    {
        return $this->InteractionService->get();
    }
    public function getById($id)
    {
        return $this->InteractionService->getById($id);
    }
    public function create(InteractionRequest $request)
    {
        return $this->InteractionService->create($request);
    }
    public function update(InteractionRequest $request,$id)
    {
        return $this->InteractionService->update($request,$id);
    }
    public function trash($id)
    {
        return $this->InteractionService->trash($id);
    }
    public function gettrashed()
    {
        return $this->InteractionService->gettrashed();
    }
    public function restoreTrashed($id)
    {
        return $this->InteractionService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->InteractionService->delete($id);
    }
}
