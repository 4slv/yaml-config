<?php

namespace YamlConfig\ClassCodeGenerator;

use YamlConfig\StructureCodeGenerator\ConfigStructureInfoInterface;
use YamlConfig\StructureCodeGenerator\StructureInfoList;
use YamlConfig\StructureCodeGenerator\UseStructure;
use YamlConfig\YamlFileToTree;

/** Список информации о классах */
class ClassInfoList extends StructureInfoList
{


    /** @var string пространство имён интерфейса узла конфига */
    protected $configInterfaceNamespace;

    /** @var YamlFileToTree относительный путь расположения yaml-файл с настройками для интерфейса */
    protected $yamlFileToTreeInterface;

    /**
     * @return YamlFileToTree|null
     */
    public function getYamlFileToTreeInterface(): ?YamlFileToTree
    {
        return $this->yamlFileToTreeInterface;
    }

    /**
     * @param YamlFileToTree|null $yamlFileToTreeInterface
     * @return $this
     */
    public function setYamlFileToTreeInterface(YamlFileToTree $yamlFileToTreeInterface = null)
    {
        $this->yamlFileToTreeInterface = $yamlFileToTreeInterface;
        return $this;
    }


    /**
     * @return ConfigStructureInfoInterface информация о структуре конфига
     */
    protected function createConfigStructureInfo()
    {
        return new ConfigClassInfo();
    }

    /**
     * @return UseStructure подключаемая структура
     */
    protected function createUseStructure()
    {
        return new UseStructure();
    }


    /**
     * @return string пространство имён интерфейса узла конфига
     */
    protected function getConfigInterfaceNamespace()
    {
        return $this->configInterfaceNamespace;
    }

    /**
     * @param string $configInterfaceNamespace пространство имён интерфейса узла конфига
     * @return $this
     */
    public function setConfigInterfaceNamespace($configInterfaceNamespace)
    {
        $this->configInterfaceNamespace = $configInterfaceNamespace;
        return $this;
    }

    /**
     * @param ConfigClassInfo $configStructureInfo
     * @param array $structureNode узел структуры для $configStructureInfo
     * @param array $path путь в виде масива к узлу
     */
    protected function addedModifiersStructure($configStructureInfo, array $structureNode, array $path)
    {
        $this->fillInterfaceList($configStructureInfo, $structureNode, $path);
        parent::addedModifiersStructure($configStructureInfo, $structureNode, $path);
    }

    /**
     * @param ConfigClassInfo $configClassInfo
     * @param array $structureNode узел структуры для $configStructureInfo
     * @param array $path путь в виде масива к узлу
     */
    protected function fillInterfaceList(ConfigClassInfo $configClassInfo, array $structureNode, array $path)
    {
        if(
            $this->getYamlFileToTreeInterface()
            &&
            $this->iStructureNode($structureNode)
            &&
            $this->getInterfaceStructureNodeByNodePath($this->getNodePath($path)) !== null
        ){
            dump($this->getInterfaceStructureNodeByNodePath($this->getNodePath($path)),$structureNode,$this->getConfigInterfaceNamespace());
            $interfaceNamespace = $this->getNamespaceByPath($this->getConfigInterfaceNamespace(), $path);
            $interfaceFullName = $interfaceNamespace. '\\'. $configClassInfo->getName();
            $interfaceAlias = $this->generateInterfaceAlias($configClassInfo->getName());

            $useStructure = $this
                ->createUseStructure()
                ->setStructureFullName($interfaceFullName)
                ->setAlias($interfaceAlias);

            $configClassInfo->addUseClasses($useStructure);
            $configClassInfo->addImplements($interfaceAlias);
        }
    }

    /**
     * @param array $nodePath список частей пути к узлу
     * @return array дерево конфигурации интерфейсов
     */
    protected function getInterfaceStructureNodeByNodePath(array $nodePath)
    {
        $out = $this->getYamlFileToTreeInterface()->getYamlConfigTree();
        if(empty($out)){
            return null;
        }
        foreach ($nodePath as $currentNodeName){
            if(isset( $out[$currentNodeName])) {
                $out = $out[$currentNodeName];
            }else{
                return null;
            }
        }
        return $out;
    }

    /**
     * @param string $interfaceName название интерфейса
     * @return string название псевдонима интерфейса
     */
    protected function generateInterfaceAlias(string $interfaceName):string
    {
        return $interfaceName.'Interface';
    }

}