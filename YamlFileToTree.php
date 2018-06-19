<?php

namespace YamlConfig;


use Symfony\Component\Yaml\Yaml;
use DOMDocument;
use DOMXpath;
use DOMNodeList;

/** Преобразователь конфигурационного файла в конфигурацию */
class YamlFileToTree
{

    /** @var string путь к папке проекта */
    protected $projectPath;

    /** @var string относительный путь расположения yaml-файл с настройками  */
    protected $configRelativePath;

    /** @var string содержимое конфигурации yaml */
    protected $yamlConfigContent;

    /** @var array дерево конфигурации yaml */
    protected $yamlConfigTree;

    /** @var string[] комментарии к узлам конфига */
    protected $configNodeComments;

    /** @var DOMDocument дерево конфигурации yaml */
    protected $yamlConfigDom;

    /** @var DOMNodeList[] дерево конфигурации yaml */
    protected $yamlConfigDomNodeList;


    /**
     * @return string
     */
    public function getProjectPath()
    {
        return $this->projectPath;
    }

    /**
     * @param string $projectPath
     * @return $this
     */
    public function setProjectPath(string $projectPath)
    {
        $this->projectPath = $projectPath;
        return $this;
    }

    /**
     * @return string
     */
    public function getConfigRelativePath()
    {
        return $this->configRelativePath;
    }

    /**
     * @param string $configRelativePath
     * @return $this
     */
    public function setConfigRelativePath(string $configRelativePath)
    {
        $this->configRelativePath = $configRelativePath;
        return $this;
    }

    /**
     * @return string|null содержимое конфига yaml
     */
    public function getYamlConfigContent()
    {
        if(is_null($this->yamlConfigContent) && file_exists($this->getConfigFullPath())){
            $this->yamlConfigContent = file_get_contents(
                $this->getConfigFullPath()
            );
        }
        return $this->yamlConfigContent;
    }

    /**
     * @return string полный путь к конфигу
     */
    public function getConfigFullPath()
    {
        return $this->getProjectPath().
            DIRECTORY_SEPARATOR.
            $this->getConfigRelativePath();
    }

    /**
     * @return array|null массив дерева конфига
     */
    public function getYamlConfigTree()
    {
        if(is_null($this->yamlConfigTree)  && !is_null( $this->getYamlConfigContent())){
            $this->yamlConfigTree = Yaml::parse(
                $this->getYamlConfigContent()
            );
        }

        return $this->yamlConfigTree;
    }


    /**
     * @return string[]|null комментарии к узлам конфига
     */
    public function getConfigNodeComments()
    {
        if(is_null($this->configNodeComments) && !is_null( $this->getYamlConfigContent())){
            $this->configNodeComments = YamlCommentsParser::parse(
                $this->getYamlConfigContent()
            );
        }

        return $this->configNodeComments;
    }

    /**
     * @param string $rootNode
     * @return DOMDocument
     */
    public function getTreeAsDOMDocument($rootNode = 'root')
    {
        if(is_null($this->yamlConfigDom) && !is_null($this->getYamlConfigTree())) {
            $this->yamlConfigDom = new DOMDocument('1.0', 'UTF-8');
            $this->yamlConfigDom->formatOutput = true;
            $root = $this->yamlConfigDom->createElement($rootNode);
            $this->yamlConfigDom->appendChild($root);
            $array2xml = function ($node, $array) use (&$array2xml) {
                foreach ($array as $key => $value) {
                    if (is_array($value)) {
                        $n = $this->yamlConfigDom->createElement($key);
                        $node->appendChild($n);
                        $array2xml($n, $value);
                    } else {
                        $node2 = $this->yamlConfigDom->createElement(is_numeric($key) ? 'item' : $key);
                        $node->appendChild($node2);
                        $node2->appendChild($this->yamlConfigDom->createTextNode($value));
                    }
                }
            };
            $array2xml($root, $this->getYamlConfigTree());
        }
        return $this->yamlConfigDom;
    }


    /**
     * @param string $xpath
     * @param string $rootNode
     * @return DOMNodeList|false
     * @return DOMNodeList|false
     */
    public function getDOMNodeListByXpath(string $xpath, string $rootNode = 'root')
    {
        if(isset($this->yamlConfigDomNodeList[$xpath])){
            return $this->yamlConfigDomNodeList[$xpath];
        }
        return $this->yamlConfigDomNodeList[$xpath] = (new DOMXpath($this->getTreeAsDOMDocument($rootNode)))->query(
            '/'.$rootNode.$xpath
        );
    }
}