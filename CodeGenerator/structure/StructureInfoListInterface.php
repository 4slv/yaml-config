<?php

namespace YamlConfig\StructureCodeGenerator;

use YamlConfig\YamlFileToTree;

/** Список информации о структурах */
interface StructureInfoListInterface
{
    /**
     * @return ConfigStructureInfoInterface[] список информации о структуре конфига
     */
    public function getStructureInfoList();

    /**
     * @param YamlFileToTree $yamlFileToTree преобразователь конфигурационного файла в конфигурацию
     */
    public function setYamlFileToTree(YamlFileToTree $yamlFileToTree);

    /**
     * @param string $configNamespace пространство имён конфига
     */
    public function setConfigNamespace($configNamespace);

    /**
     * @param array $tree дерево конфига
     * @param string[] $path текущий путь
     * @return ConfigStructureInfoInterface[] список информации о структуре
     */
    public function initFromTree($tree, $path = []);

}
