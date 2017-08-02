<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 24.01.2017
 * Time: 09:03
 */
class SipaPlgInput {

    public static function gGet($key, $default=null, $stript=true)
    {
        if(isset($_GET[$key]))
        {
            if($stript)
                return (htmlentities ($_GET[$key]));

            return $_GET[$key];
        }

        return $default;
    }

    public static function pGet($key, $default=null, $stript=true)
    {
        if(isset($_POST[$key]))
        {
            if($stript)
                return (htmlentities ($_POST[$key]));

            return $_POST[$key];
        }
        return $default;
    }

    public static function rGet($key, $default=null, $stript=true)
    {
        if(isset($_REQUEST[$key]))
        {
            if($stript)
                return (htmlentities ($_REQUEST[$key]) );

            return $_REQUEST[$key];
        }
        return $default;
    }


}