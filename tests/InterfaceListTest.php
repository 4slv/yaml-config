<?php

use YamlConfig\ClassCodeGenerator\ConfigClassTreeGenerator;
use YamlConfig\YamlFileToTree;
use YamlConfig\InterfaceListCodeGenerator\ConfigInterfaceListTreeGenerator;

$autoload = require __DIR__.DIRECTORY_SEPARATOR."../vendor/autoload.php";
/**
 * @var $autoload Composer\Autoload\ClassLoader
 */

class InterfaceListTest
{

    const PROJECT_FOLDER = __DIR__.DIRECTORY_SEPARATOR.'InterfaceListTest'.DIRECTORY_SEPARATOR.'tmp';

    const CONFIG_FOLDER = __DIR__.DIRECTORY_SEPARATOR.'InterfaceListTest'.DIRECTORY_SEPARATOR.'config';

    const REFERENCE_FOLDER = __DIR__.DIRECTORY_SEPARATOR.'InterfaceListTest'.DIRECTORY_SEPARATOR.'reference';

    const FILE_NAME = 'test.yaml';
    const CODE_RELATIVE_PATH = 'Test';
    const NAME = 'Test';
    const NAMESPACE = 'Test';

    const INTERFACE_FILE_NAME = 'test.interface.yaml';
    const INTERFACE_CODE_RELATIVE_PATH = 'Interfaces/Test';
    const INTERFACE_NAMESPACE = 'Interfaces\\Test';


    /**
     * @return ConfigClassTreeGenerator генератор классов конфига
     */
    protected function createConfigClassTreeGenerator()
    {
        return (new ConfigClassTreeGenerator())->setProjectPath(static::PROJECT_FOLDER);
    }

    /**
     * @return YamlFileToTree класс файла для генератора конфига
     */
    protected function createYamlFileToTree()
    {
        return (new YamlFileToTree())->setProjectPath(static::CONFIG_FOLDER);
    }
    /**
     * @return ConfigInterfaceListTreeGenerator
     */
    protected function createConfigInterfaceListGenerator()
    {
        return (new ConfigInterfaceListTreeGenerator())->setProjectPath(static::PROJECT_FOLDER);;
    }

    public function start()
    {
        $this->recreateFolder();
        $this->generatInterface();
        $this->generatConfig();
        $this->comparison();
    }

    protected function recreateFolder()
    {
        $this->removeDirectory(static::PROJECT_FOLDER);
        mkdir(static::PROJECT_FOLDER);
    }

    /**
     * @param string $path
     */
    protected function removeDirectory(string $path)
    {
        if(!file_exists($path))
            return;
        $files = glob($path . '/*');
        foreach ($files as $file) {
            is_dir($file) ? $this->removeDirectory($file) : unlink($file);
        }
        rmdir($path);
        return;
    }

    protected function generatConfig()
    {
        $yamlFileToTree = $this->createYamlFileToTree()
            ->setConfigRelativePath(static::FILE_NAME);
        $yamlFileToTreeInterfaceList = $this->createYamlFileToTree()
            ->setConfigRelativePath(static::INTERFACE_FILE_NAME);
        $this
            ->createConfigClassTreeGenerator()
            ->setYamlFileToTree($yamlFileToTree)
            ->setConfigInterfaceNamespace(static::INTERFACE_NAMESPACE)
            ->setYamlFileToTreeInterfaceList($yamlFileToTreeInterfaceList)
            ->setConfigCodeRelativePath(static::CODE_RELATIVE_PATH)
            ->setConfigName(static::NAME)
            ->setConfigNamespace(static::NAMESPACE)
            ->generate();
    }



    protected function generatInterface()
    {
        $yamlFileToTree = $this->createYamlFileToTree()
            ->setConfigRelativePath(static::INTERFACE_FILE_NAME);
        $this->createConfigInterfaceListGenerator()
            ->setYamlFileToTree($yamlFileToTree)
            ->setConfigCodeRelativePath(static::INTERFACE_CODE_RELATIVE_PATH)
            ->setConfigNamespace(static::INTERFACE_NAMESPACE)
            ->generate();
    }

    public function comparison()
    {
        $directory = new RecursiveDirectoryIterator(static::REFERENCE_FOLDER);
        $iterator = new RecursiveIteratorIterator($directory);
        $result = true;
        foreach($iterator as $file){
            if($file->isFile()) {
                $filePathReference = $file->getPathname();
                $filePathResult =  substr_replace($filePathReference,static::PROJECT_FOLDER,0,strlen(static::REFERENCE_FOLDER));
                if(!is_file($filePathResult)) {
                    $result = false;
                    echo "Файл не создан: ".$filePathResult.PHP_EOL;
                    continue;
                };
                if(md5_file($filePathReference) != md5_file($filePathResult)){
                    $result = false;
                    echo "Файл ".$filePathResult." не соответствует оригиналу ".$filePathReference.PHP_EOL;
                    continue;
                }
            }
        }
        if($result){
            echo "Тест успешно пройден ".PHP_EOL;
        }
    }


}

(new InterfaceListTest)->start();