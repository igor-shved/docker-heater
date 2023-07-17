<?php


namespace App\Http\Controllers;
use Illuminate\Routing\Controller as BaseController;
//use Illuminate\Support\Facades\Storage;


class MainPageController extends BaseController
{
    public function index(){
        return view('index');
    }
}
