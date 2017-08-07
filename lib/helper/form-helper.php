<?php
defined('PF_VERSION') OR header('Location:404.html');
define('FORM_HELPER_SHORTCODE_LIST',__TINYMCE_SHORTCODE_LIST__);
function form_hidden($name, $value = '')
{
    $hidden = '';

    if (is_array($name)) {
        foreach ($name as $key => $val) {
            $hidden .= form_hidden($key, $val);
        }
        return $hidden;
    }

    $value = set_value($name, $value);

    if (!is_array($value)) {
        $hidden .= '<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . form_prep($value) . '" />' . "\n";
    } else {
        foreach ($value as $k => $v) {
            $k = (is_int($k)) ? '' : $k;
            $hidden .= form_hidden($name . '[' . $k . ']', $v);
        }
    }

    return $hidden;
}

function form_date($data = '', $value = '',$format = 'YYYY-MM-DD', $extra = ''){
    $defaults = array(
            'type' => 'text',
            'name' => ((!is_array($data)) ? $data : ''),
            'class' => 'form-control',
            'value' => ((!is_array($data)) ? set_value($data, $value) : $value)
    );
    
    if (is_array($data) && !empty($data ['name'])) {
        $data ['value'] = set_value($data ['name'], ((!empty($data ['value'])) ? $data ['value'] : ''));
    }
    
    if (!empty($data['type'])) {
        if (!empty($_POST)) {
            unset($defaults ['value']);
            unset($data ['value']);
        }
    }
    if (!empty($data['value']) && is_string($data['value'])){
        $data['value'] = str_to_mysqldate($data['value'],$format,$format);
    }
    
    $date_id = 'date-'.time().'-'.rand(1, 1000);
    
    return '<div class="input-group date" id="'.$date_id.'" data-date-format="'.$format.'">
                '."<input " . _parse_attributes($data, $defaults) . $extra . " />".'
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <script>
                $(document).ready(function(){
                    $("#'.$date_id.'").datetimepicker({pickTime: false});
                });   
            </script>';
}

function form_date_time($data = '', $value = '',$format = 'YYYY-MM-DD', $extra = ''){
    $defaults = array(
            'type' => 'text',
            'name' => ((!is_array($data)) ? $data : ''),
            'class' => 'form-control',
            'value' => ((!is_array($data)) ? set_value($data, $value) : $value)
    );

    if (is_array($data) && !empty($data ['name'])) {
        $data ['value'] = set_value($data ['name'], ((!empty($data ['value'])) ? $data ['value'] : ''));
    }

    if (!empty($data['type'])) {
        if (!empty($_POST)) {
            unset($defaults ['value']);
            unset($data ['value']);
        }
    }
    if (!empty($data['value']) && is_string($data['value'])){
        $data['value'] = str_to_mysqldate($data['value'],$format,$format);
    }

    $date_id = 'date-'.time().'-'.rand(1, 1000);

    return '<div class="input-group date" id="'.$date_id.'" data-date-format="'.$format.' hh:mm A">
                '."<input " . _parse_attributes($data, $defaults) . $extra . " />".'
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span>
                </span>
            </div>
            <script>
                $(document).ready(function(){
                    $("#'.$date_id.'").datetimepicker();
                });
            </script>';
}

function form_media($data = '', $value = '', $extra = ''){
    $defaults = array(
            'type' => 'text',
            'name' => ((!is_array($data)) ? $data : ''),
            'class' => 'form-control',
            'value' => ((!is_array($data)) ? set_value($data, $value) : $value)
    );
    
    if (is_array($data) && !empty($data ['name'])) {
        $data ['value'] = set_value($data ['name'], ((!empty($data ['value'])) ? $data ['value'] : ''));
    }
    
    if (!empty($data['type']) && $data['type'] == 'password') {
        if (!empty($_POST)) {
            unset($defaults ['value']);
            unset($data ['value']);
        }
    }
    
    $media_id = 'media-'.time().'-'.rand(1, 1000);
    
    $return = '
    <div class="input-group">
      '."<input " . _parse_attributes($data, $defaults) . $extra . " />".'
      <span class="input-group-btn">
        <a class="btn btn-default" id="'.$media_id.'"  href="'.site_url(false) . RELATIVE_PATH . '/app/plugins/default/media/filemanager/dialog.php?type=1&field_id='.$defaults['name'].'" ><i class="fa fa-file-image-o"></i></a>
      </span>
    </div>
<script>
    $("#'.$media_id.'").fancybox({
        "width": "75%",
        "height": "90%",
        "autoScale": false,
        "transitionIn": "none",
        "transitionOut": "none",
        "type": "iframe"
    }); 
</script>';
    
    return $return;
}

