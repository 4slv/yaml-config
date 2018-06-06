<?php

namespace Interfaces\Test;

use YamlConfig\InterfaceCodeGenerator\InterfaceConfigNode;
use needleObject;


interface TestLv2 extends InterfaceConfigNode {

    /** @return needleObject  */
    public function getNeedleObject();

}
