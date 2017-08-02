<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 26.01.2017
 * Time: 09:03
 */
class SipaPlgSipaAjax
{

    protected $sipaDB;
    protected $db;
    protected $settingsDB;
    protected $the_post;


    /**
     * @param wpdb $wpdb
     */
    function __construct( wpdb $wpdb )
    {
        try {
            if ( !$this->db )
                $this->db = $wpdb;

            $this->sipaDB();
            $this->settingsDB();

        } catch ( Exception $e ) {
            echo $e->getMessage();exit;
        }
    }

    function doRate()
    {
        $ipCli = SipaPlgServer::getClientIP();

        if( !sipa_plg_isPost() || !inet_pton($ipCli))
            die('Wrong request type!');

        $tknSrv = SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1');
        $tknCli = SipaPlgInput::rGet('token');
        $rate = intval(SipaPlgInput::rGet('rate',1));
        if($rate>5)$rate=5;
        if($rate<1)$rate=1;

        $idFromUrl = intval( url_to_postid(SipaPlgServer::get('HTTP_REFERER')) );

        if(
            ( $tknSrv<>$tknCli ) ||
            (intval(SipaPlgInput::rGet('post_id',-12)) <> $idFromUrl)  ||
            ( 0 == $idFromUrl )
        )
        {
            sipa_plg_pa(array(
                'status' => false,
                'msg' => '<i>' . SipaPlgLang::get('token.wrong') . '</i>',
                'token_srv' => SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1'),
            ));
            exit;
        }

        $idFromUrl = intval( url_to_postid(SipaPlgServer::get('HTTP_REFERER')) );

        /*var_dump($this->getCliRateStatus($rate)) ;exit;*/
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX )
        {

            $postSt = $this->settingsDB()->findFromPostID(SipaPlgInput::rGet('post_id'));

            /*var_dump($postSt);exit;*/
            if(isset($postSt['post_id']) && $postSt['active']==2)
            {
                SipaPlgSession::set('GOKSEL__SIPA_TOKEN_1', SipaPlgToken::makeAuto());

                $oldData = $this->sipaDB()->getDataFromIP(SipaPlgInput::rGet('post_id'));


                if(count($oldData) && ( ( $oldData['review_time'] ) >=  time() - intval(SipaPlgConfig::get('sipa','rating.timeout.ip'))  ) )
                {

                    if( $oldData['review_session_id'] == session_id())
                    {
                        $this->sipaDB()->updateRecord($oldData['id'],array(
                            'date_time' => date('Y-m-d H:i:s'),
                            'reviewer_name' => 'guest',
                            'reviewer_email' => '',
                            'reviewer_tel' => '',
                            'review_title' => '',
                            'review_rating' => $rate,
                            'review_text' => '',
                            'review_status' => $this->getCliRateStatus($rate),
                            'reviewer_ip' => $ipCli,
                            'refer' => SipaPlgServer::get('HTTP_REFERER'),
                            'agent' => SipaPlgServer::get('HTTP_USER_AGENT'),
                            'post_id' => $oldData['post_id'],
                            'review_category' => '',
                            'reviewer_image' => '',
                            'reviewer_id' => 0,
                            'review_session_id' => session_id(),
                            /*'review_time' => $oldData['review_time'],*/
                        ));


                        sipa_plg_pa(array(
                            'status' => true,
                            'msg' => '<i>' . SipaPlgLang::get('rate.do.update') . '</i>',
                            'token_srv' => SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1'),
                        ));exit;
                    }
                    else
                    {
                        sipa_plg_pa(array(
                            'status' => false,
                            'msg' => '<i>' . SipaPlgLang::get('rate.do.exists') . '</i>',
                            'token_srv' => SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1'),
                            'rate' => $oldData['review_rating'],
                        ));exit;
                    }

                }
                else
                {
                    $this->sipaDB()->insertRecord(array(
                        'date_time' => date('Y-m-d H:i:s'),
                        'reviewer_name' => 'guest',
                        'reviewer_email' => '',
                        'reviewer_tel' => '',
                        'review_title' => '',
                        'review_rating' => $rate,
                        'review_text' => '',
                        'review_status' => $this->getCliRateStatus($rate),
                        'reviewer_ip' => $ipCli,
                        'refer' => SipaPlgServer::get('HTTP_REFERER'),
                        'agent' => SipaPlgServer::get('HTTP_USER_AGENT'),
                        'post_id' => $postSt['post_id'],
                        'review_category' => '',
                        'reviewer_image' => '',
                        'reviewer_id' => 0,
                        'review_session_id' => session_id(),
                        'review_time' => time(),
                    ));

                    sipa_plg_pa(array(
                        'status' => true,
                        'msg' => '<i>' . SipaPlgLang::get('rate.do.ok') . '</i>',
                        'token_srv' => SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1'),

                    ));exit;

                }
            }

            sipa_plg_pa(array(
                'status' => false,
                'msg' => '<i>' . SipaPlgLang::get('post.wrong') . ' 2</i>',
                'token_srv' => SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1'),
                'p' => $postSt,
            ));exit;

        }

    }

    private function getCliRateStatus($rate=false)
    {
        $isAuto = SipaPlgConfig::get('sipa', 'rating.approve.auto');
        $minAppr = SipaPlgConfig::get('sipa', 'rating.approve.auto.min');

        if($rate===false) $rate = intval(SipaPlgInput::rGet('rate',0));
        if($isAuto==true)
        {
            if($rate>=$minAppr)
                return 2;
        }

        return 1;
    }

    /**
     * @return SipaSettingsDB
     */
    function settingsDB()
    {
        if ( !$this->settingsDB )
            $this->settingsDB = new SipaSettingsDB( $this->db );

        return $this->settingsDB;
    }

    /**
     * @return SipaDB
     */
    function sipaDB()
    {
        if ( !$this->sipaDB )
            $this->sipaDB = new SipaDB( $this->db );

        return $this->sipaDB;
    }
}