<?php

namespace TestCode\TestCode\TestLv1;

use YamlConfig\ClassCodeGenerator\ClassConfigNode;
use Interfaces\TestCode\TestLv2 as TestLv2Interface;
use TestCode\TestCode\TestLv1\TestLv2\NeedleObject;


class TestLv2 extends ClassConfigNode implements TestLv2Interface
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
