<?php

namespace app\Models\api;

use Illuminate\Filesystem\Filesystem;

class FileSystemStorage
{
    private string $pathFileSystem;

    public function __construct($path)
    {
        $this->pathFileSystem = $path;
    }

    public function getContent($pathFile, $lock = false)
    {
        $fs = new Filesystem();
        $arrayData = ['success' => false, 'data' => ''];
        try {
            $dataFile = $fs->get($this->pathFileSystem . $pathFile, $lock);
            $arrayData['success'] = true;
            $arrayData['data'] = $dataFile;
        } catch (\Throwable $exception) {
            $arrayData['success'] = false;
            $arrayData['data'] = $exception->getMessage() . ', file path => ' . $pathFile;
        }
        return $arrayData;
    }

    public function putContent($pathFile, $content, $lock = false)
    {
        $fs = new Filesystem();
        $arrayData = ['success' => false, 'data' => ''];
        try {
            $fs->put($this->pathFileSystem . $pathFile, $content, $lock);
            $arrayData['success'] = true;
        } catch (\Throwable $exception) {
            $arrayData['success'] = false;
            $arrayData['data'] = $exception->getMessage() . ', file path => ' . $pathFile;
        }
        return $arrayData;
    }
}
