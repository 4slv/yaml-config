<?php

namespace YamlConfig\StructureCodeGenerator;

use Slov\Helper\ArrayHelper;
use YamlConfig\YamlFileToTree;

/** Список информации о структурах */
class StructureInfoList implements StructureInfoListInterface
{
    /** @var ConfigStructureInfoInterface[] список информации о структуре конфига */
    protected $structureInfoList;

    /** @var string пространство имён конфига */
    protected $configNamespace;

    /** @var YamlFileToTree преобразователь конфигурационного файла в конфигурацию */
    protected $yamlFileToTree;

    /**
     * @return YamlFileToTree|null преобразователь конфигурационного файла в конфигурацию
     */
    public function getYamlFileToTree(): ?YamlFileToTree
    {
        return $this->yamlFileToTree;
    }

    /**
     * @param YamlFileToTree $yamlFileToTree преобразователь конфигурационного файла в конфигурацию
     * @return $this
     */
    public function setYamlFileToTree(YamlFileToTree $yamlFileToTree)
    {
        $this->yamlFileToTree = $yamlFileToTree;
        return $this;
    }


    public function getStructureInfoList()
    {
        return $this->structureInfoList;
    }

    /**
     * @param ConfigStructureInfoInterface[] $structureInfoList список информации о структуре конфига
     */
    protected function setStructureInfoList($structureInfoList)
    {
        $this->structureInfoList = $structureInfoList;
    }

    /**
     * @return string пространство имён конфига
     */
    protected function getConfigNamespace()
    {
        return $this->configNamespace;
    }

    public function setConfigNamespace($configNamespace)
    {
        $this->configNamespace = $configNamespace;
    }

    /**
     * @return StructureProperty свойство структуры
     */
    protected function createStructureProperty()
    {
        return new StructureProperty();
    }

    /**
     * @return ConfigStructureInfoInterface информация о структуре конфига
     */
    protected function createConfigStructureInfo()
    {
        return new ConfigStructureInfo();
    }

    /**
     * @return UseStructure
     */
    protected function createUseStructure()
    {
        return new UseStructure();
    }


    /**
     * @param ConfigStructureInfoInterface $configStructureInfo добавление информации о структуре в список
     */
    protected function addStructureInfo(ConfigStructureInfoInterface $configStructureInfo)
    {
        $this->structureInfoList[] = $configStructureInfo;
    }

    public function initFromTree($tree, $path = [])
    {
        $this->setStructureInfoList([]);
        foreach ($tree as $nodeName => $node){
            if($this->iStructureNode($node)){
                $classInfo = $this->getConfigStructureInfo(
                    $node, [$nodeName], ucfirst($nodeName)
                );
                $this->addStructureInfo($classInfo);
            }
        }
        return $this->structureInfoList;
    }

    /** Проверка является ли узел структурой
     * @param mixed $node узел дерева конфига
     * @return boolean true - является */
    protected function iStructureNode($node)
    {
        return
            is_array($node)
            &&
            ArrayHelper::isList($node) === false
            &&
            ArrayHelper::isDateList($node) === false;
    }

    /**
     * @param array $structureNode узел структуры
     * @param string[] $path путь к структуре
     * @param string $structureName имя структуры
     * @return ConfigStructureInfoInterface информация о структуре
     */
    protected function getConfigStructureInfo($structureNode, $path, $structureName)
    {
        $configStructureInfo = $this->createConfigStructureInfo();
        $namespace = $this->getNamespaceByPath($this->getConfigNamespace(),$path);
        $nodePath = $this->getNodePath($path);
        $configStructureInfo->setNamespace($namespace);
        $configStructureInfo->setComment(
            $this->getCommentByPath($nodePath)
        );
        $configStructureInfo->setName($structureName);
        $this->addedModifiersStructure($configStructureInfo, $structureNode, $path);
        return $configStructureInfo;
    }

