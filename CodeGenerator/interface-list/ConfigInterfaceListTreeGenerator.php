<?php
/**
 * Created by PhpStorm.
 * User: r.shustrov
 * Date: 6/1/2018
 * Time: 12:59 PM
 */

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