<?php

namespace app\Models\DataModel\Traits;

use app\Models\DataModel\DataUserModel;

trait DataUserGetSetTrait
{

    public function toJson()
    {
        $res = [];
        if($this->getUserId() > 0) {
            $res['u_id'] = $this->getUserId();
        }

        return $res;
    }

    /**
     * @return mixed
     */
    public function getUserName()
    {
        return $this->user_name;
    }

    /**
     * @param mixed $user_name
     * @return DataUserModel
     */
    public function setUserName($user_name)
    {
        $this->user_name = $user_name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     * @return DataUserModel
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
        return $this;
    }


    public function som_func()
    {
        // TODO: Implement som_func() method.
    }
}
