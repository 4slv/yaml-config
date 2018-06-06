<?php

namespace Interfaces\Test;

use YamlConfig\InterfaceCodeGenerator\InterfaceConfigNode;


interface NeedleObject extends InterfaceConfigNode {

    /** @return int  */
    public function getParam1();

    public function getParam2();

}
