<?php


namespace App\Service\PaymentMethod;


use App\Http\Requests\Plan\PlanRequest;
use App\Models\Payment\Payment_Method;
use App\Models\Stores\Store;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Http\Request;


class PaymentMethodsServices
{
    private $payments;
    private $storemodel;
    private $PAGINATION_COUNT;

    use GeneralTrait;

    public function __construct(Payment_Method $payments,Store $storemodel)
    {
        $this->payments = $payments;
        $this->storemodel = $storemodel;
    }
    /****Get All  plans  ****/
    public function getAll()
    {
        try {
            $paymentss = $this->payments->get();
            return count($paymentss) > 0 ?
                 $this->returnData('payments', $paymentss, 'done'):
                 $this->returnSuccessMessage('payments', 'payments doesnt exist yet');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function assigningToStore(Request $request,$storeId)
    {
        try {
        if ($request->has('payments')) {
            $store = $this->storemodel->find($storeId);
            $store->Payment_Method()->syncWithoutDetaching($request->get('payments'));
             $store_payment=$store->with('Payment_Method')->get();
            return$this->returnData('payments', $store_payment, 'done');
        }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function deleteFromStore($paymentId,$storeId)
    {
        try {
            $store = $this->storemodel->find($storeId);
            $store->Payment_Method()->detach($paymentId);
            $store_payment= $store->with('Payment_Method')->get();
                return$this->returnData('payments', $store_payment, 'done');

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getByStore($storeId)
    {
             $store = $this->storemodel->with('Payment_Method')->find($storeId);
        return is_null($store)  ?
            $this->returnSuccessMessage('store payments method', 'store doesnt exist yet'):
             $this->returnData('store payments method', $store, 'done');
    }
    /*__________________________________________________________________*/
    /****Get Active plan By ID  ***
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {
        try {
//            Gate::authorize('Read Brand');
            $plan = $this->plan->findOrFail($id);
            if (!isset($plan)) {
                return  $this->returnSuccessMessage('This plan not found', 'done');
            }
            return  $this->returnData('plan', $plan, 'done');
        } catch (\Exception $ex) {
            if ($ex instanceof TokenExpiredException){
                return $this->returnError('400', $ex->getMessage());
            }
            return $this->returnError('400', $ex->getMessage());

        }
    }
    /*__________________________________________________________________*/
    /****ــــــ This Functions For Trashed plan  ****/
    /****Get All Trashed plan Or By ID  ****/
    public function getTrashed()
    {
        try {
            $plan = $this->plan->where('is_active', 0)->get();

            if (count($plan) > 0) {
                return $this->returnData('plan', $plan, 'done');
            } else {
                return $this->returnSuccessMessage('plan', 'plan trashed doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****Restore plan Fore Active status  ***
     * @param $id
     * @return JsonResponse
     */
    public function restoreTrashed($id)
    {
        try {
            $plan = $this->plan->find($id);
            if (is_null($plan)) {
                return $response = $this->returnSuccessMessage('plan', 'This plan not found');
            } else {
                $plan->is_active = true;
                $plan->save();
                return $this->returnData('plan', $plan, 'This plan Is trashed Now');
            }
        } catch (\Exception $ex) {
            if($ex instanceof AccessDeniedException)
                return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****   plan's Soft Delete   ***
     * @param $id
     * @return JsonResponse
     */
    public function trash($id)
    {
        try {
            $plan = $this->plan->find($id);
            if (is_null($plan)) {
                return $response = $this->returnSuccessMessage('plan', 'This plan not found');
            } else {
                $plan->is_active = false;
                $plan->save();
                return $this->returnData('plan', $plan, 'This plan Is trashed Now');
            }

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Create plan   ***
     * @return JsonResponse
     */
    public function create(PlanRequest $request)
    {
        try {
            $request->validated();
            $request->is_active ? $is_active = true : $is_active = false;
            /** transformation to collection */
            $allplans = collect($request->plan)->all();
            DB::beginTransaction();
            /** create the default language's ActivityType **/
            $unTransplanid = $this->plan->insertGetId([
                'is_active' => $request['is_active'],
                'activity_id'=> $request['activity_id'],
                'price_per_month'=> $request['price_per_month']
            ]);
            /** check the ActivityType and request */
            if (isset($allplans) && count($allplans)) {
                /**  insert other translations for ActivityType */
                foreach ($allplans as $allplan) {
                    $transaplan_arr[] = [
                        'name' => $allplan ['name'],
                        'local' => $allplan['local'],
                        'plan_id' => $unTransplanid,
                    ];
                }
                $this->planTranslation->insert($transaplan_arr);
            }
            DB::commit();
            return $this->returnData('plan', [$unTransplanid,$allplans], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('plan', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Update plan   ***
     * @param PlanRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(PlanRequest $request, $id)
    {
        $request->validated();
        try {
            $plan = $this->plan->find($id);
            if (!$plan)
                return $this->returnError('400', 'not found this plan');
            if (!($request->has('plans.is_active')))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);
            $unTransplan_id = $this->plan->where('plans.id', $id)
                ->update([
                    'is_active' => $request['is_active'],
                    'activity_id'=> $request['activity_id'],
                    'price_per_month'=> $request['price_per_month']
                ]);
            $request_plans = array_values($request->plan);
            foreach ($request_plans as $request_plan) {
                $this->planTranslation->where('plan_id', $id)
                    ->where('local', $request_plan['local'])
                    ->update([
                        'name' => $request_plan ['name'],
                        'local' => $request_plan['local'],
                        'plan_id' => $id,
                    ]);
            }
            DB::commit();
            return $this->returnData('plan', [$id,$request_plans], 'done');
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
            $plan = $this->plan->find($id);
            $plan ->destroy($id);
            return $this->returnData('plan', $plan, 'This plan Is deleted Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
}