<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 19.01.2017
 * Time: 12:25
 *
 * Backend Main Class
 */

class SipaPlgSipaAdmin
{

    protected $adminDB;
    protected $settingsDB;
    protected $db;
    protected $lang;

    function __construct(wpdb $wpdb)
    {
        try
        {
            if(!$this->db)
                $this->db = $wpdb;

            $this->adminDB();
            $this->settingsDB();
            $this->initClass();

            $this->lang = SipaPlgConfig::get('sipa', 'lang');
        }
        catch(Exception $e)
        {

        }
    }

    /**
     * @return SipaAdminDB
     */
    function adminDB()
    {
        if(!$this->adminDB)
            $this->adminDB = new SipaAdminDB($this->db);

        return $this->adminDB;
    }

    /**
     * @return SipaSettingsDB
     */
    function settingsDB()
    {
        if(!$this->settingsDB)
            $this->settingsDB = new SipaSettingsDB($this->db);

        return $this->settingsDB;
    }

    private function initClass()
    {
        add_action('init', array(&$this, 'init'));
    }

    public function init()
    {
        //$this->load_styles();
        add_action('admin_menu', array(&$this, 'sipa_admin_menu'), 7);

        add_action( 'post.php', array( &$this, 'sipa_admin_widget_action' ), 1, 2 );
        //add_action('edit_form_advanced',array( &$this, 'sipa_admin_widget_view' ), 1,5);
        add_action('edit_form_after_editor',array( &$this, 'sipa_admin_widget_view' ), 1,5);
        add_action( 'save_post', array( &$this, 'sipa_admin_widget_action' ), 10, 9 );

        add_action('wp_loaded', array( &$this, 'sipa_admin_widget_action') );

        add_action('update_cache', array(&$this, 'cron_update_cache'));
        if ( ! wp_next_scheduled( 'update_cache' ) ) {
            wp_schedule_event( time(), 'hourly', 'update_cache' );
        }
    }


    function sipa_admin_menu()
    {
        add_menu_page(SipaPlgLang::get('page.settings.title',array(),$this->lang ), SipaPlgLang::get('menu.main',array(),$this->lang ), 'manage_options', 'sipa_admin_settings', array( &$this, 'sipa_admin_settings_view' ), 'dashicons-star-half', 7);
        add_submenu_page( 'sipa_admin_settings', SipaPlgLang::get('page.rates.title',array()), SipaPlgLang::get('menu.list',array() ), 'manage_options', 'sipa_admin_rates', array( &$this, 'sipa_admin_rates_view' ));
        add_submenu_page( 'sipa_admin_settings', SipaPlgLang::get('menu.rater',array()), SipaPlgLang::get('menu.rater',array()), 'manage_options', 'sipa_admin_make_rate', array( &$this, 'sipa_admin_make_rate' ));
    }

