<?php

namespace YamlConfig\ClassCodeGenerator;


use YamlConfig\StructureCodeGenerator\ConfigStructureInfo;

/** Информация о классе конфига */
class ConfigClassInfo extends ConfigStructureInfo
{

    /**
     * @var string[] список названий реализуемых интерфейсов
     */
    protected $implements;

    /**
     *
     * @return string[] список названий реализуемых интерфейсов
     */
    public function getImplements()
    {
        return $this->implements;
    }

    /**
     *
     * @param string[] $implements список названий реализуемых интерфейсов
     * @return $this
     */
    public function setImplements(array $implements = null)
    {
        $this->implements = $implements;
        return $this;
    }

    /**
     *
     * @param string $implements название реализуемого интерфейс
     * @return $this
     */
    public function addImplements(string $implements)
    {
        $this->implements[] = $implements;
        return $this;
    }


}