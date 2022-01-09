<?php

namespace App\Service\Interaction;

use App\Http\Requests\Interaction\InteractionRequest;
use App\Models\Interaction\Interaction;
use App\Traits\GeneralTrait;

class InteractionService
{
    use GeneralTrait;
    private $InteractionModel;

    public function __construct(Interaction $interaction)
    {
        $this->InteractionModel=$interaction;
    }
    public function get()
    {
        try{
            $interaction=$this->InteractionModel::paginate(5);
             return $this->returnData('Interaction',$interaction,'done');
        }
        catch (\Exception $ex)
        {
            return $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function getById($id)
    {
        try{
            $interaction=$this->InteractionModel::find($id);
            if(!$id)
            {
                return $this->returnError('400','not found this Interaction');
            }
            else{
                return $this->returnData('Interaction',$interaction,'done');
            }
        }
        catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function create(InteractionRequest $request)
    {
        try{
            $interaction=new $this->InteractionModel();

            $interaction->user_id          =$request->user_id;
            $interaction->offer_id         =$request->offer_id;
            $interaction->interaction_type          =$request->interaction_type;
            $interaction->is_active        =$request->is_active;

            $result=   $interaction->save();

            if(!$result){
                return $this->returnError('400','saving failed');
            }
            else{
               return  $this->returnData('Interaction',$interaction,'done');
            }
        }
        catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function update(InteractionRequest $request,$id)
    {
        try{
            $interaction=$this->InteractionModel::find($id);
            if(!$interaction){return $this->returnError('400','not found this Interaction');}

            $interaction->user_id          =$request->user_id;
            $interaction->offer_id         =$request->offer_id;
            $interaction->interaction_type          =$request->interaction_type;
            $interaction->is_active        =$request->is_active;


            $result= $interaction->save();

            if(!$result){
                return $this->returnError('400','updating failed');
            }
            else{
               return  $this->returnData('Interaction',$interaction,'done');
            }
        }
        catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function trash($id)
    {
        try{
            $interaction=$this->InteractionModel::find($id);
            if(!$interaction)
            {
                return  $this->returnError('400','not found this Interaction');
            }
            else
            {
               $interaction->is_active=0;
               $interaction->save();
                return $this->returnData('Interaction',$interaction,'this interaction is trashed now');
            }
        }
        catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }

    public function gettrashed()
    {
        try{
            $interaction=$this->InteractionModel::NotActive();
            return $this->returnData('interaction',$interaction,'done');
        }
        catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function restoreTrashed($id)
    {
        try{
            $interaction=$this->InteractionModel::find($id);
            if(!$interaction)
            {
                return  $this->returnError('400','not found this interaction');
            }
            else
            {
                $interaction->is_active=1;
                $interaction->save();
                return $this->returnData('interaction',$interaction,'this interaction is trashed now');
            }
        }
        catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }
    public function delete($id)
    {
        try{
            $interaction=$this->InteractionModel::find($id);
            if(!$interaction)
            {
                return $this->returnError('400','not found this interaction');
            }
            elseif($interaction->is_active==0){
                   $interaction=$this->InteractionModel->delete();
                return $this->returnData('interaction',$interaction,'this interaction is deleted now');
            }
            else{
                return $this->returnError('400','this interaction can not deleted now');
            }
        }
        catch (\Exception $ex){
            return  $this->returnError($ex->getCode(),$ex->getMessage());
        }
    }

}
