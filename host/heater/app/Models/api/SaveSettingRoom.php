<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SaveSettingRoom extends Model
{
    use HasFactory;

    public function saveSetting($requestRoom)
    {
        $force = false;
        $force2 = false;

        $saveSettingRoom = new SaveSettingRoom();
        $requestData = collect($requestRoom);
        $idRoom = $requestData->get('id');
        $diskFiles = Storage::disk('files_project');
        $pathDisk = config('filesystems.disks.files_project.root');
        $fs = new FileSystemStorage($pathDisk);
        $result = $fs->putContent('/outputs/date.x', $requestData->get('thisServerUpdateTime'), true);
        if ($result['success'] === false) {
            return response()->json(['success' => false, 'data' => $result['data'], 'status' => 500]);
        }
        //Log::channel('debug')->debug(json_encode($saveSettingRoom->generateSettingsText($requestData)));
        $newDataRoom = $saveSettingRoom->generateSettingsText($requestData);
        $oldDataRoom = '';
        $result = $fs->getContent('/outputs/' . $idRoom);
        if ($result['success'] === true) {
            $oldDataRoom = $result['data'];
        } else {
            return response()->json(['success' => false, 'data' => $result['data'], 'status' => 500]);
        }
        $newDataArray = explode(':', $newDataRoom);
        $oldDataArray = explode(':', $oldDataRoom);
        $rndStr = $saveSettingRoom->generateRandomString(6);
        $state = '';
        $result = $fs->getContent('/outputs/st');
        if ($result['success'] === true) {
            $state = $result['data'];
        } else {
            return response()->json(['success' => false, 'data' => $result['data'], 'status' => 500]);
        }
        $mode = $state[$idRoom];

        //dump($state, $mode);
        // m.dirty - 'outputs/st' был выдан уже контроллеру в ответ
        // d.dirty - 'outputs/x', где x = 0..16 был выдан уже контроллеру в ответ
        // o.dirty - данные 'outputs/st' или 'outputs/x' устарели,
        // контроллер должен начать процедуру запроса данных с начала (refresh)
        // x.dirty = 000 (первый байт = o.dirty, второй байт m.dirty, третий байт - d.dirty)
        // if $mode == 0 => mode = 1 // 1 - only temp & states;
        // if $mode == 1 => mode = 1
        // if $mode == 2 => mode = 3 // temp & shed
        if ($force && $mode == 0) {
            $mode = 1;
        }
        if ($force2) {
            $mode = 3;
        }
        if ($newDataArray[0] != $oldDataArray[0]) {
            // if $mode == 0 => mode = 1 // 1 - only temp & states;
            // if $mode == 1 => mode = 1
            // if $mode == 2 => mode = 3 // temp & shed
            // so just
            if ($mode != 1) {
                $mode++;
                //print "a";
            }
        }
        if ($newDataArray[1] != $oldDataArray[1]) {
            // if $mode == 0 => mode = 2  // only shed
            // if $mode == 1 => mode = 3  // temp & shed
            // if $mode == 2 => mode = 2
            // so just
            if ($mode != 2) {
                $mode += 2;
            }
        }
        if ($mode > 3) {// if $mode was 3, we have back it to 3
            $mode = 3;
        }

        $lock_flag = '';
        if ($mode !== 0)   // d.dirty
        {
            $flag_file = '';
            $result = $fs->getContent('/outputs/x.dirty', true);
            if ($result['success'] === true) {
                $lock_flag = $result['data'];
            } else {
                return response()->json(['success' => false, 'data' => $result['data'], 'status' => 500]);
            }
            // o.dirty == 0 && ( d.dirty == 1 || m.dirty == 1 )
            if ($lock_flag[0] == 0 && ($lock_flag[2] == 1 || $lock_flag[1] == 1)) {
                $lock_flag[0] = 1;        // устанавливаем флаг o.dirty - устаревшие данные
                $result = $fs->putContent('/outputs/x.dirty', true);
                if ($result['success'] === false) {
                    return response()->json(['success' => false, 'data' => $result['data'], 'status' => 500]);
                }
            }
        }
        if ($state[$idRoom] !== $mode) {
            $state[$idRoom] = $mode;
            $stx = explode(":", $state);
            $state = $stx[0] . ':' . $rndStr;
            $result = $fs->putContent('/outputs/st', $state, true);
            if ($result['success'] === false) {
                return response()->json(['success' => false, 'data' => $result['data'], 'status' => 500]);
            }
            return response()->json(['success' => true, 'data' => $requestData['hashStrSetting'], 'status' => 200]);
        } else {
            return response()->json(['success' => true, 'data' => $requestData['hashStrSetting'], 'status' => 200]);
        }

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
            $strScheduleMode = $strScheduleMode . $item['mode'];
        }
        Log::channel('debug')->debug('$strScheduleMode' . json_encode($strScheduleMode));
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
