<?php

namespace YamlConfig\InterfaceCodeGenerator;

use DateTime;

/** Интерфейс узла конфига */
interface InterfaceConfigNode
{
    /**
     * @param DateTime $actualDate фактическая дата
     */
    public function __construct(DateTime $actualDate = null);

    /**
     * @return DateTime фактическая дата
     */
    public function getActualDate();

    /**
     * @return array ассоциативный массив свойств
     */
    public function children();

    /**
     * @return string имя текущего свойства конфига
     */
    public function getConfigName();

    /**
     * @param string $children существование свойства
     * @return bool
     */
    public function issetChildrenByName(string $children);

}