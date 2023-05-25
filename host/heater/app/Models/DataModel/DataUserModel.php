<?php

namespace app\Models\DataModel;

use app\Models\DataModel\Traits\DataUserGetSetTrait;
use app\Models\traits\CoreInter;
use app\Models\traits\InterdTest;
use app\Models\traits\SomeTrait;

class DataUserModel extends CoreInter
{
    use DataUserGetSetTrait;
    use SomeTrait;
    private $user_name;

    private $user_id;


    public function __construct($user_name = "UNK",$user_id = 0)
    {
        $this->setUserName($user_name)->setUserId($user_id);
    }

}
