<?php


namespace App\Service\RestaurantType;


use App\Http\Requests\RestaurantType\RestaurantTypeRequest;
use App\Models\Restaurant\Restaurant;
use App\Models\RestaurantType\RestaurantType;
use App\Models\RestaurantType\RestaurantTypeTranslation;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class RestaurantTypeService
{
    private $RestaurantTypeModel;
    use GeneralTrait;


    public function __construct(RestaurantType $RestaurantType)
    {
        $this->RestaurantTypeModel=$RestaurantType;

    }
    public function get()
    {
         try{
            $RestaurantType= $this->RestaurantTypeModel::paginate(5);
            return $this->returnData('restaurant type',$RestaurantType,'done');
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }

    public function getById($id)
    {
        try{
            $RestaurantType= $this->RestaurantTypeModel->find($id);
            if (is_null($RestaurantType)){
                return $this->returnSuccessMessage('this type of restaurant not found','done');
            }
            else {
                return $this->returnData('type of restaurant', $RestaurantType, 'done');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }
//__________________________________________________________________________//
    public function create( RestaurantTypeRequest $request )
    {
        try {
            $allrestauranttype = collect($request->restaurantType)->all();
            DB::beginTransaction();
            $unTransrestauranttype_id =RestaurantType::insertGetId([
                'image' => $request['image'],
                'is_approved' => $request['is_approved'],
                'is_active' => $request['is_active'],
            ]);
            if (isset($allrestauranttype)) {
                foreach ($allrestauranttype as $allrestauranttypes) {
                    $transrestauranttype[] = [
                        'title' => $allrestauranttypes['title'],
                        'short_description' => $allrestauranttypes['short_description'],
                        'long_description' => $allrestauranttypes['short_description'],
                        'locale' => $allrestauranttypes['locale'],
                        'restaurant_type_id' => $unTransrestauranttype_id,
                    ];
                }
                RestaurantTypeTranslation::insert($transrestauranttype);
            }
            DB::commit();
            return $this->returnData('type of restaurant', [$unTransrestauranttype_id,  $transrestauranttype], 'done');
        }
        catch(\Exception $ex)
        {
            DB::rollback();
            return $this->returnError('type of restaurant', $ex->getMessage());
        }
    }
//_________________________________________________________//
    public function update(RestaurantTypeRequest $request,$id)
    {
        try{
            $RestaurantType= RestaurantType::find($id);
            if(!$RestaurantType)
                return $this->returnError('400', 'not found this restaurant');
            $allrestauranttype = collect($request->restaurantType)->all();
            if (!($request->has('restaurant_types.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            $newrestauranttype=RestaurantType::where('restaurant_types.id',$id)
                ->update([
                    'image' => $request['image'],
                    'is_approved' => $request['is_approved'],
                    'is_active' => $request['is_active'],
                ]);

            $ss=RestaurantTypeTranslation::where('restaurant_type_translations.restaurant_type_id',$id);
            $collection1 = collect($allrestauranttype);
            $allrestauranttypelength=$collection1->count();
            $collection2 = collect($ss);

            $db_restaruranttype= array_values(RestaurantTypeTranslation::where('restaurant_type_translations.restaurant_type_id',$id)
                ->get()
                ->all());
            $dbrestaruranttype = array_values($db_restaruranttype);
            $request_restaruranttype= array_values($request->restaurantType);
            foreach($dbrestaruranttype as $dbrestaruranttypes){
                foreach($request_restaruranttype as $request_restaruranttypes){
                    $values= RestaurantTypeTranslation::where('restaurant_type_translations.restaurant_type_id',$id)
                        ->where('locale',$request_restaruranttypes['locale'])
                        ->update([
                            'title' => $dbrestaruranttypes ['title'],
                            'short_description' => $dbrestaruranttypes ['short_description'],
                            'long_description' => $dbrestaruranttypes ['short_description'],
                            'locale' => $dbrestaruranttypes['locale'],
                            'restaurant_type_id' => $id,
                        ]);
                }
            }
            DB::commit();
            return $this->returnData('restaurant type',[$dbrestaruranttype,$values],'done');

        }
        catch(\Exception $ex){
            DB::rollBack();
            return $this->returnError('400', $ex->getMessage());
        }
    }
//___________________________________________________________//
    public function search($restaurant_type_title)
    {
        try {
            $RestaurantType = DB::table('restaurant_type_translations')
                ->where("title", "like", "%" . $restaurant_type_title . "%")
                ->get();
            if (!$RestaurantType) {
                return $this->returnError('400', 'not found this restaurant type');
            } else {
                return $this->returnData('restaurant type', $RestaurantType, 'done');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }
    public function trash( $id)
    {
        try{
            $RestaurantType= $this->RestaurantTypeModel::find($id);
            if(is_null($RestaurantType)){
                return $this->returnSuccessMessage('This restaurant type not found', 'done');}
            else{
                $RestaurantType->is_active =0;
                $RestaurantType->save();
                return $this->returnData('doctor', $RestaurantType, 'This restaurant type is trashed Now');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }
    public function getTrashed()
    {
        try {
            $RestaurantType = $this->RestaurantTypeModel::NotActive()->get();
            return $this->returnData('restaurant type', $RestaurantType, 'done');
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }
    public function restoreTrashed( $id)
    {
        try {
            $RestaurantType = $this->RestaurantTypeModel::find($id);
            if (is_null($RestaurantType)) {
                return $this->returnSuccessMessage('This restaurant type not found', 'done');
            } else {
                $RestaurantType->is_active =1;
                $RestaurantType->save();
                return $this->returnData('restaurant type', $RestaurantType, 'This restaurant type is trashed Now');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }
    public function delete($id)
    {
        try{
            $RestaurantType = $this->RestaurantTypeModel::find($id);
            if ($RestaurantType->is_active == 0) {
                $RestaurantType->delete();
                $RestaurantType->restaurantTypeTranslation()->delete();
                return $this->returnData('restaurant type', $RestaurantType, 'This restaurant type is deleted Now');
            }
            else{
                return $this->returnData('restaurant type', $RestaurantType, 'This restaurant type can not deleted Now');
            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getRestaurant($id)
    {
        try{
            $RestaurantType = RestaurantType::with('restaurant')->find($id);
            return $this->returnData('restaurant type', $RestaurantType, 'done');
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }

}
