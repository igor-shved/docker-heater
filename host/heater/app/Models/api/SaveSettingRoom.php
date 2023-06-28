<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;



class SaveSettingRoom extends Model
{
    use HasFactory;
    public function saveSetting($requestRoom)
    {
        $requestData = collect($requestRoom);
        //dd($requestRoom);
        return response()->json(['success' => true, 'data' => $requestRoom->id, 'status' => 200]);
    }
}
