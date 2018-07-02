# yaml_config

* Модуль позволяет сгенерировать ООП код конфига из
yaml-файла.

Например, из **yaml-файла**:

```yaml
family: # семья
  father: # отец
    name: Bob # имя
    hobby: # хобби
      - sport # спорт
      - boardgames # настольные игры
    story: | # биография
      родился в Бафало: # место рождения
      учился в церковно-приходской школе
  doter: # дочь
    name: Mila # имя
    age: # возраст
      '2017-04-17': 0
      '2018-04-17': 1
      '2019-04-17': 2
```

* **модуль** сгенерирует php-код, который,
будет позволять обращаться
к значениям конфига в ООП-стиле:

```php
$config = new Config($date);
$fatherName = $config
    ->getFamily()
    ->getFather()
    ->getName();

```
Переменная **$fatherName** будет содержать
значение `Bob`.

* **Модуль** позволяет создавать
свойства с ограниченным
сроком действия, например, в вышеуказанном
**yaml-файле** обращение к свойству
**family.doter.age** будет зависеть
от переданной в конструктор даты:

```php
$dateList = [
    '2018-04-17',
    '2019-09-12',
    '2017-09-01'
];
foreach($dateList as $date){
    $dateTime = new DateTime($date);
    $config = new Config($dateTime);
    $doterAgeList[] = $config
        ->getFamily()
        ->getDoter()
        ->getAge();
}

```

Переменная **$doterAgeList** содержит
массив: `[1,2,0]`

* Создаваемый **модулем** php-код будет
содержать phpDoc-комментарии, соответствующие
комментариям в **yaml-файле**

## Как использовать

```php
use YamlConfig\ClassCodeGenerator\ConfigClassTreeGenerator;
use YamlConfig\YamlFileToTree;

$configGenerator = new ConfigClassTreeGenerator();
$yamlFileToTree = new YamlFileToTree(); // объект преобразователь конфигурационного файла в конфигурацию
$yamlFileToTree
    ->setConfigRelativePath($organizationsRelativePath); //относительный путь расположения yaml-файл с настройками
$configGenerator
    ->setProjectPath($rootDir) // путь к папке проекта
    ->setYamlFileToTree($yamlFileToTree) // объект преобразователь конфигурационного файла в конфигурацию
    ->setConfigCodeRelativePath($organizationsCodeRelativePath) // относительный путь к папке в которой будут сгенерирован код конфига
    ->setConfigName('Family') // название класса конфига
    ->setConfigNamespace('Config\Family') // пространство имён конфига
    ->generate(); // Генерация кода конфига
```

Особенности функции **generate**:
1) Если изменений в исходном конфиге (по сравнению со сгенерированным кодом) нет, то перегенерация не происходит.
2) В качестве необязательного параметра **generate** принимает функцию, которая будет вызвана после генерации кода.

## Интерфейсы
 помощью модуля есть возможность генерации интерфейсов для классов конфигурации.
