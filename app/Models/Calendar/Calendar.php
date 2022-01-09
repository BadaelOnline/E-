<?php

namespace App\Models\Calendar;

use App\Models\Appointment\Appointment;
use App\Models\Appointment\AppointmentTranslation;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    use HasFactory;

    protected $table='calendars';
    protected $fillable=['id','day_name','timestamps','holiday_name','holiday_note'];

    public function appointment()
    {
        return $this->hasmany(Appointment::class);
    }

}
