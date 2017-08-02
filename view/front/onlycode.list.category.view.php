<?php if(!defined('GOKSEL__SIPA_TOKEN'))  die(''); ?><div style="display: none" itemscope itemtype="http://schema.org/WebPage">
    <span itemprop="name"><?php sipa_plg_print($title ? $title: single_cat_title() ) ?></span>
    <?php if( $ratesData['count'] ) { ?>
        <div style="display: none;" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
            <span itemprop="bestRating"><?php sipa_plg_print($ratesData['max']) ?></span>
            <span itemprop="worstRating"><?php sipa_plg_print($ratesData['min']) ?></span>
            <span itemprop="ratingValue"><?php sipa_plg_print( number_format($ratesData['avg'], 2) ) ?></span>
            <span itemprop="ratingCount"><?php sipa_plg_print($ratesData['count']) ?></span>
        </div>
    <?php } ?>
</div>