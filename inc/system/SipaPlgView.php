<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 20.01.2017
 * Time: 10:34
 */

class SipaPlgView
{
    static private $dir = 'view';

    /**
     * @param string $________dir view/subdirectory
     * @param string $________view view file
     * @param array $_______data passing variables
     * @param string $before
     * @param string $after
     */
    static public function render($________dir='', $________view, $_______data=array(), $before='', $after='' )
    {
        $________ddir = ( ($________dir != '') ? (DIRECTORY_SEPARATOR . $________dir . DIRECTORY_SEPARATOR) : DIRECTORY_SEPARATOR);

        $________file = GOKSEL__PLUGIN_DIR . self::$dir . $________ddir . $________view . '.php';
        if(count($_______data))
            extract($_______data);

        if(file_exists( $________file )) {
            echo $before;
            include $________file;
            echo $after;
        }
    }


}