<?php

namespace YamlConfig\InterfaceCodeGenerator;

use YamlConfig\StructureCodeGenerator\ConfigStructureTreeGenerator;
use YamlConfig\StructureCodeGenerator\StructureInfoList;
use YamlConfig\StructureCodeGenerator\StructureInfoListInterface;


/** Генератор структуры интерфейсов конфига */
class ConfigInterfaceTreeGenerator extends ConfigStructureTreeGenerator
{

    protected function createConfigStructureGenerator($configStructureInfo)
    {
        $configClassGenerator = new ConfigInterfaceGenerator();
        return $configClassGenerator->setStructureInfo($configStructureInfo);
    }

    /**
     * @return StructureInfoListInterface список информации об интерфейсах
     */
    protected function createStructureInfoList()
    {
        return new StructureInfoList();
    }
}
