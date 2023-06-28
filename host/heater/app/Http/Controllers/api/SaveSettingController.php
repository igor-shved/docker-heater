<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\SaveSettingRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class SaveSettingController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $requestRoom = Arr::get(request()->all(), 'data', []);
        $saveSettingRoom = new SaveSettingRoom();
        return  $saveSettingRoom->saveSetting($request);
    }
}
