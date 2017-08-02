<?php if(!defined('GOKSEL__SIPA_TOKEN'))  die(''); ?>
<div class="_g_sipa-rating _g_sipa-rating-rate" data-action="sipa_do_rate" data-url="<?php sipa_plg_print ( admin_url('admin-ajax.php') ) ?>" data-post="<?php sipa_plg_print($post_id) ?>" data-avg="<?php sipa_plg_print( number_format( $ratesData['avg'] ,2) ) ?>" data-tkn="<?php sipa_plg_print( SipaPlgSession::get('GOKSEL__SIPA_TOKEN_1')) ?>">
    <strong class="_g_info"><?php
        if($ratesData['count'])
            sipa_plg_print(SipaPlgLang::get('widget.p.rate.info', array( ':all'=> $ratesData['count'] , ':ort'=> $ratesData['avg'], ':min'=>$ratesData['min'], ':max' => $ratesData['max'] )));
        else
            sipa_plg_print(SipaPlgLang::get('widget.p.rate.info.norate'));

        ?></strong>
    <div class="_g_sipa-rating-stars">
        <span class="_ayg user">
            <span style="width:0px"></span>
        </span>
        <span class="_g_msg"></span>
    </div>
</div>

<div style="display: none" itemscope itemtype="http://schema.org/<?php sipa_plg_print($rtype?$rtype:'WebPage') ?>">
    <span itemprop="name"><?php sipa_plg_print($title ? $title: the_title() ) ?></span>
    <link itemprop="mainEntityOfPage" href="<?php sipa_plg_print( get_category_link( $category_id ) ) ?>" />
    <link itemprop="url" content="<?php sipa_plg_print( get_permalink() ) ?>" href="<?php sipa_plg_print( get_permalink() ) ?>" />

    <div itemprop="description"><?php sipa_plg_print( $description?sipa_plg_shrt_cnt($description,160):sipa_plg_shrt_cnt($post_html,160) ) ?></div>

    <meta itemprop="image" content="<?php sipa_plg_print( $img_url?$img_url:get_the_post_thumbnail_url() ) ?>">

    <?php if(!$price ) { ?>
        <span itemprop="publisher"><?php sipa_plg_print( sipa_plg_domain( get_site_url() )) ?></span>
        <span itemprop="inLanguage" content="<?php sipa_plg_print(get_locale()) ?>"><?php sipa_plg_print(get_locale()) ?></span>

        <?php if(count($tags)) { ?>
            <div itemprop="keywords">
                <?php foreach ( $tags as $tag ) { ?>
                    <a content="<?php sipa_plg_print($tag->name) ?>" href="<?php sipa_plg_print(get_tag_link($tag->term_id)) ?>" rel="tag"><?php sipa_plg_print($tag->name) ?></a>,
                <?php } ?>
            </div>
        <?php } ?>


        <span itemprop="author"><?php sipa_plg_print(get_the_author_meta()) ?></span>
        <span itemprop="headline" content="<?php sipa_plg_print($title ? $title: the_title() ) ?>">

        <span itemprop="datePublished"><?php sipa_plg_print(get_the_date(DATE_ISO8601)) ?></span>
        <span itemprop="dateModified"><?php sipa_plg_print(the_modified_date(DATE_ISO8601)) ?></span>

    <?php } ?>

    <?php if($ratesData['count']) { ?>
        <div style="display: none;" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">

            <span itemprop="bestRating"><?php sipa_plg_print($ratesData['max']) ?></span>
            <span itemprop="worstRating"><?php sipa_plg_print($ratesData['min']) ?></span>
            <span itemprop="ratingValue"><?php sipa_plg_print( number_format($ratesData['avg'], 2) ) ?></span>
            <span itemprop="ratingCount"><?php sipa_plg_print($ratesData['count']) ?></span>

        </div>
    <?php } ?>

    <?php if($price) { ?>
        <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
            <?php sipa_plg_print( SipaPlgLang::get('widget.s.offers.price') . number_format($price, 2, ',', '') . ' ' . ($currency?$currency:'TRY') ) ?>
            <span itemprop="priceCurrency"><?php sipa_plg_print(($currency?$currency:'TRY')) ?></span>
            <span itemprop="price"><?php sipa_plg_print( number_format($price, 2, '.', '') ) ?></span>
            <span itemprop="seller" itemscope itemtype="http://schema.org/Organization">
                <span itemprop="name"><?php sipa_plg_print( ( $seller_name?$seller_name:sipa_plg_domain( get_site_url() ) ) ) ?></span>
                <span itemprop="telephone"><?php sipa_plg_print( ( $seller_tel?$seller_tel:'000-000-00-00' ) ) ?></span>
                <span itemprop="faxNumber"><?php sipa_plg_print( sipa_plg_print( ( $seller_fax?$seller_fax:'000-000-00-00' ) ) ) ?></span>
                <span itemprop="email"><?php sipa_plg_print( sipa_plg_print( ( $seller_email?$seller_email:('info@'.sipa_plg_domain( get_site_url() )) ) ) ) ?></span>
            </span>
            <?php if($avail==2) { ?>
                <link itemprop="availability" href="http://schema.org/InStock"/>
            <?php } ?>
        </div>
    <?php } ?>
</div>