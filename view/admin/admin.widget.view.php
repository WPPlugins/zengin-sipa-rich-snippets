<?php if(!defined('GOKSEL__SIPA_TOKEN'))  die(''); ?><div id="goksel__sipa_r" class="postbox ">
    <button type="button" class="handlediv button-link" aria-expanded="true">
        <span class="screen-reader-text"><?php echo SipaPlgLang::get('page.widget.title'); ?></span>
        <span class="toggle-indicator" aria-hidden="true"></span>
    </button>
    <h2 class="hndle ui-sortable-handle">
        <span><?php echo SipaPlgLang::get('page.widget.title'); ?></span>
    </h2>
    <div class="inside">
        <div class="postcustomstuff">
            <small><?php echo SipaPlgLang::get('page.widget.desc') ?></small>
            <hr />

            <input name="_g_sipa-doaction" type="hidden" id="_g_sipa-doaction" value="edit">
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label for="_g_sipa-active-2"><?php echo SipaPlgLang::get('active'); ?></label></th>
                    <td>
                        <label class="selectit"><input <?php if($active==1) sipa_plg_print('checked="checked" ');  ?> value="1" type="radio" name="_g_sipa-active" id="_g_sipa-active-1" > <?php echo SipaPlgLang::get('no'); ?></label>
                        <label class="selectit"><input <?php if($active==2) sipa_plg_print('checked="checked" ');  ?> value="2" type="radio" name="_g_sipa-active" id="_g_sipa-active-2" > <?php echo SipaPlgLang::get('yes'); ?></label>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="_g_sipa-type"><?php echo SipaPlgLang::get('page.widget.select.snippets'); ?></label></th>
                    <td>
                        <select name="_g_sipa-type" id="_g_sipa-type" class="" data-sl="<?php sipa_plg_print($rtype) ?>">
                            <option value=""><?php echo SipaPlgLang::get('page.widget.select.type') ?></option>
                            <option <?php if($rtype==SipaPlgConfig::get('plugin','schema.webpage.name')) echo _('selected="selected" ');  ?> value="<?php sipa_plg_print( SipaPlgConfig::get('plugin','schema.webpage.name')) ?>"><?php sipa_plg_print( SipaPlgConfig::get('plugin','schema.webpage.name') ) ?></option>
                            <option <?php if($rtype==SipaPlgConfig::get('plugin','schema.product.name')) echo _('selected="selected" ');  ?> value="<?php sipa_plg_print( SipaPlgConfig::get('plugin','schema.product.name')) ?>"><?php sipa_plg_print( SipaPlgConfig::get('plugin','schema.product.name') ) ?></option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><label for="_g_sipa-headline"><?php echo SipaPlgLang::get('widget.s.headline'); ?></label></th>
                    <td><input name="_g_sipa-headline" type="text" id="_g_sipa-headline" placeholder="auto" value="<?php sipa_plg_print($title) ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="_g_sipa-author"><?php echo SipaPlgLang::get('widget.s.author'); ?></label></th>
                    <td><input name="_g_sipa-author" type="text" id="_g_sipa-author" placeholder="auto" value="<?php sipa_plg_print($author) ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="_g_sipa-inlanguage"><?php echo SipaPlgLang::get('widget.s.inlanguage'); ?></label></th>
                    <td><input name="_g_sipa-inlanguage" type="text" id="_g_sipa-inlanguage" placeholder="auto or EN [TR,NL,GR,...]"  value="<?php sipa_plg_print($lang) ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="_g_sipa-image"><?php echo SipaPlgLang::get('widget.s.image'); ?></label></th>
                    <td><input name="_g_sipa-image" type="url" id="_g_sipa-image" placeholder="auto" value="<?php sipa_plg_print($img_url)  ?>" class="regular-text code"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="_g_sipa-url"><?php echo SipaPlgLang::get('widget.s.url'); ?></label></th>
                    <td><input name="_g_sipa-url" type="url" id="_g_sipa-url" placeholder="auto" value="<?php sipa_plg_print($post_url)  ?>" class="regular-text code"></td>
                </tr>

                <tr>
                    <th scope="row"><label for="_g_sipa-description"><?php echo SipaPlgLang::get('widget.s.description'); ?></label></th>
                    <td><textarea name="_g_sipa-description" id="_g_sipa-description" placeholder="max 160" class="regular-text code"><?php sipa_plg_print($description)  ?></textarea></td>
                </tr>

                <tr>
                    <th scope="row"><label for="_g_sipa-pricecurrency"><?php echo SipaPlgLang::get('widget.s.offers.pricecurrency'); ?></label></th>
                    <td><input name="_g_sipa-pricecurrency" type="text" id="_g_sipa-pricecurrency" placeholder="TRY | [USD, EUR, ...]" value="<?php sipa_plg_print($currency)  ?>" class="regular-text code"></td>
                </tr>

                <tr>
                    <th scope="row"><label for="_g_sipa-price"><?php echo SipaPlgLang::get('widget.s.offers.price'); ?></label></th>
                    <td><input name="_g_sipa-price" type="text" id="_g_sipa-price" placeholder="25.75" value="<?php sipa_plg_print($price) ?>" class="regular-text code"></td>
                </tr>


                <tr>
                    <th scope="row"><label for="_g_sipa-seller_name"><?php echo SipaPlgLang::get('widget.s.offers.seller.name'); ?></label></th>
                    <td><input name="_g_sipa-seller_name" type="text" id="_g_sipa-seller_name" placeholder="Site Name" value="<?php sipa_plg_print($seller_name) ?>" class="regular-text code"></td>
                </tr>

                <tr>
                    <th scope="row"><label for="_g_sipa-tel"><?php echo SipaPlgLang::get('widget.s.offers.tel'); ?></label></th>
                    <td><input name="_g_sipa-tel" type="text" id="_g_sipa-tel" placeholder="+90 505 726 47 98" value="<?php sipa_plg_print($seller_tel) ?>" class="regular-text code"></td>
                </tr>

                <tr>
                    <th scope="row"><label for="_g_sipa-fax"><?php echo SipaPlgLang::get('widget.s.offers.fax'); ?></label></th>
                    <td><input name="_g_sipa-fax" type="text" id="_g_sipa-fax" placeholder="+90 505 726 47 98" value="<?php sipa_plg_print($seller_fax) ?>" class="regular-text code"></td>
                </tr>

                <tr>
                    <th scope="row"><label for="_g_sipa-email"><?php echo SipaPlgLang::get('widget.s.offers.email'); ?></label></th>
                    <td><input name="_g_sipa-email" type="text" id="_g_sipa-email" placeholder="themrigi@gmail.com" value="<?php sipa_plg_print($seller_email) ?>" class="regular-text code"></td>
                </tr>


                <tr>
                    <th scope="row"><label for="_g_sipa-avail-2"><?php echo SipaPlgLang::get('widget.s.offers.availability'); ?></label></th>
                    <td>
                        <label class="selectit"><input <?php if($avail==1) sipa_plg_print('checked="checked" ');  ?> value="1" type="radio" name="_g_sipa-avail" id="_g_sipa-avail-1" > <?php echo SipaPlgLang::get('no'); ?></label>
                        <label class="selectit"><input <?php if($avail==2) sipa_plg_print('checked="checked" ');  ?> value="2" type="radio" name="_g_sipa-avail" id="_g_sipa-avail-2" > <?php echo SipaPlgLang::get('yes'); ?></label>
                    </td>
                </tr>



                </tbody>
            </table>


        </div>
    </div>

</div>



