<?php
/*
Plugin Name: Zengin Sipa Rich Snippets

Description: Rich Snippets
Version: 1.0.3
Author: ilhan goksel idin
Author URI: https://plus.google.com/+ilhang%C3%B6ksel%C4%B0D%C4%B0N
License: GNU
*/

if ( ! function_exists( 'plugin_dir_path' ) ) die('You can not send direct request');

session_start();

define( 'GOKSEL__PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'GOKSEL__PLUGIN_DIR_INC', plugin_dir_path( __FILE__ ) . 'inc'  );
define( 'GOKSEL__PLUGIN_DIR_INC_SYSTEM', GOKSEL__PLUGIN_DIR_INC . DIRECTORY_SEPARATOR . 'system'  );

define( 'GOKSEL__PLUGIN_DIR_ASSETS', plugins_url() . '/' . 'zengin-sipa-rich-snippets' . '/' . 'assets'  );
define( 'GOKSEL__PLUGIN_DIR_ASSETS_JS', GOKSEL__PLUGIN_DIR_ASSETS . '/' . 'js'  );
define( 'GOKSEL__PLUGIN_DIR_ASSETS_CSS', GOKSEL__PLUGIN_DIR_ASSETS . '/' . 'css'  );
define( 'GOKSEL__PLUGIN_DIR_ASSETS_IMG', GOKSEL__PLUGIN_DIR_ASSETS . '/' . 'img'  );
define( 'GOKSEL__ADMIN_URL', get_admin_url() );
define( 'GOKSEL__SIPA_TOKEN', md5('' . uniqid('', true)   ) );
define( 'GOKSEL__SIPA_WP_LOCALE', get_locale() );

global $wpdb;

try
{
    $splName = 'goksel_autoloader_' . md5('sipa_autoloader');

    require_once GOKSEL__PLUGIN_DIR . 'functions.php';

    $$splName = function ( $class_name ) {
        try
        {
            $class_file = GOKSEL__PLUGIN_DIR_INC . DIRECTORY_SEPARATOR . $class_name . '.php';
            $class_file_system = GOKSEL__PLUGIN_DIR_INC_SYSTEM . DIRECTORY_SEPARATOR . $class_name . '.php';

            if( file_exists($class_file) )
                require_once $class_file;
            elseif( file_exists($class_file_system) )
            {
                require_once $class_file_system;
            }
            else
            {

            }

        }
        catch(Exception $e)
        {
        }

    };
    spl_autoload_register( $$splName );

    if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {

        $sipaadmin = new SipaPlgSipaAdmin($wpdb);

    } else {

        $sipafront = new SipaPlgSipa($wpdb);

    }



}
catch (Exception $e)
{
    die('have a problem at Sipa Plugin!');
}


try
{
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
        $sipaAjax = new SipaPlgSipaAjax($wpdb);

        add_filter( 'wp_ajax_sipa_do_rate', function() use($sipaAjax){
            $sipaAjax->doRate();
        } );

        add_filter( 'wp_ajax_nopriv_sipa_do_rate', function() use($sipaAjax){
            $sipaAjax->doRate();
        } );
    }

}
catch(Exception $e)
{
    die($e->getTraceAsString());
}
