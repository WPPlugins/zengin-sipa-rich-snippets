<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 19.01.2017
 * Time: 12:26
 *
 * Frontend Main Class
 */

class SipaPlgSipa
{

    protected $sipaDB;
    protected $db;
    protected $settingsDB;
    protected $the_post;
    protected $counter=1;


    /**
     * @param wpdb $wpdb
     */
    function __construct(wpdb $wpdb)
    {
        try
        {
            if(!$this->db)
                $this->db = $wpdb;

            $this->sipaDB();

            add_filter( 'the_content', array(&$this, 'sipa_front_widget_view') );

            if(SipaPlgConfig::get('sipa','widget.active.oncat'))
                add_action( 'get_footer', array(&$this, 'sipa_front_widget_view_category') );

            add_action( 'wp_enqueue_scripts', array(&$this, 'sipaStyle') );
            add_filter( 'parse_request', array(&$this,'sipa_custom_css_request') );
        }
        catch(Exception $e)
        {
            //echo $e->getMessage();exit;
        }
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

    /**
     * @return SipaDB
     */
    function sipaDB()
    {
        if(!$this->sipaDB)
            $this->sipaDB = new SipaDB($this->db);

        return $this->sipaDB;
    }

    /**
     * @param string $r the_content()
     */
    function sipa_front_widget_view($r)
    {
        if(SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1')==null)
            SipaPlgSession::set('GOKSEL__SIPA_TOKEN_1', SipaPlgToken::makeAuto() );

        //var_dump(get_query_var('cat'));exit;

        if(is_single() || is_page())
        {

            $this->the_post = get_post();


            if(isset($this->the_post->ID))
            {
                $data = $this->settingsDB()->findFromPostID($this->the_post->ID);
                if(is_array($data) && isset($data['active']))
                {
                    $data['ratesData'] = $this->sipaDB()->getRateDataFromPost($this->the_post->ID);
                    $data['post_id'] = $this->the_post->ID;
                    $data['post_html'] = $this->the_post->post_content;
                    $data['category_id'] = $this->the_post->post_category;
                    $data['tags'] = wp_get_post_tags($this->the_post->ID);

                    if($data['active']==2)
                    {
                        if(SipaPlgConfig::get('sipa','widget.position') == 1)
                            SipaPlgView::render('front', 'widget.rater.view', $data , '', do_shortcode( $r ));
                        else
                            SipaPlgView::render('front', 'widget.rater.view', $data , do_shortcode( $r ), '');
                    }
                    else /* aktif değilse sadece içeriği basıp bırakıyoruz */
                        echo do_shortcode( $r );
                }
                else /* aktif değilse sadece içeriği basıp bırakıyoruz */
                    echo do_shortcode( $r );

            }
        }
        elseif(SipaPlgConfig::get('sipa','widget.active.onlist'))
        {
            $this->the_post = get_post();

            if(isset($this->the_post->ID))
            {
                $data = $this->settingsDB()->findFromPostID($this->the_post->ID);
                if(is_array($data) && isset($data['active']))
                {
                    $data['ratesData'] = $this->sipaDB()->getRateDataFromPost($this->the_post->ID);
                    $data['post_id'] = $this->the_post->ID;

                    if($data['active']==2)
                    {
                        if(SipaPlgConfig::get('sipa','widget.position') == 1)
                            SipaPlgView::render('front', 'widget.list.view', $data , '', sipa_plg_shrt_content(do_shortcode( $r )));
                        else
                            SipaPlgView::render('front', 'widget.list.view', $data , sipa_plg_shrt_content(do_shortcode( $r )), '');
                    }
                    else /* aktif değilse sadece içeriği basıp bırakıyoruz */
                        echo sipa_plg_shrt_content(do_shortcode( $r ));
                }
                else /* aktif değilse sadece içeriği basıp bırakıyoruz */
                    echo sipa_plg_shrt_content(do_shortcode( $r ));

            }
        }
        else /* aktif değilse sadece içeriği basıp bırakıyoruz */
            echo sipa_plg_shrt_content(do_shortcode( $r ));


    }

    /**
     * @param string $r the_content()
     */
    function sipa_front_widget_view_category($r)
    {
        if(SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1')==null)
            SipaPlgSession::set('GOKSEL__SIPA_TOKEN_1', SipaPlgToken::makeAuto() );

        if( intval(get_query_var('cat'))>0 )
        {
            if(SipaPlgConfig::get('sipa','widget.active.oncat'))
            {
                $data['cat_id'] = intval(get_query_var('cat'));
                $data['ratesData'] = $this->sipaDB()->getRateDataFromCat(intval(get_query_var('cat')));

                //ob_get_clean();
                //var_dump($data);exit;
                if(SipaPlgConfig::get('sipa','widget.active.oncat.show'))
                {
                    if(SipaPlgConfig::get('sipa','widget.position') == 1)
                        SipaPlgView::render('front', 'widget.list.category.view', $data , '', ($r));
                    else
                        SipaPlgView::render('front', 'widget.list.category.view', $data , ($r), '');
                }
                else
                    SipaPlgView::render('front', 'onlycode.list.category.view', $data , ($r), '');
            }

        }

    }

    function sipaStyle()
    {
        $version = SipaPlgConfig::get('plugin','sipa.version');
        wp_enqueue_style( 'sipa-default-style', GOKSEL__PLUGIN_DIR_ASSETS_CSS . '/' . 'sipa-rating-widget.css', array(), $version );

        $custom_css = SipaPlgConfig::get( 'sipa','customcss' );
        if(strlen($custom_css))
        {
            wp_register_style( 'sipa-custom-css', get_bloginfo( 'url' ) . '/?sipa_custom_css=css', array(), $version );
            wp_enqueue_style( 'sipa-custom-css' );

        }

        if(SipaPlgConfig::get('sipa','jquery')==true)
            wp_enqueue_script( 'sipa-jquery', 'https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.js', array(), false, true );

        wp_enqueue_script( 'sipa-default-js', GOKSEL__PLUGIN_DIR_ASSETS_JS . '/' . 'sipa-rating-widget.js', array(), $version, true );
    }

    function sipa_custom_css_request($wp=null)
    {
        if(SipaPlgInput::rGet('sipa_custom_css') === 'css')
        {
            ob_get_clean();
            header( 'Content-Type: text/css' );
            die( SipaPlgConfig::get( 'sipa','customcss' ) );
        }
    }


}