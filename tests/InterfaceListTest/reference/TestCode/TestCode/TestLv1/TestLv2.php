<?php

namespace TestCode\TestCode\TestLv1;

use YamlConfig\ClassCodeGenerator\ClassConfigNode;
use \TestLv2 as TestLv2Interface;
use TestCode\TestCode\TestLv1\TestLv2\NeedleObject;
use TestCode\TestCode\TestLv1\TestLv2\NeedleObject2;


class TestLv2 extends ClassConfigNode implements TestLv2Interface
{

    /** @var NeedleObject  */
    protected $needleObject;

    /** @var NeedleObject2  */
    protected $needleObject2;

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

    /** @return NeedleObject2  */
    public function getNeedleObject2()
    {
        if(is_null($this->needleObject2)){
            $this->needleObject2 = new NeedleObject2(
                $this->getActualDate()
            );
        }
        return $this->needleObject2;
    }

}