function form_input($data = '', $value = '', $extra = '')
{
    $defaults = array(
        'type' => 'text',
        'name' => ((!is_array($data)) ? $data : ''),
        'class' => 'form-control',
        'value' => ((!is_array($data)) ? set_value($data, $value) : $value)
    );

    if (is_array($data) && !empty($data ['name'])) {
        $data ['value'] = set_value($data ['name'], ((!empty($data ['value'])) ? $data ['value'] : ''));
    }

    if (!empty($data['type']) && $data['type'] == 'password') {
        if (!empty($_POST)) {
            unset($defaults ['value']);
            unset($data ['value']);
        }
    }

    return "<input " . _parse_attributes($data, $defaults) . $extra . " />";
}

function form_password($data = '', $value = '', $extra = '')
{
    if (!is_array($data)) {
        $data = array(
            'name' => $data,
            'value' => $value
        );
    }

    $data ['type'] = 'password';
    return form_input($data, $value, $extra);
}

function form_upload($data = '', $value = '', $extra = '')
{
    if (!is_array($data)) {
        $data = array(
            'name' => $data
        );
    }

    $data ['type'] = 'file';
    return form_input($data, $value, $extra);
}

function form_textarea($data = '', $value = '', $extra = '')
{
    if (is_array($data) && !empty($data['value'])) {
        $data['value'] = '';
    }
    $defaults = array(
        'name' => ((!is_array($data)) ? $data : ''),
        'class' => 'form-control',
        'cols' => '40',
        'rows' => '10'
    );

    if (!is_array($data) || !isset($data ['value'])) {
        if (is_array($data)) {
            if (!empty($data['name'])) {
                $val = set_value($data['name'], $value);
            } else {
                $val = $value;
            }
        } else {
            $val = set_value($data, $value);
        }
    } else {
        $val = (!empty($data['name'])) ? set_value($data['name'], $data ['value']) : $data ['value'];
        unset($data ['value']); // textareas don't use the value attribute
    }

    $name = (is_array($data)) ? $data ['name'] : $data;
    return "<textarea " . _parse_attributes($data, $defaults) . $extra . ">" . form_prep($val) . "</textarea>";
}

