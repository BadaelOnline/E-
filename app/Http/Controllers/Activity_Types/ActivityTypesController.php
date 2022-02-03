<?php

namespace App\Http\Controllers\Activity_Types;

use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityType\ActivityTypeRequest;
use App\Service\Activiy_Types\ActivityTypesServicie;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;

class ActivityTypesController extends Controller
{
    use GeneralTrait;
    private $activityTypesServicie;
    public function __construct(ActivityTypesServicie $activityTypesServicie)
    {
        $this->activityTypesServicie=$activityTypesServicie;
    }
    public function ActivityGet()
    {
        return $this->activityTypesServicie->ActivityGet();
    }
    public function getAll()
    {
        return $this->activityTypesServicie->getAll();
    }
    public function getById($id)
    {
        return $this->activityTypesServicie->getById($id);
    }
    public function getByActivity($activity_id)
    {
        return $this->activityTypesServicie->getByActivity($activity_id);
    }
    public function getTrashed()
    {
        return $this->activityTypesServicie->getTrashed();
    }
    public function create(ActivityTypeRequest $request)
    {
        return $this->activityTypesServicie->create($request);
    }
    public function update(ActivityTypeRequest $request,$id)
    {
        return $this->activityTypesServicie->update($request,$id);
    }
    public function trash($id)
    {
        return $this->activityTypesServicie->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->activityTypesServicie->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->activityTypesServicie->delete($id);
    }
}
