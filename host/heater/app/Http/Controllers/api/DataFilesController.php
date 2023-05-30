<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\api\DataFiles;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class DataFilesController extends Controller
{
    public function getDataFiles(Request $request)
    {
        $data = Arr::get($request->all(),'data',[]);
        Log::channel('debug')->debug('$data get: ' . json_encode($data));
        $data_files = new DataFiles();
        return $data_files->getDataFromFiles($data);
    }
    public function saveSettingToFiles(Request $request)
    {
        $data = Arr::get($request->all(),'data',[]);
        Log::channel('debug')->debug('$data save: ' . json_encode($data));
        return view('debug_data', [$data]);
    }
}