function form_editor($data = '', $value = '', $extra = '')
{
    $editor = form_textarea($data, $value, $extra);
    $id = "";
    if (!empty($data) && is_string($data)) {
        $id = $data;
    } else if (is_array($data)) {
        $id = (!empty($data['id'])) ? $data['id'] : (!empty($data['name']) ? $data['name'] : '');
    }
    if ($id != "") {
        
    }

    $languages = array(
        'en-us' => 'en_GB',
        'ja' => 'ja',
        'zh-cn' => 'zh_CN',
        'de' => 'de',
        'fr' => 'fr_FR'
    );
    
    $editor .= '
<style>
.mce-textbox{
  line-height:20px;
}
</style>
<script type="text/javascript">
$(document).ready(function(){

    tinymce.init({
        selector: "#' . $id . '",
        language: "' . $languages[DEFAULT_LOCALE] . '",
        fontsize_formats: "8pt 9pt 10pt 11pt 12pt 26pt 36pt",
        convert_urls: false,
        forced_root_block : "",
        force_br_newlines:true,
        force_p_newlines:false,
        convert_newlines_to_brs:true,
        save_enablewhendirty: true,
        save_onsavecallback: function() {console.log("Save");},
        valid_elements : "@[id|class|style|title|dir<ltr?rtl|lang|xml::lang|onclick|ondblclick|"
                        + "onmousedown|onmouseup|onmouseover|onmousemove|onmouseout|onkeypress|"
                        + "onkeydown|onkeyup],a[rel|rev|charset|hreflang|tabindex|accesskey|type|"
                        + "name|href|target|title|class|onfocus|onblur],strong/b,em,i,strike,u,"
                        + "#p,-ol[type|compact],-ul[type|compact],-li,br,img[longdesc|usemap|"
                        + "src|border|alt=|title|hspace|vspace|width|height|align],-sub,-sup,"
                        + "-blockquote,-table[border=0|cellspacing|cellpadding|width|frame|rules|"
                        + "height|align|summary|bgcolor|background|bordercolor],-tr[rowspan|width|"
                        + "height|align|valign|bgcolor|background|bordercolor],tbody,thead,tfoot,"
                        + "#td[colspan|rowspan|width|height|align|valign|bgcolor|background|bordercolor"
                        + "|scope],#th[colspan|rowspan|width|height|align|valign|scope],caption,-div,"
                        + "-span,-code,-pre,address,-h1,-h2,-h3,-h4,-h5,-h6,hr[size|noshade],-font[face"
                        + "|size|color],dd,dl,dt,cite,abbr,acronym,del[datetime|cite],ins[datetime|cite],"
                        + "object[classid|width|height|codebase|*],param[name|value|_value],embed[type|width"
                        + "|height|src|*],script[src|type],map[name],area[shape|coords|href|alt|target],bdo,"
                        + "button,col[align|char|charoff|span|valign|width],colgroup[align|char|charoff|span|"
                        + "valign|width],dfn,fieldset,form[action|accept|accept-charset|enctype|method],"
                        + "input[accept|alt|checked|disabled|maxlength|name|readonly|size|src|type|value],"
                        + "kbd,label[for],legend,noscript,optgroup[label|disabled],option[disabled|label|selected|value],"
                        + "q[cite],samp,select[disabled|multiple|name|size],small,"
                        + "textarea[cols|rows|disabled|name|readonly],tt,var,big,time",
        filemanager_title:"Filemanager",
        external_filemanager_path:"' . RELATIVE_PATH . '/app/plugins/default/media/filemanager/",
        external_plugins: { "filemanager" : "' . RELATIVE_PATH . '/app/plugins/default/media/filemanager/plugin.min.js"},
        menubar: "' . __TINYMCE_MENUBAR__ . '",
        plugins: ' . __TINYMCE_PLUGINS__ . ',
        ' . __TINYMCE_TOOLBAR__ . ',
        image_advtab: true,
         setup: function(editor) {
            editor.on("Init",function(ed){
                ed.target.getDoc().body.style.fontSize = "13px";
                ed.target.getDoc().body.style.lineHeight = "19px";
                //console.log(ed.target.iframeHTML);
            });
             editor.addButton("shortcode", {
                 title: "Shortcode manager",
                 onclick: function() {
                    var shortcode_manager_popup = editor.windowManager.open({
                    title: "Shortcode Manager ",
                    width:400,
                    height:80,
                        body: [
                            {type: "listbox", 
                                name: "shortcode_list", 
                                label: "Shortcode", 
                                "values": [
                                    '.FORM_HELPER_SHORTCODE_LIST.'
                                ]
                            }
                        ],
                        onsubmit: function(e) {    
                            editor.insertContent(shortcode_manager_popup.find("#shortcode_list")[0].value());
                        }
                    });
                 }
              });
            editor.on("PostProcess", function(ed) {

            });
            editor.on("GetContent",function(ed) {

            });
            editor.on("SaveContent",function(ed) {
                   ed.content = ed.content.replace(new RegExp("<br class=\"br\" />","g"), "\n");
            });
             editor.on("BeforeSetContent",function(ed) {
                    var text = ed.content;
                    var lines = text.split("\n");
                    var html="";
                    var br = true;
                    var count = lines.length;
                    for(i=0;i<count;i++){
                         if(lines[i].length ==0){
                           continue;
                         }
                         re = /<(\w+)[^>]*>/g;
                         var match = re.exec(lines[i]);
                         if(match && "ul" == match[1]){
                           br=false;
                         }
                          html+=lines[i];
                          if(br){
                            if(i!=count-1){
                                j=i+1;
                                if(lines[j].length != 0)
                                    html+="<br class=\"br\"/>";
                            }
                          }
                         if(lines[i] == "</ul>"){
                            br = true;
                         }
                    }
                    ed.content = html;
            });
            editor.on("PostRender", function(ed) {

            });
            editor.on("Paste",function(ed, e) {
               var text = ed.clipboardData.getData("Text");
               text = text.replace(/\r?\n/g, "<br class=\"br\"/>");
            });
            editor.on("SetContent", function(ed) {

            });
         }
      }
    );

});
</script>';

    return $editor;
}

