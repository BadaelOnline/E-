<?php


namespace App\Service\Location;


use App\Models\Location\Location;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class LocationService

{
    use GeneralTrait;

    private $location;
    private $PAGINATION_COUNT;

    /**
     * Category Service constructor.
     * @param Location $location
     */
    public function __construct(Location $location)
    {
        $this->location = $location;
        $this->PAGINATION_COUNT = 25;
    }

    public function getAll()
    {
        return 1;
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

    /**
     * create location .
     * @param Request $request
     * @return JsonResponse $new_location
     */
    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $new_location = $this->location->insertGetId([
                'name' => $request['name'],
                'country' => $request['country'],
                'governorate' => $request['governorate'],
                'street' => $request['street'],
                'building_name' => $request['building_name'],
                'phone_number' => $request['phone_number'],
                'latitude' => $request['latitude'],
                'longitude' => $request['longitude'],
                'is_active' => $request['is_active'],
            ]);
            DB::commit();
            return $new_location;
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError('Location', $ex->getMessage());
        }

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