1) **Иерархические интерфейсы**.
    >По структуре yaml соответствуют yaml конфига. На пример yaml:
    >```yaml
    >family: # семья
    >  father: # отец
    >    name: Bob # имя
    >    hobby: # хобби 
    >      - sport # спорт
    >      - boardgames # настольные игры
    >    story: | # биография
    >      родился в Бафало: # место рождения
    >      учился в церковно-приходской школе
    >  doter: # дочь
    >    name: Mila # имя
    >    age: # возраст
    >      '2017-04-17': 0
    >      '2018-04-17': 1
    >      '2019-04-17': 2
    >```
    >Создаст интерфейс на самом верхнем иерархическом уровне:
    >```php
    ><?php
    >
    >namespace Config\Interfaces\Family;
    >
    >use YamlConfig\InterfaceCodeGenerator\InterfaceConfigNode;
    >use Config\Interfaces\Family\Family\Father;
    >use Config\Interfaces\Family\Family\Doter;
    >
    >
    >interface Family extends InterfaceConfigNode {
    >
    >    /** @return Father отец */
    >    public function getFather();
    >    
    >    /** @return Doter дочь */
    >    public function getDoter();
    >
    >}
    >```
    >На следующем уровне по иерархии будут находится интерфейсы Father и Doter. На пример интерфейс Doter:
    >
    >```php
    ><?php
    >
    >namespace Config\Interfaces\Family\Family;
    >
    >use YamlConfig\InterfaceCodeGenerator\InterfaceConfigNode;
    >
    >
    >interface Doter extends InterfaceConfigNode {
    >
    >    /** @return string отец */
    >    public function getName();
    >    
    >    /** @return int дочь */
    >    public function getAge();
    >
    >}
    >```
    >Код для генерации интерфейсов очень похож на код для генерации конфига. Будет выглядеть следующим образом:
    >
    >```php
    ><?php
    >
    >use YamlConfig\InterfaceCodeGenerator\ConfigInterfaceTreeGenerator;
    >use \YamlConfig\YamlFileToTree;
    >
    >$configInterfaceTreeGenerator = new ConfigInterfaceTreeGenerator();
    >$yamlFileToTree = new YamlFileToTree(); // объект преобразователь конфигурационного файла в конфигурацию
    >$yamlFileToTree
    >    ->setConfigRelativePath($organizationsRelativePath); //относительный путь расположения yaml-файл с настройками
    >$configInterfaceTreeGenerator
    >    ->setProjectPath($rootDir)  // путь к папке проекта
    >    ->setYamlFileToTree($yamlFileToTreeInterface) // объект преобразователь конфигурационного файла в конфигурацию
    >    ->setConfigCodeRelativePath($organizationsCodeRelativePath) // относительный путь к папке в которой будут сгенерирован код конфига
    >    ->setConfigName('Family') // название класса конфига
    >    ->setConfigNamespace('Config\Interfaces\Family') // пространство имён конфига
    >    ->generate();
    >```
2) **Интерфейсы описанные списком**.
    >Пример структуры yaml:
    >```yaml
    >family_member: # Наименование интерфейса(Комментарий будет использоваться как комментарий к интерфейсу)
    >  xpath: # Список xpath к элементам которые будут его реализовывать
    >    - /family/*[name]
    >  property: # Список свойств указывается для указания геттеров для них
    >    name: # наименование свойства (Комментарий будет использоваться как комментарий к гетерру)
    >      type: string #тип свойства
    >family_father: # Наименование интерфейса 
    >  xpath: # Список xpath к элементам которые будут его реализовывать
    >     - /family/father[name and hobby]
    >  property:
    >    hobby: # наименование свойства  
    >      type: string
    >    name: # наименование свойства
    >      type: string #тип свойства
    >```
    >Данный yaml будет создавать 2 интерфейса:
    >```php
    ><?php
    >
    >namespace Config\Interfaces\Family;
    >
    >use YamlConfig\InterfaceCodeGenerator\InterfaceConfigNode;
    >
    >/**
    > * Наименование интерфейса(Комментарий будет использоваться как комментарий к интерфейсу)
    > */
    >interface FamilyMember extends InterfaceConfigNode {
    >
    >    /** @return string наименование свойства (Комментарий будет использоваться как комментарий к гетерру) */
    >    public function getName();
    >
    >}
    >```
    >```php
    ><?php
    >
    >namespace Config\Interfaces\Family;
    >
    >use YamlConfig\InterfaceCodeGenerator\InterfaceConfigNode;
    >
    >/**
    > * Наименование интерфейса
    > */
    >interface FamilyFather extends InterfaceConfigNode {
    >
    >    /** @return string наименование свойства */
    >    public function getName();
    >
    >    /** @return string наименование свойства */
    >    public function getHobby();
    >
    >}
    >```
    >Код для генерации интерфейсов:
    >
    >```php
    ><?php
    >
    >use YamlConfig\InterfaceCodeGenerator\ConfigInterfaceListTreeGenerator;
    >use \YamlConfig\YamlFileToTree;
    >
    >$configInterfaceTreeGenerator = new ConfigInterfaceListTreeGenerator();
    >$yamlFileToTree = new YamlFileToTree(); // объект преобразователь конфигурационного файла в конфигурацию
    >$yamlFileToTree
    >    ->setConfigRelativePath($organizationsRelativePath); //относительный путь расположения yaml-файл с настройками
    >$configInterfaceTreeGenerator
    >    ->setProjectPath($rootDir)  // путь к папке проекта
    >    ->setYamlFileToTree($yamlFileToTreeInterface) // объект преобразователь конфигурационного файла в конфигурацию
    >    ->setConfigCodeRelativePath($organizationsCodeRelativePath) // относительный путь к папке в которой будут сгенерирован код конфига
    >    ->setConfigName('Family') // название класса конфига
    >    ->setConfigNamespace('Config\Interfaces\Family') // пространство имён конфига
    >    ->generate();
    >```
