<?php

namespace TestCode\TestCode\TestLv1;

use YamlConfig\ClassCodeGenerator\ClassConfigNode;
use TestCode\TestCode\TestLv1\TestLv2_2\TestLv3;


class TestLv2_2 extends ClassConfigNode 
{

    /** @var TestLv3  */
    protected $testLv3;

    /** @return TestLv3  */
    public function getTestLv3()
    {
        if(is_null($this->testLv3)){
            $this->testLv3 = new TestLv3(
                $this->getActualDate()
            );
        }
        return $this->testLv3;
    }

}
