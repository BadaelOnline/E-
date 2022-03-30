<?php

namespace App\Service\Offer;

use App\Http\Requests\Offer\OfferRequest;
use App\Models\Offer\Offer;
use App\Models\Offer\OfferTranslation;
use App\Models\Stores\Store;
use App\Traits\GeneralTrait;
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

    public function get (){
        try {
            $offer = $this->OfferModel::paginate (5);
            return $this->returnData ('Offer', $offer, 'Done');
        } catch (\Exception $ex) {
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }

    public function getById ($id){
        try {
            $offer = $this->OfferModel::find ($id);
            if (!$offer) {
                return $this->returnError ('400', 'not found this offer');
            } else {
                return $this->returnData ('offer', $offer, 'Done');
            }
        } catch (\Exception $ex) {
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }

    public function create($request){
        try {
            $offers = collect($request->Offer)->all();
            DB::beginTransaction();
            $unTransOffer_Id = $this->OfferModel::insertGetId([
                'store_id'         =>$request->store_id,
                'store_product_id' =>$request->store_product_id,
                'user_email'       =>$request->user_email,
                'offer_price'      =>$request->offer_price,
                'selling_quantity' =>$request->selling_quantity,
                'started_at'       =>$request->started_at,
                'ended_at'         =>$request->ended_at,
                'is_active'        =>$request->is_active,
                'is_offer'         =>$request->is_offer
            ]);
             if(isset($offers) && count($offers)) {
                 foreach ($offers as $offer) {
                     $transOffer_arr[] = [
                         'name' => $offer ['name'],
                         'short_description' => $offer['short_description'],
                         'long_description' => $offer['long_description'],
                         'locale' => $offer['locale'],
                         'offer_id' => $unTransOffer_Id,
                     ];
                 }
                 OfferTranslation::insert ($transOffer_arr);
             }
            DB::commit ();
            return $this->returnData ('offer', [$unTransOffer_Id, $transOffer_arr], 'done');
//            Mail::To ($untransId->user_email)->send (new OfferMail($untransId->user_email));
//            return $this->returnData ('email', $eamil, 'An email has been sent to you');
        } catch (\Exception $ex) {
            DB::rollBack ();
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }

    public function update(OfferRequest $request,$id){
      Try {
          $offer = $this->OfferModel::findOrFail($id);
          DB::beginTransaction();
          if(!$offer)
            return $this->returnError('400','not found this offer');
          if (!($request->has('offers.is_active')))
              $request->request->add(['is_active'=>0]);
          else
              $request->request->add(['is_active',1]);
          $offers = collect($request->Offer)->all();
          $unTransOffer_Id = $this->OfferModel::where('offers.id',$offer->id)->update([
              'store_id'        =>$request->store_id,
              'store_product_id'=>$request->store_product_id,
              'user_email'      =>$request->user_email,
              'offer_price'   =>$request->offer_price,
              'selling_quantity'        =>$request->selling_quantity,
              'started_at'      =>$request->started_at,
              'ended_at'        =>$request->ended_at,
              'is_active'       =>$request->is_active,
              'is_offer'        =>$request->is_offer
          ]);
          $db_offers=array_values(OfferTranslation::where('offer_translations.offer_id',$id)
              ->get()->all());
          $dboffers=(array_values($db_offers));
          $offers =(array_values($request->Offer));
          foreach ($dboffers as $dboffer){
              //insert other translations for Offer
              foreach ($offers  as $offer){
                  OfferTranslation::where ('offer_translations.offer_id', $id)
                      ->where ('locale', $offer['locale'])
                      ->update ([
                          'name' => $offer['name'],
                          'short_description' => $offer['short_description'],
                          'long_description' => $offer['long_description'],
                          'offer_id' =>$unTransOffer_Id
                      ]);
              }
          }
          DB::commit ();
          return $this->returnData ('offer', [$offers], 'done');
      } catch (\Exception $ex) {
          return $this->returnError ($ex->getCode (), $ex->getMessage ());
      }
    }

    public function Trash ($id){
        try {
            $offer = $this->OfferModel::find ($id);
            if (!$offer) {
                return $this->returnError ('400', 'not found this offer');
            } else {
                $offer->is_active = 0;
                $offer->save ();
                return $this->returnData ('offer', $offer, 'this offer is trashed now');
            }
        } catch (\Exception $ex) {
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }

    public function restoreTrashed ($id){
        try {
            $offer = $this->OfferModel::find ($id);
            if (!$offer) {
                return $this->returnError ('400', 'not found this offer');
            } else {
                $offer->is_active = 1;
                $offer->save ();
                return $this->returnData ('offer', $offer, 'this offer is restore trashed now');
            }
        } catch (\Exception $ex) {
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }

    public function delete($id){
        try {
            $offer=$this->OfferModel::find($id);
            if (!$offer) {
                return $this->returnError ('400', 'not found this offer');
            } elseif ($offer->is_active == 0) {
                $offer->delete ();
                $offer->OfferTranslation ()->delete ();
                return $this->returnData ('offer', $offer, 'this offer is deleted now');
            } else {
                return $this->returnError ('400', 'this offer can not deleted now');
            }
        } catch (\Exception $ex) {
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }

    public function getStoreByOfferId($Offer_id){
        try {
            $offer = $this->OfferModel::find ($Offer_id);
            if (!$offer) {
                return $this->returnError ('400', 'not found this Offer');
            } else {
                $offer = $this->OfferModel::with ('Store')->find ($Offer_id);
                return $this->returnData ('Offer', $offer, 'done');
            }
        } catch (\Exception $ex) {
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }

    public function getOfferByStoreId ($Store_id){
        try {
            $store = $this->StoreModel::find ($Store_id);
            if (!$store) {
                return $this->returnError ('400', 'not found this store');
            } else {
                $store = $this->StoreModel::with ('Offer')->find ($Store_id);
                return $this->returnData ('Store', $store, 'done');
            }
        } catch (\Exception $ex) {
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }

    public function getTrashed (){
        try {
            $offer = $this->OfferModel::NotActive ();
            return $this->returnData ('offer', $offer, 'done');
        } catch (\Exception $ex) {
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }

    public function getAdvertisement (){
        try {
            $offer = $this->OfferModel::Advertisement ();
            return $this->returnData ('advertisement', $offer, 'this is advertisements');
        } catch (\Exception $ex) {
            return $this->returnError ($ex->getCode (), $ex->getMessage ());
        }
    }
}
