<?php if(!defined('GOKSEL__SIPA_TOKEN'))  die(''); ?>
<div class="_g_sipa-rating _g_sipa-rating-cat" data-action="sipa_do_rate" data-url="<?php sipa_plg_print ( admin_url('admin-ajax.php') ) ?>" data-avg="<?php sipa_plg_print( number_format( $ratesData['avg'] ,2) ) ?>" data-tkn="<?php sipa_plg_print( SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1')) ?>">
    <strong class="_g_info"><?php
        if(isset($ratesData['count']) )
            sipa_plg_print(SipaPlgLang::get('widget.p.rate.info.cat', array( ':all'=> $ratesData['count'] , ':ort'=> $ratesData['avg'], ':min'=>$ratesData['min'], ':max' => $ratesData['max'] )));
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
<div style="display: none" itemscope itemtype="http://schema.org/WebPage">
    <span itemprop="name"><?php sipa_plg_print(isset($title) ? $title: single_cat_title() ) ?></span>
    <?php if($ratesData['count']) { ?>
        <div style="display: none;" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <span itemprop="bestRating"><?php sipa_plg_print($ratesData['max']) ?></span>
            <span itemprop="worstRating"><?php sipa_plg_print($ratesData['min']) ?></span>
            <span itemprop="ratingValue"><?php sipa_plg_print( number_format($ratesData['avg'], 2) ) ?></span>
            <span itemprop="ratingCount"><?php sipa_plg_print($ratesData['count']) ?></span>
        </div>
    <?php } ?>
</div>