<?php

namespace YamlConfig\StructureCodeGenerator;

/** Подключаемая структура */
class UseStructure
{

    /**
     * @var string полное название подключаемой структуры (с неймспейсом)
     */
    protected $structureFullName;

    /**
     * @var string псевдоним структуры
     */
    protected $alias;

    /**
     * @return string полное название подключаемой структуры (с неймспейсом)
     */
    public function getStructureFullName()
    {
        return $this->structureFullName;
    }

    /**
     * @param string $structureFullName полное название подключаемой структуры (с неймспейсом)
     * @return $this
     */
    public function setStructureFullName(string $structureFullName = null)
    {
        $this->structureFullName = $structureFullName;
        return $this;
    }

    /**
     * @return string псевдоним структуры
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param string $alias псевдоним структуры
     * @return $this
     */
    public function setAlias(string $alias = null)
    {
        $this->alias = $alias;
        return $this;
    }


}