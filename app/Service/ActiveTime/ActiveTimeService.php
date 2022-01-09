<?php


namespace App\Service\ActiveTime;
use App\Http\Requests\ActiveTime\ActiveTimeRequest;
use App\Models\ActiveTime\ActiveTime;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\DB;

class ActiveTimeService
{
    private $ActiveTimeModel;
    use GeneralTrait;


    public function __construct(ActiveTime $ActiveTime)
    {
        $this->ActiveTimeModel=$ActiveTime;
    }
    public function get()
    {
        try
        {
            $ActiveTime= $this->ActiveTimeModel::paginate(5);
            return $this->returnData('ActiveTime',$ActiveTime,'done');
        }
       catch(\Exception $ex)
       {
           return $this->returnError('400',$ex->getMessage());
       }
    }

    public function getById($id)
    {
        try
        {
            $ActiveTime= $this->ActiveTimeModel::find($id);
            if (is_null($ActiveTime) ){
                return $this->returnSuccessMessage('This Active time not found','done');}
        else{
            return  $this->returnData('ActiveTime',$ActiveTime,'done');
        }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400',$ex->getMessage());
        }
    }


    public function create( ActiveTimeRequest $request )
    {
        try {
            $ActiveTime = new ActiveTime();

            $ActiveTime->start_time   = $request->start_time;
            $ActiveTime->end_time     = $request->end_time;
            $ActiveTime->is_approved  = $request->is_approved;
            $ActiveTime->is_active    = $request->is_active;

            $result = $ActiveTime->save();

            if ($result)
            {
                return $this->returnData('ActiveTime', $ActiveTime, 'done');
            }
            else
            {
                return $this->returnError('400', 'saving failed');
            }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function update(ActiveTimeRequest $request,$id)
    {
        try {
        $ActiveTime= $this->ActiveTimeModel::find($id);

        $ActiveTime->start_time                =$request->start_time;
        $ActiveTime->end_time                  =$request->end_time ;
        $ActiveTime->is_approved               =$request->is_approved;
        $ActiveTime->is_active                 =$request->is_active;

        $result=$ActiveTime->save();
        if ($result)
        {
            return $this->returnData('ActiveTime', $ActiveTime,'done');
        }
        else
        {
            return $this->returnError('400', 'updating failed');
        }
        }
        catch(\Exception $ex)
        {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function trash( $id)
    {
        try {
            $ActiveTime = $this->ActiveTimeModel::find($id);
            if (is_null($ActiveTime)) {
                return $this->returnSuccessMessage('This Active Time not found', 'done');
            } else {
                $ActiveTime->is_active = 0;
                $ActiveTime->save();
                return $this->returnData('ActiveTime', $ActiveTime, 'This Active Time is trashed Now');
            }
        }
        catch (\Exception $ex)
        {
            return $this->returnError('400', $ex->getMessage());
        }
    }
    public function getTrashed()
    {
        try {
        $ActiveTime= $this->ActiveTimeModel::NotActive()->get();
        return $this -> returnData('ActiveTime',$ActiveTime,'done');
        }
       catch (\Exception $ex)
        {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function restoreTrashed( $id)
    {
        try {
            $ActiveTime =$this->ActiveTimeModel->find($id);
            if (is_null($ActiveTime)) {
                return $this->returnSuccessMessage('This Active Time not found', 'done');
            } else {
                $ActiveTime->is_active = 1;
                $ActiveTime->save();
                return $this->returnData('ActiveTime', $ActiveTime, 'This Active Time is trashed Now');
            }
        }
            catch (\Exception $ex)
        {
            return $this->returnError('400', $ex->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            $ActiveTime = $this->ActiveTimeModel->find($id);
            if ($ActiveTime->is_active == 0) {
                $ActiveTime = $this->ActiveTimeModel->delete();

            return $this->returnData('Active Time', $ActiveTime, 'This Active Time Is deleted Now');
            }
            else {
                return $this->returnData('Active Time', $ActiveTime, 'This Active Time can not deleted Now');

            }
        } catch (\Exception $ex) {
            return $this->returnError('400', $ex->getMessage());
        }
    }
}
