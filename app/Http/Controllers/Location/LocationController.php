<?php

namespace App\Http\Controllers\Location;

use App\Http\Controllers\Controller;
use App\Service\Location\LocationService;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    private  $location;

    /**
     * Category Service constructor.
     * @param LocationService $location
     */
    public function __construct(LocationService $location)
    {
        $this->location = $location;
    }

    public function getAll()
    {
        return $this->location->getAll();
    }

    public function getById($id)
    {
    }

    public function getTrashed()
    {
    }

    public function restoreTrashed($id)
    {
    }

    public function trash($id)
    {
    }

    public function create(Request $request)
    {
        return $this->location->create($request);
    }

    public function update(Request $request, $id)
    {
    }

    public function search($name)
    {
    }

    public function delete($id)
    {
    }

    public function upload($image, $folder)
    {
    }

    public function update_upload(Request $request, $id)
    {
    }
}
