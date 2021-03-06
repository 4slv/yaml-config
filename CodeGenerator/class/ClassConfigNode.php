<?php

namespace YamlConfig\ClassCodeGenerator;

use DateTime;
use Slov\Helper\ArrayHelper;
use ReflectionClass;

/** Класс узла конфига */
abstract class ClassConfigNode
{
    /** @var DateTime фактическая дата */
    protected $actualDate;

    /**
     * @param DateTime $actualDate фактическая дата
     */
    public function __construct(DateTime $actualDate = null)
    {
        $this->actualDate = isset($actualDate)
            ? clone $actualDate
            : new DateTime();
        $this->actualDate->setTime(0,0);
    }

    /**
     * @return DateTime фактическая дата
     */
    public function getActualDate()
    {
        return $this->actualDate;
    }

    /**
     * @param string $propertyName название свойства
     * @return mixed актуальное значение свойства
     */
    protected function getActualProperty($propertyName)
    {
        $propertyValue = $this->$propertyName;
        if(is_array($propertyValue) && ArrayHelper::isDateList($propertyValue)){
            $historyProperty = array_slice($propertyValue, 0);
            krsort($historyProperty);
            foreach($historyProperty as $dateString => $value){
                $date = DateTime::createFromFormat(
                    'Y-m-d', $dateString, $this->actualDate->getTimezone()
                );
                $date->setTime(0,0);
                if($date <= $this->actualDate){
                    return $value;
                }
            }
        } else {
            return $propertyValue;
        }
    }
    
    /**
     * @return array ассоциативный массив свойств
     */
    public function children()
    {
        $properties = get_object_vars($this);
        $children = [];
        foreach($properties as $propertyName => $propertyValue)
        {
            if($propertyName !== 'actualDate'){
                $childrenName = ltrim($propertyName, '_');
                $getChildrenFunction = 'get'. ucfirst($childrenName);
                $children[$childrenName] = $this->{$getChildrenFunction}();
            }
        }
        return $children;
    }

    /**
     * @param string $children существование свойства
     * @return bool
     */
    public function issetChildrenByName(string $children)
    {
        return isset($this->children()[$children]);
    }

    /**
     * @return string имя текущего свойства конфига
     */
    public function getConfigName()
    {
        return (new ReflectionClass($this))->getShortName();
    }
}