function form_multiselect($name = '', $options = array(), $selected = array(), $extra = '')
{
    if (!strpos($extra, 'multiple')) {
        $extra .= ' multiple="multiple"';
    }

    return form_dropdown($name, $options, $selected, $extra);
}

function form_dropdown($name = '', $options = array(), $selected = array(), $extra = '')
{
    if (!is_array($selected)) {
        $selected = array(
            $selected
        );
    }

    if (isset($_POST [$name])) {
        if (is_array($_POST [$name])) {
            $selected = $_POST [$name];
        } else {
            $selected = array(
                $_POST [$name]
            );
        }
    }

    if ($extra != '')
        $extra = ' ' . $extra;

    $multiple = (count($selected) > 1 && strpos($extra, 'multiple') === false) ? ' multiple="multiple"' : '';

    if (strpos($extra, 'multiple') !== false) {
        $name .= '[]';
    }

    $form = '<select class="form-control" name="' . $name . '"' . $extra . $multiple . ">\n";

    if (!empty($options) && is_array($options)){
        foreach ($options as $key => $val) {
            $key = (string) $key;
    
            if (is_array($val) && !empty($val)) {
                $form .= '<optgroup label="' . $key . '">' . "\n";
    
                foreach ($val as $optgroup_key => $optgroup_val) {
                    $sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';
    
                    $form .= '<option value="' . $optgroup_key . '"' . $sel . '>' . (string) $optgroup_val . "</option>\n";
                }
    
                $form .= '</optgroup>' . "\n";
            } else {
                $sel = (in_array($key, $selected)) ? ' selected="selected"' : '';
    
                $form .= '<option value="' . $key . '"' . $sel . '>' . (string) $val . "</option>\n";
            }
        }
    }

    $form .= '</select>';

    return $form;
}

function form_checkbox($data = '', $value = '', $checked = false, $extra = '')
{
    $defaults = array(
        'type' => 'checkbox',
        'name' => ((!is_array($data)) ? $data : ''),
        'value' => $value
    );

    if (is_array($data) && array_key_exists('checked', $data)) {
        $checked = $data ['checked'];

        if ($checked == false) {
            unset($data ['checked']);
        } else {
            if (!empty($data['name']) && !isset($_POST[$data['name']])) {
                $data ['checked'] = 'checked';
            }
        }
    }

    if ($checked == true) {
        if (is_array($data)) {
            if (!empty($data['name']) && !isset($_POST[$data['name']])) {
                $defaults ['checked'] = 'checked';
            }
        } else {
            if (empty($_REQUEST[$data])) {
                $defaults ['checked'] = 'checked';
            }
        }
    } else {
        unset($defaults ['checked']);
    }

    if (!empty($_POST)) {
        unset($defaults ['checked']);
        if (is_array($data)) {
            unset($data ['checked']);
            if (!empty($data['name']) ) {
                $data['name'] = str_replace('[]', '', $data['name']);
                if (isset($_POST[$data['name']])){
                    if (is_array($_POST[$data['name']])){
                        if (isset($data['value']) && in_array($data['value'], $_POST[$data['name']])) {
                            $data ['checked'] = 'checked';
                        }
                    }else{
                        if (isset($data['value']) && $_POST[$data['name']] == $data['value']) {
                            $data ['checked'] = 'checked';
                        }
                    }
                }
            }
        } else {
            $data = str_replace('[]', '', $data);
            if (isset($_POST[$data])) {
                if (is_array($_POST[$data])){
                    if (isset($defaults['value']) && in_array($defaults['value'], $_POST[$data])) {
                        $defaults ['checked'] = 'checked';
                    }
                }else{
                    if (isset($defaults['value']) && $_POST[$data] == $defaults['value']) {
                        $defaults ['checked'] = 'checked';
                    }    
                }
            }
        }
    }

    return "<input " . _parse_attributes($data, $defaults) . $extra . " />";
}

