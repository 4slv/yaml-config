<?php

namespace YamlConfig\InterfaceListCodeGenerator;


use YamlConfig\StructureCodeGenerator\ConfigStructureInfo;
use YamlConfig\StructureCodeGenerator\ConfigStructureInfoInterface;
use YamlConfig\StructureCodeGenerator\StructureInfoListInterface;
use YamlConfig\StructureCodeGenerator\StructureProperty;

class IntrefaceListInfoList implements  StructureInfoListInterface
{

    /** @var string полный путь к конфигу  */
    protected $configFullPath;

    /** @var string пространство имён конфига */
    protected $configNamespace;

    /** @var ConfigStructureInfoInterface[] пространство имён конфига */
    protected $structureInfoList = [];

    /**
     * @return ConfigStructureInfoInterface информация о структуре конфига
     */
    protected function createConfigStructureInfo()
    {
        return new ConfigStructureInfo();
    }

    /**
     * @return StructureProperty свойство структуры
     */
    protected function createStructureProperty()
    {
        return new StructureProperty();
    }

    /**
     * @return string
     */
    public function getConfigFullPath()
    {
        return $this->configFullPath;
    }

    /**
     * @param string $configFullPath
     * @return $this
     */
    public function setConfigFullPath($configFullPath)
    {
        $this->configFullPath = $configFullPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfigNamespace()
    {
        return $this->configNamespace;
    }

    /**
     * @param string $configNamespace
     * @return $this
     */
    public function setConfigNamespace($configNamespace)
    {
        $this->configNamespace = $configNamespace;
        return $this;
    }


    /**
     * @return ConfigStructureInfoInterface[]
     */
    public function getStructureInfoList()
    {
        return $this->structureInfoList;
    }

    /**
     * @param ConfigStructureInfoInterface[] $structureInfoList список информации о структуре конфига
     *  @return $this
     */
    protected function setStructureInfoList($structureInfoList)
    {
        $this->structureInfoList = $structureInfoList;
        return $this;
    }

    /**
     * @param ConfigStructureInfoInterface $configStructureInfo
     * @return $this
     */
    protected function addStructureInfo(ConfigStructureInfoInterface $configStructureInfo)
    {
        $this->structureInfoList[] = $configStructureInfo;
        return $this;
    }



    public function initFromTree($tree, $path = [])
    {
        foreach ($tree as $interfaceName => $interfaceData) {
            $configStructureInfo = $this->initConfigStructureInfo($interfaceName,$interfaceData['property']);
            $this->addStructureInfo($configStructureInfo);
        }
    }

    /**
     * @param string $interfaceName
     * @param array $ptoperties
     * @return ConfigStructureInfoInterface
     */
    protected function initConfigStructureInfo(string $interfaceName,array $ptoperties)
    {
        $configStructureInfo = $this->createConfigStructureInfo();
        $configStructureInfo->setName($interfaceName);
        $configStructureInfo->setNamespace($this->getConfigNamespace());
        foreach($ptoperties as $ptopertyName => $ptopertyInfo){
            $property = $this->createStructureProperty();
            $property->setName($ptopertyName);
            $property->setType($ptopertyInfo['type']);
            $configStructureInfo->addPropertyList($property);
        }
        return $configStructureInfo;
    }
}