<?php


namespace App\Service\Calendar;

use App\Http\Requests\Calendar\CalendarRequest;
use App\Traits\GeneralTrait;
use App\Models\Calendar\Calendar;


class CalendarService
{
    private $CalendarModel;
    use GeneralTrait;

    public function __construct(Calendar $Calendar)
    {
        $this->CalendarModel=$Calendar;
    }
    public function get()
    {
        $Calendar= $this->CalendarModel::IsActive();
        return $this->returnData('Calendar',$Calendar,'done');
    }

    public function getById($id)
    {
        $Calendar= $this->CalendarModel::find($id);
        return $this->returnData('Calendar',$Calendar,'done');
    }

    public function create( CalendarRequest $request )
    {
        $Calendar=new Calendar();

        $Calendar->day_name                  =$request->day_name ;
        $Calendar->timestamps                =$request->timestamps ;
        $Calendar->holiday_name              =$request->holiday_name;
        $Calendar->holiday_note              =$request->holiday_note;


        $result=$Calendar->save();
        if ($result)
        {
            return $this->returnData('Calendar', $Calendar,'done');
        }
        else
        {
            return $this->returnError('400', 'saving failed');
        }
    }

    public function update(CalendarRequest $request,$id)
    {
        $Calendar= $this->CalendarModel::find($id);

        $Calendar->day_name                  =$request->day_name ;
        $Calendar->timestamps                =$request->timestamps ;
        $Calendar->holiday_name              =$request->holiday_name;
        $Calendar->holiday_note              =$request->holiday_note;

        $result=$Calendar->save();
        if ($result)
        {
            return $this->returnData('Calendar', $Calendar,'done');
        }
        else
        {
            return $this->returnError('400', 'updating failed');
        }
    }

    public function trash( $id)
    {
        $Calendar= $this->CalendarModel::find($id);
        $Calendar->is_active=false;
        $Calendar->save();
        return $this->returnData('Calendar', $Calendar,'This Calendar is trashed Now');
    }

    public function restoreTrashed( $id)
    {
        $Calendar=Calendar::find($id);
        $Calendar->is_active=true;
        $Calendar->save();
        return $this->returnData('Calendar', $Calendar,'This Calendar is trashed Now');
    }

    public function delete($id)
    {
        $Calendar=Calendar::find($id);
        $Calendar->is_active = false;
        $Calendar->save();
        return $this->returnData('Calendar', $Calendar, 'This Calendar is deleted Now');
    }
}
