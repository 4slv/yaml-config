<?php

namespace TestCode\TestCode\TestLv1\TestLv2_2;

use YamlConfig\ClassCodeGenerator\ClassConfigNode;
use TestCode\TestCode\TestLv1\TestLv2_2\TestLv3\NeedleObject;


class TestLv3 extends ClassConfigNode 
{

    /** @var NeedleObject  */
    protected $needleObject;

    /** @return NeedleObject  */
    public function getNeedleObject()
    {
        if(is_null($this->needleObject)){
            $this->needleObject = new NeedleObject(
                $this->getActualDate()
            );
        }
        return $this->needleObject;
    }

}
