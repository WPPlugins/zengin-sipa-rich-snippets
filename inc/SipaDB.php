<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 18.01.2017
 * Time: 12:30
 *
 * Class for Frontend DB
 */

class SipaDB
{
    protected $db ;
    protected $table = 'sipa_rating_test';


    function __construct(wpdb $wpdb)
    {
        try
        {
            $this->db = $wpdb;
            $this->table = ($this->db->prefix ? $this->db->prefix : '') . SipaPlgConfig::get('plugin','table');
        }
        catch(Exception $e)
        {

        }
    }

    function getRateDataFromPost($post_id)
    {
        $post_id = intval($post_id);
        $whereStatement = " where post_id = $post_id and review_status=2";

        $approvedReviewsCount = intval($this->db->get_var("SELECT COUNT(*) FROM $this->table " . $whereStatement));
        $averageRating = 0;
        $minRate = 0;
        $maxRate = 0;
        if ($approvedReviewsCount != 0) {
            $averageRating = $this->db->get_var("SELECT AVG(review_rating) FROM $this->table " . $whereStatement);
            $averageRating = floor(10*floatval($averageRating))/10;
            $mmr = $this->db->get_row("SELECT min(review_rating) as minRate, max(review_rating) as maxRate FROM $this->table " . $whereStatement);
            $minRate = $mmr->minRate;
            $maxRate = $mmr->maxRate;
        }

        return array(
            'count' => $approvedReviewsCount,
            'avg' => $averageRating,
            'min' => $minRate *1,
            'max' => $maxRate *1,
        );
    }

    function getRateDataFromCat($cat_id=1)
    {
        //$cat_id = intval($cat_id);
        //$cat_id = intval(1);
        $settings_table = $this->db->prefix . SipaPlgConfig::get('plugin', 'table_settings');
        $whereStatement = " where rr.review_status=2 and ss.cat_id= $cat_id and ss.active = 2 ";
        $joinSettings = " join $this->table as rr on ss.post_id = rr.post_id ";

        //$approvedReviewsCount = $this->db->get_var("select count(*) as adet from $settings_table as ss join $this->table as rr on ss.post_id = rr.post_id where rr.review_status=2 and ss.cat_id= $cat_id and ss.active = 2");

        //var_dump($approvedReviewsCount);exit;
        $approvedReviewsCount = intval($this->db->get_var("select count(*) as adet from $settings_table as ss " . $joinSettings . $whereStatement));
        $averageRating = 0;
        $minRate = 0;
        $maxRate = 0;
        if ($approvedReviewsCount != 0) {
            $averageRating = $this->db->get_var("SELECT AVG(rr.review_rating) from $settings_table as ss " . $joinSettings  . $whereStatement);
            $averageRating = floor(10*floatval($averageRating))/10;
            $mmr = $this->db->get_row("SELECT min(rr.review_rating) as minRate, max(rr.review_rating) as maxRate from $settings_table as ss " . $joinSettings  . $whereStatement);
            $minRate = $mmr->minRate;
            $maxRate = $mmr->maxRate;
        }

        return array(
            'count' => $approvedReviewsCount,
            'avg' => $averageRating,
            'min' => $minRate *1,
            'max' => $maxRate *1,
        );
    }

    function getDataFromIP($post_id, $ip='')
    {
        if(!$ip) $ip = SipaPlgServer::getClientIP();

        $ip = $this->db->_escape($ip);
        $post_id = intval($post_id);


        $whereStatement = " where post_id = $post_id and reviewer_ip = '$ip' and review_status > 0 order by id desc";

        //$row = $this->db->get_var("SELECT COUNT(*) FROM $this->table " . $whereStatement);
        $row = $this->db->get_row("SELECT * FROM $this->table " . $whereStatement , 'ARRAY_A');

        //var_dump($row);exit;
        if(isset($row['id']))
            return $row;

        return array();
    }

    function insertRecord($data)
    {
        if($this->db->insert($this->table, $data) )
            return $this->db->insert_id;

        return 0;
    }

    function updateRecord($id,$data)
    {
        return $this->db->update($this->table, $data, array('id'=>$id));
    }

}