<?php if(!defined('GOKSEL__SIPA_TOKEN'))  die(''); ?><div class="wrap" style="
    width: 98%;
">
    <h1 class="wp-heading-inline"><?php sipa_plg_print(SipaPlgLang::get('page.rates.title.' . $listType)) ?></h1>

    <!--a href="http://wp.dev/wp-admin/post-new.php" class="page-title-action"><?php sipa_plg_print('Yeni Ekle') ?></a-->
    <hr class="wp-header-end">

    <ul class='subsubsub' style="
    width: 100%;
    float: left;
">
        <li class='all'><a href="admin.php?page=sipa_admin_rates&list=3" <?php if($listType==3) { ?>class="current" <?php } ?>><?php sipa_plg_print(SipaPlgLang::get('page.rates.title.3')) ?> <span class="count">(<?php sipa_plg_print($adet['all']) ?>)</span></a> |</li>
        <li class='publish'><a href="admin.php?page=sipa_admin_rates&list=2" <?php if($listType==2) { ?>class="current" <?php } ?>><?php sipa_plg_print(SipaPlgLang::get('page.rates.title.2')) ?> <span class="count">(<?php sipa_plg_print($adet['appr']) ?>)</span></a> |</li>
        <li class='draft'><a href="admin.php?page=sipa_admin_rates&list=1" <?php if($listType==1) { ?>class="current" <?php } ?>><?php sipa_plg_print(SipaPlgLang::get('page.rates.title.1')) ?> <span class="count">(<?php sipa_plg_print($adet['wait']) ?>)</span></a></li>
    </ul>
    <form id="sipa-list" method="get">

        <h2 class='screen-reader-text'><?php sipa_plg_print(SipaPlgLang::get('page.rates.title.' . $listType)) ?><</h2>
        <table class="wp-list-table widefat fixed striped ">
            <thead>
            <tr>
                <th scope="col" id='refer' class='manage-column column-title column-primary'>
                    <a>
                        <span><?php sipa_plg_print(SipaPlgLang::get('list.refer')) ?></span>
                    </a>
                </th>
                <th scope="col" id='list_ip' class='manage-column '><?php sipa_plg_print(SipaPlgLang::get('list.ip')) ?></th>
                <th scope="col" id='list_date' class='manage-column '><?php sipa_plg_print(SipaPlgLang::get('list.rate')) ?></th>
                <th scope="col" id='puan' class='manage-column '><?php sipa_plg_print(SipaPlgLang::get('list.date')) ?></th>

            </tr>
            </thead>

            <tbody id="rate-list">

            <?php if($count) { ?>

            <?php foreach ( $rows as $row ) { ?>

                    <tr id="record-<?php sipa_plg_print($row->id) ?>" class="iedit">

                        <td class="title column-title has-row-actions column-primary page-title" data-colname="<?php sipa_plg_print(SipaPlgLang::get('list.refer')) ?>">

                            <strong>
                                <a aria-label="<?php sipa_plg_print(SipaPlgLang::get('list.refer')) ?>"><?php sipa_plg_print($row->refer) ?></a>
                            </strong>


                            <div class="row-actions">
                        <span class="edit">
                            <a href="admin.php?page=sipa_admin_rates<?php sipa_plg_print('&list=' . $listType . '&id=' . $row->id . '&status=2') ?>" aria-label="<?php sipa_plg_print(SipaPlgLang::get('list.approve')) ?>"><?php sipa_plg_print(SipaPlgLang::get('list.approve')) ?></a> |
                        </span>

                        <span class="trash">
                            <a href="admin.php?page=sipa_admin_rates<?php sipa_plg_print('&list=' . $listType . '&id=' . $row->id . '&status=1') ?>" class="submitdelete" aria-label="<?php sipa_plg_print(SipaPlgLang::get('list.wait')) ?>"><?php sipa_plg_print(SipaPlgLang::get('list.wait')) ?></a> |
                        </span>

                        <span class="trash">
                            <a href="admin.php?page=sipa_admin_rates<?php sipa_plg_print('&list=' . $listType . '&id=' . $row->id . '&status=0') ?>" class="submitdelete" aria-label="<?php sipa_plg_print(SipaPlgLang::get('list.del')) ?>"><?php sipa_plg_print(SipaPlgLang::get('list.del')) ?></a> |
                        </span>
                            </div>
                            <button type="button" class="toggle-row">
                                <span class="screen-reader-text">See more</span>
                            </button>
                        </td>

                        <td class='author column-author' data-colname="<?php sipa_plg_print(SipaPlgLang::get('list.ip')) ?>">
                            <a href="edit.php?post_type=post&#038;author=1"><?php sipa_plg_print($row->reviewer_ip) ?></a>
                        </td>
                        <td class='categories column-categories' data-colname="<?php sipa_plg_print(SipaPlgLang::get('list.rate')) ?>"><?php sipa_plg_print($row->review_rating) ?></td>
                        <td class='date column-date' data-colname="<?php sipa_plg_print(SipaPlgLang::get('list.date')) ?>">
                            <abbr title="<?php sipa_plg_print($row->review_time>0 ? date('d.m.Y H:i:s', $row->review_time) : '---') ?>"><?php sipa_plg_print($row->review_time>0 ? date('d.m.Y H:i:s', $row->review_time) : '---') ?></abbr>
                        </td>
                    </tr>


            <?php } ?>
            <?php  ?>
            <?php  ?>

            <?php } ?>


            </tbody>

            <tfoot>
            <tr>
                <th scope="col" id='refer' class='manage-column column-title column-primary'>
                    <a>
                        <span><?php sipa_plg_print(SipaPlgLang::get('list.refer')) ?></span>
                    </a>
                </th>
                <th scope="col" id='list_ip' class='manage-column '><?php sipa_plg_print(SipaPlgLang::get('list.ip')) ?></th>
                <th scope="col" id='list_date' class='manage-column '><?php sipa_plg_print(SipaPlgLang::get('list.rate')) ?></th>
                <th scope="col" id='puan' class='manage-column '><?php sipa_plg_print(SipaPlgLang::get('list.date')) ?></th>

            </tr>
            </tfoot>

        </table>

        <div class="tablenav bottom">


            <div class="alignleft actions">
            </div>

            <div class="tablenav-pages">
                <span class="displaying-num"><?php sipa_plg_print($count) ?></span>
                <span class="pagination-links">

                    <a class="tablenav-pages-navspan" <?php ($allPages==1 || $page<2) ? sipa_plg_print('aria-hidden="true"') : sipa_plg_print('href="admin.php?page=sipa_admin_rates&list=' . $listType . '"') ?>>
                        <span class="screen-reader-text">First</span>
                        <span aria-hidden="false">«</span>
                    </a>

                    <a class="prev-page" <?php ($allPages==1 || $page<2) ? sipa_plg_print('aria-hidden="true"') : sipa_plg_print('href="admin.php?page=sipa_admin_rates&list=' . $listType . '&p=' . ($page-1<1?1:$page-1) . '"') ?>>
                        <span class="screen-reader-text">Prev</span>
                        <span aria-hidden="false">‹</span>
                    </a>
                    <span class="screen-reader-text">Current</span>

                    <span id="table-paging" class="paging-input">
                        <span class="tablenav-paging-text">
                            <?php sipa_plg_print($page) ?> / <span class="total-pages"><?php sipa_plg_print($allPages) ?></span>
                        </span>
                    </span>

                    <a class="next-page" <?php $allPages==1 || $page==$allPages ? sipa_plg_print('aria-hidden="true"') : sipa_plg_print('href="admin.php?page=sipa_admin_rates&list=' . $listType . '&p=' . ($page+1>$allPages?$allPages:$page+1) . '"'); ?>>
                        <span class="screen-reader-text">Next</span><span aria-hidden="true">›</span>
                    </a>
                    <a class="tablenav-pages-navspan" <?php $allPages==1 || $page==$allPages ? sipa_plg_print('aria-hidden="true"') : sipa_plg_print('href="admin.php?page=sipa_admin_rates&list=' . $listType . '&p=' . $allPages . '"'); ?>>
                        <span class="screen-reader-text">Last</span><span aria-hidden="true">»</span>
                    </a>
                </span>

            </div>

            <br class="clear">
        </div>


    </form>




    <div id="ajax-response"></div>
    <br class="clear" />
</div>