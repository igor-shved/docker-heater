<?php

namespace App\Models\api;

use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DataFiles extends Model
{
    public function getDataFromFiles($arrayPath)
    {
//        $dataPath = Http::withHeaders([
//            'Content-Type' => 'application/json'
//        ])->get('http://heater-new.kl.loc/datalog/latestdata.php');
//        dd($dataPath->body());

        $arrayData = [];
        foreach ($arrayPath as $item) {
            $arrayData[] = DataFiles::getDataPath($item);
            //$arrayData[] = DataFiles::getDataPath('http://192.168.200.77/heater/datalog/latestdata.php');
            //Log::channel('debug')->debug('$arrayData: ' . json_encode($arrayData));
        }
        return response()->json(['success' => true, 'data' => $arrayData, 'status' => 200]);
    }

    private function getDataPath($pathFile)
    {
        try {
            return Http::get($pathFile)->body();
        } catch (\Exception  | \Throwable $e) {
            //Log::channel('debug')->debug('error no path: ' . json_encode($pathFile) . $e->getMessage());
            return '';
        }
    }
}
