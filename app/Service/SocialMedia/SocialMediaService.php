<?php


namespace App\Service\SocialMedia;


use App\Http\Requests\SocialMedia\SocialMediaRequest;
use App\Models\SocialMedia\SocialMedia;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class SocialMediaService
{
    private $SocialMediaModel;
    use GeneralTrait;

    public function __construct(SocialMedia $SocialMedia)
    {
        $this->SocialMediaModel=$SocialMedia;
    }
    public function get()
    {
        try{
        $SocialMedia=$this->SocialMediaModel::paginate(5);
        return $this->returnData('SocialMedia',$SocialMedia,'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),  $ex->getMessage());
        }
    }
    public function getById($id)
    {
        try{
        $SocialMedia= $this->SocialMediaModel::find($id);
            if (is_null($SocialMedia)){
                return $this->returnSuccessMessage('this Social Media not found','done');
            }
            else{
                return $this->returnData('Social Media',$SocialMedia,'done');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),  $ex->getMessage());
        }
    }

    public function create( SocialMediaRequest $request )
    {
        try{
                   $SocialMedia=new SocialMedia();

                   $SocialMedia->phone_number                       =$request->phone_number ;
                   $SocialMedia->whatsapp_number                    =$request->whatsapp_number;
                   $SocialMedia->facebook_account                   =$request->facebook_account;
                   $SocialMedia->instagram_account                  =$request->instagram_account;
                   $SocialMedia->telegram_number                    =$request->telegram_number ;
                   $SocialMedia->email                              =$request->email  ;
                   $SocialMedia->user_id                          =$request->user_id   ;
                   $SocialMedia->is_active                          =$request->is_active   ;

                   $result=$SocialMedia->save();
                   if ($result)
                   {
                       return $this->returnData('SocialMedia', $SocialMedia,'done');
                   }
                   else
                   {
                       return $this->returnError('400', 'saving failed');
                   }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),  $ex->getMessage());
        }
   }
    public function update(SocialMediaRequest $request,$id)
    {
        try{
        $SocialMedia= $this->SocialMediaModel::find($id);

        $SocialMedia->phone_number                       =$request->phone_number ;
        $SocialMedia->whatsapp_number                    =$request->whatsapp_number;
        $SocialMedia->facebook_account                   =$request->facebook_account;
        $SocialMedia->instagram_account                  =$request->instagram_account;
        $SocialMedia->telegram_number                    =$request->telegram_number ;
        $SocialMedia->email                              =$request->email  ;
        $SocialMedia->user_id                          =$request->user_id   ;
        $SocialMedia->is_active                          =$request->is_active   ;

        $result=$SocialMedia->save();
        if ($result)
        {
            return $this->returnData('SocialMedia', $SocialMedia,'done');
        }
        else
        {
            return $this->returnError('400', 'updating failed');
        }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),  $ex->getMessage());
        }

    }
    public function trash( $id)
    {
        try{
        $SocialMedia= $this->SocialMediaModel::find($id);
            if (is_null($SocialMedia)) {
                return $this->returnSuccessMessage('This Medical file not found', 'done');
            }
            else
            {
                $SocialMedia->is_active=0;
                $SocialMedia->save();
                return $this->returnData('SocialMedia', $SocialMedia,'This SocialMedia is trashed Now');
            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),  $ex->getMessage());
        }
    }
    public function getTrashed()
    {
        try{
        $SocialMedia= $this->SocialMediaModel::NotActive();
        return $this -> returnData('SocialMedia',$SocialMedia,'done');
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }
    public function restoreTrashed( $id)
    {
        try{
        $SocialMedia=SocialMedia::find($id);
        if (is_null($SocialMedia)) {
            return $this->returnSuccessMessage('This Social Media not found', 'done');
        }
        else
        {
            $SocialMedia->is_active=1;
            $SocialMedia->save();
            return $this->returnData('SocialMedia', $SocialMedia,'This SocialMedia is trashed Now');
        }
    }
        catch (\Exception $ex) {
              return $this->returnError($ex->getCode(),  $ex->getMessage());
       }

    }
    public function delete($id)
    {
        try{
        $SocialMedia = SocialMedia::find($id);
            if ($SocialMedia->is_active == 0) {
                $SocialMedia->delete();
                return $this->returnData('Social Media', $SocialMedia, 'This Social Media is deleted Now');
            }
            else{
                return $this->returnData('Social Media', $SocialMedia, 'This Social Media can not deleted Now');

            }
        }
        catch (\Exception $ex) {
            return $this->returnError($ex->getCode(),  $ex->getMessage());
        }
    }

}
