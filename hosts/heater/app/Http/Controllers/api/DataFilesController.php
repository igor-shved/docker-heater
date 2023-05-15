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
        Log::channel('debug')->debug('$data: ' . json_encode($data));
        return DataFiles::getDataFromFiles($data);
    }
}
