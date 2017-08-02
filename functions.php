<?php
/**
 * Created by PhpStorm.
 * User: Goksel
 * Date: 24.01.2017
 * Time: 13:23
 */

function sipa_plg_print($string='')
{
    echo stripslashes(html_entity_decode($string) );
}

function sipa_plg_asset($f='', $plugin='zengin-sipa')
{
    echo plugins_url( ( $plugin ? $plugin . DIRECTORY_SEPARATOR : '') . $f,$plugin);
}

function sipa_plg_pa($val='')
{
    echo json_encode( $val );
}

function sipa_plg_excerpt($limit, $dots='...')
{
    $excerpt = explode(' ', get_the_excerpt(), $limit);
    if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt). $dots;
    } else {
        $excerpt = implode(" ",$excerpt);
    }
    $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
    return ($excerpt);
}

function sipa_plg_shrt_content($content='', $limit=30, $dots='...')
{
    $excerpt = explode(' ',  strip_tags($content), $limit);
    if (count($excerpt)>=$limit) {
        array_pop($excerpt);
        $excerpt = implode(" ",$excerpt). $dots;
    } else {
        $excerpt = implode(" ",$excerpt);
    }
    $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
    return ($excerpt);
}

function sipa_plg_shrt_cnt($content='', $limitLength=160, $dots='...')
{

    $replace = array(
        '/<!--[^\[](.*?)[^\]]-->/s' => '',
        "/<\?php/"                  => '<?php ',
        "/\n([\S])/"                => '$1',
        "/\r/"                      => '',
        "/\n/"                      => ' ',
        "/\t/"                      => '',
        "/ +/"                      => ' ',
    );


    $excerpt = mb_strlen($content) > $limitLength ? mb_substr($content,0,$limitLength) : $content;
    $excerpt = preg_replace(array_keys($replace), array_values($replace), $excerpt);

    return htmlspecialchars_decode(strip_tags($excerpt));
}

function sipa_plg_domain($url)
{
    $pieces = parse_url($url);
    $domain = isset($pieces['host']) ? $pieces['host'] : '';
    if (preg_match('/(?P<domain>[a-z0-9][a-z0-9\-]{1,63}\.[a-z\.]{2,6})$/i', $domain, $regs)) {
        return $regs['domain'];
    }
    return $url;
}

function sipa_plg_isPost()
{
    if( isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post' ) return true;

    return false;
}

function sipa_plg_isGet()
{
    if( isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'get' ) return true;

    return false;
}

function sipa_plg_asbool($val='')
{
    if(in_array($val, array('true',1,'1'))) return true;

    //if(in_array($val, array('false',false,0,'0')))
        return false;

}

function sipa_plg_redirect($url, $statusCode = 303)
{
    ob_get_clean();
    header('Location: ' . $url, true, $statusCode);
    die();
}

function sipa_plg_redirectJS($url)
{
    echo ("
    <script>window.location = \"$url\"</script>
    ");
    exit;
}