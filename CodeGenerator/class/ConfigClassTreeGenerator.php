<?php

namespace YamlConfig\ClassCodeGenerator;

use YamlConfig\StructureCodeGenerator\ConfigStructureGenerator;
use YamlConfig\StructureCodeGenerator\ConfigStructureInfoInterface;
use YamlConfig\StructureCodeGenerator\ConfigStructureTreeGenerator;
use YamlConfig\YamlFileToTree;


/** Генератор структуры классов конфига */
class ConfigClassTreeGenerator extends ConfigStructureTreeGenerator
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
     * @return YamlFileToTree|null преобразователь конфигурационного файла в конфигурацию для интерфейса
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
     * @return string пространство имён конфига
     */
    protected function getConfigHierarchicalInterfacesNamespace()
    {
        return $this->configHierarchicalInterfacesNamespace;
    }

    /**
     * @param string $configHierarchicalInterfacesNamespace пространство имён конфига для и нтерфейса
     * @return $this
     */
    public function setConfigHierarchicalInterfacesNamespace($configHierarchicalInterfacesNamespace)
    {
        $this->configHierarchicalInterfacesNamespace = $configHierarchicalInterfacesNamespace;
        return $this;
    }

    /**
     * @return string пространство имён конфига
     */
    protected function getConfigDescribedInterfacesNamespace()
    {
        return $this->configDescribedInterfacesNamespace;
    }

    /**
     * @param string $configDescribedInterfacesNamespace пространство имён конфига для и нтерфейса
     * @return $this
     */
    public function setConfigDescribedInterfacesNamespace($configDescribedInterfacesNamespace)
    {
        $this->configDescribedInterfacesNamespace = $configDescribedInterfacesNamespace;
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
            ->setYamlFileToTreeHierarchicalInterfaces($this->getYamlFileToTreeHierarchicalInterfaces())
            ->setYamlFileToTreeDescribedInterfaces($this->getYamlFileToTreeDescribedInterfaces())
            ->setConfigHierarchicalInterfacesNamespace($this->getConfigHierarchicalInterfacesNamespace())
            ->setConfigDescribedInterfacesNamespace($this->getConfigDescribedInterfacesNamespace());
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
