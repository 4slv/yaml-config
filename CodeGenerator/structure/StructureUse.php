<?php
/**
 * Created by PhpStorm.
 * User: r.shustrov
 * Date: 5/30/2018
 * Time: 4:43 PM
 */

namespace YamlConfig\StructureCodeGenerator;


class StructureUse
{

    /**
     * @var string
     */
    protected $structure;

    /**
     * @var string
     */
    protected $alias;

    /**
     *
     * @return string|null
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     *
     * @param string $structure
     * @return $this
     */
    public function setStructure(string $structure = null): self
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     *
     * @param string $alias
     * @return $this
     */
    public function setAlias(string $alias = null): self
    {
        $this->alias = $alias;
        return $this;
    }


}