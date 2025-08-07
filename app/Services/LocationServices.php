<?php

namespace App\Services;

use App\Exceptions\FieldInUseException;
use App\Helpers\Helper;
use App\Http\Resources\LocationCollection;
use App\Http\Resources\LocationResource;
use App\Models\Location;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LocationServices
{
    public function createLocation(array $data)
    {

        $location = Location::create([
            "name" => $data['name'],
            "latitude" => $data['latitude'],
            "longitude" => $data['longitude'],
            "radius" => $data['radius'],
            "address" => $data['address'],
            "description" => $data['description'],
            "default" => $data['default']
        ]);

        if (!$location) {
            throw new \Exception(__( 'errorMessages.create_failed'));
        }

        return $location;
    }

    public function getLocationList(bool $isAll = false, $search = null)
    {

        if ($isAll) {
            $locations = Location::get()->map(function ($location) {
                return [
                    "id" => $location->id,
                    "name" => $location->name,
                    "default" => $location->default,
                ];
            });
        } else {
            $query = Location::query();
            if ($search) {
                $query->where("name", "like", "%{$search}%");
            }
            $locations = new LocationCollection($query->paginate(10));
        }
        return $locations;
    }

    public function getLocationById($id)
    {
        $location = location::find($id);
        if (!$location) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }
        return new LocationResource($location);
    }

    public function updateLocationById(array $data, int $id)
    {

        $location = location::find($id);
        if ($location) {
            $location->name = $data["name"];
            $location->description = $data["description"];
            $location->latitude = $data["latitude"];
            $location->longitude = $data["longitude"];
            $location->radius = $data["radius"];
            $location->address = $data["address"];
            $location->default = $data["default"] ?? false;
            $location->save();
        } else {
            return throw new NotFoundHttpException(__('errorMessages.not_found'));
        }

        return $location;
    }

    public function deleteLocationById(int $id)
    {
        $location = Location::find($id);
        if (!$location) {
            throw new NotFoundHttpException(__('errorMessages.not_found'));
        }

        if ($location->schedule()->exists()) {
            throw new FieldInUseException(__('errorMessages.field_in_use'));
        }

        $location->delete();

        return $location;
    }
}
