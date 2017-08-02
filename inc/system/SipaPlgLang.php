<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 19.01.2017
 * Time: 17:09
 */

class SipaPlgLang {
    private static $_singleton;
    private $dir = 'lang/';
    private $tmp_cfg_file = false;
    private $file = 'tr';
    private $tmp_cfg_obj = false;
    private $lang = false;
    
    private static function init() {
        if(is_null (self::$_singleton) ) {
            self::$_singleton = new self;

            self::$_singleton->lang = SipaPlgSession::get('_sipa__lang',get_locale());
        }
        return self::$_singleton;
    }
    
    public static function get($key, $par=array(), $lang=false)
    {
        $lang = SipaPlgSession::get('_sipa__lang',get_locale());
        if( $lang == 'tr_TR' || $lang == 'tr' ) $lang = 'tr'; else $lang = 'en';

        return self::init()->getCFG($lang)->getVal($key,$par);
    }
    
    private function getCFG($file)
    {

        $this->tmp_cfg_file = GOKSEL__PLUGIN_DIR . $this->dir . $file . '.php';

        $this->file = $file;
        if(file_exists($this->tmp_cfg_file))
        {
            if( !isset($this->tmp_cfg_obj[$file]) )
                $this->tmp_cfg_obj[$file]  = (object) include($this->tmp_cfg_file);
            
            return $this;
        }
        
        return null;
    }
    
    private function setCFG($file)
    {
        try
        {
            $this->tmp_cfg_file = GOKSEL__PLUGIN_DIR . $this->dir . $file . '.php';
            
            if(file_exists($this->tmp_cfg_file))
            {
                $this->tmp_cfg_obj[$file] = (object) include($this->tmp_cfg_file);
                
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


    
    private function getVal($key, $par=array())
    {
        if(count($par))
        {
            if(isset($this->tmp_cfg_obj[$this->file]->$key))
            {
                $tmp = $this->tmp_cfg_obj[$this->file]->$key;
                foreach ( $par as $k=>$p ) {
                    $tmp = str_replace($k, $p, $tmp);
                }

                return $tmp;
            }

        }

        if(isset($this->tmp_cfg_obj[$this->file]->$key))
            return $this->tmp_cfg_obj[$this->file]->$key;
        
        return $key;
        
    }

}