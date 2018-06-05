<?php

namespace YamlConfig\StructureCodeGenerator;

use Slov\Helper\FileHelper;
use YamlConfig\YamlFileToTree;

/** Генератор структуры */
abstract class ConfigStructureTreeGenerator
{

    /** @var string путь к папке проекта */
    protected $projectPath;

    /** @var string относительный путь к папке в которой будет сгенерирован код конфига */
    protected $configCodeRelativePath;

    /** @var string пространство имён конфига */
    protected $configNamespace;

    /** @var string название структуры конфига */
    protected $configName;

    /** @var YamlFileToTree Класс работы с файлом yaml */
    protected $yamlFileToTree;

    /**
     * @return YamlFileToTree|null
     */
    public function getYamlFileToTree(): ?YamlFileToTree
    {
        return $this->yamlFileToTree;
    }

    /**
     * @param YamlFileToTree|null $yamlFileToTree
     * @return $this
     */
    public function setYamlFileToTree(YamlFileToTree $yamlFileToTree = null)
    {
        $this->yamlFileToTree = $yamlFileToTree;
        return $this;
    }

    /**
     * @return string путь к папке проекта
     */
    protected function getProjectPath()
    {
        return $this->projectPath;
    }

    /**
     * @param string $projectPath путь к папке проекта
     * @return $this
     */
    public function setProjectPath($projectPath)
    {
        $this->projectPath = realpath($projectPath);
        return $this;
    }

    /**
     * @return string относительный путь к папке в которой будут сгенерирован код конфига
     */
    protected function getConfigCodeRelativePath()
    {
        return $this->configCodeRelativePath;
    }

    /**
     * @param string $configCodeRelativePath относительный путь к папке в которой будут сгенерирован код конфига
     * @return $this
     */
    public function setConfigCodeRelativePath($configCodeRelativePath)
    {
        $this->configCodeRelativePath = $configCodeRelativePath;
        return $this;
    }


    /**
     * @return string пространство имён конфига
     */
    protected function getConfigNamespace()
    {
        return $this->configNamespace;
    }

    /**
     * @param string $configNamespace пространство имён конфига
     * @return $this
     */
    public function setConfigNamespace($configNamespace)
    {
        $this->configNamespace = $configNamespace;
        return $this;
    }

    /**
     * @return string название структуры конфига
     */
    protected function getConfigName()
    {
        return $this->configName;
    }

    /**
     * @param string $configName название структуры конфига
     * @return $this
     */
    public function setConfigName($configName)
    {
        $this->configName = $configName;
        return $this;
    }
    
    /**
     * @return StructureInfoListInterface список информации о структурах
     */
    protected function createStructureInfoList()
    {
        return new StructureInfoList();
    }
    
    /**
     * 
     * @param ConfigStructureInfoInterface $configStructureInfo информация о структуре конфига
     * @return ConfigStructureGeneratorInterface
     */
    abstract protected function createConfigStructureGenerator($configStructureInfo);


    /**
     * @return string относительный путь к папке в которой будет сгенерирован код конфига
     */
    protected function getConfigCodeFullPath()
    {
        return
            $this->getConfigCodeRelativePath() === ''
                ? $this->getProjectPath()
                : $this->getProjectPath().
                DIRECTORY_SEPARATOR.
                $this->getConfigCodeRelativePath();
    }

    /** Генерация кода конфига
     * @param callable $callback функция вызываемая после успешной генерации */
    public function generate($callback = null)
    {
        if($this->generationNeeded()){
            FileHelper::recreateDirectory(
                $this->getConfigCodeFullPath()
            );

            $tree = $this->getYamlFileToTree()->getYamlConfigTree();
            $this->addedModifiersForTree($tree);
            $structureInfoList = $this->buildStructureInfoList($tree);

            foreach($structureInfoList->getStructureInfoList() as $structureInfo){
                $this->saveStructureContent($structureInfo);
            };

            if(is_callable($callback)){
                $callback();
            }
        }
    }

    /**
     * @param array &$tree дерево конфига
     */
    protected function addedModifiersForTree(&$tree)
    {
        $this->addConfigWithRootForTree($tree);
    }

    /**
     * @param array &$tree дерево конфига
     */
    protected function addConfigWithRootForTree (&$tree)
    {
        $tree = [
            $this->getConfigName() => $tree
        ];
    }
    
    /**
     * @return bool true - если генерация требуется
     */
    protected function generationNeeded()
    {
        return
            is_dir($this->getConfigCodeFullPath()) === false
            ||
            filemtime($this->getYamlFileToTree()->getConfigFullPath()) > filemtime($this->getConfigCodeFullPath());
    }

    /** Сгенерировать и сохранить контент структуры конфига
     * @param ConfigStructureInfoInterface $structureInfo информация о структуре */
    protected function saveStructureContent(ConfigStructureInfoInterface $structureInfo)
    {
        $structureContent = $this->generateStructureContent($structureInfo);
        $fileRootDirectory = $this->getConfigCodeFullPath();

        $namespaceParts = explode('\\', $structureInfo->getNamespace());
        $baseNamespaceParts = explode('\\', $this->getConfigNamespace());
        $relativeNamespaceParts = array_slice($namespaceParts, count($baseNamespaceParts));
        $fileRelativeDirectory = implode(DIRECTORY_SEPARATOR, $relativeNamespaceParts);
        
        $fileDirectoryPath = strlen($fileRelativeDirectory) > 0
            ? $fileRootDirectory.
                DIRECTORY_SEPARATOR.
                $fileRelativeDirectory
            : $fileRootDirectory;

        $fileFullPath = $fileDirectoryPath.
            DIRECTORY_SEPARATOR.
            $structureInfo->getName(). '.php';
        FileHelper::createDirectory($fileDirectoryPath);
        file_put_contents($fileFullPath, $structureContent);
    }

    /**
     * @param array $configTree дерево конфига
     * @return StructureInfoListInterface список информации о генерируемых структурах
     */
    protected function buildStructureInfoList($configTree)
    {
        $structureInfoList = $this->createStructureInfoList();
        $structureInfoList->setYamlFileToTree(
            $this->getYamlFileToTree()
        );
        $structureInfoList->setConfigNamespace(
            $this->getConfigNamespace()
        );
        $structureInfoList->initFromTree($configTree);
        return $structureInfoList;
    }

    /**
     * @param ConfigStructureInfoInterface $structureInfo информация о структуре
     * @return string содержимое сгенерированной структуры
     */
    protected function generateStructureContent(ConfigStructureInfoInterface $structureInfo)
    {
        $structureGenerator = $this->createConfigStructureGenerator(
            $structureInfo
        );
        return $structureGenerator->generateStructureContent();
    }
}
