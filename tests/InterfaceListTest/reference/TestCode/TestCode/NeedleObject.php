<?php

namespace TestCode\TestCode;

use YamlConfig\ClassCodeGenerator\ClassConfigNode;
use Interfaces\TestCode\NeedleObject as NeedleObjectInterface;


class NeedleObject extends ClassConfigNode implements NeedleObjectInterface
{

    /** @var integer  */
    protected $param1 = 1;

    /** @var integer  */
    protected $param2 = 2;

    /** @return integer  */
    public function getParam1()
    {
        return $this->getActualProperty('param1');
    }

    /** @return integer  */
    public function getParam2()
    {
        return $this->getActualProperty('param2');
    }

}
