<?php
namespace App\Service\Stores;

use App\Http\Requests\Store\StoreRequest;
use App\Models\Images\Banner;
use App\Models\Stores\Store;
use App\Models\Stores\StoreTranslation;
use App\Scopes\BrandScope;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use App\Models\Attachments\Attachment;
use App\Models\Plans\Subscription;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;


class  StoreService
{
    use GeneralTrait;
    private $storeTranslation;
    private $Store;
    private $attachment;
    private $subscription;
    private $banner;
    public function __construct(Store $store ,StoreTranslation $storeTranslation,Banner $banner,
     Attachment $attachment , Subscription $subscription)
    {
        $this->storeModel=$store;
        $this->storeTranslation=$storeTranslation;
        $this->attachment=$attachment;
        $this->subscription=$subscription;
        $this->banner=$banner;
        $this->PAGINATION_COUNT=25;
    }
    /****________________   admins dashboard ________________****/
    /****________________   Store's approved ________________****/
    public function aprrove( $id)
    {
        try{
            $store=$this->storeModel->find($id);
            return $store==null ? $this->returnSuccessMessage('Store','This stores not found'):
            $this->returnData('Store',$store->update(['is_approved'=>true]) ,'This Store Is Approved Now');
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
            return count($store) > 0 ? $this->returnData('Stores',$store,'done'):
            $this->returnSuccessMessage('Store','stores doesnt exist yet');
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
                return $q->where('is_cover',1)->get();}])->get();
            return count($store) > 0 ? $this->returnData('Stores',$store,'done'):
                $this->returnSuccessMessage('Store','stores doesnt exist yet');
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
            return is_null($store) ? $this->returnSuccessMessage('Store','stores doesnt exist yet'):
                $this->returnData('Stores',$store,'done');
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
    public function trash($id)
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
        $attachments = collect($request->attachments)->all();
        $subscriptions = collect($request->subscriptions)->all();
        $logo_folder=public_path('images/stores/logo/');
        DB::beginTransaction();
        /**** // //create the default language's product****/
        $unTransStore_id=$this->storeModel->insertGetId([
            'currency_id' =>$request['currency_id'],
            'location_id' =>$request['location_id'],
            'social_media_id' =>$request['social_media_id'],
            'activity_type_id'=>$request['activity_type_id'],
            'owner_id'=>$request['owner_id'],
            'section_id'=>$request['section_id'],
            'is_active'=>$request['is_active'],
            'is_approved'=>0,
            'logo'=>$this->upload($request['logo'],$logo_folder)
        ]);
        //check the category and request
        if(isset($stores) && count($stores))
        {
            //insert other traslations for products
            foreach ($stores as $store)
            {
                $transstore_arr[]=[
                    'local'=>$store['local'],
                    'name' =>$store['name'],
                    'store_id'=>$unTransStore_id
                ];
            }
            $this->storeTranslation->insert($transstore_arr);
        }
            if ($request->has('section')) {
                $store = $this->storeModel->find($unTransStore_id);
                $store->Section()->syncWithoutDetaching($request->get('section'));
            }
            if ($request->has('attachments')) {
                if(isset($attachments) && count($attachments))
                {
                    $folder=public_path('images/attachments/stores' . '/' . $unTransStore_id . '/');
                    foreach ($attachments as $attachment)
                    {
                        $attachments_arr[]=[
                            'path'=>$this->upload($attachment['path'],$folder),
                            'activity_id' =>$attachment['activity_id'],
                            'attachments_type_id' =>$attachment['attachments_type_id'],
                            'record_num'=>$unTransStore_id
                        ];
                    }
                    $this->attachment->insert($attachments_arr);
                }
            }
            if ($request->has('subscriptions')) {
                if(isset($subscriptions) && count($subscriptions))
                {
                    foreach ($subscriptions as $subscription)
                    {
                         $subscriptions_arr[]=[
                            'start_date'=>$subscription['start_date'],
                            'end_date' =>$subscription['end_date'],
                            'plan_id' =>$subscription['plan_id'],
                            'transaction_id' =>$subscription['transaction_id'],
                            'is_active' =>$subscription['is_active'],
                            'store_id'=>$unTransStore_id
                        ];
                    }
                    $this->subscription->insert($subscriptions_arr);
                }
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
            $logo_folder=public_path('images/stores/logo/');

            DB::beginTransaction();
            $nStore=$this->storeModel->where('stores.id',$id)
                ->update([
                    'currency_id' =>$request['currency_id'],
                    'location_id' =>$request['location_id'],
                    'social_media_id' =>$request['social_media_id'],
                    'activity_type_id'=>$request['activity_type_id'],
                    'owner_id'=>$request['owner_id'],
                    'section_id'=>$request['section_id'],
                    'is_active'=>$request['is_active'],
                    'is_approved'=>0,
                    'logo'=>$this->upload($request['logo'],$logo_folder)
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
            }
            catch(\Exception $ex){
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
        }
        catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    public function account($storeId)
    {
        $store=$this->storeModel->find($storeId);
        return $store;
    }
    public function createBanner(Request $request,$storeId)
    {
        try{
//            return $request;
        $folder = public_path('images/stores/banners' . '/' . $storeId . '/');
        $banners = collect($request->banners)->all();
        if(isset($banners) && count($banners))
        {
                foreach ($banners as $banner)
                {
                    $banners_arr[]=[
                        'image'=>$this->upload($banner['image'],$folder),
                        'description'=>$banner['description'],
                        'store_id'=>$storeId,
                        'is_active'=>$banner['is_active'],
                        'is_appear'=>$banner['is_appear']
                    ];
                }
                 $this->banner->insert($banners_arr);
            }
            return $this->returnData('Banners', [$banners],'done');
        }
      catch(\Exception $ex)
         {
            DB::rollback();
            return $this->returnError('store',$ex->getMessage());
        }
    }
    public function updateBanner(Request $request,$bannerId,$storeId)
    {
        try{
            $folder = public_path('images/stores/banners' . '/' . $storeId . '/');
             $banner=$this->banner->find($bannerId);
             $banner->update([
                 'image'=>$this->upload($request['image'],$folder),
                 'description'=>$request['description'],
                 'is_active'=>$request['is_active'],
                 'is_appear'=>$request['is_appear']
             ]);
            return $this->returnData('Banners', $banner,'done');
        }catch(\Exception $ex){
            return $this->returnError('400',$ex->getMessage());
        }
    }
    public function getBanner($storeId)
    {
        try{
            $banner=$this->banner->where('banners.store_id',$storeId)->get();
        return $this->returnData('Banners', $banner,'done');
    }catch(\Exception $ex){
        return $this->returnError('400',$ex->getMessage());
        }
    }
    public function upload($image,$folder)
    {
        // $folder = public_path('images/attachments/stores' . '/' . $id . '/');
        $filename = time() . '.' . $image->getClientOriginalName();
        //  $imageUrl='images/attachments/stores/' . $id  . '/' .  $filename;
        if (!File::exists($folder)) {
            File::makeDirectory($folder, 0775, true, true);
        }
        $image->move($folder,$filename);
        return $filename;
    }
    public function storeUsers($storeId)
    {
        return $this->storeModel->with('User')->find($storeId);
    }

}
