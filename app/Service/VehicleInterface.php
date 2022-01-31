<?php

namespace App\Service;

use Illuminate\Http\Request;
use Nette\Utils\Json;

interface VehicleInterface
{

    /**
     * all active vehicle list
     *
     * @return Json App\Http\Controllers\API\BaseController
     */
    public function getVehicles();

    /**
     * create new vehicle
     *
     * @param Request $request
     * @return Json App\Http\Controllers\API\BaseController
     */
    public function createVehicle(Request $request);

    /**
     * view specific data
     *
     * @param integer $id
     * @return json App\Http\Controllers\API\BaseController
     */
    public function showVehicle(int $id);

    /**
     * update active vehicle
     *
     * @param Request $request
     * @param integer $id
     * @return json App\Http\Controllers\API\BaseController
     */
    public function updateVehicle(Request $request, int $id);

    /**
     * delete data
     *
     * @param integer $id
     * @return json App\Http\Controllers\API\BaseController
     */
    public function deleteVehicle(int $id);

    /**
     * all deleted vehicle list
     *
     * @return Json App\Http\Controllers\API\BaseController
     */
    public function deletedVehicleList();

}
