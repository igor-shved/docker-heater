<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class SaveSettingRoom extends Model
{
    use HasFactory;

    public function saveSetting($requestRoom)
    {
        $requestData = collect($requestRoom);
        $diskFiles = Storage::disk('files_project');
        $diskFiles->put('/outputs/date.x', $requestData->get('thisServerUpdateTime'));
        if (!$diskFiles->exists('/outputs/st')) {
            $diskFiles->put('/outputs/st', '00000000000000000');
        }

        //return response()->json(['success' => true, 'data' => $requestData, 'status' => 200]);
        return response()->json(['success' => true, 'data' => $requestData->get('thisServerUpdateTime'), 'status' => 200]);
    }
}