    function sipa_admin_settings_view()
    {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        //SipaPlgLang::setLang(SipaPlgConfig::get('sipa','lang'));

        $___data = array();
        $___data['err'] = '';
        $___data['suc'] = '';

        if(sipa_plg_isPost())
        {
            $___data['inputs'] = array(

                'rating.approve.auto' => sipa_plg_asbool(SipaPlgInput::rGet('auto-aprove')),
                'rating.approve.auto.min' => intval(SipaPlgInput::rGet('auto-aprove-min')),
                'rating.timeout.ip' => intval(SipaPlgInput::rGet('time-out')),
                'lang' => SipaPlgInput::rGet('lang'),

                'widget.position' => intval(SipaPlgInput::rGet('position')),

                'widget.active.onlist' => sipa_plg_asbool(SipaPlgInput::rGet('onlist')),
                'widget.active.oncat' => sipa_plg_asbool(SipaPlgInput::rGet('oncat')),
                'widget.active.oncat.show' => sipa_plg_asbool(SipaPlgInput::rGet('oncatshow')),
                'jquery' => sipa_plg_asbool(SipaPlgInput::rGet('jquery')),
                'customcss' => SipaPlgInput::rGet('customcss'),

            );
            $vl = SipaPlgValidator::validate($___data['inputs'],array(
                'rating.approve.auto' => array(
                    'required',
                    'boolean'
                ),
                'rating.approve.auto.min' => array(
                    'required',
                    'integer',
                    'min_val(1)',
                    'max_val(5)',
                ),
                'rating.timeout.ip' => array(
                    'required',
                    'integer',
                    'min_val(30)',
                    'max_val(31536000)',
                ),
                'lang' => array(
                    'required',
                    'min_length(2)',
                    'max_length(6)',
                ),
                'widget.position' => array(
                    'required',
                    'integer',
                    'min_val(1)',
                    'max_val(2)',
                ),
                'widget.active.onlist' => array(
                    'required',
                    'boolean'
                ),
                'widget.active.oncat' => array(
                    'required',
                    'boolean'
                ),
                'widget.active.oncat.show' => array(
                    'required',
                    'boolean'
                ),
                'jquery' => array(
                    'required',
                    'boolean'
                ),
                'customcss' => array(
                    'max_length(1024)',
                ),
            ), array(
                'rating.approve.auto' => SipaPlgLang::get('rating.approve.auto'),
                'rating.approve.auto.min' => SipaPlgLang::get('rating.approve.auto.min'),
                'rating.timeout.ip' => SipaPlgLang::get('rating.timeout.ip'),
                'lang' => SipaPlgLang::get('lang.plugin'),
                'widget.position' => SipaPlgLang::get('widget.position'),
                'widget.active.onlist' => SipaPlgLang::get('widget.active.onlist'),
                'widget.active.oncat' => SipaPlgLang::get('widget.active.oncat'),
                'widget.active.oncatshow' => SipaPlgLang::get('widget.active.oncatshow'),
                'jquery' => SipaPlgLang::get('jquery'),
                'customcss' => SipaPlgLang::get('widget.customcss'),
            ));

            if ($vl->isSuccess() == true) {
                $___data['errc'] = SipaPlgConfig::setAll('sipa',$___data['inputs']);
            } else {
                $___data['err'] = ($vl->getErrors());
            }

        }

        $___data['data'] = SipaPlgConfig::getCfgAll('sipa');
        $this->lang = $___data['data']['lang'];
        SipaPlgSession::set('_sipa__lang',$___data['data']['lang']);
        $___data['lang'] = $___data['data']['lang'] ;
        /*var_dump(
        array(
            SipaPlgLang::get('yes',array(), $___data['lang']),
            $___data['lang'],
        )
        );exit;*/

        SipaPlgView::render('admin', 'settings.view', $___data );
    }

    function sipa_admin_widget_view()
    {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        $data = $this->settingsDB()->findFromPostID(SipaPlgInput::gGet('post'));
        SipaPlgView::render('admin', 'admin.widget.view', $data );
    }

    function sipa_admin_make_rate()
    {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        $___data = array(
            'lang' => $this->lang,
        );

        if(sipa_plg_isPost())
        {

            $post_id = SipaPlgInput::rGet('post_id');
            $rate = intval(SipaPlgInput::rGet('rate',5));
            $adet = intval(SipaPlgInput::rGet('adet',1));
            if($rate>5) $rate = 5;
            if($rate<1) $rate = 1;

            if($adet>200) $adet = 200;
            if($adet<1) $adet = 1;


            if (intval($post_id)) {

                $ipCli = SipaPlgServer::getClientIP();

                $postSt = $this->settingsDB()->findFromPostID($post_id);

                if( isset($postSt['post_id']) )
                {
                    $link = get_permalink($postSt['post_id']);

                    for($i=0;$i<$adet;$i++){
                        $this->adminDB()->insertRecord(array(
                            'date_time' => date('Y-m-d H:i:s'),
                            'reviewer_name' => 'admin',
                            'reviewer_email' => '',
                            'reviewer_tel' => '',
                            'review_title' => '',
                            'review_rating' => intval($rate),
                            'review_text' => '',
                            'review_status' => 2,
                            'reviewer_ip' => $ipCli,
                            'refer' => $link,
                            'agent' => SipaPlgServer::get('HTTP_USER_AGENT'),
                            'post_id' => intval($postSt['post_id']),
                            'review_category' => '',
                            'reviewer_image' => '',
                            'reviewer_id' => 0,
                            'review_session_id' => session_id(),
                            'review_time' => time(),
                        ));
                    }


                    $___data['err'] = array(SipaPlgLang::get('rate.do.ok'));
                }

            }

        }

        SipaPlgView::render('admin', 'rater.view', $___data );
    }

