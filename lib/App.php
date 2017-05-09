<?php namespace Lib;

/**
 * Class App
 *
 * Primary configuration for the app.
 */
class App {

    protected $config = [];

    protected $codes = [];

    protected $db;

    protected $request;

    protected $language = 'en';

    protected $langData = [];


    public function __construct()
    {
        $this->loadLanguageData();
    }

    /**
     * @param $lang
     *
     * @return $this
     */
    public function setLanguage($lang)
    {
        $this->language = $lang;

        $this->loadLanguageData();

        $this->loadLanguageData();

        return $this;
    }


    /**
     * @return array
     */
    public function getCodes()
    {
        return $this->codes;
    }

    /**
     *
     */
    private function loadLanguageData()
    {
        if (file_exists(dirname(dirname(__FILE__)) . '/config/lang/' . $this->language . '/data.php')) {
            $this->langData = require dirname(dirname(__FILE__)) . '/config/lang/' . $this->language . '/data.php';
        }

        if (file_exists(dirname(dirname(__FILE__)) . '/config/lang/' . $this->language . '/errorCodes.php')) {
            $this->codes = require dirname(dirname(__FILE__)) . '/config/lang/' . $this->language . '/errorCodes.php';
        }
    }

    /**
     * @param string $key
     * @param string $default
     *
     * @return array|string
     */
    public function getConfig($key = '', $default = '')
    {
        $array = $this->config;

        if (isset($array[$key]))
            return $array[$key];

        foreach (explode('.', $key) as $segment)
        {
            if ( ! is_array($array) || ! array_key_exists($segment, $array))
            {
                return $default;
            }
            $array = $array[$segment];
        }

        return $array;
    }


    /**
     * @param $key
     *
     * @return array|string
     */
    public function config($key)
    {
        return $this->getConfig($key);
    }


    /**
     * @param $conf
     * @param string $key
     *
     * @return $this
     */
    public function setConfig($conf, $key = '')
    {
        if (is_array($conf)) {
            $this->config = $conf;
        } else {
            $this->config[$key] = $conf;
        }

        return $this;
    }


    /**
     * @param $code
     *
     * @return string
     */
    public function code($code)
    {
        return (array_key_exists($code, $this->codes)) ? $this->codes[$code] : '';
    }


    /**
     * @param $code
     *
     * @return string
     */
    public function lang($code)
    {
        return (array_key_exists($code, $this->langData)) ? $this->langData[$code] : '';
    }


    /**
     * @return \PDO
     */
    public function db()
    {
        try {
            return new \PDO(
                'mysql:host=' . $this->config['mysql']['host'] . ';dbname=' . $this->config['mysql']['db'],
                $this->config['mysql']['user'],
                $this->config['mysql']['pass'],
                array(
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8;"
                )
            );
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
        }
    }

}