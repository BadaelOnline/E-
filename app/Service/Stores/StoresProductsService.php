<?php

namespace App\Service\Stores;

use App\Models\Categories\Category;
use App\Models\Stores\Store;
use App\Models\Products\Product;
use App\Models\Stores\StoreProduct;
use App\Models\Stores\StoreProductDetails;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class StoresProductsService
{
    use GeneralTrait;

    private $storeProductModel;
    private $productModel;
    private $storeModel;
    private $details;
    private $category;

    public function __construct(StoreProduct        $storeProductModel,
                                Product             $product,
                                Store               $store,
                                StoreProductDetails $details,
                                Category            $category)
    {
        $this->storeProductModel = $storeProductModel;
        $this->productModel = $product;
        $this->storeModel = $store;
        $this->details = $details;
        $this->category = $category;
    }

    public function viewStoresHasProduct($id): JsonResponse
    {
        try {
            $product = $this->productModel->with(['StoreProduct'=> function($q){
                return $q->with(['Store','StoreProductDetails'])->get();
            }])->find($id);
            if (is_null($product)) {
                return $this->returnSuccessMessage('This Product not found', 'done');
            } else {
                return $this->returnData('Product in Store', $product, 'done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function viewProductByCategory($category_id): JsonResponse
    {
        try {
            $product = $this->category->with(['Product' => function ($q) {
                return $q->with(['StoreProduct' => function ($q) {
                    return $q->with(['StoreProductDetails' => function ($q) {
                        return $q->with(['Custom_Field_Value'])->get();
                    }])->get();
                }])->get();
            }])->find($category_id);
            if (is_null($product)) {
                return $this->returnSuccessMessage('This Product Not Found', 'done');
            } else {
                return $this->returnData('Products In Category', $product, 'done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function viewProductByCategoryDetails($product_id): JsonResponse
    {
        try {
            $product = $this->productModel->getCategoryProductsList()->find($product_id);
            if (is_null($product)) {
                return $this->returnSuccessMessage('This Product not found', 'done');
            } else {
                return $this->returnData('Category', $product, 'done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function viewProductsInStore($store_id): JsonResponse
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

    public function viewProductsDetailsInStore($product_id): JsonResponse
    {
        $product = $this->storeProductModel
            ->getStoreProductsList()->find($product_id);
        if (!is_null($product)) {
            return $this->returnData('Product in Store', $product, 'done');
        } else {
            return $this->returnSuccessMessage('This Product not found', 'done');
        }
    }

    public function rangeOfPrice($id): JsonResponse
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

    public function insertProductToStore(Request $request, $store_id): JsonResponse
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
            return $this->returnData('New Product in Store', $store_products, 'done');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError('400', [$ex->getMessage(), $ex->getLine()]);
        }
    }

    public function updateProductDetailsInStore($price, $quantity, $detailsId)
    {
        try {
            $details = StoreProductDetails::find($detailsId);
            $newDetails = $details->update([
                'price' => $price,
                'quantity' => $quantity
            ]);
//            return $details;
        } catch (\Exception $ex) {
            return $this->returnError('400', [$ex->getMessage(), $ex->getLine()]);
        }
    }

    public function hiddenProductByQuantity($id): JsonResponse
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

    public function updateMultyProductsPricesInStore(Request $request, $store_id): JsonResponse
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

    public function updatePricesPyRatio(Request $request, $store_id): JsonResponse
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

    public function deleteProductFromStore($product_id): JsonResponse
    {
        try {
            $product = $this->storeProductModel->find($product_id);
            DB::beginTransaction();
            $product->update([
                'is_active' => 0
            ]);
            DB::commit();
            return $this->returnData('deleted done', $product, 'done');
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->returnError('400', [$ex->getMessage(), $ex->getLine()]);
        }

    }
}




