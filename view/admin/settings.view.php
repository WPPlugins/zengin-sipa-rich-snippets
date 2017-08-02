<?php if(!defined('GOKSEL__SIPA_TOKEN'))  die(''); ?><div id="wpbody-content" tabindex="0">

    <div class="wrap">
        <h1><?php sipa_plg_print(SipaPlgLang::get('page.settings.title',array(), $lang)) ?></h1>
        <p class="description"><?php sipa_plg_print(SipaPlgLang::get('page.settings.desc',array(), $lang)) ?></p>

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


        <form method="post" action="admin.php?page=sipa_admin_settings" novalidate="novalidate">

            <table class="form-table">

                <tr>
                    <th scope="row"><label for="auto-aprove"><?php sipa_plg_print(SipaPlgLang::get('rating.approve.auto',array(), $lang)) ?></label></th>
                    <td>
                        <select name="auto-aprove" id="auto-aprove">
                            <option <?php if($data['rating.approve.auto'] == true) sipa_plg_print(' selected="selected" ') ?> value='true'><?php sipa_plg_print(SipaPlgLang::get('yes',array(), $lang)) ?></option>
                            <option <?php if($data['rating.approve.auto'] == false) sipa_plg_print(' selected="selected" ') ?> value='false'><?php sipa_plg_print(SipaPlgLang::get('no',array(), $lang)) ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="auto-aprove-min"><?php sipa_plg_print(SipaPlgLang::get('rating.approve.auto.min',array(), $lang)) ?></label></th>
                    <td>
                        <select name="auto-aprove-min" id="auto-aprove-min">
                            <?php for($i=1;$i<6;$i++) { ?>
                            <option <?php if($data['rating.approve.auto.min'] == $i) sipa_plg_print(' selected="selected" ') ?> value='<?php sipa_plg_print($i) ?>'><?php sipa_plg_print($i) ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="time-out"><?php sipa_plg_print(SipaPlgLang::get('rating.timeout.ip',array(), $lang)) ?></label></th>
                    <td>
                        <input name="time-out" type="text" id="time-out" value="<?php sipa_plg_print($data['rating.timeout.ip']) ?>" class="regular-text" />
                        <p class="description"> 1 = 1s, 86400 = 1 day, <b>86400*7 = 1 week</b>  </p>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="lang"><?php sipa_plg_print(SipaPlgLang::get('lang.plugin',array(), $lang)) ?></label></th>
                    <td>
                        <select name="lang" id="lang">
                            <option <?php if($data['lang'] != 'tr_TR') sipa_plg_print(' selected="selected" ') ?> value='en'>English</option>
                            <option <?php if($data['lang'] == 'tr_TR') sipa_plg_print(' selected="selected" ') ?> value='tr_TR'>Türkçe</option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="position"><?php sipa_plg_print(SipaPlgLang::get('widget.position',array(), $lang)) ?></label></th>
                    <td>
                        <select name="position" id="position">
                            <option <?php if($data['widget.position'] == 1) sipa_plg_print(' selected="selected" ') ?> value='1'><?php sipa_plg_print(SipaPlgLang::get('at.before',array(), $lang)) ?></option>
                            <option <?php if($data['widget.position'] == 2) sipa_plg_print(' selected="selected" ') ?> value='2'><?php sipa_plg_print(SipaPlgLang::get('at.after',array(), $lang)) ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="onlist"><?php sipa_plg_print(SipaPlgLang::get('widget.active.onlist',array(), $lang)) ?></label></th>
                    <td>
                        <select name="onlist" id="onlist">
                            <option <?php if($data['widget.active.onlist'] == true) sipa_plg_print(' selected="selected" ') ?> value='true'><?php sipa_plg_print(SipaPlgLang::get('yes',array(), $lang)) ?></option>
                            <option <?php if($data['widget.active.onlist'] == false) sipa_plg_print(' selected="selected" ') ?> value='false'><?php sipa_plg_print(SipaPlgLang::get('no',array(), $lang)) ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="oncat"><?php sipa_plg_print(SipaPlgLang::get('widget.active.oncat',array(), $lang)) ?></label></th>
                    <td>
                        <select name="oncat" id="oncat">
                            <option <?php if($data['widget.active.oncat'] == true) sipa_plg_print(' selected="selected" ') ?> value='true'><?php sipa_plg_print(SipaPlgLang::get('yes',array(), $lang)) ?></option>
                            <option <?php if($data['widget.active.oncat'] == false) sipa_plg_print(' selected="selected" ') ?> value='false'><?php sipa_plg_print(SipaPlgLang::get('no',array(), $lang)) ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="oncatshow"><?php sipa_plg_print(SipaPlgLang::get('widget.active.oncat.show',array(), $lang)) ?></label></th>
                    <td>
                        <select name="oncatshow" id="oncatshow">
                            <option <?php if($data['widget.active.oncat.show'] == true) sipa_plg_print(' selected="selected" ') ?> value='true'><?php sipa_plg_print(SipaPlgLang::get('yes',array(), $lang)) ?></option>
                            <option <?php if($data['widget.active.oncat.show'] == false) sipa_plg_print(' selected="selected" ') ?> value='false'><?php sipa_plg_print(SipaPlgLang::get('no',array(), $lang)) ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="jquery"><?php sipa_plg_print(SipaPlgLang::get('jquery',array(), $lang)) ?></label></th>
                    <td>
                        <select name="jquery" id="jquery">
                            <option <?php if($data['jquery'] == true) sipa_plg_print(' selected="selected" ') ?> value='true'><?php sipa_plg_print(SipaPlgLang::get('yes',array(), $lang)) ?></option>
                            <option <?php if($data['jquery'] == false) sipa_plg_print(' selected="selected" ') ?> value='false'><?php sipa_plg_print(SipaPlgLang::get('no',array(), $lang)) ?></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <th scope="row"><label for="customcss"><?php sipa_plg_print(SipaPlgLang::get('widget.customcss',array(), $lang)) ?></label></th>
                    <td>
                        <textarea class="regular-text" name="customcss" id="customcss"><?php sipa_plg_print($data['customcss']) ?></textarea>
                    </td>
                </tr>

            </table>


            <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php sipa_plg_print(SipaPlgLang::get('save',array(), $lang)) ?>"  /></p></form>

    </div>

    <div class="clear"></div>
</div>