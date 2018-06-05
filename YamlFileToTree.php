<?php

namespace YamlConfig;


use Symfony\Component\Yaml\Yaml;

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
}