<?php

namespace YamlConfig\ClassCodeGenerator;

use Symfony\Component\Yaml\Yaml;
use YamlConfig\StructureCodeGenerator\ConfigStructureTreeGenerator;
use YamlConfig\YamlFileToTree;


/** Генератор структуры классов конфига */
class ConfigClassTreeGenerator extends ConfigStructureTreeGenerator
{

    /** @var YamlFileToTree относительный путь расположения yaml-файл с настройками для интерфейса */
    protected $yamlFileToTreeInterface;

    /** @var string пространство имён конфига интерфейса */
    protected $configInterfaceNamespace;

    /** @var YamlFileToTree относительный путь расположения yaml-файл с настройками для интерфейса(список) */
    protected $yamlFileToTreeInterfaceList;

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


    protected function createConfigStructureGenerator($configStructureInfo)
    {
        $configClassGenerator = new ConfigClassGenerator();
        return $configClassGenerator->setStructureInfo($configStructureInfo);
    }
}
