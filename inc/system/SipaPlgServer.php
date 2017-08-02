<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 26.01.2017
 * Time: 10:27
 */

class SipaPlgServer
{
    private static $_singleton;

    private static function init() {
        if(is_null (self::$_singleton) ) {
            self::$_singleton = new self;
        }
        return self::$_singleton;
    }

    public static function get($key)
    {
        return self::init()->getVal($key);
    }

    public static function getClientIP($cloudflare=true)
    {
        return self::init()->__getClientIP($cloudflare);
    }

    protected function getVal($key)
    {
        try
        {
            if( (trim($key)!='' || $key!=null ) && isset($_SERVER[$key]))
            {
                return  $_SERVER[$key];
            }
        }
        catch(Exception $e)
        {
            return false;
        }

        return null;
    }

    protected function __getClientIP($cloudflare=true)
    {
        try
        {
            $cf = $this->getVal('HTTP_CF_CONNECTING_IP');
            $ip = $this->getVal('REMOTE_ADDR');
            return ($cloudflare && $cf) ? $cf : $ip;
        }
        catch(Exception $e)
        {
            die($e->getMessage());
        }

    }




}