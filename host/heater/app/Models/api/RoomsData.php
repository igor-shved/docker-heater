<?php

namespace App\Models\api;

use App\Helpers\ServiceHelpers;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use PHPUnit\Util\Xml\Exception;


class RoomsData extends Model
{
    private $diskFiles;

    // Loik Max example
//    private $can_edit = false;
//
//    /**
//     * @return bool
//     */
//    public function isCanEdit(): bool
//    {
//        return $this->can_edit;
//    }
//
//    /**
//     * @param bool $can_edit
//     */
//    public function setCanEdit(bool $can_edit): RoomsData
//    {
//        $this->can_edit = $can_edit;
//        return $this;
//    }
//
//    public function getSomeSetings($key)
//    {
//
//        if($this->can_edit) {
//            if ($key == ' count') {
//                return $this->countVal(1);
//            }
//        }
//        else {
//            throw new Exception("DENY");
//        }
//
//        return $this;
//    }
//
//    public static  function getInstanse()
//    {
//        return new RoomsData();
//    }


    public function __construct()
    {
        $this->diskFiles = Storage::disk('files_project');
    }


    public function countVal($val)
    {
        $retVal = 0;
        if (!is_null($val)) {
            $retVal = 1;
            if (is_countable($val)) {
                $retVal = count($val);
            }
        }
        return $retVal;
    }

    public function getArrNames(): array
    {
        $filePath = './outputs/names';
        $arrNamesFile = [];
        if ($this->diskFiles->exists($filePath)) {
            $arrNamesFile = explode(';', $this->diskFiles->get($filePath), 18);
        }
        $collectNamesFile = collect($arrNamesFile);
        return $collectNamesFile->filter(function ($item, $key) {
            return $key > 0;
        })->mapWithKeys(function ($item, $key) {
            return [
                $key - 1 => $item
            ];
        })->all();
    }

    private function getSt()
    {
        $filePath = './outputs/st';
        $st = '';
        if ($this->diskFiles->exists($filePath)) {
            $st = $this->diskFiles->get($filePath);
        }
        if (strlen($st) < 17) {
            $st = "00000000000000000:restore";
            $this->diskFiles->put($filePath, $st, true);
        }
        return $st;
    }

    private function getUpdateSettings($st): array
    {
        $updateSettings = [];
        for ($i = 0; $i < 17; $i++)
            if ($st[$i] == 0)
                $updateSettings[$i] = 0;
            else
                $updateSettings[$i] = 1;

        return $updateSettings;
    }


    public function getLatestData(): array
    {
        $filePath = './datalog/latestdata.php';
        $latestData = [];
        if ($this->diskFiles->exists($filePath)) {
            $latestData = explode(';', $this->diskFiles->get($filePath));
        }
        return $latestData;
    }

    private function setState($latestData): int
    {
        $state = 0;
        if (isset($latestData[2]))
            $state = $latestData[2];
        return $state;
    }

    public function getScheduleTemp($arrMode)
    {
        /*$curDateTimeZone = new \DateTimeZone("Europe/Kiev");
        $curDateTime = new \DateTime("now", $curDateTimeZone);
        $timeOffset = $curDateTimeZone->getOffset($curDateTime);
        $curDate = $curDateTime->getTimestamp() + $timeOffset;*/
        //date_default_timezone_set("Europe/Kiev");
        $scheduleArrTime = $arrMode['scheduleArrTime'];
        $scheduleIntervalsNum = $arrMode['scheduleIntervalsNum'];
        $scheduleArrIntervalMode = $arrMode['scheduleArrIntervalMode'];
        $scheduleArrTemp = $arrMode['scheduleArrTemp'];
        $curTime = time();
        $currentHours = intval(date('H', $curTime));
        $currentMinutes = intval(date('i', $curTime));
        $currentSeconds = intval(date('s', $curTime));
        $scheduleArr = array_fill(0, 7, null);
//        dump($arrMode['id']);
//        dump($scheduleArrTime);
        $scheduleArr[0] = 0;
        $scheduleArr[6] = 2360;
        $countScheduleArrTime = $this->countVal($scheduleArrTime);
        for ($i = 1; $i < 6; $i++) {
            if ($scheduleIntervalsNum < $i) {
                break;
            }
            $scheduleArr[$i] = $scheduleArrTime[$i - 1];
        }
        $timeVal = $currentHours * 3600 + $currentMinutes * 60 + $currentSeconds;
        for ($i = 0; $i < $scheduleIntervalsNum; $i++) {
            // convert time to num of seconds from 00:00
            $timeValFromSchedule1 = floor($scheduleArr[$i] / 100) * 3600 + ($scheduleArr[$i] % 100) * 60;
            $timeValFromSchedule2 = floor($scheduleArr[$i + 1] / 100) * 3600 + ($scheduleArr[$i + 1] % 100) * 60;
            if ($timeValFromSchedule1 >= $timeVal && $timeVal < $timeValFromSchedule2)
                break;
        }
        //ServiceHelpers::debughtml(['$scheduleArrIntervalMode' => $scheduleArrIntervalMode]);
        $mode = $scheduleArrIntervalMode[$i - 1];
        switch ($mode) {
            case 0:
                return number_format($scheduleArrTemp[$i - 1], 1) . '°c';
            case 1:
                return 'вкл';
            default:
                return 'выкл';
        }
    }

