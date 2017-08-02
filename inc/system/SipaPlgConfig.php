<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 18.01.2017
 * Time: 13:16
 *
 * Class for Local Settings or Configuration
 */

class SipaPlgConfig {
    private static $_singleton;
    private $xml;
    private $cfgdir = 'config/';
    private $tmp_cfg_file = false;
    private $tmp_cfg_fp = false;
    private $tmp_cfg_obj = false;


    private static function init() {
        if(is_null (self::$_singleton) ) {
            self::$_singleton = new self;
        }
        return self::$_singleton;
    }

    public static function get($cfgFile, $key)
    {
        return self::init()->getCFG($cfgFile)->getVal($key);
    }

    public static function set($cfgFile, $key, $val)
    {
        return self::init()->setCFG($cfgFile)->setVal($key, $val);
    }

    public static function getCfgAll($cfgFile)
    {
        return self::init()->_getCFGAll($cfgFile);
    }

    public static function setCfgDefault($cfgFile, $default=array())
    {
        return self::init()->setConfigDefault($cfgFile,$default);
    }

    public static function setAll($cfgFile, $default=array())
    {
        return self::init()->setConfigAll($cfgFile,$default);
    }

    private function getCFG($cfgFile)
    {
        $this->tmp_cfg_file = GOKSEL__PLUGIN_DIR . $this->cfgdir . $cfgFile . '.php';
        $this->tmp_cfg_fp = $cfgFile;

        if(file_exists($this->tmp_cfg_file))
        {
            if($this->tmp_cfg_obj === false || !isset($this->tmp_cfg_obj[$cfgFile]) )
                $this->tmp_cfg_obj[$cfgFile] = (array) include($this->tmp_cfg_file);


            return $this;
        }

        return null;
    }

    private function _getCFGAll($cfgFile)
    {
        $this->tmp_cfg_file = GOKSEL__PLUGIN_DIR . $this->cfgdir . $cfgFile . '.php';
        $this->tmp_cfg_fp = $cfgFile;

        if(file_exists($this->tmp_cfg_file))
        {
            if($this->tmp_cfg_obj === false || !isset($this->tmp_cfg_obj[$cfgFile]) )
                $this->tmp_cfg_obj[$cfgFile] = (array) include($this->tmp_cfg_file);


            return $this->tmp_cfg_obj[$cfgFile];
        }

        return array();
    }

    private function setCFG($cfgFile)
    {
        try
        {
            $this->tmp_cfg_fp = $cfgFile;

            $this->tmp_cfg_file = GOKSEL__PLUGIN_DIR . $this->cfgdir . $cfgFile . '.php';

            if(file_exists($this->tmp_cfg_file))
            {
                $this->tmp_cfg_obj[$this->tmp_cfg_fp] = (array) include($this->tmp_cfg_file);

                return $this;
            }
            else
            {
                $tmp_str = '<?php return array();';

                file_put_contents($this->tmp_cfg_file, $tmp_str);

                return $this;
            }
        }
        catch(Exception $e)
        {
            return null;
        }

    }

    private function setVal($key, $val='')
    {
        if(file_exists($this->tmp_cfg_file))
        {

            try
            {
                $this->tmp_cfg_obj[$this->tmp_cfg_fp]->$key = $val;

                $tmp = json_decode(json_encode($this->tmp_cfg_obj[$this->tmp_cfg_fp]), true);
                $tmp_str = '<?php return ' . var_export($tmp, true) . ';';

                file_put_contents($this->tmp_cfg_file, $tmp_str);

                return true;
            }
            catch(Exception $e)
            {
                return false;
            }

        }

        return false;

    }

    private function getVal($key)
    {
        if(isset($this->tmp_cfg_obj[$this->tmp_cfg_fp][$key]))
            return $this->tmp_cfg_obj[$this->tmp_cfg_fp][$key];

        return null;

    }

    /**
     * @param $cfgFile
     * @param array $default
     * @return int 2=default keyler set edildi 0=cfg dosyası var bir şey yapmadı -1=hata
     */
    private function setConfigDefault($cfgFile, $default=array())
    {
        $cfgTmp = GOKSEL__PLUGIN_DIR . $this->cfgdir . $cfgFile . '.php';
        if(!file_exists($cfgTmp))
        {
            try
            {
                //$tmp = json_decode(json_encode($default), true);
                $tmp_str = '<?php return ' . var_export($default, true) . ';';

                file_put_contents($cfgTmp, $tmp_str);

                $this->tmp_cfg_fp = $cfgFile;
                $this->tmp_cfg_obj[$cfgFile] = (array) $default;

                return 2;
            }
            catch(Exception $e)
            {
                return -1;
            }

        }

        return 0;

    }

    /**
     * @param $cfgFile
     * @param array $default
     * @return int 2=default keyler set edildi 0=cfg dosyası var bir şey yapmadı -1=hata
     */
    private function setConfigAll($cfgFile, $default=array())
    {
        $cfgTmp = GOKSEL__PLUGIN_DIR . $this->cfgdir . $cfgFile . '.php';

        try
        {
            //$tmp = json_decode(json_encode($default), true);
            $tmp_str = '<?php return ' . var_export($default, true) . ';';

            file_put_contents($cfgTmp, $tmp_str);

            $this->tmp_cfg_fp = $cfgFile;
            $this->tmp_cfg_obj[$cfgFile] = (array) $default;

            return 2;
        }
        catch(Exception $e)
        {
            return -1;
        }



    }

    public function openXml($xml_file) {
        $this->xml = simplexml_load_file($xml_file);
        return $this;
    }

    public function getXmlConfig($path=null) {
        if (!is_object($this->xml)) {
            return false;
        }
        if (!$path) {
            return $this->xml;
        }
        $xml = $this->xml->xpath($path);
        if (is_array($xml)) {
            if (count($xml) == 1) {
                return (string)$xml[0];
            }
            if (count($xml) == 0) {
                return false;
            }
        }
        return $xml;
    }
}