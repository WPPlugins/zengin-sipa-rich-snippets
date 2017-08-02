<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 24.01.2017
 * Time: 10:12
 */

class SipaSettingsDB extends GOKSELDB
{
    var $table = 'sipa_settings_test';

    function __construct(wpdb $wpdb)
    {
        try
        {
            $this->db = $wpdb;
            $this->table = ($this->db->prefix ? $this->db->prefix : '') . SipaPlgConfig::get('plugin','table_settings');
            parent::__construct($this->db, $this->table);

        }
        catch (Exception $e)
        {

        }

    }

    function refresh_database()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE $this->table (
				 id int(11) NOT NULL AUTO_INCREMENT,
				 post_id int(11) DEFAULT '0',
				 cat_id int(11) DEFAULT '0',

				 title varchar(150) DEFAULT NULL,
				 description varchar(160) DEFAULT NULL,
				 rtype varchar(100) DEFAULT NULL,
				 author varchar(100) DEFAULT NULL,
				 lang varchar(5) DEFAULT NULL,
				 img_url varchar(255) DEFAULT NULL,
				 post_url varchar(255) DEFAULT NULL,
				 currency varchar(5) DEFAULT NULL,
				 price FLOAT DEFAULT '0',
				 avail INTEGER DEFAULT '2',
				 seller_name varchar(255) DEFAULT NULL,
				 seller_tel varchar(25) DEFAULT NULL,
				 seller_fax varchar(25) DEFAULT NULL,
				 seller_email varchar(255) DEFAULT NULL,
				 seller_street_addr varchar(255) DEFAULT NULL,
				 seller_postal_code varchar(25) DEFAULT NULL,
				 seller_city_country varchar(255) DEFAULT NULL,
				 active int(11) DEFAULT '2',

				PRIMARY KEY  (id)
				)
				CHARACTER SET utf8
				COLLATE utf8_general_ci;
				";

        dbDelta($sql);
    }

    function recordExists($post_id)
    {
        $this->select('COUNT(*)');
        $this->where('post_id', intval($post_id) );
        return $this->get_var();
    }

    /**
     * @param integer $post_id
     * @param string $output
     * @return array|null|object|void
     */
    function findFromPostID($post_id, $output='ARRAY_A')
    {
        $post_id = intval($post_id);

        $row = $this->db->get_row("select * from $this->table WHERE post_id = $post_id ORDER by id DESC LIMIT 0,1", $output);

        /*var_dump( $row ); exit;*/
        if( isset($row['id']) || isset($row->id) )
            return $row;

        return array();
    }


}