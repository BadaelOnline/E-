<?php

namespace App\Service\Stores;

use App\Models\Stores\Store;
use App\Models\Products\Product;
use App\Models\Stores\StoreProduct;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class StoresProductsService
{
    use GeneralTrait;
    private $StoresProductsService;
    private $storeProductModel;
    private $productModel;
    private $storeModel;

    public function __construct(StoreProduct $storeProduct,Product $product,Store $store )
    {
        $this->storeProductModel=$storeProduct;
        $this->productModel=$product;
        $this->storeModel=$store;
    }
    /*__________________________________________________________________*/
    public function viewStoresHasProduct($id)
    {
        try{
         $product = $this->productModel->with('Store')->find($id);
            if (is_null($product) ){
                return $response= $this->returnSuccessMessage('This Product not found','done');
            }else{
                return $response= $this->returnData('Product in Store',$product,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    public function viewProductsInStore($id)
    {
        $product = $this->storeModel->with('Product')->find($id);
        if (is_null($product) ){
            return $response= $this->returnSuccessMessage('This Product not found','done');
        }else{
            return $response= $this->returnData('Product in Store',$product,'done');
        }
    }
    /*__________________________________________________________________*/
    public function rangeOfPrice($id)
    {
        try{
        $products = $this->storeProductModel->where('product_id',$id)->get();
           foreach($products as $product) {
               $collection1[] =
                   $product['price'];
           }
              $collection = collect($collection1)->all();
               $collectionq1 = array_values($collection);
               $max = collect($collectionq1)->max();
               $min = collect($collectionq1)->min();

               return $response = $this->returnData('range Of Price in all Store', ['max', $max, 'min', $min], 'done');
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
        }
    /*__________________________________________________________________*/
    public function insertProductToStore(Request $request)
    {
        try{
        $request->is_active?$is_active=true:$is_active=false;
        $request->is_appear?$is_appear=true:$is_appear=false;
            $Products=collect($request->Product)->all();
            $store = $this->storeModel->find($request->store_id);
            $storeProduct=new StoreProduct();
            $storeProduct->store_id =$request->store_id;
            $storeProduct->Product_id =$request->Product_id;
            $storeProduct->price = $request->price;
            $storeProduct->quantity =$request->quantity;
            $storeProduct->is_active =$request->is_active;
            $storeProduct->is_appear =$request->is_appear;
            $storeProduct->save();
        return  $this->returnData('Product in Store',$storeProduct,'done');
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
//request
//{
//"store_id": 2,
//"Product_id": 1,
//"price": "651",
//"quantity": "5450"
//}
    /*__________________________________________________________________*/
    public function updateProductInStore(Request $request,$id)
    {
        $Products=collect($request->Product)->all();
        $storeProduct=$this->storeProductModel->where('Product_id',$id)->update([
            'store_id'=>$request->Product_id,
            'Product_id' =>$request->Product_id,
            'price'=>$request->price,
            'quantity'=>$request->quantity,
        ]);
        return $response= $this->returnData('Product in Store',$storeProduct,'done');
    }
    /*__________________________________________________________________*/
    public function hiddenProductByQuantity( $id)
    {
        try{
        $product=$this->storeProductModel->find($id);
        if ($product->quantity==0)
        {
            $product=$this->storeProductModel->where('product_id',$id)->Update([
                'is_appear'=>$product['is_appear']=0
            ]);
         return $this->returnData('product', $product,'This Product Is empty Now');
        }
            else {
                return $this->returnData('product', $product,'This Product Is available Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
        }
    /*__________________________________________________________________*/
    public function updateMultyProductsPricesInStore(Request $request,$store_id)
    {
         $ids=$request->ids;
        foreach ($ids as $id) {
            $arrs[] = $id['Product_id'];
        }
        foreach($arrs as $arr) {
            $storeProduct=$this->storeProductModel
                ->where('stores_products.Product_id',$arr)
                ->where('stores_products.store_id',$store_id)
                ->update([
                'price'=>$request->newPrice
            ]);
        }
        return $this->returnData('Product in Store',$storeProduct,'done');
    }
    /*__________________________________________________________________*/
    public function updatePricesPyRatio(Request $request,$store_id)
    {
         $ids=$request->ids;
        foreach ($ids as $id) {
            $arrs[] = $id['Product_id'];
        }
        foreach ($ids as $id1) {
            $arrs1[] = (($id1['price']*$request->ratio)/100)+$id1['price'];
        }
        foreach($arrs as $arr) {
            foreach ($arrs1 as $arr1) {
                $storeProduct = $this->storeProductModel
                    ->where('stores_products.Product_id', $arr)
                    ->where('stores_products.store_id', $store_id)
                    ->update([
                        'price' => $arr1
                    ]);
            }
        }
        return $this->returnData('Product in Store',$storeProduct,'done');
    }
}




