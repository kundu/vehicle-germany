<?php

namespace App\Service;

use App\Http\Controllers\API\BaseController;
use App\Models\VehicleDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleService implements VehicleInterface
{
    private $baseController;

    function __construct() {
        $this->baseController = new BaseController();
    }
    public function getVehicles(){
        $vehicleDetails = VehicleDetail::with('createdBy')->orderBy('id', 'desc')->get();
        return $this->baseController->serviceResponse(true, 'All vehicle data.', $vehicleDetails);
    }

    public function createVehicle(Request $request){
        try {
            $vehicleDetail                     = new VehicleDetail();
            $vehicleDetail->manufacturer       = $request->manufacturer;
            $vehicleDetail->model              = $request->model;
            $vehicleDetail->fin                = $request->fin;
            $vehicleDetail->first_registration = $request->first_registration;
            $vehicleDetail->kilometers_stand   = $request->kilometers_stand;
            $vehicleDetail->created_by         = auth()->user()->id;
            $vehicleDetail->save();
            return $vehicleDetail;
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function showVehicle(int $id){
        $vehicleDetail = VehicleDetail::with('createdBy')->find($id);
        if(!$vehicleDetail)
            return $this->baseController->serviceResponse(false, 'Vehicle not found');

        return $this->baseController->serviceResponse(true, 'Vehicle details', $vehicleDetail);
    }

    public function updateVehicle(Request $request, int $id){
        try {
            $vehicleDetail = VehicleDetail::find($id);
            if(!$vehicleDetail)
                return $this->baseController->serviceResponse(false, 'Vehicle not found');

            $vehicleDetail->manufacturer = $request->manufacturer;
            $vehicleDetail->model        = $request->model;
            $vehicleDetail->fin          = $request->fin;

            if($request->first_registration)
                $vehicleDetail->first_registration = $request->first_registration;
            if($request->kilometers_stand)
                $vehicleDetail->kilometers_stand = $request->kilometers_stand;

            $vehicleDetail->save();

            return $this->baseController->serviceResponse(true, 'Vehicle data is updated successfully.', $vehicleDetail);
        } catch (\Throwable $th) {
            throw new Exception($th);
        }
    }

    public function deleteVehicle(int $id){
        try{
            $vehicleDetail = VehicleDetail::find($id);
            if(!$vehicleDetail)
                return $this->baseController->serviceResponse(false, 'Vehicle not found');

            DB::beginTransaction();
            $vehicleDetail->last_edited_by = auth()->user()->id;
            $vehicleDetail->save();
            $vehicleDetail->delete();
            DB::commit();
            return $this->baseController->serviceResponse(true, 'Vehicle is deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th);
        }
    }

    public function deletedVehicleList(){
        $vehicleDeletedData = VehicleDetail::with('lastEditedBy')->onlyTrashed()->get();
        return $this->baseController->serviceResponse(true, 'All deleted vehicle data.', $vehicleDeletedData);
    }

}
