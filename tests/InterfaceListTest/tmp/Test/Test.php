<?php

namespace Test;

use YamlConfig\ClassCodeGenerator\ClassConfigNode;
use Test\Test\TestLv1;
use Test\Test\NeedleObject;


class Test extends ClassConfigNode 
{

    /** @var TestLv1  */
    protected $testLv1;

    /** @var NeedleObject  */
    protected $needleObject;

    /** @return TestLv1  */
    public function getTestLv1()
    {
        if(is_null($this->testLv1)){
            $this->testLv1 = new TestLv1(
                $this->getActualDate()
            );
        }
        return $this->testLv1;
    }

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
