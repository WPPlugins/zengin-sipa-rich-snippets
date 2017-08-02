<?php if(!defined('GOKSEL__SIPA_TOKEN'))  die(''); ?>
<div class="_g_sipa-rating _g_sipa-rating-list" data-action="sipa_do_rate" data-url="<?php sipa_plg_print ( admin_url('admin-ajax.php') ) ?>" data-post="<?php sipa_plg_print($post_id) ?>" data-avg="<?php sipa_plg_print( number_format( $ratesData['avg'] ,2) ) ?>" data-tkn="<?php sipa_plg_print( SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1')) ?>">
    <strong class="_g_info"><?php
        if($ratesData['count'])
            sipa_plg_print(SipaPlgLang::get('widget.p.rate.info', array( ':all'=> $ratesData['count'] , ':ort'=> $ratesData['avg'], ':min'=>$ratesData['min'], ':max' => $ratesData['max'] )));
        else
            sipa_plg_print(SipaPlgLang::get('widget.p.rate.info.norate.list'));

        ?></strong>
    <div class="_g_sipa-rating-stars">
        <span class="_ayg user">
            <span style="width:0px"></span>
        </span>
        <span class="_g_msg"></span>
    </div>
</div>