    private function getCurrentModeText($arrMode)
    {
        switch ($arrMode['currentMode']) {
            case 1:
                return number_format($arrMode['rightNowTemp'], 1) . '°c';
            case 3:
                return number_format($arrMode['standByTemp'], 1) . '°c';
            case 4:
                return 'вкл';
            case 5:
                return 'выкл';
            case 2:
                return $this->getScheduleTemp($arrMode);
            default:
                return '--°c';
        }
    }

    private function getNowTempText($tempNow)
    {
        if ($tempNow === '')
            return '--°c';
        else
            return number_format($tempNow, 1) . '°c';
    }

    private function isSelectSet($idMode, $currentMode): bool
    {
        if ($idMode === $currentMode) return true; else return false;
    }

    public function getArraySettings(): array
    {
        $currentMode = [];
        $rightNowTemp = [];
        $standByTemp = [];
        $scheduleIntervalsNum = [];
        $scheduleArrTime = [];
        $scheduleArrTemp = [];
        $scheduleArrIntervalMode = [];
        $updateSettings = [];

        $roomsTsensorsNames = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'a', 'b', 'c', 'd', 'e', 'f'];
        $roomsTsensors = [0, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15];
        $roomsPOutputs = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];

        $roomsName = $this->getArrNames();

        $latestData = $this->getLatestData();
        $modeXStrPrev = $latestData[23];
        $state = $this->setState($latestData);
        $stateArray = [];
        for ($i = 1; $i <= 16; $i++) {
            $stateArray[] = ['number' => $i, 'state' => $state % 2];
            $state = floor($state / 2);
        }
        //ServiceHelpers::debughtml(['$stateArray' => $stateArray]);
        $st = $this->getSt();
        //Todo изменение выходов
        $stateDebugStr = $st;
        $len = strlen($st);
        for ($i = 0; $i < 16; $i++) {
            if ($i < $len)
                $followAllHouse[16 - $i] = $st[$i];
            else
                $followAllHouse[16 - $i] = 0;
        }
        $followAllHouse[0] = 1;

        $updateSettings = $this->getupdateSettings($st);
        for ($i = 0; $i < 17; $i++) {
            $filePath = './outputs/' . $i;
            $parts = explode(':', $this->diskFiles->get($filePath));
            $p1 = explode(";", $parts[0]);
            $p2 = explode(";", $parts[1]);
            $currentMode[$i] = $p1[1];
            $rightNowTemp[$i] = hexdec($p1[2]) / 10.0;
            $roomsPOutputs[$i] = hexdec($p2[0]);
            $roomsTsensors[$i] = hexdec($p2[1]);
            $standByTemp[$i] = hexdec($p2[2]) / 10.0;
            $intervalsNum = hexdec($p2[3]);
            $scheduleIntervalsNum[$i] = $intervalsNum;
            if ($intervalsNum > 10)
                $intervalsNum = 10;

            $j = 4;
            for ($j = 0; $j < $intervalsNum; $j++) {
                $val = hexdec($p2[$j + 4]);

                $time = substr($val, 0, $this->countVal($val) - 4);
                $scheduleArrTime[$i][$j] = $time;

                $temp = substr($val, -3, 3);
                $scheduleArrTemp[$i][$j] = $temp / 10.0;
            }
            $val = hexdec($p2[$j + 4]);
            $fstr = "%0$intervalsNum" . "d";
            $istr = sprintf($fstr, $val);

            for ($j = $intervalsNum; $j < 6; $j++)
                $istr .= "0";
            for ($j = 0; $j < 6; $j++) {
                $scheduleArrIntervalMode[$i][$j] = $istr[$j];
            }
        }
        //todo $thisServerUpdateTime - "outputs/date.x"
        // m.dirty - 'outputs/st' был выдан уже контроллеру в ответ
        // d.dirty - 'outputs/x', где x = 0..16 был выдан уже контроллеру в ответ
        // o.dirty - данные 'outputs/st' или 'outputs/x' устарели,
        // контроллер должен начать процедуру запроса данных с начала (refresh)
        // x.dirty = 000 (первый байт = o.dirty, воторой байт m.dirty, третий байт - d.dirty)
        $arrayRooms = [];
        $stateCol = collect($stateArray);
        foreach ($roomsName as $key => $value) {
            $index = $key;
            $currentStatusRelay = 0;
            $curPNumber = $roomsPOutputs[$index];
            if (!$index == 0) {
                $currentStatusRelayFilter = $stateCol->filter(function ($item, $key) use ($curPNumber) {
                    return $item['number'] == $curPNumber;
                })->values();
                if ($currentStatusRelayFilter->isNotEmpty()) {
                    $currentStatusRelay = $currentStatusRelayFilter[0]['state'];
                }
            }
            $currentModeValue = (int)$currentMode[$index];
            if ($index == 0) {
                $arrayModes = [
                    ['id' => 1, 'textMode' => 'ручной', 'img' => 'mode-hand.png', 'isSelect' => $this->isSelectSet(1, $currentModeValue)],
                    ['id' => 2, 'textMode' => 'по расписанию', 'img' => 'mode-schedule.png', 'isSelect' => $this->isSelectSet(2, $currentModeValue)],
                    ['id' => 3, 'textMode' => 'никого нет дома', 'img' => 'mode-standby.png', 'isSelect' => $this->isSelectSet(3, $currentModeValue)],
                    ['id' => 4, 'textMode' => 'включить', 'img' => 'mode-on.png', 'isSelect' => $this->isSelectSet(4, $currentModeValue)],
                    ['id' => 5, 'textMode' => 'выключить', 'img' => 'mode-off.png', 'isSelect' => $this->isSelectSet(5, $currentModeValue)],
                    ['id' => 7, 'textMode' => 'индивидуально', 'img' => 'individual.png', 'isSelect' => $this->isSelectSet(7, $currentModeValue)],
                ];
            } else {
                $arrayModes = [
                    ['id' => 1, 'textMode' => 'ручной', 'img' => 'mode-hand.png', 'isSelect' => $this->isSelectSet(1, $currentModeValue)],
                    ['id' => 2, 'textMode' => 'по расписанию', 'img' => 'mode-schedule.png', 'isSelect' => $this->isSelectSet(2, $currentModeValue)],
                    ['id' => 3, 'textMode' => 'никого нет дома', 'img' => 'mode-standby.png', 'isSelect' => $this->isSelectSet(3, $currentModeValue)],
                    ['id' => 4, 'textMode' => 'включить', 'img' => 'mode-on.png', 'isSelect' => $this->isSelectSet(4, $currentModeValue)],
                    ['id' => 5, 'textMode' => 'выключить', 'img' => 'mode-off.png', 'isSelect' => $this->isSelectSet(5, $currentModeValue)],
                ];
            }
            $arrMode = [
                'id' => $index,
                'currentMode' => $currentMode[$index],
                'rightNowTemp' => $rightNowTemp[$index],
                'standByTemp' => $standByTemp[$index],
                'scheduleArrTime' => $scheduleArrTime[$index],
                'scheduleIntervalsNum' => $scheduleIntervalsNum[$index],
                'scheduleArrIntervalMode' => $scheduleArrIntervalMode[$index],
                'scheduleArrTemp' => $scheduleArrTemp[$index],
            ];
            $scheduleArrRoom = [];
            for ($i = 0; $i < count($scheduleArrTime[$index]); $i++) {
                $scheduleArrRoom[] = [
                    'numStr' => $i + 1,
                    'time' => intval($scheduleArrTime[$index][$i]),
                    'temp' => $scheduleArrTemp[$index][$i],
                    'mode' => intval($scheduleArrIntervalMode[$index][$i]),
                ];
            }
            $arrayRooms[] = [
                'id' => $index,
                'roomName' => $roomsName[$index],
                'tempName' => '',
                'relayName' => '',
                'currentMode' => $currentModeValue,
                'currentModeTextArray' => $this->getCurrentModeText($arrMode),
                'roomNowTemp' => $this->getNowTempText($latestData[3 + $roomsTsensors[$index]]),
                'currentStatusRelay' => $currentStatusRelay,
                'rightNowTemp' => $rightNowTemp[$index],
                'standByTemp' => $standByTemp[$index],
                'scheduleIntervalsNum' => $scheduleIntervalsNum[$index],
                'roomsTsensors' => $roomsTsensors[$index],
                'roomsPOutputs' => $roomsPOutputs[$index],
                'updateSettings' => $updateSettings[$index],
                'followAllHouse' => $followAllHouse,
                'scheduleArrTime' => $scheduleArrTime[$index],
                'scheduleArrTemp' => $scheduleArrTemp[$index],
                'scheduleArrIntervalMode' => $scheduleArrIntervalMode[$index],
                'scheduleArrRoom' => $scheduleArrRoom,
                'roomsTsensorsName' => $roomsTsensorsNames[$roomsTsensors[$index]],
                'modeXStrPrev' => $modeXStrPrev,
                'state' => $state,
                'stateDebugStr' => $stateDebugStr,
                'arrayModes' => $arrayModes,
            ];
        }
        //dd($latestData, $roomsTsensors, $roomsPOutputs, $arrayRooms);
        return $arrayRooms;
    }

    public function saveSettings($arraySettings)
    {

    }
}
