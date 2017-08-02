<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 24.01.2017
 * Time: 12:44
 */

class SipaPlgSession
{
    private static $_singleton;

    private static function init() {
        if(is_null (self::$_singleton) ) {
            self::$_singleton = new self;
        }
        return self::$_singleton;
    }

    /**
     * @param $key
     * @param null $default
     * @return mixed
     */
    public static function get($key, $default=null)
    {
        return self::init()->getVal($key, $default);
    }

    /**
     * @param $key
     * @return bool|null
     */
    public static function remove($key)
    {
        return self::init()->removeVal($key);
    }

    /**
     * @param $key
     * @param $val
     * @return bool
     */
    public static function set($key, $val)
    {
        return self::init()->setVal($key, $val);
    }

    /**
     * @param $key
     * @param null $default
     * @return null
     */
    protected function getVal($key, $default=null)
    {
        if(isset($_SESSION[$key])) return $_SESSION[$key];

        if($default!=null)
            self::init()->setVal($key, $default);

        return $default;
    }

    protected function setVal($key, $val)
    {
        try
        {
            $_SESSION[$key] = $val;
        }
        catch(Exception $e)
        {
            return false;
        }

        return true;
    }

    protected function removeVal($key)
    {
        try
        {
            if(isset($_SESSION[$key]))
            {
                unset( $_SESSION[$key] );
                return true;
            }
        }
        catch(Exception $e)
        {
            return false;
        }

        return null;
    }
}