3) **Подключение интерфейсов к объектам конфигурации:**
    >```php
    ><?php
    >use YamlConfig\ClassCodeGenerator\ConfigClassTreeGenerator;
    >use YamlConfig\YamlFileToTree;
    >
    >$configGenerator = new ConfigClassTreeGenerator();
    >$yamlFileToTree = new YamlFileToTree(); // объект преобразователь конфигурационного файла в конфигурацию
    >$yamlFileToTree
    >    ->setConfigRelativePath($organizationsRelativePath); //относительный путь расположения yaml-файл с настройками
    >$yamlFileToTreeHierarchicalInterface = new YamlFileToTree(); // объект преобразователь конфигурационного файла в конфигурацию
    >$yamlFileToTreeHierarchicalInterface
    >    ->setConfigRelativePath($organizationsRelativePath); //относительный путь расположения yaml-файл с настройками
    >$yamlFileToTreeDescribedInterfaces = new YamlFileToTree(); // объект преобразователь конфигурационного файла в конфигурацию
    >$yamlFileToTreeDescribedInterfaces
    >    ->setConfigRelativePath($organizationsRelativePath); //относительный путь расположения yaml-файл с настройками
    >$configGenerator
    >    ->setProjectPath($rootDir) // путь к папке проекта
    >    ->setYamlFileToTree($yamlFileToTree) // объект преобразователь конфигурационного файла в конфигурацию
    >    ->setConfigCodeRelativePath($organizationsCodeRelativePath) // относительный путь к папке в которой будут сгенерирован код конфига
    >    ->setConfigName('Family') // название класса конфига
    >    ->setConfigNamespace('Config\Family') // пространство имён конфига
    >    ->setYamlFileToTreeHierarchicalInterfaces($yamlFileToTreeHierarchicalInterface) // преобразователь конфигурационного файла в конфигурацию для иерархических интерфейсов 
    >    ->setYamlFileToTreeDescribedInterfaces($yamlFileToTreeDescribedInterfaces) // преобразователь конфигурационного файла в конфигурацию для описанных интерфейсов
    >    ->setConfigHierarchicalInterfacesNamespace('Config\Interfaces\HierarchicalInterface') // пространство имён иерархических интерфейсов для узлов конфига
    >    ->setConfigDescribedInterfacesNamespace('Config\Interfaces\DescribedInterfaces') //  пространство имён описанных интерфейсов для узлов конфига
    >    ->generate(); // Генерация кода конфига
    >```
    > Пример класса Father  генерируемого этим кодом:
    >```php
    ><?php
    >
    >namespace Config\Family\Family;
    >
    >use YamlConfig\InterfaceCodeGenerator\InterfaceConfigNode;
    >use Config\Interfaces\HierarchicalInterface\Family\Family\Father as FatherInterface;
    >use Config\Interfaces\DescribedInterfaces\FamilyFather as FamilyFatherInterface;
    >use use Config\Interfaces\DescribedInterfaces\FamilyMember as FamilyMemberInterface;
    >
    >/**
    > * Наименование интерфейса
    > */
    >class Father extends InterfaceConfigNode implements FatherInterface, FamilyFatherInterface, FamilyMemberInterface
    >{
    >
    >    /** @return string наименование свойства */
    >    public function getName()
    >    {
    >        return $this->getActualProperty('name');
    >    }
    >
    >    /** @return string наименование свойства */
    >    public function getHobby()
    >    {
    >        return $this->getActualProperty('hobby');
    >    }
    >
    >}
    >```