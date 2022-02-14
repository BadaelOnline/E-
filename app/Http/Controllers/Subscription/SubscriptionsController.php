<?php

namespace App\Http\Controllers\Subscription;

use App\Http\Controllers\Controller;
use App\Http\Requests\Subscription\SubscriptionRequest;
use App\Service\Subscriptions\SubscriptionsService;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;

class SubscriptionsController extends Controller
{
    use GeneralTrait;
    private $subscriptionsService;
    public function __construct(SubscriptionsService $subscriptionsService)
    {
        $this->subscriptionsService=$subscriptionsService;
    }
    public function getAll()
    {
        return $this->subscriptionsService->getAll();
    }
    public function getById($id)
    {
        return $this->subscriptionsService->getById($id);
    }
    public function getTrashed()
    {
        return $this->subscriptionsService->getTrashed();
    }
    public function create(Request $request,$store_id)
    {
        return $this->subscriptionsService->create($request,$store_id);
    }
    public function update(SubscriptionRequest $request,$id)
    {
        return $this->subscriptionsService->update($request,$id);
    }
    public function trash($id)
    {
        return $this->subscriptionsService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->subscriptionsService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->subscriptionsService->delete($id);
    }
}
