<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\RoomsData;

class RoomsDataController extends Controller
{
    public function getRoomsData()
    {
        return RoomsData::getArraySettings();
    }
}
