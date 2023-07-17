<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use app\Models\DataModel\DataUserModel;
use Illuminate\Http\Request;
use App\Models\api\RoomsData;

class RoomsDataController extends Controller
{
    public function getRoomsData()
    {
        $room_model = new RoomsData();
        // Loik Maks
        //$room_model->setCanEdit(true)->getSomeSetings($key)->getArrNames();
        //$oom_inst = RoomsData::getInstanse();
        //$data_model = new DataUserModel();
        //return $data_model->toJson();
        return $room_model->getArraySettings();
    }
    public function saveRoomsData(Request $request)
    {
        dd($request);
        $room_model = new RoomsData();
    }
}
