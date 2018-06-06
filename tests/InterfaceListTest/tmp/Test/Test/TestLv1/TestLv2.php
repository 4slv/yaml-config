<?php

namespace Test\Test\TestLv1;

use YamlConfig\ClassCodeGenerator\ClassConfigNode;
use Interfaces\Test\TestLv2 as TestLv2Interface;
use Test\Test\TestLv1\TestLv2\NeedleObject;


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
