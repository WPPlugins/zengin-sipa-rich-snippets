<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 26.01.2017
 * Time: 09:26
 */

class SipaPlgToken
{
    static public function makeAuto()
    {
        return md5('' . uniqid('', true) );
    }
}