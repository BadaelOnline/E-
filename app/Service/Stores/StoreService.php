<?php
namespace App\Service\Stores;

use App\Http\Requests\Store\StoreRequest;
use App\Http\Requests\StoreProduct\StoreProductRequest;
use App\Models\Stores\Store;
use App\Models\Stores\StoreTranslation;
use App\Scopes\BrandScope;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class  StoreService
{
    use GeneralTrait;
    private $storeTranslation;
    private $Store;
    public function __construct(Store $store ,StoreTranslation $storeTranslation)
    {
        $this->storeModel=$store;
        $this->storeTranslation=$storeTranslation;
        $this->PAGINATION_COUNT=25;
    }
    /****________________   admins dashboard ________________****/
    /****________________   Store's approved ________________****/
    public function aprrove( $id)
    {
        try{
            $store=$this->storeModel->find($id);
            if (is_null($store) ){
                return $response= $this->returnSuccessMessage('Store','This stores not found');
            }else{
                $store->is_approved=true;
                $store->save();
                return $this->returnData('Store', $store,'This Store Is aprroved Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /****________________   Store's list ________________****/
    public function dashgetAll()
    {
        try {
            $store =$this->storeModel->with([
                'Section',
                'Brand'=> function ($q) {
                    return $q->withoutGlobalScope(BrandScope::class)
                        ->select(['brands.id'])
                        ->with(['BrandTranslation'=>function($q){
                            return $q->where('brand_translation.local',
                                '=',
                                Config::get('app.locale'))
                                ->select(['brand_translation.name','brand_translation.brand_id'
                                ])->get();
                        }])->get();
                },
                'StoreImage'=>function($q){
                return $q->where('is_cover',1)
                    ->get();}
            ])->get();
            if (count($store) > 0){
                return $this->returnData('Stores',$store,'done');
            }else{
                return $this->returnSuccessMessage('Store','stores doesnt exist yet');
            }
        } catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /****________________   client side functions ________________****/
    /****________________ Get All Active Store Or By ID  ________________****/
    public function getAll()
    {
        try {
            $store =$this->storeModel->with([
                'Section',
                'Product',
                'Brand'=> function ($q) {
                    return $q->withoutGlobalScope(BrandScope::class)
                        ->select(['brands.id'])
                        ->with(['BrandTranslation'=>function($q){
                            return $q->where('brand_translation.local','='
                                , Config::get('app.locale'))
                                ->select(['brand_translation.name','brand_translation.brand_id'
                                ])->get();
                        }])->get();},
                'StoreImage'=>function($q){
                return $q->where('is_cover',1)->get();}])->paginate($this->PAGINATION_COUNT);
            if (count($store) > 0){
                return $this->returnData('Stores',$store,'done');
            }else{
                return $this->returnSuccessMessage('stores doesnt exist yet',200);
            }
        } catch(\Exception $ex){

            return $this->returnError('400',$ex->getMessage());
        }
    }
   public function getById($store_id)
    {
        try {
        $store =  $this->storeModel->with(['Product'=>function($q) use ($store_id) {
            return $q->with(['Category'=>function($q){
                return $q->with('Section')->get();
            },'StoreProduct'=>function($q) use ($store_id) {
                return $q->where('store_id',$store_id)->get();
            }])->get();
        },'Section','Brand','StoreImage'])->find($store_id);
            if (is_null($store) ){
                return $response= $this->returnSuccessMessage('Store','This stores not found');
            }else{
                return $this->returnData('Store',$store,'done');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /****________________  This Functions For Trashed Store  ________________****/
    /****________________  Get All Trashed Stores Or By ID   ________________****/
    public function getTrashed()
    {
        try {
        $store = $this->storeModel->where('is_active',0)->get();
            if (count($store) > 0){
                return $response= $this->returnData('Store',$store,'done');
            }else{
                return $response= $this->returnSuccessMessage('Store','stores doesnt exist yet');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    /****________________Restore Store Fore Active status  ________________****/
    public function restoreTrashed( $id)
    {
        try{
            $store=$this->storeModel->find($id);
            if (is_null($store) ){
                return $response= $this->returnSuccessMessage('Store','This stores not found');
            }else{
                $store->is_active=true;
                $store->save();
                return $this->returnData('Store', $store,'This Store Is trashed Now');
            }
            }catch(\Exception $ex){
        return $this->returnError('400',$ex->getMessage());
        }
    }
    /****________________   Store's Soft Delete   ________________****/
    public function trash( $id)
    {
        try{
            $store=$this->storeModel->find($id);
            if (is_null($store) ){
                return $response= $this->returnSuccessMessage('Store','This stores not found');
            }else{
                $store->is_active=false;
                $store->save();
                return $this->returnData('Store', $store,'This Store Is trashed Now');
            }
        }catch(\Exception $ex){
              return $this->returnError('400',$ex->getMessage());
        }
    }
    /****________________  Create Store   ________________****/
    public function create(Request $request)
    {
        try {
//            $request->validated();
      /***  //transformation to collection*////
        $stores = collect($request->store)->all();
        DB::beginTransaction();
        /**** // //create the default language's product****/
        $unTransStore_id=$this->storeModel->insertGetId([
            'loc_id' =>$request['loc_id'],
            'country_id' =>$request['country_id'],
            'gov_id' =>$request['gov_id'],
            'city_id'=>$request['city_id'],
            'street_id'=>$request['street_id'],
            'offer_id'=>$request['offer_id'],
            'socialMedia_id'=>$request['socialMedia_id'],
            'followers_id'=>$request['followers_id'],
            'is_active'=>$request['is_active'],
            'is_approved'=>$request['is_approved'],
            'delivery'=>$request['delivery'],
            'edalilyPoint'=>$request['edalilyPoint'],
            'rating'=>$request['rating'],
            'workingHours'=>$request['workingHours'],
            'logo'=>$request['logo']
        ]);
        $logo = $request->logo;
            if (isset($logo)) {
                if ($request->hasFile($logo)) {
                    //save
                    $file_extension = $logo->getClientOriginalExtension();
                    $file_name = time() . $file_extension;
                    $path = 'images/stores/logo';
                    $logo->move($path, $file_name);
                }
            }
        //check the category and request
        if(isset($stores) && count($stores))
        {
            //insert other traslations for products
            foreach ($stores as $store)
            {
                $transstore_arr[]=[
                    'local'=>$store['local'],
                    'title' =>$store['title'],
                    'store_id'=>$unTransStore_id
                ];
            }
            $this->storeTranslation->insert($transstore_arr);
        }
            if ($request->has('section')) {
                $store = $this->storeModel->find($unTransStore_id);
                $store->Section()->syncWithoutDetaching($request->get('section'));
            }
            $images = $request->images;
            if ($request->has('images')) {
                foreach ($images as $image) {
                    $storeImages = $this->storeModel->find($unTransStore_id);
                    $storeImages->StoreImage()->insert([
                        'store_id' => $unTransStore_id,
                        'image' => $image['image'],
                        'is_cover' => $image['is_cover']
                    ]);
                }
            }
        DB::commit();
        return $this->returnData('Store', [$unTransStore_id,$transstore_arr],'done');
        }
        catch(\Exception $ex)
            {
                DB::rollback();
                return $this->returnError('store',$ex->getMessage());
            }
    }
    /****__________________  Update Store   ___________________****/
    public function update(Request $request,$id)
    {
        try{
            //$validated = $request->validated();
            $store= $this->storeModel->find($id);
            if(!$store)
                return $this->returnError('400', 'not found this Store');
            if (!($request->has('stores.is_active')))
                $request->request->add(['is_active'=>0]);
            else
                $request->request->add(['is_active'=>1]);

            DB::beginTransaction();
            $nStore=$this->storeModel->where('stores.id',$id)
                ->update([
                    'loc_id' => $request['loc_id'],
                    'country_id' => $request['country_id'],
                    'gov_id' => $request['gov_id'],
                    'city_id'=>$request['city_id'],
                    'street_id'=>$request['street_id'],
                    'offer_id'=>$request['offer_id'],
                    'socialMedia_id'=>$request['socialMedia_id'],
                    'followers_id'=>$request['followers_id'],
                    'is_active'=>$request['is_active'],
                    'is_approved'=>$request['is_approved'],
                    'delivery'=>$request['delivery'],
                    'edalilyPoint'=>$request['edalilyPoint'],
                    'rating'=>$request['rating'],
                    'workingHours'=>$request['workingHours'],
                    'logo'=>$request['logo'],
                ]);
        $stores = collect($request->store)->all();
        //Stores in database
            $dbdstores=$this->storeModel->where('Store_id',$id)->get();
            foreach($dbdstores as $dbdstore){
                foreach($stores as $store){
                    $values= StoreTranslation::where('store_id',$id)
                        ->where('local',$store['local'])
                        ->update([
                            'title'=>$store['title'],
                            'local'=>$store['local'],
                            'store_id'=>$id
                        ]);
                }
            }
            if ($request->has('section')) {
                $store = $this->storeModel->find($id);
                $store->Section()->syncWithoutDetaching($request->get('section'));
            }
            $images = $request->images;
            if ($request->has('images')) {
                foreach ($images as $image) {
                    $storeImages = $this->storeModel->find($id);
                    $storeImages->StoreImage()->insert([
                        'store_id' => $id,
                        'image' => $image['image'],
                        'is_cover' => $image['is_cover']
                    ]);
                }
            }
            DB::commit();
            return $this->returnData('Store', [$nStore,$dbdstores],'Updated Done');
        }
        catch(\Exception $ex){
            DB::rollback();
            return $this->returnError('400', $ex->getMessage());
        }
    }
    /****________________  ٍsearch for Store _________________****/
    public function search($title)
    {
        try{
        $store = DB::table('Store')
            ->where("name","like","%".$title."%")
            ->get();
        if (!$store)
        {
            return $this->returnError('400', 'not found this Store');
        }
        else
        {
            return $this->returnData('Store', $store,'done');
        }
            }catch(\Exception $ex){
        return $this->returnError('400',$ex->getMessage());
        }
    }
    /****_______________  Delete Store   ________________****/
    public function delete($id)
    {
        try
        {
         $store =$this->storeModel->find($id);
        if ($store->is_active==0)
        {
            $store=Store::destroy($id);

        }
        return $this->returnData('Category', $store,'This Store Is deleted Now');
         }catch(\Exception $ex){
           return $this->returnError('400',$ex->getMessage());
        }
    }
    public function getSectionInStore($id)
    {
        try {
            $store =$this->storeModel->with('Section')->find($id);
            if (is_null($store) ){
                return $response= $this->returnSuccessMessage('Store','This stores not found');
            }else {
                return $this->returnData('Category', $store, 'This Store Is deleted Now');
            }
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
}
