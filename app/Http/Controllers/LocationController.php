<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use App\Http\Requests\Location\CreateLocationRequest;
use App\Http\Requests\Location\LocationRequest;
use App\Services\LocationServices;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    protected LocationServices $locationServices;

    public function __construct(LocationServices $locationService)
    {
        $this->locationServices = $locationService;
    }

    public function getLocationDropdown() {
        $data = $this->locationServices->getLocationList(true);
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }
    public function getLocations(Request $request)
    {
        $data = $this->locationServices->getLocationList(
            false,
            $request->query('search'),
            (int)$request->query('rows')
        );
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function getLocationById(Request $request)
    {
        $data = $this->locationServices->getLocationById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.fetch_success'));
    }

    public function createLocation(LocationRequest $request)
    {
        $data = $request->validated();
        $data = $this->locationServices->createLocation($data);
        return Helper::responseSuccessTry($data, __('successMessages.create_success'));
    }

    public function updateLocationById(LocationRequest $request)
    {
        $data = $request->validated();
        $data = $this->locationServices->updateLocationById($data, $request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.update_success'));
    }

    public function deleteLocationById(Request $request)
    {
        $data = $this->locationServices->deleteLocationById($request->route('id'));
        return Helper::responseSuccessTry($data, __('successMessages.delete_success'));
    }

}
