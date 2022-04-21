<?php

namespace App\Service\Stores;

use App\Models\Stores\Store;
use App\Models\Products\Product;
use App\Models\Stores\StoreProduct;
use App\Models\Stores\StoreProductDetails;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StoresProductsService
{
    use GeneralTrait;

    private $storeProductModel;
    private $productModel;
    private $storeModel;
    private $details;

    public function __construct(StoreProduct        $storeProductModel,
                                Product             $product, Store $store,
                                StoreProductDetails $details)
    {
        $this->storeProductModel = $storeProductModel;
        $this->productModel = $product;
        $this->storeModel = $store;
        $this->details = $details;
    }

    /*__________________________________________________________________*/
    public function viewStoresHasProduct($id)
    {
        try {
            $product = $this->productModel->with('Store')->find($id);
            if (is_null($product)) {
                return $this->returnSuccessMessage('This Product not found', 'done');
            } else {
                return $this->returnData('Product in Store', $product, 'done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /*__________________________________________________________________*/
    public function viewProductsInStore($store_id)
    {
        $product = $this->storeProductModel
            ->where('store_id', $store_id)
            ->getStoreProductsList()
            ->get();
        if (count($product) > 0) {
            return $this->returnData('Product in Store', $product, 'done');
        } else {
            return $this->returnSuccessMessage('This Product not found', 'done');
        }
    }

    /*__________________________________________________________________*/
    public function viewProductsDetailsInStore($product_id)
    {
        $product = $this->productModel
            ->with(['Custom_Field_Value' => function ($q) {
                return $q->with('Custom_field')->get();
            }])->find($product_id);
        if (!is_null($product)) {
            return $this->returnData('Product in Store', $product, 'done');
        } else {
            return $this->returnSuccessMessage('This Product not found', 'done');
        }
    }

    /*__________________________________________________________________*/
    public function rangeOfPrice($id)
    {
        try {
            $products = $this->storeProductModel->where('product_id', $id)->get();
            foreach ($products as $product) {
                $collection1[] =
                    $product['price'];
            }
            $collection = collect($collection1)->all();
            $collectionq1 = array_values($collection);
            $max = collect($collectionq1)->max();
            $min = collect($collectionq1)->min();

            return $response = $this->returnData('range Of Price in all Store', ['max', $max, 'min', $min], 'done');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /*__________________________________________________________________*/
    public function insertProductToStore(Request $request, $store_id)
    {
        try {
            $Products = collect($request->products)->all();
            DB::beginTransaction();
            foreach ($Products as $product) {
                $store_products_id = $this->storeProductModel->insertGetId([
                    'store_id' => $store_id,
                    'product_id' => $product['id'],
                ]);
                $store_products_details_ids[] = $this->details->insertGetId([
                    'price' => $product['price'],
                    'quantity' => $product['quantity'],
                    'store_products_id' => $store_products_id,
                ]);
                foreach ($store_products_details_ids as $store_products_details_id) {
                    if (isset($product['Custom_Field_Value'])) {
                        $details = $this->details->find($store_products_details_id);
                        $details->Custom_Field_Value()->syncWithoutDetaching($product['Custom_Field_Value']);
                    }
                }
            }
            DB::commit();
            return $this->returnData('Product in Store', $Products, 'done');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError('400', [$ex->getMessage(), $ex->getLine()]);
        }
    }

    /*__________________________________________________________________*/
    public function updateProductInStore(Request $request, $store_id, $product_id)
    {
        try {
            DB::beginTransaction();
            $store_products = StoreProduct::
            with(['Product', 'StoreProductDetails'])
                ->where('product_id', $product_id)
                ->where('store_id', $store_id)
                ->first();
            $details_new_value = collect($request->store_product_details)->first();
            $this->updateProductDetailsInStore(
                $details_new_value['price'],
                $details_new_value['quantity'],
                $details_new_value['id']
            );
            DB::commit();
            return $this->returnData('New Product in Store', $details_new_value, 'done');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError('400', [$ex->getMessage(), $ex->getLine()]);
        }
    }

    protected function updateProductDetailsInStore($price, $quantity, $detailsId)
    {
        try {
            $details = StoreProductDetails::find($detailsId);
            $newDetails = $details->update([
                'price' => $price,
                'quantity' => $quantity
            ]);
        } catch (\Exception $ex) {
            return $this->returnError('400', [$ex->getMessage(), $ex->getLine()]);
        }
    }

    /*__________________________________________________________________*/
    public function hiddenProductByQuantity($id)
    {
        try {
            $product = $this->storeProductModel->find($id);
            if ($product->quantity == 0) {
                $product = $this->storeProductModel->where('product_id', $id)->Update([
                    'is_appear' => $product['is_appear'] = 0
                ]);
                return $this->returnData('product', $product, 'This Product Is empty Now');
            } else {
                return $this->returnData('product', $product, 'This Product Is available Now');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /*__________________________________________________________________*/
    public function updateMultyProductsPricesInStore(Request $request, $store_id)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $arrs[] = $id['Product_id'];
        }
        foreach ($arrs as $arr) {
            $storeProduct = $this->storeProductModel
                ->where('stores_products.Product_id', $arr)
                ->where('stores_products.store_id', $store_id)
                ->update([
                    'price' => $request->newPrice
                ]);
        }
        return $this->returnData('Product in Store', $storeProduct, 'done');
    }

    /*__________________________________________________________________*/
    public function updatePricesPyRatio(Request $request, $store_id)
    {
        $ids = $request->ids;
        foreach ($ids as $id) {
            $arrs[] = $id['Product_id'];
        }
        foreach ($ids as $id1) {
            $arrs1[] = (($id1['price'] * $request->ratio) / 100) + $id1['price'];
        }
        foreach ($arrs as $arr) {
            foreach ($arrs1 as $arr1) {
                $storeProduct = $this->storeProductModel
                    ->where('stores_products.Product_id', $arr)
                    ->where('stores_products.store_id', $store_id)
                    ->update([
                        'price' => $arr1
                    ]);
            }
        }
        return $this->returnData('Product in Store', $storeProduct, 'done');
    }
}




