<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="row pull-right" style="margin-bottom: 15px;">
    <div class="col-md-12">
        <?php add_toolbar_button(form_button("<i class='fa fa-check'></i> " . __('Save changes', 'configuration'), array('onclick' => 'pf_setting_save_change();', 'class' => 'btn btn-primary'))); ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo __('Settings', 'configuration'); ?>
                </h3>
            </div>
            <div class="panel-body">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#general_setting" data-toggle="tab"><?php echo __('General settings', 'configuration'); ?></a></li>
                        <?php if (!empty($setting->properties)) { ?>
                            <?php foreach ($setting->properties as $k => $property) { ?>
                                <li><a href="<?php echo '#' . $k; ?>tab-pane" data-toggle="tab"><?php echo $property['title']; ?></a></li>
                            <?php } ?>
                        <?php } ?>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane active" id="general_setting">
                            <div class="box-body" id="general">
                                <form class="form-horizontal">
                                    <table class="table table-bordered table-configuration">
                                        <col class="col-md-2" />
                                        <tr>
                                            <th><label for="site_name" class="control-label"><?php echo __('Site name', 'configuration'); ?></label></th>
                                            <td><input type="text" id="site_name"  class="form-control" name="site_name"></td>
                                        </tr>
                                        <tr>
                                            <th><label class="control-label"><?php echo __('Site offline', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-12 no-padding">
                                                    <label> <?php echo __('Yes', 'configuration'); ?> <input type="radio" name="site_offline" value="1" id="site_offline"/></label>  &nbsp; 
                                                    <label> <?php echo __('No', 'configuration'); ?> <input type="radio" name="site_offline" value="0" id="site_offline"/></label>
                                                </div>
                                                <script type="text/javascript">
                                                    $(document).ready(function () {
                                                        $('input[name="site_offline"]').on('ifClicked', function () {
                                                            if (+$(this).val() === 1) {
                                                                $('#site_offline_page').show();
                                                            } else {
                                                                $('#site_offline_page').hide();
                                                            }
                                                        });
                                                    });
                                                </script>
                                                <div class="col-md-12 no-padding" id="site_offline_page">
                                                    <label><?php echo __('Please choose the page which will be displayed in offline mode', 'configuration'); ?></label>
                                                    <?php echo form_dropdown('site_offline_page', Pf_Plugin_Singleton::list_page(), array(), 'id="site_offline_page"') ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="accessibility" class="control-label"><?php echo __('Accessibility', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-4 no-padding">
                                                    <?php
                                                    $arr = array(
                                                        'everyone' => __('Everyone', 'configuration'),
                                                        'onlymember' => __('Member only', 'configuration')
                                                    );
                                                    echo form_dropdown('accessibility', $arr, get_configuration('accessibility'));
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label class="control-label"><?php echo __('Homepage', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-4 no-padding">
                                                    <select for="error_page" name="default_page" class="form-control" id="default_page">
                                                        <option></option>
                                                        <?php if (!empty($pages) && is_array($pages)) { ?>
                                                            <?php foreach ($pages as $page) { ?>
                                                                <option value="<?php echo $page['id']; ?>"><?php echo htmlspecialchars($page['page_title']); ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="error_page" class="control-label"><?php echo __('Error page', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-4 no-padding">
                                                    <select name="error_page" class="form-control" id="error_page">
                                                        <option></option>
                                                        <?php if (!empty($pages) && is_array($pages)) { ?>
                                                            <?php foreach ($pages as $page) { ?>
                                                                <option value="<?php echo $page['id']; ?>"><?php echo htmlspecialchars($page['page_title']); ?></option>
                                                            <?php } ?>
                                                        <?php } ?>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="error_page" class="control-label"><?php echo __('HTML Charset', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-4 no-padding">
                                                    <?php
                                                    echo form_dropdown('charset_html', $html_charset_list, get_configuration('charset_html'));
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="error_page" class="control-label"><?php echo __('Main Menu', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-4 no-padding">
                                                    <?php

                                                    function list_menu() {
                                                        $all = get_option('menu');
                                                        foreach ($all as $menu) {
                                                            $list[$menu['id']] = $menu['name'];
                                                        }
                                                        return $list;
                                                    }

                                                    echo form_dropdown('main_menu', list_menu(), get_configuration('main_menu'));
                                                    ?>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="site_name" class="control-label"><?php echo __('Backup Folder', 'configuration'); ?></label></th>
                                            <td><input type="text" id="backup_dir"  class="form-control" name="backup_dir"><div style="font-size:12px"><?php echo __("Absolute path to backup folder, with slash in the end. Example:", "configuration") . "\"" . ABSPATH . "/tmp/backup/\".<br/>" . __('For security reason, we recommend you to create new backup folder', 'configuration'); ?></div></td>
                                        </tr>
                                        <tr>
                                            <th><label for="items_per_page" class="control-label"><?php echo __('Items per page', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-2 no-padding">
                                                    <select name="items_per_page" class="form-control" id="items_per_page">
                                                        <option>10</option>
                                                        <option>20</option>
                                                        <option>50</option>
                                                        <option>100</option>
                                                        <option>200</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="time_zone" class="control-label"><?php echo __('Timezone', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-3 no-padding">
                                                    <select name="time_zone" id="time_zone" class="form-control">
                                                        <option value="Africa/Abidjan">Africa/Abidjan</option>
                                                        <option value="Africa/Accra">Africa/Accra</option>
                                                        <option value="Africa/Addis_Ababa">Africa/Addis_Ababa</option>
                                                        <option value="Africa/Algiers">Africa/Algiers</option>
                                                        <option value="Africa/Asmara">Africa/Asmara</option>
                                                        <option value="Africa/Bamako">Africa/Bamako</option>
                                                        <option value="Africa/Bangui">Africa/Bangui</option>
                                                        <option value="Africa/Banjul">Africa/Banjul</option>
                                                        <option value="Africa/Bissau">Africa/Bissau</option>
                                                        <option value="Africa/Blantyre">Africa/Blantyre</option>
                                                        <option value="Africa/Brazzaville">Africa/Brazzaville</option>
                                                        <option value="Africa/Bujumbura">Africa/Bujumbura</option>
                                                        <option value="Africa/Cairo">Africa/Cairo</option>
                                                        <option value="Africa/Casablanca">Africa/Casablanca</option>
                                                        <option value="Africa/Ceuta">Africa/Ceuta</option>
                                                        <option value="Africa/Conakry">Africa/Conakry</option>
                                                        <option value="Africa/Dakar">Africa/Dakar</option>
                                                        <option value="Africa/Dar_es_Salaam">Africa/Dar_es_Salaam</option>
                                                        <option value="Africa/Djibouti">Africa/Djibouti</option>
                                                        <option value="Africa/Douala">Africa/Douala</option>
                                                        <option value="Africa/El_Aaiun">Africa/El_Aaiun</option>
                                                        <option value="Africa/Freetown">Africa/Freetown</option>
                                                        <option value="Africa/Gaborone">Africa/Gaborone</option>
                                                        <option value="Africa/Harare">Africa/Harare</option>
                                                        <option value="Africa/Johannesburg">Africa/Johannesburg</option>
                                                        <option value="Africa/Juba">Africa/Juba</option>
                                                        <option value="Africa/Kampala">Africa/Kampala</option>
                                                        <option value="Africa/Khartoum">Africa/Khartoum</option>
                                                        <option value="Africa/Kigali">Africa/Kigali</option>
                                                        <option value="Africa/Kinshasa">Africa/Kinshasa</option>
                                                        <option value="Africa/Lagos">Africa/Lagos</option>
                                                        <option value="Africa/Libreville">Africa/Libreville</option>
                                                        <option value="Africa/Lome">Africa/Lome</option>
                                                        <option value="Africa/Luanda">Africa/Luanda</option>
                                                        <option value="Africa/Lubumbashi">Africa/Lubumbashi</option>
                                                        <option value="Africa/Lusaka">Africa/Lusaka</option>
                                                        <option value="Africa/Malabo">Africa/Malabo</option>
                                                        <option value="Africa/Maputo">Africa/Maputo</option>
                                                        <option value="Africa/Maseru">Africa/Maseru</option>
                                                        <option value="Africa/Mbabane">Africa/Mbabane</option>
                                                        <option value="Africa/Mogadishu">Africa/Mogadishu</option>
                                                        <option value="Africa/Monrovia">Africa/Monrovia</option>
                                                        <option value="Africa/Nairobi">Africa/Nairobi</option>
                                                        <option value="Africa/Ndjamena">Africa/Ndjamena</option>
                                                        <option value="Africa/Niamey">Africa/Niamey</option>
                                                        <option value="Africa/Nouakchott">Africa/Nouakchott</option>
                                                        <option value="Africa/Ouagadougou">Africa/Ouagadougou</option>
                                                        <option value="Africa/Porto-Novo">Africa/Porto-Novo</option>
                                                        <option value="Africa/Sao_Tome">Africa/Sao_Tome</option>
                                                        <option value="Africa/Tripoli">Africa/Tripoli</option>
                                                        <option value="Africa/Tunis">Africa/Tunis</option>
                                                        <option value="Africa/Windhoek">Africa/Windhoek</option>
                                                        <option value="America/Adak">America/Adak</option>
                                                        <option value="America/Anchorage">America/Anchorage</option>
                                                        <option value="America/Anguilla">America/Anguilla</option>
                                                        <option value="America/Antigua">America/Antigua</option>
                                                        <option value="America/Araguaina">America/Araguaina</option>
                                                        <option value="America/Argentina/Buenos_Aires">America/Argentina/Buenos_Aires</option>
                                                        <option value="America/Argentina/Catamarca">America/Argentina/Catamarca</option>
                                                        <option value="America/Argentina/Cordoba">America/Argentina/Cordoba</option>
                                                        <option value="America/Argentina/Jujuy">America/Argentina/Jujuy</option>
                                                        <option value="America/Argentina/La_Rioja">America/Argentina/La_Rioja</option>
                                                        <option value="America/Argentina/Mendoza">America/Argentina/Mendoza</option>
                                                        <option value="America/Argentina/Rio_Gallegos">America/Argentina/Rio_Gallegos</option>
                                                        <option value="America/Argentina/Salta">America/Argentina/Salta</option>
                                                        <option value="America/Argentina/San_Juan">America/Argentina/San_Juan</option>
                                                        <option value="America/Argentina/San_Luis">America/Argentina/San_Luis</option>
                                                        <option value="America/Argentina/Tucuman">America/Argentina/Tucuman</option>
                                                        <option value="America/Argentina/Ushuaia">America/Argentina/Ushuaia</option>
                                                        <option value="America/Aruba">America/Aruba</option>
                                                        <option value="America/Asuncion">America/Asuncion</option>
                                                        <option value="America/Atikokan">America/Atikokan</option>
                                                        <option value="America/Bahia">America/Bahia</option>
                                                        <option value="America/Bahia_Banderas">America/Bahia_Banderas</option>
                                                        <option value="America/Barbados">America/Barbados</option>
                                                        <option value="America/Belem">America/Belem</option>
                                                        <option value="America/Belize">America/Belize</option>
                                                        <option value="America/Blanc-Sablon">America/Blanc-Sablon</option>
                                                        <option value="America/Boa_Vista">America/Boa_Vista</option>
                                                        <option value="America/Bogota">America/Bogota</option>
                                                        <option value="America/Boise">America/Boise</option>
                                                        <option value="America/Cambridge_Bay">America/Cambridge_Bay</option>
                                                        <option value="America/Campo_Grande">America/Campo_Grande</option>
                                                        <option value="America/Cancun">America/Cancun</option>
                                                        <option value="America/Caracas">America/Caracas</option>
                                                        <option value="America/Cayenne">America/Cayenne</option>
                                                        <option value="America/Cayman">America/Cayman</option>
                                                        <option value="America/Chicago">America/Chicago</option>
                                                        <option value="America/Chihuahua">America/Chihuahua</option>
                                                        <option value="America/Costa_Rica">America/Costa_Rica</option>
                                                        <option value="America/Creston">America/Creston</option>
                                                        <option value="America/Cuiaba">America/Cuiaba</option>
                                                        <option value="America/Curacao">America/Curacao</option>
                                                        <option value="America/Danmarkshavn">America/Danmarkshavn</option>
                                                        <option value="America/Dawson">America/Dawson</option>
                                                        <option value="America/Dawson_Creek">America/Dawson_Creek</option>
                                                        <option value="America/Denver">America/Denver</option>
                                                        <option value="America/Detroit">America/Detroit</option>
                                                        <option value="America/Dominica">America/Dominica</option>
                                                        <option value="America/Edmonton">America/Edmonton</option>
                                                        <option value="America/Eirunepe">America/Eirunepe</option>
                                                        <option value="America/El_Salvador">America/El_Salvador</option>
                                                        <option value="America/Fortaleza">America/Fortaleza</option>
                                                        <option value="America/Glace_Bay">America/Glace_Bay</option>
                                                        <option value="America/Godthab">America/Godthab</option>
                                                        <option value="America/Goose_Bay">America/Goose_Bay</option>
                                                        <option value="America/Grand_Turk">America/Grand_Turk</option>
                                                        <option value="America/Grenada">America/Grenada</option>
                                                        <option value="America/Guadeloupe">America/Guadeloupe</option>
                                                        <option value="America/Guatemala">America/Guatemala</option>
                                                        <option value="America/Guayaquil">America/Guayaquil</option>
                                                        <option value="America/Guyana">America/Guyana</option>
                                                        <option value="America/Halifax">America/Halifax</option>
                                                        <option value="America/Havana">America/Havana</option>
                                                        <option value="America/Hermosillo">America/Hermosillo</option>
                                                        <option value="America/Indiana/Indianapolis">America/Indiana/Indianapolis</option>
                                                        <option value="America/Indiana/Knox">America/Indiana/Knox</option>
                                                        <option value="America/Indiana/Marengo">America/Indiana/Marengo</option>
                                                        <option value="America/Indiana/Petersburg">America/Indiana/Petersburg</option>
                                                        <option value="America/Indiana/Tell_City">America/Indiana/Tell_City</option>
                                                        <option value="America/Indiana/Vevay">America/Indiana/Vevay</option>
                                                        <option value="America/Indiana/Vincennes">America/Indiana/Vincennes</option>
                                                        <option value="America/Indiana/Winamac">America/Indiana/Winamac</option>
                                                        <option value="America/Inuvik">America/Inuvik</option>
                                                        <option value="America/Iqaluit">America/Iqaluit</option>
                                                        <option value="America/Jamaica">America/Jamaica</option>
                                                        <option value="America/Juneau">America/Juneau</option>
                                                        <option value="America/Kentucky/Louisville">America/Kentucky/Louisville</option>
                                                        <option value="America/Kentucky/Monticello">America/Kentucky/Monticello</option>
                                                        <option value="America/Kralendijk">America/Kralendijk</option>
                                                        <option value="America/La_Paz">America/La_Paz</option>
                                                        <option value="America/Lima">America/Lima</option>
                                                        <option value="America/Los_Angeles">America/Los_Angeles</option>
                                                        <option value="America/Lower_Princes">America/Lower_Princes</option>
                                                        <option value="America/Maceio">America/Maceio</option>
                                                        <option value="America/Managua">America/Managua</option>
                                                        <option value="America/Manaus">America/Manaus</option>
                                                        <option value="America/Marigot">America/Marigot</option>
                                                        <option value="America/Martinique">America/Martinique</option>
                                                        <option value="America/Matamoros">America/Matamoros</option>
                                                        <option value="America/Mazatlan">America/Mazatlan</option>
                                                        <option value="America/Menominee">America/Menominee</option>
                                                        <option value="America/Merida">America/Merida</option>
                                                        <option value="America/Metlakatla">America/Metlakatla</option>
                                                        <option value="America/Mexico_City">America/Mexico_City</option>
                                                        <option value="America/Miquelon">America/Miquelon</option>
                                                        <option value="America/Moncton">America/Moncton</option>
                                                        <option value="America/Monterrey">America/Monterrey</option>
                                                        <option value="America/Montevideo">America/Montevideo</option>
                                                        <option value="America/Montserrat">America/Montserrat</option>
                                                        <option value="America/Nassau">America/Nassau</option>
                                                        <option value="America/New_York">America/New_York</option>
                                                        <option value="America/Nipigon">America/Nipigon</option>
                                                        <option value="America/Nome">America/Nome</option>
                                                        <option value="America/Noronha">America/Noronha</option>
                                                        <option value="America/North_Dakota/Beulah">America/North_Dakota/Beulah</option>
                                                        <option value="America/North_Dakota/Center">America/North_Dakota/Center</option>
                                                        <option value="America/North_Dakota/New_Salem">America/North_Dakota/New_Salem</option>
                                                        <option value="America/Ojinaga">America/Ojinaga</option>
                                                        <option value="America/Panama">America/Panama</option>
                                                        <option value="America/Pangnirtung">America/Pangnirtung</option>
                                                        <option value="America/Paramaribo">America/Paramaribo</option>
                                                        <option value="America/Phoenix">America/Phoenix</option>
                                                        <option value="America/Port-au-Prince">America/Port-au-Prince</option>
                                                        <option value="America/Port_of_Spain">America/Port_of_Spain</option>
                                                        <option value="America/Porto_Velho">America/Porto_Velho</option>
                                                        <option value="America/Puerto_Rico">America/Puerto_Rico</option>
                                                        <option value="America/Rainy_River">America/Rainy_River</option>
                                                        <option value="America/Rankin_Inlet">America/Rankin_Inlet</option>
                                                        <option value="America/Recife">America/Recife</option>
                                                        <option value="America/Regina">America/Regina</option>
                                                        <option value="America/Resolute">America/Resolute</option>
                                                        <option value="America/Rio_Branco">America/Rio_Branco</option>
                                                        <option value="America/Santa_Isabel">America/Santa_Isabel</option>
                                                        <option value="America/Santarem">America/Santarem</option>
                                                        <option value="America/Santiago">America/Santiago</option>
                                                        <option value="America/Santo_Domingo">America/Santo_Domingo</option>
                                                        <option value="America/Sao_Paulo">America/Sao_Paulo</option>
                                                        <option value="America/Scoresbysund">America/Scoresbysund</option>
                                                        <option value="America/Sitka">America/Sitka</option>
                                                        <option value="America/St_Barthelemy">America/St_Barthelemy</option>
                                                        <option value="America/St_Johns">America/St_Johns</option>
                                                        <option value="America/St_Kitts">America/St_Kitts</option>
                                                        <option value="America/St_Lucia">America/St_Lucia</option>
                                                        <option value="America/St_Thomas">America/St_Thomas</option>
                                                        <option value="America/St_Vincent">America/St_Vincent</option>
                                                        <option value="America/Swift_Current">America/Swift_Current</option>
                                                        <option value="America/Tegucigalpa">America/Tegucigalpa</option>
                                                        <option value="America/Thule">America/Thule</option>
                                                        <option value="America/Thunder_Bay">America/Thunder_Bay</option>
                                                        <option value="America/Tijuana">America/Tijuana</option>
                                                        <option value="America/Toronto" selected="selected">America/Toronto</option>
                                                        <option value="America/Tortola">America/Tortola</option>
                                                        <option value="America/Vancouver">America/Vancouver</option>
                                                        <option value="America/Whitehorse">America/Whitehorse</option>
                                                        <option value="America/Winnipeg">America/Winnipeg</option>
                                                        <option value="America/Yakutat">America/Yakutat</option>
                                                        <option value="America/Yellowknife">America/Yellowknife</option>
                                                        <option value="Antarctica/Casey">Antarctica/Casey</option>
                                                        <option value="Antarctica/Davis">Antarctica/Davis</option>
                                                        <option value="Antarctica/DumontDUrville">Antarctica/DumontDUrville</option>
                                                        <option value="Antarctica/Macquarie">Antarctica/Macquarie</option>
                                                        <option value="Antarctica/Mawson">Antarctica/Mawson</option>
                                                        <option value="Antarctica/McMurdo">Antarctica/McMurdo</option>
                                                        <option value="Antarctica/Palmer">Antarctica/Palmer</option>
                                                        <option value="Antarctica/Rothera">Antarctica/Rothera</option>
                                                        <option value="Antarctica/Syowa">Antarctica/Syowa</option>
                                                        <option value="Antarctica/Vostok">Antarctica/Vostok</option>
                                                        <option value="Arctic/Longyearbyen">Arctic/Longyearbyen</option>
                                                        <option value="Asia/Aden">Asia/Aden</option>
                                                        <option value="Asia/Almaty">Asia/Almaty</option>
                                                        <option value="Asia/Amman">Asia/Amman</option>
                                                        <option value="Asia/Anadyr">Asia/Anadyr</option>
                                                        <option value="Asia/Aqtau">Asia/Aqtau</option>
                                                        <option value="Asia/Aqtobe">Asia/Aqtobe</option>
                                                        <option value="Asia/Ashgabat">Asia/Ashgabat</option>
                                                        <option value="Asia/Baghdad">Asia/Baghdad</option>
                                                        <option value="Asia/Bahrain">Asia/Bahrain</option>
                                                        <option value="Asia/Baku">Asia/Baku</option>
                                                        <option value="Asia/Bangkok">Asia/Bangkok</option>
                                                        <option value="Asia/Beirut">Asia/Beirut</option>
                                                        <option value="Asia/Bishkek">Asia/Bishkek</option>
                                                        <option value="Asia/Brunei">Asia/Brunei</option>
                                                        <option value="Asia/Choibalsan">Asia/Choibalsan</option>
                                                        <option value="Asia/Chongqing">Asia/Chongqing</option>
                                                        <option value="Asia/Colombo">Asia/Colombo</option>
                                                        <option value="Asia/Damascus">Asia/Damascus</option>
                                                        <option value="Asia/Dhaka">Asia/Dhaka</option>
                                                        <option value="Asia/Dili">Asia/Dili</option>
                                                        <option value="Asia/Dubai">Asia/Dubai</option>
                                                        <option value="Asia/Dushanbe">Asia/Dushanbe</option>
                                                        <option value="Asia/Gaza">Asia/Gaza</option>
                                                        <option value="Asia/Harbin">Asia/Harbin</option>
                                                        <option value="Asia/Hebron">Asia/Hebron</option>
                                                        <option value="Asia/Ho_Chi_Minh">Asia/Ho_Chi_Minh</option>
                                                        <option value="Asia/Hong_Kong">Asia/Hong_Kong</option>
                                                        <option value="Asia/Hovd">Asia/Hovd</option>
                                                        <option value="Asia/Irkutsk">Asia/Irkutsk</option>
                                                        <option value="Asia/Jakarta">Asia/Jakarta</option>
                                                        <option value="Asia/Jayapura">Asia/Jayapura</option>
                                                        <option value="Asia/Jerusalem">Asia/Jerusalem</option>
                                                        <option value="Asia/Kabul">Asia/Kabul</option>
                                                        <option value="Asia/Kamchatka">Asia/Kamchatka</option>
                                                        <option value="Asia/Karachi">Asia/Karachi</option>
                                                        <option value="Asia/Kashgar">Asia/Kashgar</option>
                                                        <option value="Asia/Kathmandu">Asia/Kathmandu</option>
                                                        <option value="Asia/Khandyga">Asia/Khandyga</option>
                                                        <option value="Asia/Kolkata">Asia/Kolkata</option>
                                                        <option value="Asia/Krasnoyarsk">Asia/Krasnoyarsk</option>
                                                        <option value="Asia/Kuala_Lumpur">Asia/Kuala_Lumpur</option>
                                                        <option value="Asia/Kuching">Asia/Kuching</option>
                                                        <option value="Asia/Kuwait">Asia/Kuwait</option>
                                                        <option value="Asia/Macau">Asia/Macau</option>
                                                        <option value="Asia/Magadan">Asia/Magadan</option>
                                                        <option value="Asia/Makassar">Asia/Makassar</option>
                                                        <option value="Asia/Manila">Asia/Manila</option>
                                                        <option value="Asia/Muscat">Asia/Muscat</option>
                                                        <option value="Asia/Nicosia">Asia/Nicosia</option>
                                                        <option value="Asia/Novokuznetsk">Asia/Novokuznetsk</option>
                                                        <option value="Asia/Novosibirsk">Asia/Novosibirsk</option>
                                                        <option value="Asia/Omsk">Asia/Omsk</option>
                                                        <option value="Asia/Oral">Asia/Oral</option>
                                                        <option value="Asia/Phnom_Penh">Asia/Phnom_Penh</option>
                                                        <option value="Asia/Pontianak">Asia/Pontianak</option>
                                                        <option value="Asia/Pyongyang">Asia/Pyongyang</option>
                                                        <option value="Asia/Qatar">Asia/Qatar</option>
                                                        <option value="Asia/Qyzylorda">Asia/Qyzylorda</option>
                                                        <option value="Asia/Rangoon">Asia/Rangoon</option>
                                                        <option value="Asia/Riyadh">Asia/Riyadh</option>
                                                        <option value="Asia/Sakhalin">Asia/Sakhalin</option>
                                                        <option value="Asia/Samarkand">Asia/Samarkand</option>
                                                        <option value="Asia/Seoul">Asia/Seoul</option>
                                                        <option value="Asia/Shanghai">Asia/Shanghai</option>
                                                        <option value="Asia/Singapore">Asia/Singapore</option>
                                                        <option value="Asia/Taipei">Asia/Taipei</option>
                                                        <option value="Asia/Tashkent">Asia/Tashkent</option>
                                                        <option value="Asia/Tbilisi">Asia/Tbilisi</option>
                                                        <option value="Asia/Tehran">Asia/Tehran</option>
                                                        <option value="Asia/Thimphu">Asia/Thimphu</option>
                                                        <option value="Asia/Tokyo">Asia/Tokyo</option>
                                                        <option value="Asia/Ulaanbaatar">Asia/Ulaanbaatar</option>
                                                        <option value="Asia/Urumqi">Asia/Urumqi</option>
                                                        <option value="Asia/Ust-Nera">Asia/Ust-Nera</option>
                                                        <option value="Asia/Vientiane">Asia/Vientiane</option>
                                                        <option value="Asia/Vladivostok">Asia/Vladivostok</option>
                                                        <option value="Asia/Yakutsk">Asia/Yakutsk</option>
                                                        <option value="Asia/Yekaterinburg">Asia/Yekaterinburg</option>
                                                        <option value="Asia/Yerevan">Asia/Yerevan</option>
                                                        <option value="Atlantic/Azores">Atlantic/Azores</option>
                                                        <option value="Atlantic/Bermuda">Atlantic/Bermuda</option>
                                                        <option value="Atlantic/Canary">Atlantic/Canary</option>
                                                        <option value="Atlantic/Cape_Verde">Atlantic/Cape_Verde</option>
                                                        <option value="Atlantic/Faroe">Atlantic/Faroe</option>
                                                        <option value="Atlantic/Madeira">Atlantic/Madeira</option>
                                                        <option value="Atlantic/Reykjavik">Atlantic/Reykjavik</option>
                                                        <option value="Atlantic/South_Georgia">Atlantic/South_Georgia</option>
                                                        <option value="Atlantic/St_Helena">Atlantic/St_Helena</option>
                                                        <option value="Atlantic/Stanley">Atlantic/Stanley</option>
                                                        <option value="Australia/Adelaide">Australia/Adelaide</option>
                                                        <option value="Australia/Brisbane">Australia/Brisbane</option>
                                                        <option value="Australia/Broken_Hill">Australia/Broken_Hill</option>
                                                        <option value="Australia/Currie">Australia/Currie</option>
                                                        <option value="Australia/Darwin">Australia/Darwin</option>
                                                        <option value="Australia/Eucla">Australia/Eucla</option>
                                                        <option value="Australia/Hobart">Australia/Hobart</option>
                                                        <option value="Australia/Lindeman">Australia/Lindeman</option>
                                                        <option value="Australia/Lord_Howe">Australia/Lord_Howe</option>
                                                        <option value="Australia/Melbourne">Australia/Melbourne</option>
                                                        <option value="Australia/Perth">Australia/Perth</option>
                                                        <option value="Australia/Sydney">Australia/Sydney</option>
                                                        <option value="Europe/Amsterdam">Europe/Amsterdam</option>
                                                        <option value="Europe/Andorra">Europe/Andorra</option>
                                                        <option value="Europe/Athens">Europe/Athens</option>
                                                        <option value="Europe/Belgrade">Europe/Belgrade</option>
                                                        <option value="Europe/Berlin">Europe/Berlin</option>
                                                        <option value="Europe/Bratislava">Europe/Bratislava</option>
                                                        <option value="Europe/Brussels">Europe/Brussels</option>
                                                        <option value="Europe/Bucharest">Europe/Bucharest</option>
                                                        <option value="Europe/Budapest">Europe/Budapest</option>
                                                        <option value="Europe/Busingen">Europe/Busingen</option>
                                                        <option value="Europe/Chisinau">Europe/Chisinau</option>
                                                        <option value="Europe/Copenhagen">Europe/Copenhagen</option>
                                                        <option value="Europe/Dublin">Europe/Dublin</option>
                                                        <option value="Europe/Gibraltar">Europe/Gibraltar</option>
                                                        <option value="Europe/Guernsey">Europe/Guernsey</option>
                                                        <option value="Europe/Helsinki">Europe/Helsinki</option>
                                                        <option value="Europe/Isle_of_Man">Europe/Isle_of_Man</option>
                                                        <option value="Europe/Istanbul">Europe/Istanbul</option>
                                                        <option value="Europe/Jersey">Europe/Jersey</option>
                                                        <option value="Europe/Kaliningrad">Europe/Kaliningrad</option>
                                                        <option value="Europe/Kiev">Europe/Kiev</option>
                                                        <option value="Europe/Lisbon">Europe/Lisbon</option>
                                                        <option value="Europe/Ljubljana">Europe/Ljubljana</option>
                                                        <option value="Europe/London">Europe/London</option>
                                                        <option value="Europe/Luxembourg">Europe/Luxembourg</option>
                                                        <option value="Europe/Madrid">Europe/Madrid</option>
                                                        <option value="Europe/Malta">Europe/Malta</option>
                                                        <option value="Europe/Mariehamn">Europe/Mariehamn</option>
                                                        <option value="Europe/Minsk">Europe/Minsk</option>
                                                        <option value="Europe/Monaco">Europe/Monaco</option>
                                                        <option value="Europe/Moscow">Europe/Moscow</option>
                                                        <option value="Europe/Oslo">Europe/Oslo</option>
                                                        <option value="Europe/Paris">Europe/Paris</option>
                                                        <option value="Europe/Podgorica">Europe/Podgorica</option>
                                                        <option value="Europe/Prague">Europe/Prague</option>
                                                        <option value="Europe/Riga">Europe/Riga</option>
                                                        <option value="Europe/Rome">Europe/Rome</option>
                                                        <option value="Europe/Samara">Europe/Samara</option>
                                                        <option value="Europe/San_Marino">Europe/San_Marino</option>
                                                        <option value="Europe/Sarajevo">Europe/Sarajevo</option>
                                                        <option value="Europe/Simferopol">Europe/Simferopol</option>
                                                        <option value="Europe/Skopje">Europe/Skopje</option>
                                                        <option value="Europe/Sofia">Europe/Sofia</option>
                                                        <option value="Europe/Stockholm">Europe/Stockholm</option>
                                                        <option value="Europe/Tallinn">Europe/Tallinn</option>
                                                        <option value="Europe/Tirane">Europe/Tirane</option>
                                                        <option value="Europe/Uzhgorod">Europe/Uzhgorod</option>
                                                        <option value="Europe/Vaduz">Europe/Vaduz</option>
                                                        <option value="Europe/Vatican">Europe/Vatican</option>
                                                        <option value="Europe/Vienna">Europe/Vienna</option>
                                                        <option value="Europe/Vilnius">Europe/Vilnius</option>
                                                        <option value="Europe/Volgograd">Europe/Volgograd</option>
                                                        <option value="Europe/Warsaw">Europe/Warsaw</option>
                                                        <option value="Europe/Zagreb">Europe/Zagreb</option>
                                                        <option value="Europe/Zaporozhye">Europe/Zaporozhye</option>
                                                        <option value="Europe/Zurich">Europe/Zurich</option>
                                                        <option value="Indian/Antananarivo">Indian/Antananarivo</option>
                                                        <option value="Indian/Chagos">Indian/Chagos</option>
                                                        <option value="Indian/Christmas">Indian/Christmas</option>
                                                        <option value="Indian/Cocos">Indian/Cocos</option>
                                                        <option value="Indian/Comoro">Indian/Comoro</option>
                                                        <option value="Indian/Kerguelen">Indian/Kerguelen</option>
                                                        <option value="Indian/Mahe">Indian/Mahe</option>
                                                        <option value="Indian/Maldives">Indian/Maldives</option>
                                                        <option value="Indian/Mauritius">Indian/Mauritius</option>
                                                        <option value="Indian/Mayotte">Indian/Mayotte</option>
                                                        <option value="Indian/Reunion">Indian/Reunion</option>
                                                        <option value="Pacific/Apia">Pacific/Apia</option>
                                                        <option value="Pacific/Auckland">Pacific/Auckland</option>
                                                        <option value="Pacific/Chatham">Pacific/Chatham</option>
                                                        <option value="Pacific/Chuuk">Pacific/Chuuk</option>
                                                        <option value="Pacific/Easter">Pacific/Easter</option>
                                                        <option value="Pacific/Efate">Pacific/Efate</option>
                                                        <option value="Pacific/Enderbury">Pacific/Enderbury</option>
                                                        <option value="Pacific/Fakaofo">Pacific/Fakaofo</option>
                                                        <option value="Pacific/Fiji">Pacific/Fiji</option>
                                                        <option value="Pacific/Funafuti">Pacific/Funafuti</option>
                                                        <option value="Pacific/Galapagos">Pacific/Galapagos</option>
                                                        <option value="Pacific/Gambier">Pacific/Gambier</option>
                                                        <option value="Pacific/Guadalcanal">Pacific/Guadalcanal</option>
                                                        <option value="Pacific/Guam">Pacific/Guam</option>
                                                        <option value="Pacific/Honolulu">Pacific/Honolulu</option>
                                                        <option value="Pacific/Johnston">Pacific/Johnston</option>
                                                        <option value="Pacific/Kiritimati">Pacific/Kiritimati</option>
                                                        <option value="Pacific/Kosrae">Pacific/Kosrae</option>
                                                        <option value="Pacific/Kwajalein">Pacific/Kwajalein</option>
                                                        <option value="Pacific/Majuro">Pacific/Majuro</option>
                                                        <option value="Pacific/Marquesas">Pacific/Marquesas</option>
                                                        <option value="Pacific/Midway">Pacific/Midway</option>
                                                        <option value="Pacific/Nauru">Pacific/Nauru</option>
                                                        <option value="Pacific/Niue">Pacific/Niue</option>
                                                        <option value="Pacific/Norfolk">Pacific/Norfolk</option>
                                                        <option value="Pacific/Noumea">Pacific/Noumea</option>
                                                        <option value="Pacific/Pago_Pago">Pacific/Pago_Pago</option>
                                                        <option value="Pacific/Palau">Pacific/Palau</option>
                                                        <option value="Pacific/Pitcairn">Pacific/Pitcairn</option>
                                                        <option value="Pacific/Pohnpei">Pacific/Pohnpei</option>
                                                        <option value="Pacific/Port_Moresby">Pacific/Port_Moresby</option>
                                                        <option value="Pacific/Rarotonga">Pacific/Rarotonga</option>
                                                        <option value="Pacific/Saipan">Pacific/Saipan</option>
                                                        <option value="Pacific/Tahiti">Pacific/Tahiti</option>
                                                        <option value="Pacific/Tarawa">Pacific/Tarawa</option>
                                                        <option value="Pacific/Tongatapu">Pacific/Tongatapu</option>
                                                        <option value="Pacific/Wake">Pacific/Wake</option>
                                                        <option value="Pacific/Wallis">Pacific/Wallis</option>
                                                        <option value="UTC">UTC</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="short_date" class="control-label"><?php echo __('Short date', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-3 no-padding">
                                                    <select class="form-control" name="short_date" id="short_date">
                                                        <option value="m-d-Y">12-21-<?php echo date('Y'); ?> (MM-DD-YYYY)</option>
                                                        <option value="j-m-Y">5-12-<?php echo date('Y'); ?> (D-MM-YYYY)</option>
                                                        <option value="d-m-Y">05-12-<?php echo date('Y'); ?> (DD-MM-YYYY)</option>
                                                        <option value="d.m.Y">05.12.<?php echo date('Y'); ?> (DD.MM.YYYY)</option>
                                                        <option value="Y-m-d"><?php echo date('Y'); ?>-12-05 (YYYY-MM-DD)</option>
                                                        <option value="Y.m.d"><?php echo date('Y'); ?>.12.05 (YYYY.MM.DD)</option>
                                                        <option value="m-j-y">12-5-<?php echo date('y'); ?> (MM-D-YY)</option>
                                                        <option value="j-m-y">21-12-<?php echo date('y'); ?> (D-MM-YY)</option>
                                                        <option value="M d Y">Dec 21 <?php echo date('Y'); ?></option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="long_date" class="control-label"><?php echo __('Long date', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-3 no-padding">
                                                    <select class="form-control" name="long_date" id="long_date">
                                                        <option value="M d Y">December 21, <?php echo date('Y'); ?></option>
                                                        <option value="d M Y H:i">21 December <?php echo date('Y'); ?> 19:00</option>
                                                        <option value="F d Y h:i a">December 21, <?php echo date('Y'); ?> 4:00 am</option>
                                                        <option value="l d F, Y">Monday 21 December, <?php echo date('Y'); ?></option>
                                                        <option value="l d F Y H:i">Monday 21 December <?php echo date('Y'); ?> 07:00</option>
                                                        <option value="D. d, F">Mon. 12, December</option>
                                                        <option value="l, d.m. F">Wednesday, 09.07. July</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label class="control-label"><?php echo __('Mailer', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-12 no-padding">
                                                    <label> <?php echo __('PHP', 'configuration'); ?> <input type="radio" name="mail_setting" value="PHP" id="mail_setting"/></label>  &nbsp; 
                                                    <label> <?php echo __('SMTP', 'configuration'); ?> <input type="radio" name="mail_setting" value="SMTP" id="mail_setting"/></label>
                                                </div>
                                                <script>
                                                    $(document).ready(function () {
                                                        $('input[name="mail_setting"]').on('ifClicked', function () {
                                                            if ($(this).val() == 'PHP') {
                                                                $('#smtp_config').hide();
                                                            } else {
                                                                $('#smtp_config').show();
                                                            }
                                                        });
                                                    });
                                                </script>
                                                <div class="col-md-12 no-padding" id="smtp_config">
                                                    <div class="form-group" style="padding-top:10px;">
                                                        <label for="smtp_server" class="col-md-2 control-label"><?php echo __('Server', 'configuration'); ?><span class="requiredfield"> *</span></label>
                                                        <div class="col-md-5  ">
                                                            <input type="text" class="form-control" id="smtp_server"  name="smtp_server" placeholder='<?php echo __('Server', 'configuration'); ?>'>
                                                            <div class="has-error"><p id="err-sever" style="display:none;" class='help-block'><?php echo __('The field is required', 'configuration'); ?></p></div>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="smtp_port" class="col-md-2 control-label"><?php echo __('Port', 'configuration'); ?><span class="requiredfield"> *</span></label>
                                                        <div class="col-md-2">
                                                            <input type="text" class="form-control" id="smtp_port" name="smtp_port" placeholder='<?php echo __('Port', 'configuration'); ?>'>
                                                            <div class="has-error"><p id="err-port" style="display:none;" class='help-block'><?php echo __('The field is required', 'configuration'); ?></p></div>
                                                        </div>

                                                    </div>
                                                    <div class="form-group">
                                                        <label for="smtp_username" class="col-md-2 control-label"><?php echo __('Username', 'configuration'); ?><span class="requiredfield"> *</span></label>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" id="smtp_username"  name="smtp_username"placeholder='<?php echo __('Username', 'configuration'); ?>'>
                                                            <div class="has-error"><p id="err-username" style="display:none;" class='help-block'><?php echo __('The field is required', 'configuration'); ?></p></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="smtp_password" class="col-md-2 control-label"><?php echo __('Password', 'configuration'); ?><span class="requiredfield"> *</span></label>
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control" id="smtp_password"  name="smtp_password"placeholder='<?php echo __('Password', 'configuration'); ?>'>
                                                            <div class="has-error"><p id="err-password" style="display:none;" class='help-block'><?php echo __('The field is required', 'configuration'); ?></p></div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-md-2 control-label"><?php echo __('SSL', 'configuration'); ?></label>
                                                        <div class="col-md-4">
                                                            <div class="form-control-static">
                                                                <label><?php echo __('Yes', 'configuration'); ?> <input type="radio" name="smtp_ssl" value="1" id="smtp_ssl"  /></label>  &nbsp; 
                                                                <label><?php echo __('No', 'configuration'); ?> <input type="radio" name="smtp_ssl" value="0" id="smtp_ssl"  /></label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="site_meta_keywords" class="control-label"><?php echo __('Site meta keywords', 'configuration'); ?></label></th>
                                            <td><input type="text" id="site_meta_keywords"  class="form-control" name="site_meta_keywords"></td>
                                        </tr>
                                        <tr>
                                            <th><label for="site_meta_description" class="control-label"><?php echo __('Site meta description', 'configuration'); ?></label></th>
                                            <td><textarea id="site_meta_description" class="form-control" name="site_meta_description" style="height: 10em;"></textarea></td>
                                        </tr>
                                        <tr>
                                            <th><label class="control-label" for="site_language"><?php echo __('Site languages', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-3 no-padding">
                                                    <select class="form-control" name="site_language" id="site_language">
                                                        <option value="en-us">English (United States) </option>
                                                        <option value="ja">Japanese</option>
                                                        <option value="es">Spanish (Spain)</option>
                                                        <option value="zh-cn">Chinese (PRC)</option>
                                                        <option value="de">German (Standard)</option>
                                                        <option value="fr">French (Standard)</option>
                                                        <option value="ru">Russian</option>
                                                        <option value="pt-br">Portuguese - Brazil</option>
                                                    </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label class="control-label" for="html_language"><?php echo __('HTML language', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-3 no-padding">
                                                    <select class="form-control" name="html_language" id="html_language">
                                                    	<option value="en">English</option>
                                                        <option value="ab">Abkhazian</option>
														<option value="aa">Afar</option>
														<option value="af">Afrikaans</option>
														<option value="sq">Albanian</option>
														<option value="am">Amharic</option>
														<option value="ar">Arabic</option>
														<option value="an">Aragonese</option>
														<option value="hy">Armenian</option>
														<option value="as">Assamese</option>
														<option value="ay">Aymara</option>
														<option value="az">Azerbaijani</option>
														<option value="ba">Bashkir</option>
														<option value="eu">Basque</option>
														<option value="bn">Bengali (Bangla)</option>
														<option value="dz">Bhutani</option>
														<option value="bh">Bihari</option>
														<option value="bi">Bislama</option>
														<option value="br">Breton</option>
														<option value="bg">Bulgarian</option>
														<option value="my">Burmese</option>
														<option value="be">Byelorussian (Belarusian)</option>
														<option value="km">Cambodian</option>
														<option value="ca">Catalan</option>
														<option value="zh">Chinese</option>
														<option value="zh-Hans">Chinese (Simplified)</option>
														<option value="zh-Hant">Chinese (Traditional)</option>
														<option value="co">Corsican</option>
														<option value="hr">Croatian</option>
														<option value="cs">Czech</option>
														<option value="da">Danish</option>
														<option value="nl">Dutch</option>
														<option value="eo">Esperanto</option>
														<option value="et">Estonian</option>
														<option value="fo">Faeroese</option>
														<option value="fa">Farsi</option>
														<option value="fj">Fiji</option>
														<option value="fi">Finnish</option>
														<option value="fr">French</option>
														<option value="fy">Frisian</option>
														<option value="gl">Galician</option>
														<option value="gd">Gaelic (Scottish)</option>
														<option value="gv">Gaelic (Manx)</option>
														<option value="ka">Georgian</option>
														<option value="de">German</option>
														<option value="el">Greek</option>
														<option value="kl">Greenlandic</option>
														<option value="gn">Guarani</option>
														<option value="gu">Gujarati</option>
														<option value="ht">Haitian Creole</option>
														<option value="ha">Hausa</option>
														<option value="he, iw">Hebrew</option>
														<option value="hi">Hindi</option>
														<option value="hu">Hungarian</option>
														<option value="is">Icelandic</option>
														<option value="io">Ido</option>
														<option value="id, in">Indonesian</option>
														<option value="ia">Interlingua</option>
														<option value="ie">Interlingue</option>
														<option value="iu">Inuktitut</option>
														<option value="ik">Inupiak</option>
														<option value="ga">Irish</option>
														<option value="it">Italian</option>
														<option value="ja">Japanese</option>
														<option value="jv">Javanese</option>
														<option value="kn">Kannada</option>
														<option value="ks">Kashmiri</option>
														<option value="kk">Kazakh</option>
														<option value="rw">Kinyarwanda (Ruanda)</option>
														<option value="ky">Kirghiz</option>
														<option value="rn">Kirundi (Rundi)</option>
														<option value="ko">Korean</option>
														<option value="ku">Kurdish</option>
														<option value="lo">Laothian</option>
														<option value="la">Latin</option>
														<option value="lv">Latvian (Lettish)</option>
														<option value="li">Limburgish ( Limburger)</option>
														<option value="ln">Lingala</option>
														<option value="lt">Lithuanian</option>
														<option value="mk">Macedonian</option>
														<option value="mg">Malagasy</option>
														<option value="ms">Malay</option>
														<option value="ml">Malayalam</option>
														<option value="mt">Maltese</option>
														<option value="mi">Maori</option>
														<option value="mr">Marathi</option>
														<option value="mo">Moldavian</option>
														<option value="mn">Mongolian</option>
														<option value="na">Nauru
														<option value="ne">Nepali</option>
														<option value="no">Norwegian</option>
														<option value="oc">Occitan</option>
														<option value="or">Oriya</option>
														<option value="om">Oromo (Afaan Oromo)</option>
														<option value="ps">Pashto (Pushto)</option>
														<option value="pl">Polish</option>
														<option value="pt">Portuguese</option>
														<option value="pa">Punjabi</option>
														<option value="qu">Quechua</option>
														<option value="rm">Rhaeto-Romance</option>
														<option value="ro">Romanian</option>
														<option value="ru">Russian</option>
														<option value="sm">Samoan</option>
														<option value="sg">Sangro
														<option value="sa">Sanskrit</option>
														<option value="sr">Serbian</option>
														<option value="sh">Serbo-Croatian</option>
														<option value="st">Sesotho</option>
														<option value="tn">Setswana</option>
														<option value="sn">Shona</option>
														<option value="ii">Sichuan Yi</option>
														<option value="sd">Sindhi</option>
														<option value="si">Sinhalese</option>
														<option value="ss">Siswati</option>
														<option value="sk">Slovak</option>
														<option value="sl">Slovenian</option>
														<option value="so">Somali</option>
														<option value="es">Spanish</option>
														<option value="su">Sundanese</option>
														<option value="sw">Swahili (Kiswahili)</option>
														<option value="sv">Swedish</option>
														<option value="tl">Tagalog</option>
														<option value="tg">Tajik</option>
														<option value="ta">Tamil</option>
														<option value="tt">Tatar</option>
														<option value="te">Telugu</option>
														<option value="th">Thai</option>
														<option value="bo">Tibetan</option>
														<option value="ti">Tigrinya</option>
														<option value="to">Tonga</option>
														<option value="ts">Tsonga</option>
														<option value="tr">Turkish</option>
														<option value="tk">Turkmen</option>
														<option value="tw">Twi</option>
														<option value="ug">Uighur</option>
														<option value="uk">Ukrainian</option>
														<option value="ur">Urdu</option>
														<option value="uz">Uzbek</option>
														<option value="vi">Vietnamese</option>
														<option value="vo">Volap�k</option>
														<option value="wa">Wallon</option>
														<option value="cy">Welsh</option>
														<option value="wo">Wolof</option>
														<option value="xh">Xhosa</option>
														<option value="yi, ji">Yiddish</option>
														<option value="yo">Yoruba</option>
														<option value="zu">Zulu</option>
                                                  </select>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label class="control-label"><?php echo __('Enable log', 'configuration'); ?></label></th>
                                            <td>
                                                <label><?php echo __('Yes', 'configuration'); ?> <input type="radio" name="enable_log" value="1" id="enable_log"  /></label>  &nbsp; 
                                                <label><?php echo __('No', 'configuration'); ?> <input type="radio" name="enable_log" value="0" id="enable_log"  /></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label class="control-label"><?php echo __('Enable search', 'configuration'); ?></label></th>
                                            <td>
                                                <label><?php echo __('Yes', 'configuration'); ?> <input type="radio" name="enable_search" value="1" id="enable_search"  /></label>  &nbsp; 
                                                <label><?php echo __('No', 'configuration'); ?> <input type="radio" name="enable_search" value="0" id="enable_search"  /></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="site_meta_description" class="control-label"><?php echo __('Google analytics', 'configuration'); ?></label></th>
                                            <td><textarea id="google_analytics" class="form-control" name="google_analytics" style="height: 10em;"></textarea></td>
                                        </tr>
                                        <tr>
                                            <th><label for="website_email" class="control-label"><?php echo __('Enable reCAPTCHA', 'configuration'); ?></label></th>
                                            <td>
                                                <label> <?php echo __('Yes', 'configuration'); ?> <input type="radio" name="recaptcha_enable" value="1" id="recaptcha_enable"/></label>  &nbsp; 
                                                <label> <?php echo __('No', 'configuration'); ?> <input type="radio" name="recaptcha_enable" value="0" id="recaptcha_enable"/></label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th><label for="website_email" class="control-label"><?php echo __('reCAPTCHA', 'configuration'); ?></label></th>
                                            <td>
                                                <div class="col-md-12 no-padding">
                                                    <label><?php echo __('Public key', 'configuration'); ?></label>
                                                    <input type="text" id="recaptcha_public_key" class="form-control" name="recaptcha_public_key">
                                                </div>
                                                <div class="col-md-12 no-padding" style="margin-bottom: 10px;">
                                                    <label><?php echo __('Private key', 'configuration'); ?></label>
                                                    <input type="text" id="recaptcha_private_key" class="form-control" name="recaptcha_private_key">
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>
                        <?php if (!empty($setting->properties)) { ?>
                            <?php foreach ($setting->properties as $k => $property) { ?>
                                <div class="tab-pane" id="<?php echo $k; ?>tab-pane">
                                    <div class="box-body" id="<?php echo $k; ?>">
                                        <form>
                                            <table class="table table-bordered table-configuration">
                                                <col class="col-md-2" />
                                                <?php if (!empty($property['elements'])) { ?>
                                                    <?php foreach ($property['elements'] as $element) { ?>
                                                        <tr>
                                                            <th><label for="site_meta_description" class="control-label"><?php echo $element[0] ?></label></th>
                                                            <td><?php echo $element[1] ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                <?php } ?>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</div>
<script>
    function pf_setting_save_change() {
        var obj = {};
        var smtp_server = $('#smtp_server').val();
        var smtp_port = $('#smtp_port').val();
        var smtp_username = $('#smtp_username').val();
        var smtp_password = $('#smtp_password').val();
        var mail_setting = $("#mail_setting:checked").val();
        $('.box-body').each(function () {
            var id = $(this).attr('id');
            obj[id] = $(this).children('form').serializeObject();
        });
//        validation field smtp (linhdh)
        if (mail_setting == 'SMTP') {
            if (smtp_server == '') {
                $('#err-sever').show();
            }
            else {
                $('#err-sever').hide();
            }
            if (smtp_port == '') {
                $('#err-port').show();
            }
            else {
                $('#err-port').hide();
            }
            if (smtp_username == '') {
                $('#err-username').show();
            }
            else {
                $('#err-username').hide();
            }
            if (smtp_password == '') {
                $('#err-password').show();
            }
            else {
                $('#err-password').hide();
            }
        }
        if (smtp_server != '' && smtp_port != '' && smtp_username != '' && smtp_password != '' || mail_setting == 'PHP') {
            $.post('<?php echo admin_url('action=save') ?>', obj, function (json) {
                $.notification({type: "success", width: "400", content: "<i class='fa fa-check fa-2x'></i><?php echo __("Configuration is updated successfully", 'configuration'); ?>", html: true, autoClose: true, timeOut: "2000", delay: "0", position: "topRight", effect: "fade", animate: "fadeDown", easing: "easeInOutQuad", duration: "300"});
            }, 'json');
        }

    }
<?php if (!empty($data)) { ?>
        $(document).ready(function () {
            json_data = <?php echo $data; ?>;
            for (var k in json_data) {
                var elements = json_data[k];
                for (var k1 in elements) {
                    switch ($('#' + k + ' #' + k1).attr('type')) {
                        case 'radio':
                            $('#' + k + " input[id='" + k1 + "']").each(function () {
                                if (elements[k1] != null && elements[k1] == $(this).val()) {
                                    $(this).iCheck('check');
                                }
                            });
                            break;
                        case 'checkbox':
                            if (elements[k1] != null && elements[k1] == $('#' + k + ' #' + k1).val()) {
                                $('#' + k + ' #' + k1).iCheck('check');
                            }
                            break;
                        default:
                            $('#' + k + ' #' + k1).val(elements[k1]);
                            break;
                    }
                }
            }
        });
<?php } ?>
    $(document).ready(function () {
        if ($('input[name="mail_setting"]:checked').val() == 'PHP') {
            $('#smtp_config').hide();
        } else {
            $('#smtp_config').show();
        }

        if (+$('input[name="site_offline"]:checked').val() === 1) {
            $('#site_offline_page').show();
        } else {
            $('#site_offline_page').hide();
        }
    });

    $(document).ready(function () {
    	if ($('input[name="allow_resize_image"]:checked').val() == 1) {
    		$('#allow_resize_image_width,#allow_resize_image_height').parent().parent().show();
    	}else{
    		$('#allow_resize_image_width,#allow_resize_image_height').parent().parent().hide();
    	}
        $('input[name="allow_resize_image"]').on('ifClicked', function () {
            if (+$(this).val() === 1) {
                $('#allow_resize_image_width,#allow_resize_image_height').parent().parent().show();
            } else {
            	$('#allow_resize_image_width,#allow_resize_image_height').parent().parent().hide();
            }
        });
    });
</script>