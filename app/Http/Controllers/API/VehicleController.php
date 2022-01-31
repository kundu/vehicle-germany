<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController as BaseController;
use App\Http\Resources\VehicleDetailResource;
use App\Service\VehicleService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class VehicleController extends BaseController
{
    private $vehicleService;
    private $baseController;

    function __construct() {
        $this->vehicleService = new VehicleService();
        $this->baseController = new BaseController();
    }

    /**
     * Display a listing of the resource.
     *
     */
    public function index(){
        $response = $this->vehicleService->getVehicles();
        if($response['success']){
            return $this->baseController->sendResponse(VehicleDetailResource::collection($response['data']), $response['message']);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'manufacturer'       => 'required|string|max:150',
            'model'              => 'required|string|max:150',
            'fin'                => 'required|string|max:150',
            'first_registration' => 'nullable|string|max:150',
            'kilometers_stand'   => 'nullable|string|max:150',
        ]);

        if($validator->fails()){
            return $this->baseController->sendError('Input validation error', $validator->errors());
        }
        try {
            $vehicle  = $this->vehicleService->createVehicle($request);
            return $this->baseController->sendResponse(new VehicleDetailResource($vehicle), 'Vehicle data is created successfully.');
        } catch (Exception $ex) {
            Log::error($ex);
            return $this->sendError('Internal Server Error. Please inform admin to check log file.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     */
    public function show($id){
        $response = $this->vehicleService->showVehicle($id);
        if($response['success']){
            return $this->baseController->sendResponse(new VehicleDetailResource($response['data']), $response['message']);
        }
        else{
            return $this->baseController->sendError($response['message']);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'manufacturer'       => 'required|string|max:150',
            'model'              => 'required|string|max:150',
            'fin'                => 'required|string|max:150',
            'first_registration' => 'nullable|string|max:150',
            'kilometers_stand'   => 'nullable|string|max:150',
        ]);

        if($validator->fails()){
            return $this->baseController->sendError('Input validation error', $validator->errors());
        }

        try {
            $response = $this->vehicleService->updateVehicle($request, $id);
            if($response['success']){
                return $this->baseController->sendResponse(new VehicleDetailResource($response['data']), $response['message']);
            }
            else{
                return $this->baseController->sendError($response['message']);
            }
        } catch (Exception $ex) {
            Log::error($ex);
            return $this->sendError('Internal Server Error. Please inform admin to check log file.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     */
    public function destroy($id){
        try{
            $response = $this->vehicleService->deleteVehicle($id);
            if($response['success']){
                return $this->baseController->sendResponse([], $response['message']);
            }
            else{

                return $this->baseController->sendError($response['message']);
            }
        } catch (Exception $ex) {
            Log::error($ex);
            return $this->sendError('Internal Server Error. Please inform admin to check log file.');
        }
    }

    /**
     * Display a listing of the deleted resource.
     *
     */
    public function deletedVehicleList(){
        $response = $this->vehicleService->deletedVehicleList();
        if($response['success']){
            return $this->baseController->sendResponse([], $response['message']);
        }
    }
}