function form_radio($data = '', $value = '', $checked = false, $extra = '')
{
    if (!is_array($data)) {
        $data = array(
            'name' => $data,
            'value' => $value
        );
    }

    $data ['type'] = 'radio';
    return form_checkbox($data, $value, $checked, $extra);
}

function _parse_attributes($attributes, $default)
{
    if (is_array($attributes)) {
        foreach ($default as $key => $val) {
            if (isset($attributes [$key])) {
                $default [$key] = $attributes [$key];
                unset($attributes [$key]);
            }
        }

        if (count($attributes) > 0) {
            $default = array_merge($default, $attributes);
        }
    }

    $att = '';

    if (!empty($default['name']) && empty($default['id'])) {
        $default['id'] = str_replace('[]', '', $default['name']);
    }

    foreach ($default as $key => $val) {
        if ($key == 'value') {
            $val = form_prep($val);
        }

        $att .= $key . '="' . $val . '" ';
    }

    return $att;
}

function set_value($field = '', $default = '')
{
    if (!isset($_REQUEST [$field]) && !isset($_POST [$field])) {
        return $default;
    }

    return (isset($_REQUEST [$field])) ? $_REQUEST [$field] : $_POST [$field];
}

function set_select($field = '', $value = '', $default = false)
{
    if (!isset($_POST [$field])) {
        if (count($_POST) === 0 && $default == true) {
            return ' selected="selected"';
        }
        return '';
    }

    $field = $_POST [$field];

    if (is_array($field)) {
        if (!in_array($value, $field)) {
            return '';
        }
    } else {
        if (($field == '' || $value == '') || ($field != $value)) {
            return '';
        }
    }

    return ' selected="selected"';
}

function form_prep($str = '')
{

    // if the field name is an array we do this recursively
    if (is_array($str)) {
        foreach ($str as $key => $val) {
            $str [$key] = form_prep($val);
        }

        return $str;
    }

    if ($str === '') {
        return '';
    }

    $str = htmlspecialchars($str);

    // In case htmlspecialchars misses these.
    $str = str_replace(array(
        "'",
        '"'
            ), array(
        "&#39;",
        "&quot;"
            ), $str);

    return $str;
}

function form_button($lable = '', $data = '')
{
    $defaults = array(
        'type' => 'button',
        'name' => ((!is_array($data)) ? $data : ''),
        'class' => 'btn btn-default'
    );

    return "<a " . _parse_attributes($data, $defaults) . ">" . $lable . "</a>";
}

function checkbox_values($values,$string,$separate = ','){
    $datas = explode($separate, $string);
    $return = array();
    if (!empty($datas)){
        foreach ($datas as $v){
            if(isset($values[$v])){
                $return[] = $values[$v];
            }
        }
    }
    
    return implode($separate.' ', $return);
}

function select_multiple_values($values,$string,$separate = ','){
    $datas = explode($separate, $string);
    $return = array();
    if (!empty($datas)){
        foreach ($datas as $v){
            if(isset($values[$v])){
                $return[] = $values[$v];
            }
        }
    }
    
    return implode($separate.' ', $return);
}