<?php

namespace YamlConfig\StructureCodeGenerator;

/** Информация о структуре конфига */
interface ConfigStructureInfoInterface
{
    /**
     * @return string пространство имён структуры конфига
     */
    public function getNamespace();

    /**
     * @param string $namespace пространство имён структуры конфига
     */
    public function setNamespace($namespace);

    /**
     * @return UseStructure[] список подключаемых классов
     */
    public function getUseClasses();

    /**
     * @param UseStructure[] $useStructures список подключаемых структур
     */
    public function setUseClasses($useStructures);

    /**
     * @param UseStructure $useStructure подключаемая структура
     */
    public function addUseClasses(UseStructure $useStructure);

    /**
     * @return string название структуры конфига
     */
    public function getName();

    /**
     * @param string $className название структуры конфига
     */
    public function setName($className);

    /**
     * @return string комментарий структуры конфига
     */
    public function getComment();

    /**
     * @param string $comment комментарий структуры конфига
     */
    public function setComment($comment);

    /**
     * @return StructurePropertyInterface[] список свойств
     */
    public function getPropertyList();

    /**
     * @param StructurePropertyInterface[] $classPropertyList список свойств
     */
    public function setPropertyList($classPropertyList);

    /**
     * @param StructurePropertyInterface $classPropertyList свойство
     */
    public function addPropertyList($classPropertyList);
}