<?php

namespace YamlConfig\ClassCodeGenerator;

use YamlConfig\StructureCodeGenerator\ConfigStructureInfoInterface;
use YamlConfig\StructureCodeGenerator\StructureInfoList;
use YamlConfig\StructureCodeGenerator\StructureUse;

class ClassInfoList extends StructureInfoList
{

    /** @var array дерево конфигурации интерфейсов yaml */
    protected $yamlConfigInterfaceTree = [];

    /** @var string пространство имён конфига интерфейса */
    protected $configInterfaceNamespace;

    /**
     *
     * @return array
     */
    public function getYamlConfigInterfaceTree()
    {
        return $this->yamlConfigInterfaceTree;
    }

    /**
     * @return ConfigStructureInfoInterface информация о структуре конфига
     */
    protected function createConfigStructureInfo()
    {
        return new ConfigClassInfo();
    }

    /**
     *
     * @param array $yamlConfigInterfaceTree
     * @return $this
     */
    public function setYamlConfigInterfaceTree(array $yamlConfigInterfaceTree = null)
    {
        if(is_null($yamlConfigInterfaceTree))
            $yamlConfigInterfaceTree = [];
        $this->yamlConfigInterfaceTree = $yamlConfigInterfaceTree;
        return $this;
    }

    /**
     * @return string пространство имён конфига Интерфейса
     */
    protected function getConfigInterfaceNamespace()
    {
        return $this->configInterfaceNamespace;
    }

    /**
     * @param string $configInterfaceNamespace
     * @return $this
     */
    public function setConfigInterfaceNamespace($configInterfaceNamespace)
    {
        $this->configInterfaceNamespace = $configInterfaceNamespace;
        return $this;
    }

    /**
     * @param ConfigStructureInfoInterface $configStructureInfo
     * @param array $structureNode узел структуры для $configStructureInfo
     * @param array $path путь в виде масива к узлу
     */
    protected function addedModifiersStructure(ConfigStructureInfoInterface $configStructureInfo, array $structureNode, array $path)
    {
        $this->fillInterfaceList($configStructureInfo, $structureNode, $path);
        $this->fillPropertyList($configStructureInfo, $structureNode, $path);
    }

    /**
     * @param ConfigStructureInfoInterface $configStructureInfo
     * @param array $structureNode узел структуры для $configStructureInfo
     * @param array $path путь в виде масива к узлу
     */
    protected function fillInterfaceList(ConfigStructureInfoInterface $configStructureInfo, array $structureNode, array $path)
    {
        if(
            $this->iStructureNode($structureNode)
            && $this->getInterfaceStructureNodeByNodePath($this->getNodePath($path)) !== false
            ){
            $configStructureInfo->addUseClasses(
                (new StructureUse())
                    ->setStructure(
                        implode(
                            '\\',
                            [
                                $this->getNamespaceByPath($this->getConfigInterfaceNamespace(),$path),
                                $configStructureInfo->getName()
                            ]
                        )
                    )->setAlias($configStructureInfo->getName().'Interface')
            );
            $configStructureInfo->addImplements($configStructureInfo->getName().'Interface');
          //  dump($structureNode, $this->getNodePath($path), $this->getInterfaceStructureNodeByNodePath($this->getNodePath($path)));
        }
    }

    /**
     * @param array $nodePath
     * @return array|bool|mixed
     */
    protected function getInterfaceStructureNodeByNodePath(array $nodePath)
    {
        $out = $this->getYamlConfigInterfaceTree();
        if(empty($out)){
            return false;
        }
        foreach ($nodePath as $currentNodeName){
            if(isset( $out[$currentNodeName])) {
                $out = $out[$currentNodeName];
            }else{
                return false;
            }
        }
        return $out;
    }


    /**
     * @param string $structureName
     * @return string
     */
    protected function addInterfaceSuffix( string $structureName):string
    {
        return $structureName.'Interface';
    }

}