<?php

namespace Test\Test;

use YamlConfig\ClassCodeGenerator\ClassConfigNode;
use Test\Test\TestLv1\TestLv2;
use Test\Test\TestLv1\TestLv2_2;


class TestLv1 extends ClassConfigNode 
{

    /** @var TestLv2  */
    protected $testLv2;

    /** @var TestLv2_2  */
    protected $testLv2_2;

    /** @return TestLv2  */
    public function getTestLv2()
    {
        if(is_null($this->testLv2)){
            $this->testLv2 = new TestLv2(
                $this->getActualDate()
            );
        }
        return $this->testLv2;
    }

    /** @return TestLv2_2  */
    public function getTestLv2_2()
    {
        if(is_null($this->testLv2_2)){
            $this->testLv2_2 = new TestLv2_2(
                $this->getActualDate()
            );
        }
        return $this->testLv2_2;
    }

}
