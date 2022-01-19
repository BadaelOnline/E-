<?php

namespace App\Service\Subscriptions;

use App\Http\Requests\Plan\PlanRequest;
use App\Http\Requests\Subscription\SubscriptionRequest;
use App\Models\Plans\Subscription;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class SubscriptionsService
{
    private $subscriptionModel;
    private $PAGINATION_COUNT;

    use GeneralTrait;

    public function __construct(Subscription $subscriptionModel)
    {
        $this->subscriptionModel = $subscriptionModel;
    }
    /****Get All  subscriptions  ****/
    public function getAll()
    {
        try {
            $subscription = $this->subscriptionModel->with(['Transaction','Plan','store'])->get();
            if (count($subscription) > 0) {
                return $this->returnData('subscription', $subscription, 'done');
            } else {
                return $this->returnSuccessMessage('subscription', 'subscription doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****Get Active subscription By ID  ***
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {
        try {
//            Gate::authorize('Read Brand');
            $subscription = $this->subscription->findOrFail($id);
            if (!isset($subscription)) {
                return  $this->returnSuccessMessage('This subscription not found', 'done');
            }
            return  $this->returnData('subscription', $subscription, 'done');
        } catch (\Exception $ex) {
            if ($ex instanceof TokenExpiredException){
                return $this->returnError('400', $ex->getMessage());
            }
            return $this->returnError('400', $ex->getMessage());

        }
    }
    /*__________________________________________________________________*/
    /****ــــــ This Functions For Trashed subscription  ****/
    /****Get All Trashed subscription Or By ID  ****/
    public function getTrashed()
    {
        try {
            $subscription = $this->subscription->where('is_active', 0)->get();

            if (count($subscription) > 0) {
                return $this->returnData('subscription', $subscription, 'done');
            } else {
                return $this->returnSuccessMessage('subscription', 'subscription trashed doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****Restore subscriptionsubscription Fore Active status  ***
     * @param $id
     * @return JsonResponse
     */
    public function restoreTrashed($id)
    {
        try {
            $subscription = $this->subscription->find($id);
            if (is_null($subscription)) {
                return $response = $this->returnSuccessMessage('subscription', 'This subscriptionsubscription not found');
            } else {
                $subscription->is_active = true;
                $subscription->save();
                return $this->returnData('subscription', $subscription, 'This subscriptionsubscription Is trashed Now');
            }
        } catch (\Exception $ex) {
            if($ex instanceof AccessDeniedException)
                return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****   subscriptionsubscription's Soft Delete   ***
     * @param $id
     * @return JsonResponse
     */
    public function trash($id)
    {
        try {
            $subscriptionsubscription = $this->subscriptionsubscription->find($id);
            if (is_null($subscriptionsubscription)) {
                return $response = $this->returnSuccessMessage('subscriptionsubscription', 'This subscriptionsubscription not found');
            } else {
                $subscriptionsubscription->is_active = false;
                $subscriptionsubscription->save();
                return $this->returnData('subscriptionsubscription', $subscriptionsubscription, 'This subscriptionsubscription Is trashed Now');
            }

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Create plan   ***
     * @return JsonResponse
     */
    public function create(SubscriptionRequest $request)
    {
        try {
            $request->validated();
            $request->is_active ? $is_active = true : $is_active = false;
            /** transformation to collection */
            DB::beginTransaction();
            /** create the default language's ActivityType **/
            $subscription = $this->subscription->create([
                'is_active' => $request['is_active'],
                'store_id'=> $request['store_id'],
                'plan_id'=> $request['plan_id'],
                'start_date'=> $request['start_date'],
                'end_date'=> $request['end_date'],
                'transaction_id'=> $request['transaction_id'],
            ]);
            DB::commit();
            return $this->returnData('subscription', $request, 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('subscription', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Update plan   ***
     * @param PlanRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(SubscriptionRequest $request, $id)
    {
        $request->validated();
        try {
            $subscription = $this->subscription->find($id);
            if (!$subscription)
                return $this->returnError('400', 'not found this subscription');
            if (!($request->has('subscriptions.is_active')))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);
            $subscription_update = $this->plan->where('subscriptions.id', $id)
                ->update([
                    'is_active' => $request['is_active'],
                    'store_id'=> $request['store_id'],
                    'plan_id'=> $request['plan_id'],
                    'start_date'=> $request['start_date'],
                    'end_date'=> $request['end_date'],
                    'transaction_id'=> $request['transaction_id'],
                ]);
            DB::commit();
            return $this->returnData('plan', [$id ,$request ], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Delete plan   ***
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        try {
            $subscription = $this->subscription->find($id);
            $subscription ->destroy($id);
            return $this->returnData('subscription', $subscription, 'This subscription Is deleted Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
}
