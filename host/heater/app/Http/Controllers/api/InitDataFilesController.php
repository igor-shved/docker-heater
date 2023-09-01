<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainPageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InitDataFilesController extends Controller
{
    private function initDataFiles()
    {
        $diskFiles = Storage::disk('files_project');
        $initController = new InitDataFilesController();
        $arrayPath = [['path'=>'/outputs/x.dirty', 'dataDefault'=>'000'], ['path'=>'/outputs/st', 'dataDefault'=>'00000000000000000:restore'],
            ['path'=>'/outputs/flags', 'dataDefault'=>'00000000000000000'], ['path'=>'/outputs/date.x', 'dataDefault'=>time()],
            ['path'=>'/outputs/reload.x', 'dataDefault'=>'0'], ['path'=>'/outputs/names', 'dataDefault'=>''],
            ['path'=>'/settings/key', 'dataDefault'=>'1111111111111111'], ['path'=>'/outputs/names', 'dataDefault'=>''],
            ];
        $messageError = '';
        foreach ($arrayPath as $item) {
            $mesError = $initController->checkFile($item['path'], $item['dataDefault']);
            if (!empty($mesError)) {
                $messageError = $messageError . ' ' . $mesError;
            }
        }
        if (empty($messageError)) {
            return response()->json(['success'=>true, 'data'=>'', 'status'=>200]);
        } else {
            return response()->json(['success'=>false, 'data'=>$messageError, 'status'=>500]);
        }
    }

    private function checkFile($diskFiles, $pathFile, $dataDefault)
    {
        $mesError = '';
        if (!$diskFiles->exists($pathFile)) {
            try {
                $diskFiles->put($pathFile, $dataDefault);
            } catch(\Throwable $e) {
                $mesError = $e->getMessage();
            }
        }
        return $mesError;
    }

}