    function sipa_admin_rates_view()
    {
        if ( !current_user_can( 'manage_options' ) )  {
            wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
        }

        if(sipa_plg_isGet())
        {
            $id = SipaPlgInput::rGet('id');
            $st = SipaPlgInput::rGet('status');
            $listType = intval(SipaPlgInput::rGet('list',3) );
            $page = intval(SipaPlgInput::rGet('p',1));
            $table = $this->adminDB()->getTableName();

            if($id!==null && $st!==null)
            {
                $st = intval($st);
                $id = intval($id);
                if($st>=0 && $st<=2)
                    $this->db->update($table, array('review_status'=>$st), array('id'=>$id));

                sipa_plg_redirectJS("admin.php?page=sipa_admin_rates&list=$listType&p=$page");
            }
            else
            {
                $listType = $listType>0 && $listType <4 ? $listType : 1;
                $pageNo = $page>0 ? $page-1 : 0;
                $limit = 10;
                $limitPage = $limit * $pageNo;

                if($listType===3) $where = "where review_status > 0 "; else $where = "where review_status = $listType ";

                /* sayfalama için*/
                $count = $this->db->get_var("select count(*) from $table " . $where);

                $where .= "order by id DESC limit $limitPage, $limit";
                $query = "select * from $table " . $where;

                $rows = $this->db->get_results($query);

                $allPages = ceil($count / $limit);

                /* adetler için */
                $countAll = $this->db->get_var("select count(*) from $table where review_status > 0 ");
                $countWait = $this->db->get_var("select count(*) from $table where review_status = 1 ");
                $countAppr = $this->db->get_var("select count(*) from $table where review_status = 2 ");


                SipaPlgView::render('admin', 'list.view', array(
                    'rows' => $rows,
                    'allPages' => $allPages,
                    'count' => $count,
                    'adet'=>array(
                        'all'=>$countAll,
                        'wait'=>$countWait,
                        'appr' => $countAppr,
                    ),
                    'page' => $page,
                    'pageNo' => $pageNo,
                    'listType' => $listType,
                ) );
            }

        }


    }

    function sipa_admin_widget_action()
    {
        if(SipaPlgInput::pGet('_g_sipa-doaction') == 'edit')
        {
            $tmp_id = $this->settingsDB()->recordExists(SipaPlgInput::pGet('post_ID'));
            $cat = get_the_category(SipaPlgInput::pGet('post_ID'));
            $catID = isset($cat[0]) && isset($cat[0]->cat_ID) ? $cat[0]->cat_ID : 0;
            $data = array(
                'post_id' => SipaPlgInput::pGet('post_ID'),
                'cat_id' => $catID,
                'rtype' => SipaPlgInput::pGet('_g_sipa-type',''),
                'title' => SipaPlgInput::pGet('_g_sipa-headline',''),
                'description' => SipaPlgInput::pGet('_g_sipa-description',''),
                'author' => SipaPlgInput::pGet('_g_sipa-author',''),
                'lang' => SipaPlgInput::pGet('_g_sipa-inlanguage',''),
                'img_url' => SipaPlgInput::pGet('_g_sipa-image',''),
                'post_url' => SipaPlgInput::pGet('_g_sipa-url',''),
                'currency' => SipaPlgInput::pGet('_g_sipa-pricecurrency',''),
                'price' => SipaPlgInput::pGet('_g_sipa-price'),
                'avail' => SipaPlgInput::pGet('_g_sipa-avail'),
                'seller_name' => SipaPlgInput::pGet('_g_sipa-seller_name',''),
                'seller_tel' => SipaPlgInput::pGet('_g_sipa-tel',''),
                'seller_fax' => SipaPlgInput::pGet('_g_sipa-fax',''),
                'seller_email' => SipaPlgInput::pGet('_g_sipa-email',''),
                'seller_street_addr' => SipaPlgInput::pGet('_g_sipa-seller_street_addr',''),
                'seller_postal_code' => SipaPlgInput::pGet('_g_sipa-seller_postal_code',''),
                'seller_city_country' => SipaPlgInput::pGet('_g_sipa-seller_city_country',''),
                'active' => SipaPlgInput::pGet('_g_sipa-active',1),
            );

            if(intval($tmp_id)>0)
                $this->db->update($this->settingsDB()->table, $data, array('post_id'=>SipaPlgInput::pGet('post_ID')));
            else
                $this->db->insert($this->settingsDB()->table, $data);

        }
    }


}