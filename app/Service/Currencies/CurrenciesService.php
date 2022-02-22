<?php

namespace App\Service\Currencies;

use App\Http\Requests\Brands\BrandRequest;
use App\Models\Currencies\Currency;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class CurrenciesService
{
    private $currency;
    private $PAGINATION_COUNT;

    use GeneralTrait;

    public function __construct(Currency $currency)
    {
        $this->currencyModel = $currency;
        $this->PAGINATION_COUNT = 25;
    }

    /****Get All Currencies  ****/
    public function getAll()
    {
        try {
            $currencies = $this->currencyModel->get();
            return count($currencies) > 0 ?
                $this->returnData('Currency', $currencies, 'done') :
                $this->returnSuccessMessage('Currency', 'Currencies doesnt exist yet');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****Get Active Product By ID  ***
     * @param $id
     * @return JsonResponse
     */
    public function getById($id)
    {
        try {
            $currencies = $this->currencyModel->findOrFail($id);
            return is_null($currencies) > 0 ?
                $this->returnSuccessMessage('Currency', 'Currencies doesnt exist yet') :
                $this->returnData('Currency', $currencies, 'done');
        } catch (\Exception $ex) {
            if ($ex instanceof TokenExpiredException) {
                return $this->returnError('400', $ex->getMessage());
            }
            return $this->returnError('400', $ex->getMessage());

        }
    }
    /*__________________________________________________________________*/
    /****ــــــ This Functions For Trashed Productsــــــ  ****/
    /****Get All Trashed Products Or By ID  ****/
    public function getTrashed()
    {
        try {
            Gate::authorize('Read Brand');

            $brand = $this->BrandModel->where('is_active', 0)->get();

            if (count($brand) > 0) {
                return $this->returnData('Brand', $brand, 'done');
            } else {
                return $this->returnSuccessMessage('Brand', 'Brands trashed doesnt exist yet');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****Restore Products Fore Active status  ***
     * @param $id
     * @return JsonResponse
     */
    public function restoreTrashed($id)
    {
        try {
            Gate::authorize('Restore Brand');

            $brand = $this->BrandModel->find($id);
            if (is_null($brand)) {
                return $response = $this->returnSuccessMessage('Brand', 'This Products not found');
            } else {
                $brand->is_active = true;
                $brand->save();
                return $this->returnData('Brand', $brand, 'This Brand Is trashed Now');
            }
        } catch (\Exception $ex) {
            if ($ex instanceof AccessDeniedException)
                return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****   Product's Soft Delete   ***
     * @param $id
     * @return JsonResponse
     */
    public function trash($id)
    {
        try {
            Gate::authorize('Delete Brand');

            $brand = $this->BrandModel->find($id);
            if (is_null($brand)) {
                return $response = $this->returnSuccessMessage('Brand', 'This Brands not found');
            } else {
                $brand->is_active = false;
                $brand->save();
                return $this->returnData('Brand', $brand, 'This Brand Is trashed Now');
            }

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Create Products   ***
     * @return JsonResponse
     */
    public function create(BrandRequest $request)
    {
        try {

            Gate::authorize('Create Brand');

            $validated = $request->validated();
            $request->is_active ? $is_active = true : $is_active = false;
            /** transformation to collection */
            $allbrands = collect($request->brand)->all();
            $folder = public_path('images/brands' . '/');
            DB::beginTransaction();
            // //create the default language's brand
            $unTransBrand_id = $this->BrandModel->insertGetId([
                'slug' => $request['slug'],
                'is_active' => $request['is_active'],
                'image' => $this->upload($request['image'], $folder),
            ]);
            //check the Brand and request
            if (isset($allbrands) && count($allbrands)) {
                //insert other traslations for Brands
                foreach ($allbrands as $allbrand) {
                    $transBrand_arr[] = [
                        'name' => $allbrand ['name'],
                        'local' => $allbrand['local'],
                        'description' => $allbrand['description'],
                        'brand_id' => $unTransBrand_id
                    ];
                }
                $this->brandTranslation->insert($transBrand_arr);
            }
            DB::commit();
            return $this->returnData('Brand', [$unTransBrand_id, $allbrands], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('Brand', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Update Product   ***
     * @param $id
     * @return JsonResponse
     */
    public function update(BrandRequest $request, $id)
    {
        $request->validated();
        try {
            $brand = $this->BrandModel->find($id);
            if (!$brand)
                return $this->returnError('400', 'not found this Brand');
            if (!($request->has('brand.is_active')))
                $request->request->add(['is_active' => 0]);
            else
                $request->request->add(['is_active' => 1]);
            $unTransBrand = $this->BrandModel->where('brands.id', $id)
                ->update([
                    'slug' => $request['slug'],
                    'is_active' => $request['is_active'],
//                    'image' => $request['image'],
                ]);
            $request_brands = array_values($request->brand);
            foreach ($request_brands as $request_brand) {
                $this->brandTranslation->where('brand_id', $id)
                    ->where('local', $request_brand['local'])
                    ->update([
                        'name' => $request_brand ['name'],
                        'local' => $request_brand['local'],
                        'description' => $request_brand['description'],
                        'brand_id' => $id
                    ]);
            }
            DB::commit();
            return $this->returnData('Brand', [$id, $request_brands], 'done');
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('400', $ex->getMessage());
        }
    }

    /*__________________________________________________________________*/
    public function search($title)
    {
        try {
            Gate::authorize('Read Brand');

            $brand = $this->BrandModel->searchTitle();
            if (!$brand) {
                return $this->returnError('400', 'not found this Brand');
            } else {
                return $this->returnData('brands', $brand, 'done');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /*__________________________________________________________________*/
    /****  Delete Product   ***
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        try {
            Gate::authorize('Delete Brand');

            $brand = $this->BrandModel->find($id);

            $brand->destroy($id);
            return $this->returnData('Brand', $brand, 'This Brand Is deleted Now');


        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function upload($image, $folder)
    {
        $folder = public_path('images/brands' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $imageUrl[] = 'images/brands/' . $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $image->move($folder, $filename);
        return $filename;
    }

    public function update_upload(Request $request, $id)
    {
        $brand = $this->BrandModel->find($id);
        if (!isset($brand)) {
            return $this->returnSuccessMessage('This Brand not found', 'done');
        }
        $old_image = $brand->image;
        $image = $request->file('image');
        $old_images = public_path('images/brands' . '/' . $old_image);
        if (File::exists($old_images)) {
            unlink($old_images);
        }
        $folder = public_path('images/brands' . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        $brand->update(['image' => $filename]);
        /**update in database**/
        $image->move($folder, $filename);
        return $filename;
    }
}
