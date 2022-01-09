<?php

namespace App\Service\Offer;

use App\Http\Requests\Offer\OfferRequest;
use App\Models\Offer\Offer;
use App\Models\Offer\OfferTranslation;
use App\Models\Stores\Store;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OfferService
{

    use GeneralTrait;
    protected $OfferModel;
    protected $StoreModel;

    public function __construct(Offer $offer,Store $store)
    {
        $this->OfferModel=$offer;
        $this->StoreModel=$store;
    }

    //get all offer
    public function get()
    {
        try{
            $offer=$this->OfferModel::paginate(5);
            return $this->returnData('Offer',$offer,'Done');
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//get offer by offer's id
    public function getById($id)
    {
        try{
            $offer=$this->OfferModel::find($id);
            if(!$offer)
            {
                return $this->returnError('400','not found this offer');
            }
            else
            {
               return $this->returnData('offer',$offer,'Done');
            }
        }
        catch (\Exception $ex)
        {
         return  $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }

    //create new offer
    public function create(OfferRequest $request)
    {
    
        try {
            $offer=collect($request->Offer)->all();
            DB::beginTransaction();
            $untransId=$this->OfferModel::insertGetId([
                'store_id'        =>$request->store_id,
                'store_product_id'=>$request->store_product_id,
                'user_email'      =>$request->user_email,
                'image'           =>$request->image,
                'price'           =>$request->price,
                'selling_price'   =>$request->selling_price,
                'quantity'        =>$request->quantity,
                'position'        =>$request->position,
                'started_at'      =>$request->started_at,
                'ended_at'        =>$request->ended_at,
                'is_active'       =>$request->is_active,
                'is_offer'        =>$request->is_offer
            ]);
             if(isset($offer)) {
                 foreach ($offer as $offers) {
                     $transOffer[] = [
                         'name' => $offers ['name'],
                         'short_description' => $offers['short_description'],
                         'long_description' => $offers['long_description'],
                         'locale' => $offers['locale'],
                         'offer_id' => $untransId,
                     ];
                 }
                 OfferTranslation::insert($transOffer);
             }
              DB::commit();

              return $this->returnData('offer', [$untransId,$transOffer], 'done');

              
              Mail::To($untransId->user_email)->send(new OfferMail($untransId->user_email));

              return $this->returnData('email',$eamil,'An email has been sent to you');
            
        }

        catch(\Exception $ex)
        {
            DB::rollBack();
           return  $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//update old offer
    public function update(OfferRequest $request,$id)
    {
      Try
      {
          $offer=$this->OfferModel::find($id);
          if(!$offer)
          {return $this->returnError('400','not found this offer');}
          $alloffer=collect($request->Offer)->all();
          if(!($request->has('offers.is_active')))
          $request->request->add(['is_active'=>0]);
          else{$request->request->add(['is_active',1]);}

          $newoffer=$this->OfferModel::where('offers.id',$id)->update([
              'store_id'        =>$request->store_id,
              'store_product_id'=>$request->store_product_id,
              'user_email'      =>$request->user_email,
              'image'           =>$request->image,
              'price'           =>$request->price,
              'selling_price'   =>$request->selling_price,
              'quantity'        =>$request->quantity,
              'position'        =>$request->position,
              'started_at'      =>$request->started_at,
              'ended_at'        =>$request->ended_at,
              'is_active'       =>$request->is_active,
              'is_offer'        =>$request->is_offer
          ]);
          $db_offer=array_values(OfferTranslation::where('offer_translations.offer_id',$id)
              ->get()->all());

          $dboffer=(array_values($db_offer));
          $request_offer=(array_values($request->Offer));
          foreach ($dboffer as $dboffers){
              foreach ($request_offer as $request_offers){
                  $value=OfferTranslation::where('offer_translations.offer_id',$id)
                      ->where('locale',$request_offers['locale'])
                      ->update([
                          'name'=>$request_offers['name'],
                          'short_description'=>$request_offers['short_description'],
                          'long_description'=>$request_offers['long_description'],
                          'offer_id'=>$id
                      ]);
              }
          }
          DB::commit();
          return $this->returnData('offer',[$dboffer,$value],'done');
      }
      catch (\Exception $ex)
      {
          return $this->returnError($ex->getCode(),$ex->getMessage());
      }

    }
// change is_active value to zero
    public function Trash($id)
    {
        try{
            $offer=$this->OfferModel::find($id);
            if(!$offer)
            {
              return  $this->returnError('400','not found this offer');
            }
            else
            {
                $offer->is_active=0;
                $offer->save();
            return $this->returnData('offer',$offer,'this offer is trashed now');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//change is_active value to one
    public function restoreTrashed($id)
    {
        try{
            $offer=$this->OfferModel::find($id);
            if(!$offer)
            {
                return $this->returnError('400','not found this offer');
            }
            else
            {
                $offer->is_active=1;
                $offer->save();

                return $this->returnData('offer',$offer,'this offer is restore trashed now');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    //delete the offer from database
    public function delete($id)
    {
        try
        {
            $offer=$this->OfferModel::find($id);
            if(!$offer)
            {
                return $this->returnError('400','not found this offer');
            }
            elseif($offer->is_active==0){
                $offer->delete();
                $offer->OfferTranslation()->delete();
                return $this->returnData('offer',$offer,'this offer is deleted now');
            }
            else{
                return $this->returnError('400','this offer can not deleted now');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    //Find out the store that offers this offer through this offer ID
    public function getStoreByOfferId($Offer_id)
    {
        try{
            $offer=$this->OfferModel::find($Offer_id);
            if (!$offer)
            {
                return $this->returnError('400','not found this Offer');
            }
            else {
                $offer=$this->OfferModel::with('Store')->find($Offer_id);
                return $this->returnData('Offer', $offer, 'done');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//Find out about store offers via store ID
    public function getOfferByStoreId($Store_id)
    {
        try{
            $store=$this->StoreModel::find($Store_id);
            if(!$store)
            {return $this->returnError('400','not found this store');
            }
            else {
                $store=$this->StoreModel::with('Offer')->find($Store_id);
                return  $this->returnData('Store',$store,'done');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//_____________________________________________________________________________//
//get offer where is_Active=0
    public function getTrashed()
    {
        try{
            $offer=$this->OfferModel::NotActive();
            return $this->returnData('offer',$offer,'done');
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
//get the advertisement
    public function get_advertisement()
    {
        try{
            $offer=$this->OfferModel::Advertisement();
            return $this->returnData('advertisement',$offer,'this is advertisements');
        }
        catch(\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
}