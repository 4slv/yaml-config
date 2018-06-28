<?php

namespace YamlConfig\StructureCodeGenerator;

/** Информация о структуре конфига */
class ConfigStructureInfo implements ConfigStructureInfoInterface
{
    /** @var string пространство имён структуры конфига */
    protected $namespace;

    /** @var UseStructure[] список подключаемых классов */
    protected $useClasses = [];

    /** @var string название структуры конфига */
    protected $className;

    /** @var string комментарий структуры конфига */
    protected $comment;

    /** @var StructurePropertyInterface[] список свойств */
    protected $classPropertyList = [];

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    public function getUseClasses()
    {
        return $this->useClasses;
    }

    public function setUseClasses($useClasses)
    {
        $this->useClasses = $useClasses;
    }

    public function getName()
    {
        return $this->className;
    }

    public function setName($className)
    {
        $this->className = $className;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    public function getPropertyList()
    {
        return $this->classPropertyList;
    }

    public function setPropertyList($classPropertyList)
    {
        $this->classPropertyList = $classPropertyList;
    }

    public function addUseClasses(UseStructure $useStructure)
    {
        if(!in_array($useStructure,$this->useClasses)) {
            $this->useClasses[] = $useStructure;
        }
    }

    public function addPropertyList($classPropertyList)
    {
        $this->classPropertyList[] = $classPropertyList;
    }
}