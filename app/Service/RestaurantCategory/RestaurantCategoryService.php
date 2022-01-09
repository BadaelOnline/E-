<?php


namespace App\Service\RestaurantCategory;


use App\Http\Requests\RestaurantCategory\RestaurantCategoryRequest;
use App\Models\RestaurantCategory\RestaurantCategory;
use App\Models\RestaurantCategory\RestaurantCategoryTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;

class RestaurantCategoryService
{
    private $RestaurantCategoryModel;
    use GeneralTrait;

    public function __construct(RestaurantCategory $RestaurantCategory)
    {
        $this->RestaurantCategoryModel=$RestaurantCategory;
    }
    public function get()
    {
        try {
            $Category = $this->RestaurantCategoryModel::paginate(5);
            return $this->returnData('Restaurant Category', $Category, 'done');
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getById($id)
    {
        try{
            $Category= $this->RestaurantCategoryModel::find($id);
            if (is_null($Category)){
                return $this->returnSuccessMessage('this Restaurant Category not found','done');
            }
            else{
                return $this->returnData('Restaurant Category',$Category,'done');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }
    //__________________________________________________________//
    public function create( RestaurantCategoryRequest $request )
    {
        try {
            $allCategory = collect($request->RestaurantCategory)->all();
            DB::beginTransaction();
            $unTransCategory_id = RestaurantCategory::insertGetId([
                'image'   => $request['image'],
                'is_approved' => $request['is_approved'],
                'is_active'   => $request['is_active'],

            ]);
            if (isset($allCategory)) {
                foreach ($allCategory as $allCategories) {
                    $transCategory[] = [
                        'name' => $allCategories ['name'],
                        'short_description' => $allCategories ['short_description'],
                        'long_description' => $allCategories ['long_description'],
                        'locale' => $allCategories['locale'],
                        'restaurant_category_id' => $unTransCategory_id,
                    ];
                }
                RestaurantCategoryTranslation::insert($transCategory);

                if($request->has('RestaurantProduct')){
                    $category=$this->RestaurantCategoryModel::find($unTransCategory_id);
                    $category->RestaurantProduct()->syncWithoutDetaching($request->get('RestaurantProduct'));
                }
            }
            DB::commit();
            return $this->returnData('Restaurant Category',[$unTransCategory_id, $transCategory],'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError('Restaurant Category', $ex->getMessage());
        }

    }
    //___________________________________________________________________//
    public function update(RestaurantCategoryRequest $request,$id)
    {
        try{
            $Category= RestaurantCategory::find($id);
            if(!$Category)
                return $this->returnError('400', 'not found this Restaurant Category');
            $allCategory = collect($request->RestaurantCategory)->all();
            if (!($request->has('restaurant_categories.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            $newCategory=RestaurantCategory::where('restaurant_categories.id',$id)
                ->update([
                    'image'   => $request['image'],
                    'is_approved' => $request['is_approved'],
                    'is_active'   => $request['is_active'],
                ]);

            $ss=RestaurantCategoryTranslation::where('restaurant_category_translations.restaurant_category_id',$id);
            $collection1 = collect($allCategory);
            $allcategorylength=$collection1->count();
            $collection2 = collect($ss);

            $db_category= array_values(RestaurantCategoryTranslation::where('restaurant_category_translations.restaurant_category_id',$id)
                ->get()
                ->all());
            $dbcategory = array_values($db_category);
            $request_category= array_values($request->RestaurantCategory);
            foreach($dbcategory as $dbcategories){
                foreach($request_category as $request_categories){
                    $values=RestaurantCategoryTranslation::where('restaurant_category_translations.restaurant_category_id',$id)
                        ->where('locale',$request_categories['locale'])
                        ->update([
                            'name' => $request_categories ['name'],
                            'short_description' => $request_categories ['short_description'],
                            'long_description' => $request_categories ['long_description'],
                            'locale' => $request_categories['locale'],
                            'restaurant_category_id' => $id,
                        ]);
                }
            }
            if($request->has('RestaurantProduct')){
                $category=$this->RestaurantCategoryModel::find($id);
                $category->RestaurantProduct()->syncWithoutDetaching($request->get('RestaurantProduct'));
            }
            DB::commit();
            return $this->returnData(' restaurant category', [$dbcategory,$values],'done');
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
            $Category = DB::table('restaurant_category_translations')
                ->where("name", "like", "%" . $name . "%")
                ->get();
            if (!$Category) {
                return $this->returnError('400', 'not found this Restaurant Category');
            } else {
                return $this->returnData('Restaurant Category', $Category, 'done');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function trash( $id)
    {
        try{
            $Category= $this->RestaurantCategoryModel::find($id);
            if (is_null($Category)) {
                return $this->returnSuccessMessage('This Restaurant Category not found', 'done');
            }
            else
            {
                $Category->is_active=0;
                $Category->save();
                return $this->returnData('Restaurant Category', $Category,'This Restaurant Category is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }

    }
    public function getTrashed()
    {
        try{
            $Category= $this->RestaurantCategoryModel::NotActive()->all();
            return $this -> returnData('Restaurant Category',$Category,'done');
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }    }
    public function restoreTrashed( $id)
    {
        try{
            $Category=RestaurantCategory::find($id);
            if (is_null($Category)) {
                return $this->returnSuccessMessage('This Restaurant Category not found', 'done');
            }
            else
            {
                $Category->is_active=1;
                $Category->save();
                return $this->returnData('Restaurant Category', $Category,'This Restaurant Category is trashed Now');
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
            $Category = RestaurantCategory::find($id);
            if ($Category->is_active == 0) {

                $Category->delete();
                $Category->restaurantCategoryTranslation()->delete();
                return $this->returnData('Restaurant Category', $Category, 'This Restaurant Category is deleted Now');
            }
            else{
                return $this->returnData('Restaurant Category', $Category, 'This Restaurant Category can not deleted Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getProduct($id)
    {
        try{
            $Category=  RestaurantCategory::with('RestaurantProduct')->find($id);
            return $this->returnData('Restaurant Category', $Category, 'done');

        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getRestaurant($id)
    {
        try{
            $Category= RestaurantCategory::with('Restaurant')->find($id);
            return $this->returnData('Restaurant Category', $Category, 'done');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function insertToRestaurantcategoryRestaurantproduct(Request $request)
{
    try{
    $restaurantcategory=RestaurantCategory::find($request->restaurant_category_id);
    if(!$restaurantcategory)
    return $this->returnError('400','not found this restaurant category');
    $restaurantcategory->RestaurantProduct()->syncwithoutdetaching($request->restaurant_product_id);
    return $this->returnData('restaurant category ',$restaurantcategory,'create done');
}
catch(\Exception $ex){
    return $this->returnError($ex->getcode(),$ex->getMessage());
}
}

public function insertToRestaurantcategoryItem(Request $request)
{
    try{
    $restaurantcategory=RestaurantCategory::find($request->restaurant_category_id);
    if(!$restaurantcategory)

    return $this->returnError('400','not found this restaurant category');
    $restaurantcategory->Item()->syncwithoutdetaching($request->item_id);
    return $this->returnData('restaurant category ',$restaurantcategory,'create done');
}
catch(\Exception $ex){
    return $this->returnError($ex->getcode(),$ex->getMessage());
}
}
}
