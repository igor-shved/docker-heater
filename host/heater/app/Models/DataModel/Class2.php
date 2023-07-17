<?php

namespace app\Models\DataModel;

use Illuminate\Support\Arr;

class Class2
{
    protected $user_data ;

    public function __construct(DataUserModel $user_data,$config = [])
    {
        if($this->user_data->getUserId() > 0) {
            $this->user_data = $user_data;
        }
        else {
            $this->user_data = new DataUserModel();
        }



        if(Arr::exists($config,'user_id')) {

        }

    }

    public function getUserName()
    {
        return $this->user_data->getUserName();
    }
}
