<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;


class SaveSettingRoom extends Model
{
    use HasFactory;

    public function saveSetting($requestRoom)
    {
        $saveSettingRoom = new SaveSettingRoom();
        $requestData = collect($requestRoom);

        $diskFiles = Storage::disk('files_project');
        $diskFiles->put('/outputs/date.x', $requestData->get('thisServerUpdateTime'));
        if (!$diskFiles->exists('/outputs/st')) {
            $diskFiles->put('/outputs/st', '00000000000000000');
        }
        //Log::channel('debug')->debug(json_encode($saveSettingRoom->generateSettingsText($requestData)));
        $dataRoom = json_encode($saveSettingRoom->generateSettingsText($requestData));
        $diskFiles->put('/outputs/' . $requestData->get('id'), $dataRoom);
        $rndStr = $saveSettingRoom->generateRandomString(6);
        return response()->json(['success' => true, 'data' => $dataRoom, 'status' => 200]);
        //return response()->json(['success' => true, 'data' => $requestData->get('thisServerUpdateTime'), 'status' => 200]);
    }

    private function generateSettingsText($requestData)
    {
        $P_or_room_num = dechex($requestData->get('roomsPOutputs'));
        if ($requestData->get('id') == 0) {
            $P_or_room_num = dechex($requestData->get('id'));
        }
        $timeout = "0;0";
        $strSchedule = '';
        $strScheduleMode = '';
        $strScheduleNoDechex = '';
        foreach ($requestData->get('scheduleSetting') as $item) {
            $strTemp = str_pad($item['temp'] * 10, 3, '0', STR_PAD_LEFT);
            $strSchedule = $strSchedule . dechex($item['time'] . $strTemp) . ';';
            $strScheduleNoDechex = $strSchedule . $item['time'] . $strTemp . ';';
            $strScheduleMode = $strScheduleMode . $item['mode'];
        }
        return dechex($requestData->get('id')) . ';' . dechex($requestData->get('currentMode')) . ';' . dechex($requestData->get('rightNowTemp') * 10) . ';' . $timeout . ':' . dechex($P_or_room_num) . ';' . dechex($requestData->get('roomsTsensors')) . ';' . dechex($requestData->get('standByTemp') * 10) . ';' . dechex(count($requestData->get('scheduleSetting'))) . ';' . $strSchedule . dechex($strScheduleMode) . ';';
    }

    private function hashme($str)
    {
        $hash = 0;
        $len = strlen($str);
        if ($len == 0) return $hash;
        for ($i = 0; $i < $len; $i++) {
            $chr = ord($str[$i]);
            $hash = (($hash << 5) - $hash) + $chr;
            $hash &= 0xFFFFFFFF;
        }
        return dechex($hash);
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
