<?php

namespace YamlConfig\ClassCodeGenerator;

use YamlConfig\StructureCodeGenerator\ConfigStructureGenerator;
use YamlConfig\StructureCodeGenerator\ConfigStructureInfoInterface;
use YamlConfig\StructureCodeGenerator\ConfigStructureTreeGenerator;
use YamlConfig\YamlFileToTree;


/** Генератор структуры классов конфига */
class ConfigClassTreeGenerator extends ConfigStructureTreeGenerator
{

    /** @var YamlFileToTree преобразователь конфигурационного файла в конфигурацию для интерфейса */
    protected $yamlFileToTreeInterface;

    /** @var string пространство имён конфига интерфейса */
    protected $configInterfaceNamespace;

    /** @var YamlFileToTree преобразователь конфигурационного файла в конфигурацию для интерфейса(список) */
    protected $yamlFileToTreeInterfaceList;

    /**
     * @return YamlFileToTree|null преобразователь конфигурационного файла в конфигурацию для интерфейса
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
     * @return YamlFileToTree|null
     */
    public function getYamlFileToTreeInterfaceList(): ?YamlFileToTree
    {
        return $this->yamlFileToTreeInterfaceList;
    }

    /**
     * @param YamlFileToTree|null $yamlFileToTreeInterfaceList
     * @return $this
     */
    public function setYamlFileToTreeInterfaceList(YamlFileToTree $yamlFileToTreeInterfaceList = null)
    {
        $this->yamlFileToTreeInterfaceList = $yamlFileToTreeInterfaceList;
        return $this;
    }

    /**
     * @return string пространство имён конфига
     */
    protected function getConfigInterfaceNamespace()
    {
        return $this->configInterfaceNamespace;
    }

    /**
     * @param string $configInterfaceNamespace пространство имён конфига для и нтерфейса
     * @return $this
     */
    public function setConfigInterfaceNamespace($configInterfaceNamespace)
    {
        $this->configInterfaceNamespace = $configInterfaceNamespace;
        return $this;
    }

    /**
     * @return ClassInfoList список информации о классах
     */
    protected function createClassInfoList()
    {
        return new ClassInfoList();
    }

    /**
     * @return ClassInfoList список информации о классах
     */
    protected function createStructureInfoList()
    {
        return $this->createClassInfoList()
            ->setYamlFileToTreeInterface($this->getYamlFileToTreeInterface())
            ->setYamlFileToTreeInterfaceList($this->getYamlFileToTreeInterfaceList())
            ->setConfigInterfaceNamespace($this->getConfigInterfaceNamespace());
    }

    /**
     * @param ConfigStructureInfoInterface $configStructureInfo информация о структуре конфига
     * @return ConfigStructureGenerator генератор структуры конфига
     */
    protected function createConfigStructureGenerator($configStructureInfo)
    {
        $configClassGenerator = new ConfigClassGenerator();
        return $configClassGenerator->setStructureInfo($configStructureInfo);
    }
}
