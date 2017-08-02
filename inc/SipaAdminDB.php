<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 18.01.2017
 * Time: 12:30
 *
 * Class for Backend Admin DB
 */

class SipaAdminDB extends GOKSELDB
{
    var $table = 'sipa_rating_test';
    var $table_settings = 'sipa_settings_test';

    function __construct(wpdb $wpdb)
    {
        try
		{
			if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) )
			{
				$this->db = $wpdb;
				$this->table = ($this->db->prefix ? $this->db->prefix : '') . SipaPlgConfig::get('plugin','table');
				parent::__construct($this->db, $this->table);
				$this->checkConfig();

			} else {
				ob_get_clean();
				die('Admin required!');
			}
		}
		catch (Exception $e)
		{
			ob_get_clean();
			die('Admin required!');
		}

    }

	function checkConfig()
	{
		$default = array(
			'rating.approve.auto' => true,
			'rating.approve.auto.min' => 3,
			'rating.timeout.ip' => 86400,
			'lang' => get_locale(),
			'widget.position' => 2,
			'widget.active.onlist' => false,
			'widget.active.oncat' => true,
			'widget.active.oncat.show' => false,
			'jquery' => true,
			'customcss' => '._g_sipa-rating-cat {
    background: transparent;
    display: table;
    max-width: 500px;
    float: none;
    width: 100%;
    padding: 5px;
    overflow: auto;
    margin: 0px auto;
    position: relative;
    padding-bottom: 5px;
}',
		);

		SipaPlgConfig::setCfgDefault('sipa', $default);
	}

    function refresh_database()
    {
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $sql = "CREATE TABLE $this->table (
				 id int(11) NOT NULL AUTO_INCREMENT,
				 date_time datetime NOT NULL,
				 reviewer_name varchar(100) DEFAULT NULL,
				 reviewer_email varchar(150) DEFAULT NULL,
				 reviewer_tel varchar(150) DEFAULT NULL,
				 review_title varchar(100) DEFAULT NULL,
				 review_rating tinyint(2) DEFAULT '0',
				 review_text text,
				 review_status tinyint(1) DEFAULT '0',
				 reviewer_ip varchar(255) DEFAULT NULL,
				 refer TEXT NULL,
				 agent TEXT NULL,
				 post_id int(11) DEFAULT '0',
				 review_category varchar(255) DEFAULT 'none',
				 reviewer_image varchar(255) DEFAULT NULL,
				 reviewer_id varchar(11) DEFAULT NULL,
				 review_session_id varchar(255) DEFAULT '',
				 review_time int(11) DEFAULT '0',

				PRIMARY KEY  (id)
				)
				CHARACTER SET utf8
				COLLATE utf8_general_ci;
				";

        dbDelta($sql);
    }

	function insertRecord($data)
	{
		if($this->db->insert($this->table, $data) )
			return $this->db->insert_id;

		return 0;
	}

	/**
	 * @return string table name
     */
	function getTableName()
	{
		return $this->table;
	}


}