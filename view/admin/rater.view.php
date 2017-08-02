<?php if(!defined('GOKSEL__SIPA_TOKEN'))  die(''); ?><div id="wpbody-content" tabindex="0">

    <div class="wrap">
        <h1><?php sipa_plg_print(SipaPlgLang::get('page.rater.title',array(), $lang)) ?></h1>
        <p class="description"><?php sipa_plg_print(SipaPlgLang::get('page.rater.desc',array(), $lang)) ?></p>

        <?php  if(isset($err) && is_array($err)) { ?>
            <style>
                .error-div {
                    list-style: none;
                    border: 2px dotted red;
                    padding: 5px 0px 0px 5px;
                    font-size: smaller;
                    font-weight: 800;
                }
            </style>
            <div class="error-div">
            <?php sipa_plg_print('<li>' . implode('</li><li>',$err) . '</li>'); ?>
            </div><?php } ?>


        <form method="post" action="admin.php?page=sipa_admin_make_rate" novalidate="novalidate">

            <table class="form-table">

                <tr>
                    <th scope="row"><label for="post_id"><?php sipa_plg_print(SipaPlgLang::get('list.post',array(), $lang)) ?></label></th>
                    <td>
                        <input name="post_id" type="text" aria-valuemin="1" id="post_id" placeholder="Valid Post ID" value=""  />
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="rate"><?php sipa_plg_print(SipaPlgLang::get('widget.p.yourrate',array(), $lang)) ?></label></th>
                    <td>
                        <select name="rate" id="rate">
                            <?php for($i=5;$i>0;$i--) { ?>
                                <option value='<?php sipa_plg_print($i) ?>'><?php sipa_plg_print($i) ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="adet"><?php sipa_plg_print(SipaPlgLang::get('widget.s.aggregateRating.ratingcount',array(), $lang)) ?></label></th>
                    <td>
                        <input name="adet" type="text" id="adet" value="10" class="regular-text" />
                    </td>
                </tr>



            </table>


            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php sipa_plg_print(SipaPlgLang::get('save',array(), $lang)) ?>"  /></p></form>

    </div>

    <div class="clear"></div>
</div>