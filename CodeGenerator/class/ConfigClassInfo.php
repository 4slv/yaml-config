<?php

namespace YamlConfig\ClassCodeGenerator;


use YamlConfig\StructureCodeGenerator\ConfigStructureInfo;

class ConfigClassInfo extends ConfigStructureInfo
{

    /**
     * @var string[]
     */
    protected $implements;

    /**
     *
     * @return string[]|null
     */
    public function getImplements()
    {
        return $this->implements;
    }

    /**
     *
     * @param string[] $implements
     * @return $this
     */
    public function setImplements(array $implements = null)
    {
        $this->implements = $implements;
        return $this;
    }

    /**
     *
     * @param string $implements
     * @return $this
     */
    public function addImplements(string $implements)
    {
        $this->implements[] = $implements;
        return $this;
    }


}