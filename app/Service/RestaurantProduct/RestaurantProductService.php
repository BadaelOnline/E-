<?php


namespace App\Service\RestaurantProduct;


use App\Http\Requests\RestaurantProduct\RestaurantProductRequest;
use App\Models\RestaurantProduct\RestaurantProduct;
use App\Models\RestaurantProduct\RestaurantProductTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class RestaurantProductService
{
    private $RestaurantProductModel;
    use GeneralTrait;

    public function __construct(RestaurantProduct $RestaurantProduct)
    {
        $this->RestaurantProductModel=$RestaurantProduct;
    }
    public function get()
    {
        try {
            $Product = $this->RestaurantProductModel::paginate(5);
            return $this->returnData('Restaurant Product', $Product, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getById($id)
    {
        try{
            $Product= $this->RestaurantProductModel::find($id);
            if (is_null($Product)){
                return $this->returnSuccessMessage('this Restaurant Product not found','done');
            }
            else{
                return $this->returnData('Restaurant Product',$Product,'done');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }
    //__________________________________________________________//
    public function create( RestaurantProductRequest $request )
    {
        try {
            $allProduct = collect($request->RestaurantProduct)->all();
            DB::beginTransaction();
            $unTransProduct_id = RestaurantProduct::insertGetId([
                'image'   => $request['image'],
                'is_approved' => $request['is_approved'],
                'is_active'   => $request['is_active'],
                'item_id'   => $request['item_id'],

            ]);
            if (isset($allProduct)) {
                foreach ($allProduct as $allProducts) {
                    $transProduct[] = [
                        'name' => $allProducts ['name'],
                        'short_description' => $allProducts ['short_description'],
                        'long_description' => $allProducts ['long_description'],
                        'locale' => $allProducts['locale'],
                        'restaurant_product_id' => $unTransProduct_id,
                    ];
                }
                RestaurantProductTranslation::insert($transProduct);
             
            }
            DB::commit();
            return $this->returnData('Restaurant Product',[$unTransProduct_id, $transProduct],'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError('Restaurant Product', $ex->getMessage());
        }

    }
    //___________________________________________________________________//
    public function update(RestaurantProductRequest $request,$id)
    {
        try{
            $Product= RestaurantProduct::find($id);
            if(!$Product)
                return $this->returnError('400', 'not found this Restaurant Product');
            $allProduct = collect($request->RestaurantProduct)->all();
            if (!($request->has('restaurant_Products.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            $newProduct=RestaurantProduct::where('restaurant_Products.id',$id)
                ->update([
                    'image'   => $request['image'],
                    'is_approved' => $request['is_approved'],
                    'is_active'   => $request['is_active'],
                    'item_id'   => $request['item_id'],

                ]);

            $ss=RestaurantProductTranslation::where('restaurant_product_translations.restaurant_product_id',$id);
            $collection1 = collect($allProduct);
            $allProductlength=$collection1->count();
            $collection2 = collect($ss);

            $db_Product= array_values(RestaurantProductTranslation::where('restaurant_product_translations.restaurant_product_id',$id)
                ->get()
                ->all());
            $dbProduct = array_values($db_Product);
            $request_Product= array_values($request->RestaurantProduct);
            foreach($dbProduct as $dbProducts){
                foreach($request_Product as $request_Products){
                    $values=RestaurantProductTranslation::where('restaurant_product_translations.restaurant_product_id',$id)
                        ->where('locale',$request_Products['locale'])
                        ->update([
                            'name' => $request_Products ['name'],
                            'short_description' => $request_Products ['short_description'],
                            'long_description' => $request_Products ['long_description'],
                            'locale' => $request_Products['locale'],
                            'restaurant_product_id' => $id,
                        ]);
                }
            }
            DB::commit();
            return $this->returnData(' restaurant Product', [$dbProduct,$values],'done');
        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError('400', $ex->getMessage());
        }
    }
    //____________________________________________________________//
    public function search($name)
    {
        try {
            $Product = DB::table('restaurant_product_translations')
                ->where("name", "like", "%" . $name . "%")
                ->get();
            if (!$Product) {
                return $this->returnError('400', 'not found this Restaurant Product');
            } else {
                return $this->returnData('Restaurant Product', $Product, 'done');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function trash( $id)
    {
        try{
            $Product= $this->RestaurantProductModel::find($id);
            if (is_null($Product)) {
                return $this->returnSuccessMessage('This Restaurant Product not found', 'done');
            }
            else
            {
                $Product->is_active=0;
                $Product->save();
                return $this->returnData('Restaurant Product', $Product,'This Restaurant Product is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }

    }
    public function getTrashed()
    {
        try{
            $Product= $this->RestaurantProductModel::NotActive()->all();
            return $this -> returnData('Restaurant Product',$Product,'done');
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }    }
    public function restoreTrashed( $id)
    {
        try{
            $Product=RestaurantProduct::find($id);
            if (is_null($Product)) {
                return $this->returnSuccessMessage('This Restaurant Product not found', 'done');
            }
            else
            {
                $Product->is_active=1;
                $Product->save();
                return $this->returnData('Restaurant Product', $Product,'This Restaurant Product is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    //________________________________________//
    public function delete($id)
    {
        try{
            $Product = RestaurantProduct::find($id);
            if ($Product->is_active == 0) {

                $Product->delete();
                $Product->hospitalTranslation()->delete();
                return $this->returnData('Restaurant Product', $Product, 'This Restaurant Product is deleted Now');
            }
            else{
                return $this->returnData('Restaurant Product', $Product, 'This Restaurant Product can not deleted Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getRestaurant($id)
    {
        try{
            $Product= RestaurantProduct::with('Restaurant')->find($id);
            return $this->returnData('Restaurant Product', $Product, 'done');


        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getCategory($id)
    {
        try{
            $Product=   RestaurantProduct::with('RestaurantCategory')->find($id);
            return $this->returnData('Restaurant Product', $Product, 'done');

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
}
