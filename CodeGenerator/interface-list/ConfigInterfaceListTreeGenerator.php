<?php

namespace YamlConfig\InterfaceListCodeGenerator;


use YamlConfig\InterfaceCodeGenerator\ConfigInterfaceGenerator;
use YamlConfig\StructureCodeGenerator\ConfigStructureTreeGenerator;

class ConfigInterfaceListTreeGenerator extends ConfigStructureTreeGenerator
{

    protected function createStructureInfoList()
    {
        return new IntrefaceListInfoList();
    }

    protected function createConfigStructureGenerator($configStructureInfo)
    {
        $configClassGenerator = new ConfigInterfaceGenerator();
        return $configClassGenerator->setStructureInfo($configStructureInfo);
    }

    /**
     * @param array &$tree дерево конфига
     */
    protected function addedModifiersForTree(&$tree){ }

}