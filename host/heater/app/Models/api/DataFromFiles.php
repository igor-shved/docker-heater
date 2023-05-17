<?php

namespace App\Models\api;
namespace App\Exceptions;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class DataFromFiles extends Model
{
    use HasFactory;
    public function getDataFromFiles($arrayPath)
    {
        Log::channel('debug')->debug('debug type' . get_debug_type($arrayPath));
        if (get_debug_type($arrayPath) != 'array') {
            Log::channel('debug')->debug(get_debug_type($arrayPath));
            return response()->json(['success' => false, 'data' => 'wrong type data parameter', 'status' => 200]);
        }
        $arrayData = [];
        Log::channel('debug')->debug(get_debug_type($arrayPath));
        foreach ($arrayPath as $item) {
            $arrayData[] = DataFromFiles::getDataPath($item);
        }
        return response()->json(['success' => true, 'data' => $arrayData, 'status' => 200]);
    }

    private function getDataPath($pathFile)
    {
        try {
            $dataPath = Http::get($pathFile)->json();
            Log::channel('debug')->debug('$response: ' . $dataPath);
            return $dataPath;
        } catch (Exception $e) {
            Log::channel('debug')->debug('error no path: ');
            return '';
        }
    }
}