    /**
     * @param ConfigStructureInfoInterface $configStructureInfo
     * @param array $structureNode узел структуры для $configStructureInfo
     * @param array $path путь в виде масива к узлу
     */
    protected function addedModifiersStructure($configStructureInfo, array $structureNode, array $path)
    {
        $this->fillPropertyList($configStructureInfo, $structureNode, $path);
    }

    /**
     * @param ConfigStructureInfoInterface $configStructureInfo
     * @param array $structureNode узел структуры для $configStructureInfo
     * @param array $path путь в виде масива к узлу
     */
    protected function fillPropertyList(ConfigStructureInfoInterface $configStructureInfo, array $structureNode, array $path)
    {
        $nodePath = $this->getNodePath($path);
        foreach ($structureNode as $nodeName  => $nodeValue) {
            $structureProperty = $this->getStructureProperty($nodePath, $nodeName, $nodeValue);
            if ($structureProperty->isStructure()) {
                $subStructureName = $this->fixStructureName($nodeName);
                $configStructureInfo->addUseClasses($this->createUseStructure()->setStructureFullName(
                    implode(
                        '\\',
                        [$configStructureInfo->getNamespace(), $configStructureInfo->getName(), $subStructureName]
                    )
                ));
                $structureInfo = $this->getConfigStructureInfo(
                    $nodeValue,
                    array_merge($path, [$nodeName]),
                    $subStructureName
                );
                $this->addStructureInfo($structureInfo);
            }
            $configStructureInfo->addPropertyList($structureProperty);
        }
    }

    /**
     * @param string $configNamespace
     * @param array $path
     * @return string
     */
    protected function getNamespaceByPath(string $configNamespace, array $path):string
    {
        $namespacePath = array_slice($path, 0, -1);
        foreach ($namespacePath as $pathPart){
            $configNamespace .= '\\'. $this->fixStructureName($pathPart);
        }
        return $configNamespace;
    }

    /**
     * @param array $path
     * @return array
     */
    protected function getNodePath(array $path):array
    {
        return array_slice($path, 1);
    }

    /** Исправить имя структуры
     * @param string $structureName имя структуры
     * @return string исправленное имя структуры */
    protected function fixStructureName($structureName)
    {
        return $this->fixPropertyName(ucfirst($structureName));
    }

    /** Исправить имя свойства
     * @param string $propertyName имя свойства
     * @return string исправленное имя свойства */
    protected function fixPropertyName($propertyName)
    {
        return preg_match('/^\d/', $propertyName)
            ? '_'. $propertyName
            : $propertyName;
    }

    /**
     * @param string[] $classPath путь к структуре
     * @param string $propertyName имя свойства
     * @param mixed $propertyValue значение свойства
     * @return StructurePropertyInterface
     */
    protected function getStructureProperty($classPath, $propertyName, $propertyValue)
    {
        $classProperty = $this->createStructureProperty();
        $classProperty->setName($this->fixPropertyName($propertyName));
        if ($this->iStructureNode($propertyValue)) {
            $subClassName = $this->fixStructureName($propertyName);
            $classProperty->setType($subClassName);
            $classProperty->setIsStructure(true);
        } else {
            $classProperty->setValue($propertyValue);
            $classProperty->setType(gettype($propertyValue));
            $classProperty->setIsStructure(false);
        }
        $classProperty->setComment(
            $this->getCommentByPath(array_merge($classPath, [$propertyName]))
        );
        return $classProperty;
    }

    /**
     * @param array $pathPartList список частей пути к узлу дерева
     * @param string $pathSeparator
     * @return string текст комментария
     */
    protected function getCommentByPath(array $pathPartList, $pathSeparator = '.')
    {
        $path = implode($pathSeparator, $pathPartList);
        $configNodeComments = $this->getYamlFileToTree()->getConfigNodeComments();
        if(array_key_exists($path, $configNodeComments)){
            return $configNodeComments[$path];
        }
    }

}
