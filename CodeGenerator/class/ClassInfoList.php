<?php

namespace YamlConfig\ClassCodeGenerator;

use YamlConfig\StructureCodeGenerator\ConfigStructureInfoInterface;
use YamlConfig\StructureCodeGenerator\StructureInfoList;
use YamlConfig\StructureCodeGenerator\UseStructure;
use YamlConfig\YamlFileToTree;

/** Список информации о классах */
class ClassInfoList extends StructureInfoList
{
    /** @var string пространство имён иерархических интерфейсов для узлов конфига */
    protected $configHierarchicalInterfacesNamespace;

    /** @var string пространство имён описанных интерфейсов для узлов конфига */
    protected $configDescribedInterfacesNamespace;

    /** @var YamlFileToTree преобразователь конфигурационного файла в конфигурацию для иерархических интерфейсов */
    protected $yamlFileToTreeHierarchicalInterfaces;

    /** @var YamlFileToTree преобразователь конфигурационного файла в конфигурацию для описанных интерфейсов */
    protected $yamlFileToTreeDescribedInterfaces;


    /**
     * @return YamlFileToTree|null
     */
    public function getYamlFileToTreeDescribedInterfaces(): ?YamlFileToTree
    {
        return $this->yamlFileToTreeDescribedInterfaces;
    }

    /**
     * @param YamlFileToTree|null $yamlFileToTreeDescribedInterfaces
     * @return $this
     */
    public function setYamlFileToTreeDescribedInterfaces(YamlFileToTree $yamlFileToTreeDescribedInterfaces = null)
    {
        $this->yamlFileToTreeDescribedInterfaces = $yamlFileToTreeDescribedInterfaces;
        return $this;
    }

    /**
     * @return YamlFileToTree|null
     */
    public function getYamlFileToTreeHierarchicalInterfaces(): ?YamlFileToTree
    {
        return $this->yamlFileToTreeHierarchicalInterfaces;
    }

    /**
     * @param YamlFileToTree|null $yamlFileToTreeHierarchicalInterfaces
     * @return $this
     */
    public function setYamlFileToTreeHierarchicalInterfaces(YamlFileToTree $yamlFileToTreeHierarchicalInterfaces = null)
    {
        $this->yamlFileToTreeHierarchicalInterfaces = $yamlFileToTreeHierarchicalInterfaces;
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
    protected function getConfigHierarchicalInterfacesNamespace()
    {
        return $this->configHierarchicalInterfacesNamespace;
    }

    /**
     * @param string $configHierarchicalInterfacesNamespace пространство имён интерфейса узла конфига
     * @return $this
     */
    public function setConfigHierarchicalInterfacesNamespace($configHierarchicalInterfacesNamespace)
    {
        $this->configHierarchicalInterfacesNamespace = $configHierarchicalInterfacesNamespace;
        return $this;
    }

    /**
     * @return string пространство имён интерфейса узла конфига
     */
    protected function getConfigDescribedInterfacesNamespace()
    {
        return $this->configDescribedInterfacesNamespace;
    }

    /**
     * @param string $configDescribedInterfacesNamespace пространство имён интерфейса узла конфига
     * @return $this
     */
    public function setConfigDescribedInterfacesNamespace($configDescribedInterfacesNamespace)
    {
        $this->configDescribedInterfacesNamespace = $configDescribedInterfacesNamespace;
        return $this;
    }

    /**
     * @param ConfigClassInfo $configStructureInfo
     * @param array $structureNode узел структуры для $configStructureInfo
     * @param array $path путь в виде масива к узлу
     */
    protected function addedModifiersStructure($configStructureInfo, array $structureNode, array $path)
    {
        $this->fillInterface($configStructureInfo, $structureNode, $path);
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
            $this->getYamlFileToTreeDescribedInterfaces()
        ) {
            foreach($this->getInterfaceListNameByPath($path) as $interfaceName) {
                $interfaceFullName = $this->getConfigDescribedInterfacesNamespace() . '\\' . $interfaceName;
                $interfaceAlias = $this->generateInterfaceAlias($interfaceName);
                $useStructure = $this
                    ->createUseStructure()
                    ->setStructureFullName($interfaceFullName)
                    ->setAlias($interfaceAlias);
                $configClassInfo->addUseClasses($useStructure);
                $configClassInfo->addImplements($interfaceAlias);
            }
        }
    }

    /**
     * Масив имен интерфейсов
     * @param string[] $path
     * @return string[]
     */
    protected function getInterfaceListNameByPath(array $path)
    {
        $out = [];
        foreach($this->getYamlFileToTreeDescribedInterfaces()->getYamlConfigTree() as $interfaceName => $interfaceConfig){
            if($this->inArrayXpath($path,$interfaceConfig['xpath'])){
                $out[] = $this->fixStructureName($interfaceName);
            }
        }
        return $out;
    }

    /**
     * @param array $path
     * @param array $xpathList
     * @return bool
     */
    protected function inArrayXpath(array $path, array $xpathList)
    {
        foreach($xpathList as $xpath){
            $domNodeList = $this->getYamlFileToTree()->getDOMNodeListByXpath($xpath);
            if($domNodeList == false){
                return false;
            }
            foreach($domNodeList as $domElement){
                $pathDomElement = explode('/',ltrim($domElement->getNodePath(),'/'));
                if($this->getNodePath($pathDomElement) == $this->getNodePath($path)){
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param ConfigClassInfo $configClassInfo
     * @param array $structureNode узел структуры для $configStructureInfo
     * @param array $path путь в виде масива к узлу
     */
    protected function fillInterface(ConfigClassInfo $configClassInfo, array $structureNode, array $path)
    {
        if(
            $this->getYamlFileToTreeHierarchicalInterfaces()
            &&
            $this->iStructureNode($structureNode)
            &&
            $this->getInterfaceStructureNodeByNodePath($this->getNodePath($path)) !== null
        ){
            $interfaceNamespace = $this->getNamespaceByPath($this->getConfigHierarchicalInterfacesNamespace(), $path);
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
        $out = $this->getYamlFileToTreeHierarchicalInterfaces()->getYamlConfigTree();
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