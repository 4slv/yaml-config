<?php

namespace YamlConfig\InterfaceListCodeGenerator;


use YamlConfig\StructureCodeGenerator\ConfigStructureInfo;
use YamlConfig\StructureCodeGenerator\ConfigStructureInfoInterface;
use YamlConfig\StructureCodeGenerator\StructureInfoList;
use YamlConfig\StructureCodeGenerator\UseStructure;

class IntrefaceListInfoList extends  StructureInfoList
{
    /**
     * @var array Примитивные типы языка (использование по коду для отсутсвия описания в  use)
     */
    protected static $primitiveTypes = [

        'mixed',
        '[]',
        'array',
        'string',
        'int',
        'integer',
        'float',
        'bool',
        'boolean',
        'null'
    ];


    /**
     * @param ConfigStructureInfoInterface $configStructureInfo
     * @param array $structureNode узел структуры для $configStructureInfo
     * @param array $path путь в виде масива к узлу
     */
    protected function fillPropertyList(ConfigStructureInfoInterface $configStructureInfo, array $structureNode, array $path)
    {
        foreach ($structureNode['property'] as $nodeName => $nodeValue) {
            $property = $this->createStructureProperty();
            $property->setName($nodeName);
            $property->setComment(
                $this->getCommentByPath($path)
            );
            if(!isset($nodeValue['type'])){
                $configStructureInfo->addPropertyList($property);
                return;
            }
            if($this->isPrimitiveTypes($nodeValue['type'])) {
                $property->setType($nodeValue['type']);
            }else{
                $classPath = explode('\\',$nodeValue['type']);
                $useClass = $this->createUseStructure();
                $useClass->setStructureFullName(trim($nodeValue['type'],'\\'));
                $configStructureInfo->addUseClasses($useClass);
                $property->setIsStructure(true);
                $property->setType(end($classPath));
            }
            $configStructureInfo->addPropertyList($property);
        }
    }

    /**
     * @param string $typeName
     * @return bool
     */
    protected function isPrimitiveTypes(string $typeName): bool
    {
        return in_array($typeName,static::$primitiveTypes);
    }


    /**
     * @param array $path
     * @return array
     */
    protected function getNodePath(array $path):array
    {
        return $path;
    }


}