<?php


namespace App\Service\ShippingMethods;


use App\Http\Requests\Plan\PlanRequest;
use App\Models\Shipping\Shipping_Method;
use App\Models\Stores\Store;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class ShippingMethodsServices

{
    private $shipping;
    private $storemodel;
    private $PAGINATION_COUNT;

    use GeneralTrait;

    public function __construct(Shipping_Method $shipping,Store $storemodel)
    {
        $this->shipping = $shipping;
        $this->storemodel = $storemodel;
    }
    /****Get All  plans  ****/
    public function getAll()
    {
        try {
            $shipping = $this->shipping->get();
            return count($shipping) > 0 ?
                $this->returnData('shipping', $shipping, 'done'):
                $this->returnSuccessMessage('shipping', 'shipping doesnt exist yet');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function assigningToStore(Request $request,$storeId)
    {
        if ($request->has('shipping_methods')) {
            $store = $this->storemodel->find($storeId);
            $store->Shipping_Method()->syncWithoutDetaching($request->get('shipping_methods'));
            return $store->with('Shipping_Method')->get();
        }
    }
    public function deleteFromStore($shippingId,$storeId)
    {
        $store = $this->storemodel->find($storeId);
        $store->Shipping_Method()->detach($shippingId);
        return $store->with('Shipping_Method')->get();
    }
    public function getByStore($storeId)
    {
        $store = $this->storemodel->with('Shipping_Method')->find($storeId);
        return is_null($store)  ?
            $this->returnSuccessMessage('store Shipping_Method', 'store doesnt exist yet'):
            $this->returnData('store Shipping_Method', $store, 'done');
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
            $shipping = $this->shipping->findOrFail($id);
            if (!isset($shipping)) {
                return  $this->returnSuccessMessage('This shipping not found', 'done');
            }
            return  $this->returnData('shipping', $shipping, 'done');
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
            $shipping = $this->shipping->where('is_active', 0)->get();

            if (count($shipping) > 0) {
                return $this->returnData('shipping', $shipping, 'done');
            } else {
                return $this->returnSuccessMessage('plan', 'shipping trashed doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****Restore shipping Fore Active status  ***
     * @param $id
     * @return JsonResponse
     */
    public function restoreTrashed($id)
    {
        try {
            $shipping = $this->shipping->find($id);
            if (is_null($shipping)) {
                return  $this->returnSuccessMessage('shipping', 'This shipping not found');
            } else {
                $shipping->is_active = true;
                $shipping->save();
                return $this->returnData('shipping', $shipping, 'This shipping Is trashed Now');
            }
        } catch (\Exception $ex) {
            if($ex instanceof AccessDeniedException)
                return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****   shipping's Soft Delete   ***
     * @param $id
     * @return JsonResponse
     */
    public function trash($id)
    {
        try {
            $shipping = $this->shipping->find($id);
            if (is_null($shipping)) {
                return $response = $this->returnSuccessMessage('shipping', 'This shipping not found');
            } else {
                $shipping->is_active = false;
                $shipping->save();
                return $this->returnData('shipping', $shipping, 'This shipping Is trashed Now');
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
            $allplans = collect($request->shipping)->all();
            DB::beginTransaction();
            /** create the default language's ActivityType **/
            $unTransshippingid = $this->shipping->insertGetId([
                'is_active' => $request['is_active'],
                'activity_id'=> $request['activity_id'],
                'price_per_month'=> $request['price_per_month']
            ]);
            /** check the ActivityType and request */
            if (isset($allshippings) && count($allshippings)) {
                /**  insert other translations for ActivityType */
                foreach ($allshippings as $allshipping) {
                    $transshipping_arr[] = [
                        'name' => $allshipping ['name'],
                        'local' => $allshipping['local'],
                        'plan_id' => $unTransshippingid,
                    ];
                }
                $this->shippingTranslation->insert($transshipping_arr);
            }
            DB::commit();
            return $this->returnData('shipping', [$unTransshippingid,$allshippings], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('shipping', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Update shipping   ***
     * @param shippingRequest $request
     * @param $id
     * @return JsonResponse
     */
    public function update(PlanRequest $request, $id)
    {
        $request->validated();
        try {
            $plan = $this->shipping->find($id);
            if (!$plan)
                return $this->returnError('400', 'not found this shipping');
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
            $shipping = $this->shipping->find($id);
            $shipping ->destroy($id);
            return $this->returnData('shipping', $shipping, 'This shipping Is deleted Now');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
}