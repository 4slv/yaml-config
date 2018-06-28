<?php

namespace Interfaces\TestCode;

use YamlConfig\InterfaceCodeGenerator\InterfaceConfigNode;
use needleObject;


interface TestLv2 extends InterfaceConfigNode {

    /** @return needleObject  */
    public function getNeedleObject();

    /** @return needleObject  */
    public function getNeedleObject2();

}
