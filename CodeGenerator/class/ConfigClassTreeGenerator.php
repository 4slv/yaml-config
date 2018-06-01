<?php

namespace YamlConfig\ClassCodeGenerator;

use Symfony\Component\Yaml\Yaml;
use YamlConfig\StructureCodeGenerator\ConfigStructureTreeGenerator;


/** Генератор структуры классов конфига */
class ConfigClassTreeGenerator extends ConfigStructureTreeGenerator
{

    /** @var string относительный путь расположения yaml-файл с настройками для интерфейса */
    protected $configInterfaceRelativePath;

    /** @var string пространство имён конфига интерфейса */
    protected $configInterfaceNamespace;

    /** @var array дерево конфигурации интерфейсов yaml */
    protected $yamlConfigInterfaceTree;

    /** @var string содержимое конфигурации yaml для интерфейса */
    protected $yamlConfigInterfaceContent;

    /**
     * @return string относительный путь расположения yaml-файл с настройками для интерфейса
     */
    public function getConfigInterfaceRelativePath()
    {
        return $this->configInterfaceRelativePath;
    }

    /**
     * @param string $configInterfaceRelativePath
     * @return $this
     */
    public function setConfigInterfaceRelativePath(string $configInterfaceRelativePath = null)
    {
        $this->configInterfaceRelativePath = $configInterfaceRelativePath;
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
            ->setYamlConfigInterfaceTree($this->getYamlConfigInterfaceTree())
            ->setConfigInterfaceNamespace($this->getConfigInterfaceNamespace());
    }

    /**
     * @return string полный путь к конфигу интерфейса
     */
    public function getConfigInterfaceFullPath()
    {
        if(is_null($this->getConfigInterfaceRelativePath())) {
            return null;
        }
        return $this->getProjectPath().
            DIRECTORY_SEPARATOR.
            $this->getConfigInterfaceRelativePath();
    }

    /**
     * @return string содержимое конфига yaml интерфейса
     */
    protected function getYamlConfigInterfaceContent()
    {
        if(is_null($this->yamlConfigInterfaceContent)){
            $this->yamlConfigInterfaceContent = file_get_contents(
                $this->getConfigInterfaceFullPath()
            );
        }
        return $this->yamlConfigInterfaceContent;
    }

    /**
     * @return array массив дерева конфига интерфейса
     */
    protected function getYamlConfigInterfaceTree()
    {
        if(is_null($this->yamlConfigInterfaceTree)){
            $this->yamlConfigInterfaceTree = Yaml::parse(
                $this->getYamlConfigInterfaceContent()
            );
        }

        return $this->yamlConfigInterfaceTree;
    }

    protected function createConfigStructureGenerator($configStructureInfo)
    {
        $configClassGenerator = new ConfigClassGenerator();
        return $configClassGenerator->setStructureInfo($configStructureInfo);
    }
}
