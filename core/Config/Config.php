<?php

declare(strict_types=1);

namespace Core\Config;

use Core\Config\Exception\InvalidPathException;
use Core\Config\Exception\InvalidFileExtException;
use Core\Config\Exception\InvalidFileContentException;
use Core\Config\Exception\InvalidConfigNameException;

class Config
{

    public const EXT_JSON = ".json";
    public const EXT_PHP = ".php";
    public const EXT_YML = ".yml";

    private static $sInstance;
    private static array $sAllowedExt;
    
    private $configFiles = [];
    private $configValues = [];
    private $allConfig = [];


    public static function getInstance($files = [])
    {
        if (is_null(self::$sInstance)) {
            self::$sInstance = new Config($files);
        }
        return self::$sInstance;
    }

    private function __construct(array $configFiles = [])
    {
        $this->configFiles = [];
        self::setAllowedExt();
        if (!empty($configFiles)) {
            $this->setConfigByFiles($configFiles);
        }
    }


    /**
     * to avoid unexpected result due to improper handling of $_ENV
     * we reset the config from the files stored in th Config object
     *
     * @return void
     */
    public function resetConfig()
    {
        $_ENV = $this->allConfig;
    }

    /**
     * @param array $configFiles respecting the bellow form
     * $configFiles = [
     *      [$configName => $filePath (string)]
     * ]
     * @return void
     */
    public function setConfigByFiles($configFiles)
    {
        $this->validateConfigFiles($configFiles);
        $this->setConfigFiles($configFiles);
        $this->configFiles = array_merge($this->configFiles, $configFiles);
    }

    /**
     * add the provaided configArray param in the global variable $_ENV
     * @param array $configValues respecting the bellow form
     * $configValues = [
     *      [$configName => $ConfigValue (string)]
     * ]
     * @return void
     */
    public function setConfigByValues(array $configValues)
    {
        $this->validateConfigValues($configValues);
        $this->setConfigValues($configValues);
        $this->configValues = array_merge($this->configValues, $configValues);
    }


    /**
     * validate the Config name, the configFile's path and the configFile's Ext
     *
     * @param array $configFiles respecting the bellow form
     * $configFiles = [
     *      [$configName => $filePath (string)]
     * ]
     *
     * @throws \Core\Config\Exception\InvalidConfigNameException
     * @throws \Core\Config\Exception\InvalidPathException
     * @throws \Core\Config\Exception\InvalidFileExtException
     *
     * @return void
     */
    private function validateConfigFiles(array $configFiles)
    {
        $namePattern = "#^[a-zA-Z_][\w]*$#";
        foreach ($configFiles as $configName => $filePath) {
            
            if (!is_string($configName)) {
                throw new InvalidConfigNameException(
                    "The config name '$configName' provided for the file '$filePath' is not Valid"
                );
            }
            
            if (!\preg_match($namePattern, $configName)) {
                throw new InvalidConfigNameException(
                    "The config name '$configName' provided for the file '$filePath' is not Valid"
                );
            }
            
            if (!file_exists($filePath)) {
                throw new InvalidPathException("The file '$filePath' doesn't exist");
            }

            $ext = \explode(".", $filePath);
            $ext = end($ext);
            if (!\in_array($ext, self::$sAllowedExt)) {
                throw new InvalidFileExtException(
                    "Unhandled file type. the Config file must have the followed Ext : ".
                    \implode(", ", self::$sAllowedExt)
                );
            }
        }
    }

    /**
     * validate the configName of each element in the configArray
     *
     * @param array $configArray respecting the bellow form
     * $configArray = [
     *      [$configName => $ConfigValue (mixed)]
     * ]
     *
     * @throws \Core\Config\Exception\InvalidConfigNameException
     *
     * @return void
     */
    private function validateConfigValues(array $configArray)
    {
        $namePattern = "#^[a-zA-Z_\x80-\xff][a-zA-Z0-9_\x80-\xff]*$#";

        foreach ($configArray as $configName => $configValue) {
            if (!\is_string($configName)) {
                throw new InvalidConfigNameException(
                    "The config name '$configName' is not Valid"
                );
            }
            if (!\preg_match($namePattern, $configName)) {
                throw new InvalidConfigNameException(
                    "The config name '$configName' is not Valid"
                );
            }
        }
    }

    /**
     * set the content of the $configFiles in the global Variable $_ENV
     * it use the config name as the key $_ENV and the content of the file as value of this key
     * if the config name is "main", set the content of the file directly in $_ENV
     *
     * @param array $configFiles respecting the bellow form
     * $configFiles = [
     *      [$configName => $filePath (string)]
     * ]
     *
     * @throws \Core\Config\Exception\InvalidFileContentException
     *
     * @return void
     */
    private function setConfigFiles(array $configFiles)
    {
        foreach ($configFiles as $configName => $filePath) {
            $configContent = [];

            if (str_ends_with($filePath, self::EXT_JSON)) {
                $configContent = json_decode(
                    file_get_contents($filePath),
                    true
                );
            }

            if (str_ends_with($filePath, self::EXT_PHP)) {
                $configContent = require_once($filePath);
            }

            if (!\is_array($configContent)) {
                throw new InvalidFileContentException("The content of the config file is not an array!");
            }

            if ($configName === "main") {
                foreach ($configContent as $key => $value) {
                    $this->allConfig[$key] = $value;
                }
            } else {
                $this->allConfig[$configName] = $configContent;
            }

        }

        $_ENV = $this->allConfig;
    }

    /**
     * set the content of the $configArray in the global Variable $_ENV
     * it uses the key and value of each element of the $configArray as the key and its Value in $_ENV
     *
     * @param array $configArray respecting the bellow form
     * $configArray = [
     *      [$configName => $ConfigValue (mixed)]
     * ]
     * @throws \Core\Config\Exception\InvalidFileContentException
     *
     * @return void
     */
    private function setConfigValues(array $configArray)
    {
        foreach ($configArray as $configName => $configValue) {
            $this->allConfig[$configName] = $configValue;
        }
        $_ENV = $this->allConfig;

    }

    /**
     * set the Allowed Config files extetion in the propriety $sAllowedExt
     */
    protected static function setAllowedExt()
    {
        self::$sAllowedExt = [
            "php",
            "json"
        ];
    }
}
