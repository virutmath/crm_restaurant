<?php
//Form function, tạo form - code dựa trên form_helper của code igniter
   
function form_open($action = '', $attributes = '', $hidden = array()){
	if ($attributes == ''){
		$attributes = 'method="post"';
	}
	$form = '<form action="'.$action.'"';
	$form .= _attributes_to_string($attributes, TRUE);
	$form .= '>';
	if (is_array($hidden) AND count($hidden) > 0){
		$form .= sprintf("<div style=\"display:none\">%s</div>", form_hidden($hidden));
	}
	return $form;
}

// ------------------------------------------------------------------------

/**
* Form Declaration - Multipart type
*
* Creates the opening portion of the form, but with "multipart/form-data".
*/
function form_open_multipart($action = '', $attributes = array(), $hidden = array()){
	if (is_string($attributes)){
		$attributes .= ' enctype="multipart/form-data"';
	}
	else{
		$attributes['enctype'] = 'multipart/form-data';
	}
	return form_open($action, $attributes, $hidden);
}

// ------------------------------------------------------------------------

/**
* Hidden Input Field
*
* Generates hidden fields.  You can pass a simple key/value string or an associative
* array with multiple values.
*/
function form_hidden($name, $value = '', $recursing = FALSE){
	static $form;
	if ($recursing === FALSE){
		$form = "\n";
	}
	if (is_array($name)){
		foreach ($name as $key => $val){
			form_hidden($key, $val, TRUE);
		}
		return $form;
	}
	if ( ! is_array($value)){
		$form .= '<input type="hidden" name="'.$name.'" value="'.$value.'" />'."\n";
	}
	else{
		foreach ($value as $k => $v){
			$k = (is_int($k)) ? '' : $k;
			form_hidden($name.'['.$k.']', $v, TRUE);
		}
	}
	return $form;
}

// ------------------------------------------------------------------------

/**
* Text Input Field
* data : name cua input
* value : gia tri cua input
* extra : chuoi kem them (co the la javascript hoac id, class)
*/
function form_input($data = '', $value = '', $extra = ''){
	$defaults = array('type' => 'text', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
    if( is_array($data) && $data['type'] == 'file' ){
        return '<input '._parse_form_attributes($data, $defaults).$extra.' />';
    }else {
        return "<input "._parse_form_attributes($data, $defaults).$extra." />";
    }

}

// ------------------------------------------------------------------------

/**
* Password Field
*
* Identical to the input function but adds the "password" type
*/

function form_password($data = '', $value = '', $extra = ''){
	if ( ! is_array($data)){
		$data = array('name' => $data);
	}
	$data['type'] = 'password';
	return form_input($data, $value, $extra);
}
// ------------------------------------------------------------------------

/**
* Upload Field
*
* Identical to the input function but adds the "file" type
*/
function form_upload($data = '', $value = '', $extra = ''){
	if ( ! is_array($data)){
		$data = array('name' => $data);
	}
	$data['type'] = 'file';
	return form_input($data, $value, $extra);
}
// ------------------------------------------------------------------------

/**
 * Textarea field
 */
function form_textarea($data = '', $value = '', $extra = ''){
	$defaults = array('name' => (( ! is_array($data)) ? $data : ''));
	if ( ! is_array($data) OR ! isset($data['value'])){
		$val = $value;
	}
	else{
		$val = $data['value'];
		unset($data['value']); // textareas don't use the value attribute
	}
	$name = (is_array($data)) ? $data['name'] : $data;
	return "<textarea "._parse_form_attributes($data, $defaults).$extra.">".$val."</textarea>";
}
// ------------------------------------------------------------------------

/**
 * Multi-select menu
 */
function form_multiselect($name = '', $options = array(), $selected = array(), $extra = ''){
	if ( ! strpos($extra, 'multiple')){
		$extra .= ' multiple="multiple"';
	}
	return form_dropdown($name, $options, $selected, $extra);
}
// --------------------------------------------------------------------

/**
 * Drop-down Menu
 */
function form_dropdown($name = '', $options = array(), $selected = array(), $extra = ''){
	if ( ! is_array($selected)){
		$selected = array($selected);
	}

	// If no selected state was submitted we will attempt to set it automatically
	if (count($selected) === 0){
		// If the form name appears in the $_POST array we have a winner!
		if (isset($_POST[$name])){
			$selected = array($_POST[$name]);
		}
	}
	if ($extra != '') $extra = ' '.$extra;
	$multiple = (count($selected) > 1 && strpos($extra, 'multiple') === FALSE) ? ' multiple="multiple"' : '';
	$form = '<select name="'.$name.'"'.$extra.$multiple.">\n";
	foreach ($options as $key => $val){
		$key = (string) $key;
		if (is_array($val) && ! empty($val)){
			$form .= '<optgroup label="'.$key.'">'."\n";
			foreach ($val as $optgroup_key => $optgroup_val){
				$sel = (in_array($optgroup_key, $selected)) ? ' selected="selected"' : '';
				$form .= '<option value="'.$optgroup_key.'"'.$sel.'>'.(string) $optgroup_val."</option>\n";
			}
			$form .= '</optgroup>'."\n";
		}
		else{
			$sel = (in_array($key, $selected)) ? ' selected="selected"' : '';
			$form .= '<option value="'.$key.'"'.$sel.'>'.(string) $val."</option>\n";
		}
	}
	$form .= '</select>';
	return $form;
}
// ------------------------------------------------------------------------

/**
 * Checkbox Field
 */

function form_checkbox($data = '', $value = '', $checked = FALSE, $extra = ''){
	$defaults = array('type' => 'checkbox', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
	if (is_array($data) AND array_key_exists('checked', $data)){
		$checked = $data['checked'];
		if ($checked == FALSE){
			unset($data['checked']);
		}
		else{
			$data['checked'] = 'checked';
		}
	}
	if ($checked == TRUE){
		$defaults['checked'] = 'checked';
	}
	else{
		unset($defaults['checked']);
	}
	return "<input "._parse_form_attributes($data, $defaults).$extra." />";
}


// ------------------------------------------------------------------------

/**
 * Radio Button
 */
function form_radio($data = '', $value = '', $checked = FALSE, $extra = ''){
	if ( ! is_array($data)){
		$data = array('name' => $data);
	}
	$data['type'] = 'radio';
	return form_checkbox($data, $value, $checked, $extra);
}

// ------------------------------------------------------------------------

/**
 * Submit Button
 */
function form_submit($data = '', $value = '', $extra = ''){
	$defaults = array('type' => 'submit', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
	return "<input "._parse_form_attributes($data, $defaults).$extra." />";
}

// ------------------------------------------------------------------------

/**
 * Reset Button
 */
function form_reset($data = '', $value = '', $extra = ''){
	$defaults = array('type' => 'reset', 'name' => (( ! is_array($data)) ? $data : ''), 'value' => $value);
	return "<input "._parse_form_attributes($data, $defaults).$extra." />";
}

// ------------------------------------------------------------------------

/**
 * Form Button
 */
function form_button($data = '', $content = '', $extra = ''){
	$defaults = array('name' => (( ! is_array($data)) ? $data : ''), 'type' => 'button');
	if ( is_array($data) AND isset($data['content'])){
		$content = $data['content'];
		unset($data['content']); // content is not an attribute
	}
	return "<button "._parse_form_attributes($data, $defaults).$extra.">".$content."</button>";
}

// ------------------------------------------------------------------------

/**
 * Form Label Tag
 */
function form_label($label_text = '', $id = '', $attributes = array()){
	$label = '<label';
	if ($id != ''){
		$label .= " for=\"$id\"";
	}
	if (is_array($attributes) AND count($attributes) > 0){
		foreach ($attributes as $key => $val){
			$label .= ' '.$key.'="'.$val.'"';
		}
	}
	$label .= ">$label_text</label>";
	return $label;
}

/**
 * Form Close Tag
 */
function form_close($extra = ''){
	return "</form>".$extra;
}


// ------------------------------------------------------------------------

// ------------------------------------------------------------------------

/**
 * Parse the form attributes
 * Helper function used by some of the form helpersg
 */

function _extend_attributes($attributes, &$default){
    if (is_array($attributes)){
		foreach ($default as $key => $val){
			if (isset($attributes[$key])){
				$default[$key] = $attributes[$key];
				unset($attributes[$key]);
			}
		}
		if (count($attributes) > 0){
			$default = array_merge($default, $attributes);
		}
	}
    return $default;
}

function _parse_form_attributes($attributes, $default){
	$default = _extend_attributes($attributes,$default);
	$att = '';
	foreach ($default as $key => $val){
		$att .= $key . '="' . $val . '" ';
	}
	return $att;
}


// ------------------------------------------------------------------------

/**
 * Attributes To String
 * Helper function used by some of the form helpers
 */
function _attributes_to_string($attributes, $formtag = FALSE){
	if (is_string($attributes) AND strlen($attributes) > 0){
		if ($formtag == TRUE AND strpos($attributes, 'method=') === FALSE){
			$attributes .= ' method="post"';
		}
	return ' '.$attributes;
	}
	if (is_object($attributes) AND count($attributes) > 0){
		$attributes = (array)$attributes;
	}
	if (is_array($attributes) AND count($attributes) > 0){
		$atts = '';
		if ( ! isset($attributes['method']) AND $formtag === TRUE){
			$atts .= ' method="post"';
		}
		foreach ($attributes as $key => $val){
			$atts .= ' '.$key.'="'.$val.'"';
		}
		return $atts;
	}
}
function tinyMCE($name,$id,$value,$width='99%',$height=450,$theme = 'advanced'){
	$tinyForm = '';
    $tinyForm .= '<textarea name="'.$name.'" id="'.$id.'" style="width:'.$width.';height:'.$height.'px">'.$value.'</textarea>';
    $tinyForm .=   '<script type="text/javascript">
            tinyMCE.init({
                mode:"exact",
                elements:"'.$id.'",
                theme:"'.$theme.'",
                plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
                relative_urls : false,
                // Example content CSS (should be your site CSS)
				content_css : false,
        		// using false to ensure that the default browser settings are used for best Accessibility
        		// ACCESSIBILITY SETTINGS
        		theme : "advanced",
				plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

				// Theme options
				theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
				theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
				theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
				theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
				theme_advanced_toolbar_location : "top",
				theme_advanced_toolbar_align : "left",
				theme_advanced_statusbar_location : "bottom",
				theme_advanced_resizing : true,

				

				// Drop lists for link/image/media/template dialogs
				template_external_list_url : "js/template_list.js",
				external_link_list_url : "js/link_list.js",
				external_image_list_url : "js/image_list.js",
				media_external_list_url : "js/media_list.js"
				
            })
            </script>';
	return $tinyForm;
}
class form{
    var $form_name = 'add_new';
    private $array_element = array();
    
    function textnote($arrStr = array()){
        if(is_string($arrStr)){
            $arrStr = array($arrStr);
        }
        $textnote = '<div class="form-textnote">';
        foreach($arrStr as $note){
            $textnote .= '<span class="form-asterick">*</span><span class="muted">'.$note.'</span><br />';
        }
        $textnote .= '</div>';
        return $textnote;
    }
    private function create_control($attribute = array(), $control = ''){
        $default = array('label'=>'', 'id'=>'', 'require'=> 0, 'errorMsg'=> '', 'helptext'=>'', 'helpblock'=> '','type'=>'');
        $default = _extend_attributes($attribute,$default);
        $form = '<div class="form-group">';
		
		//require
		$require = $default['require'] ? '<span class="form-asterick">*</span>' : '';
		if($default['require']){
            //add element to array
            $this->array_element[] = array('id'=>$default['id'],'msg'=>$default['errorMsg']);
		}
        //class cho div bao ngoai
        switch($default['type']){
            case 'radio':
            case 'checkbox':
                $control = '<div class="'.$default['type'].'">'.$control.'</div>';
                break;
        }
        $form .= '<label class="control-label col-xs-3" for="'.$default['id'].'">'.$require.$default['label'].'</label>';
        if($default['helptext']){
			$form .= '<div class="col-xs-8">'.$control.'<span class="help-inline">'.$default['helptext'].'</span></div>';
		}elseif($default['helpblock']){
			$form .= '<div class="col-xs-8">'.$control.'<span class="help-block">'.$default['helpblock'].'</span></div>';
		}else{
			$form .= '<div class="col-xs-8">'.$control.'</div>';
		}
		
        $form .= '</div>';
        
        return $form;
    }
    
    function form_open($name = 'add_new',$action = '', $extra = ''){
        $this->form_name = $name ? $name : 'add_new';
        $form = form_open_multipart($action, 'name="'.$this->form_name.'" class="form-horizontal" onsubmit="checkJavascript();return false;" ' . $extra);
        return $form;
    }
    function form_close($extra = ''){
        $jstring = array('form_name'=>$this->form_name,'elements'=>$this->array_element);
        $jstring = json_encode($jstring);
        $extra .= '<script type="text/javascript">
            function checkJavascript(){
                validForm(\''.$jstring.'\');
            }
            </script>';
        return form_close($extra);
    }
    
    /*
		array : label, name, id, value, extra, require, errorMsg, helptext, placeholder
	*/
    function text($attribute = array(), $width = 0, $extraClass = '', $extraString = ''){
        $extra = ' id="'.$attribute['id'].'" ';
        $extra .= $extraClass ? ' class="form-control '.$extraClass.'" ' : ' class="form-control" ';
        $extra .= $width ? ' width="'.$width.'"' : ' ';
        $extra .= $extraString;
        if(isset($attribute['title'])){
            $extra .= ' title="'.$attribute['title'].'"';
        }
        if(isset($attribute['placeholder'])){
            $extra .= ' placeholder="'.$attribute['placeholder'].'"';
        }
        if(isset($attribute['isdatepicker']) && $attribute['isdatepicker']){
            $extra .= ' datepick-element="1" ';
        }
        if(isset($attribute['autocomplete'])){
            $extra .= ' js-autocomplete="1" ';
        }
        if(isset($attribute['extra'])){
            $extra .= $attribute['extra'];
        }
        if(isset($attribute['disabled']) && $attribute['disabled']){
            $extra .= ' disabled ';
        }
        if(isset($attribute['readonly']) && $attribute['readonly']) {
            $extra .= ' readonly ';
        }
        $unique_error_string = '';
        if(isset($attribute['unique']) && $attribute['unique'] == 1){
            //kiểm tra trùng lặp dữ liệu
            $table = $attribute['table_unique'];
            $error = $attribute['error_unique'];
            $field = isset($attribute['field_unique']) ? $attribute['field_unique'] : $attribute['name'];
            $unique_error_string = '<span class="form-asterick alert-unique-input" style="display:none;">*'.$error.'</span>';
            $extra .= ' data-unique="1" data-unique-field="'.$field.'" data-unique-table="'.$table.'" ';
        }
        foreach($attribute as $k=>$v){
            if(preg_match('/^data-/',$k)){
                $extra .= ' '.$k . '="'.$v.'" ';
            }
        }
        if(!isset($attribute['value'])) $attribute['value'] = '';
        $control = form_input($attribute['name'],$attribute['value'],$extra) . $unique_error_string;
        //thêm addon nếu có option addon
        if(isset($attribute['addon']) && $attribute['addon']) {
            $control = '<div class="input-group">'.$control.'<span class="input-group-addon">'.$attribute['addon'].'</span></div>';
        }
        return $this->create_control($attribute,$control);
    }

    function staticInput($attribute = array('label'=>'','value'=>'')) {
        $control = form_input('','','disabled="disabled" placeholder="'.$attribute['value'].'"');
        return $this->create_control($attribute, $control);
    }

    function staticText($attribute = array('label'=>'', 'value'=>'', 'id'=>'')) {
        $control = '<span class="static-text form-control"';
        if(isset($attribute['id'])) {
            $control .= ' id="'.$attribute['id'].'"';
        }
        if(isset($attribute['extra'])) {
            $control .= ' ' . $attribute['extra'];
        }
        $control .= '>' . $attribute['value'] .'</span>';
        return $this->create_control($attribute, $control);
    }

    function password($attribute = array(), $width = 0, $extraClass = '', $extraString = ''){
        $extra = ' id="'.$attribute['id'].'" ';
        $extra .= $extraClass ? ' class="form-control '.$extraClass.'" ' : ' class="form-control" ';
        $extra .= $width ? ' width="'.$width.'"' : ' ';
        $extra .= $extraString;
        if(isset($attribute['title'])){
            $extra .= ' title="'.$attribute['title'].'"';
        }
        if(isset($attribute['placeholder'])){
            $extra .= ' placeholder="'.$attribute['placeholder'].'"';
        }
        if(isset($attribute['isdatepicker'])){
            $extra .= ' datepick-element="1" ';
        }
        if(isset($attribute['extra'])){
            $extra .= $attribute['extra'];
        }
        if(!isset($attribute['value'])) $attribute['value'] = '';        
        $control = form_password($attribute['name'],$attribute['value'],$extra);
        return $this->create_control($attribute,$control);
    }
    function number($attribute = array()){
        if(isset($attribute['value'])){
            $attribute['value'] = (int)$attribute['value'];
        }else{
            $attribute['value'] = 0;
        }
        $disabled = isset($attribute['disabled']) && $attribute['disabled'] ? ' disabled ' : '';
        if(isset($attribute['addon']) && $attribute['addon']){
            $control =   '<div class="input-group col-xs-4">
                              <input type="text" class="form-control align-right" '.$disabled.' data-role="auto-numeric" data-target-value="'.$attribute['id'].'" value="'.$attribute['value'].'">
                              <input type="hidden" name="'.$attribute['name'].'" id="'.$attribute['id'].'" value="'.$attribute['value'].'"/>
                              <span class="input-group-addon">'.$attribute['addon'].'</span>
                          </div>';
        }else{
            $control = '<div class="row col-xs-4">
                            <input type="text" class="form-control align-right" '.$disabled.' data-role="auto-numeric" data-target-value="'.$attribute['id'].'" value="'.$attribute['value'].'">
                            <input type="hidden" name="'.$attribute['name'].'" id="'.$attribute['id'].'" value="'.$attribute['value'].'"/>
                        </div>';
        }
        return $this->create_control($attribute,$control);
    }
    function checkbox($attribute = array()){
    	/*
		array : label, name, id, value, currentValue, extra, require, errorMsg, helptext
    	*/
		$extra = ' id='.$attribute['id'];
        $attribute['type'] = 'checkbox';
		if(isset($attribute['title'])){
            $extra .= ' title="'.$attribute['title'].'"';
        }
		if(!isset($attribute['value'])) $attribute['value'] = '';
		$checked = FALSE;
		if(!isset($attribute['currentValue'])) $attribute['currentValue'] = '';
		if(isset($attribute['value']) && $attribute['value']){
			if(isset($attribute['currentValue']) && $attribute['currentValue'] == $attribute['value'])
				$checked = TRUE;
			else $checked = FALSE;
		}
        if(isset($attribute['extra'])){
            $extra .= $attribute['extra'];
        }
        $control = form_checkbox($attribute['name'], $attribute['value'], $checked, $extra);
		return $this->create_control($attribute, $control);
    }
	function form_divider(){
		return '<hr>';
	}
    function list_checkbox($attribute = array()){
		$class_column = 'col-xs-12';
		if(isset($attribute['column']) && $attribute['column']){
			switch($attribute['column']){
				case 1:
				default:
					$class_column = 'col-xs-12';
					break;
				case 2:
					$class_column = 'col-xs-6';
					break;
				case 3:
					$class_column = 'col-xs-4';
					break;
				case 4:
					$class_column = 'col-xs-3';
			}
		}
        $control = '<div class="row"><ul class="list-unstyled list-checkbox">';
        foreach($attribute['list'] as $checkbox){
            $checked = isset($checkbox['is_check']) && $checkbox['is_check'] ? 'checked="checked"' : '';
            $control .= '<li class="'.$class_column.' list-checkbox-item">
            <label style="font-weight:normal">
            <input type="checkbox" class="checkbox" '.$checked.' value="'.$checkbox['value'].'" name="'.$checkbox['name'].'" id="'.$checkbox['id'].'"/>
            '.$checkbox['label'].'</label>
            </li>';
        }
        $control .= '</ul></div>';
        return $this->create_control($attribute,$control);
    }
	function radio($attribute = array()){
		/*
		array : label, name, id, value, currentValue, extra, require, errorMsg, helptext
    	*/
		$extra = ' id='.$attribute['id'];
        $attribute['type'] = 'checkbox';
		if(isset($attribute['title'])){
            $extra .= ' title="'.$attribute['title'].'"';
        }
		if(!isset($attribute['value'])) $attribute['value'] = '';
		$checked = FALSE;
		if(!isset($attribute['currentValue'])) $attribute['currentValue'] = '';
		if(isset($attribute['value']) && $attribute['value']){
			if(isset($attribute['currentValue']) && $attribute['currentValue'] == $attribute['value'])
				$checked = TRUE;
			else $checked = FALSE;
		}
        if(isset($attribute['extra'])){
            $extra .= $attribute['extra'];
        }
        $control = form_radio($attribute['name'], $attribute['value'], $checked, $extra);
		return $this->create_control($attribute, $control);
	}
    function list_radio($attribute = array()){
        $class_column = 'col-xs-12';
        if(isset($attribute['column']) && $attribute['column']){
            switch($attribute['column']){
                case 1:
                default:
                    $class_column = 'col-xs-12';
                    break;
                case 2:
                    $class_column = 'col-xs-6';
                    break;
                case 3:
                    $class_column = 'col-xs-4';
                    break;
                case 4:
                    $class_column = 'col-xs-3';
            }
        }
        $control = '<div class="row"><ul class="list-unstyled list-radio">';
        foreach($attribute['list'] as $radio){
            $checked = isset($radio['is_check']) && $radio['is_check'] ? 'checked="checked"' : '';
            $control .= '<li class="'.$class_column.' list-radio-item">
            <label style="font-weight:normal">
            <input type="radio" class="radio" '.$checked.' value="'.$radio['value'].'" name="'.$radio['name'].'" id="'.$radio['id'].'"/>
            '.$radio['label'].'</label>
            </li>';
        }
        $control .= '</ul></div>';
        return $this->create_control($attribute,$control);
    }
	function tinyMCE($titleControl,$name,$id,$value,$width='100%',$height=450,$theme = 'advanced'){
		$tinyForm = '';
        $tinyForm .= '<div class="tinyMCE-wrapper" style="text-align:left; width:' . $width . '">' . $titleControl;
        $tinyForm .= '<textarea name="'.$name.'" id="'.$id.'" style="width:'.$width.';height:'.$height.'px">'.$value.'</textarea>';
        $tinyForm .=   '<script type="text/javascript">
                tinymce.init({
                    selector: "#'.$id.'",
                    skin:"lightgray",
                    plugins: [
                        "advlist autolink lists link image charmap print preview anchor",
                        "searchreplace visualblocks code fullscreen",
                        "insertdatetime media table contextmenu paste"
                    ],
                    toolbar_items_size: "small",
                    toolbar: "insertfile undo redo | styleselect | bold italic underline | fontselect | fontsizeselect | subscript superscript | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | hr charmap emoticons | preview fullscreen"
                });
                </script>';
        $tinyForm .= '</div>';
		return $tinyForm;
    }
	function miniMCE($label,$name,$id,$value=''){
        $miniForm = '';
        $miniForm .= $this->form_group_custom(array('label'=>$label,'control_width'=>5));
        $miniForm .= '<textarea name="'.$name.'" id="'.$id.'">'.$value.'</textarea>';
        $miniForm .= '<script type="text/javascript">
                tinymce.init({
                    selector: "#'.$id.'",
                    skin:"lightgray",
                    menubar : false,
                    toolbar_items_size: "small",
                    toolbar: "undo redo bold italic underline fontselect fontsizeselect alignleft aligncenter alignright alignjustify"
                });
                </script>';
        $miniForm .= $this->form_group_custom('close');
        return $miniForm;
    }
	function textarea($attribute = array()){
		$extra = 'class="form-control" id='.$attribute['id'];
		if(isset($attribute['title'])){
            $extra .= ' title="'.$attribute['title'].'"';
        }
		if(isset($attribute['style'])){
            $extra .= ' style="'.$attribute['style'].'"';
		}
		if(!isset($attribute['value'])) $attribute['value'] = '';
        if(isset($attribute['extra'])){
            $extra .= ' '.$attribute['extra'];
        }
		$control = form_textarea($attribute['name'], $attribute['value'],$extra);
		return $this->create_control($attribute,$control);
	}
    
    function select($attribute = array('label'=>'','title'=>'','name'=>'','id'=>'','option'=>'','selected','extra'=>'')){
        $extra = 'class="form-control" id="'.$attribute['id'].'"';
		if(isset($attribute['title'])){
            $extra .= ' title="'.$attribute['title'].'"';
        }
		if(isset($attribute['style'])){
            $extra .= ' style="'.$attribute['style'].'"';
		}
        if(!isset($attribute['selected'])) $attribute['selected'] = '';
        if(isset($attribute['extra'])){
            $extra .= $attribute['extra'];
        }
        if(!isset($attribute['option']))    $attribute['option'] = array(''=>'--');
        
        $control = '<div class="row col-xs-6">'.form_dropdown($attribute['name'],$attribute['option'],$attribute['selected'],$extra).'</div>';
        return $this->create_control($attribute,$control);
    }

    function selectCatMulti($attribute = array('name'=>'','id'=>'','label'=>'','require'=>0,'table'=>'','id_field'=>'','name_field'=>'','parent_id_fileld'=>'')){
        $html = '';
        $default = array(
            'label'=>'',
            'name'=>'',
            'id'=>'',
            'require'=>0,
            'table'=>'categories_multi',
            'id_field'=>'cat_id',
            'name_field'=>'cat_name',
            'parent_id_field'=>'cat_parent_id'
        );
        _extend_attributes($attribute,$default);
        $id_field = $default['id_field'];
        $name_field = $default['name_field'];
        $parent_id_field = $default['parent_id_field'];
        $table = $default['table'];
        $control_name = $default['name'];
        $control_id = $default['id'];

        $cat_1 = array(''=>'Danh mục cấp 1');
        $db_cat_1 = new db_query('SELECT '.$id_field.','.$name_field.'
                                    FROM '.$table.'
                                    WHERE '.$parent_id_field.' = 0');
        while($row = mysqli_fetch_assoc($db_cat_1->result)){
            $cat_1[$row[$id_field]] = $row[$name_field];
        }
        //Lấy ra các category
        switch(MAX_CATEGORY_LEVEL){
            case 2:
            default:
                $control_1 = form_dropdown('levelcate1',$cat_1,'','id="levelcate1" class="form-control" data-target="'.$control_id.'" data-action="change-cat" data-name-field="'.$name_field.'" data-id-field="'.$id_field.'" data-table="'.$table.'" data-parent-field="'.$parent_id_field.'"');
                $control_2 = form_dropdown($control_name,array(''=>'Danh mục cấp 2'),'','disabled="disabled" class="form-control col-xs-4"  id="'.$control_id.'"');
                $control_3 = '';
                break;
            case 3:
                $control_1 = form_dropdown('levelcate1',$cat_1,'','id="levelcate1" class="form-control" data-target="levelcate2" data-action="change-cat"  data-name-field="'.$name_field.'" data-id-field="'.$id_field.'" data-table="'.$table.'" data-parent-field="'.$parent_id_field.'"');
                $control_2 = form_dropdown('levelcate2',array(''=>'Danh mục cấp 2'),'','disabled="disabled" id="levelcate2" class="form-control" data-target="'.$control_id.'" data-action="change-cat"  data-name-field="'.$name_field.'" data-id-field="'.$id_field.'" data-table="'.$table.'" data-parent-field="'.$parent_id_field.'"');
                $control_3 = form_dropdown($control_name,array(''=>'Danh mục cấp 3'),'','disabled="disabled" class="form-control" id="'.$control_id.'"');
                break;
        }
        $require = $default['require']?'<span class="form-asterick">*</span>':'';
        $html .= '<div class="form-group">
            <label class="control-label col-xs-2">'.$require.$default['label'].'</label>
            <div class="col-xs-10 select-cat-multi"><div class="row">'.
                '<div class="col-xs-3">'.$control_1.'</div>'.
                '<div class="col-xs-3">'.$control_2.'</div>'.
                '<div class="col-xs-3">'.$control_3.'</div>'.
            '</div></div>
        </div>';
        return $html;
    }
    
    function button($attribute = array()){
        
    }
    function hidden($attribute = array('name'=>'','id'=>'','value'=>'')){
        $default = array('name'=>'','id'=>'','value'=>'');
        _extend_attributes($attribute,$default);
        $html = '<div class="hidden hidden-value">';
        $html .= '<input type="hidden" value="'.$default['value'].'" id="'.$default['id'].'" name="'.$default['name'].'" />';
        $html .= '</div>';
        return $html;
    }
    //Combo box multi ajax load
    function selectMultiRelate($attribute = array()){
        $html = '';
        $level = count($attribute);
        if(!$level) return $html;
        $html .= '<div class="select-multi-relate" data-auto-form-name="select-multi-relate">';
        for($i = 0; $i < $level; $i ++){
            $item = $attribute[$i];
            if($i < $level - 1){
                $next_item = $attribute[$i+1];
                $extra = ' data-target="'.$next_item['id'].'" data-action="'.$item['action'].'" ';
            }else{
                if(!isset($item['option']))
                    $extra = ' disabled="disabled" ';
                else{
                    $extra = '';
                }
            }
            if(isset($item['extra'])){
                $item['extra'] .= ' '. $extra . ' ';
            }else{
                $item['extra'] = ' '. $extra . ' ';
            }

            $html .= $this->select($item);
        }
        $html .= '</div>';
        return $html;
    }

    function getFile($attribute = array()){
        $extra = '';
        $extra .= 'class="form-control" id = "'.$attribute['id'].'"';
        if(isset($attribute['extra'])){
            $extra .= ' '.$attribute['extra'] . ' ';
        }
        if(isset($attribute['size']) && $attribute['size']){
            $extra .= ' size="'.$attribute['size'].'"';
        }
        if(isset($attribute['title']) && $attribute['title']){
            $extra .= ' title="'.$attribute['title'].'"';
        }
        $control = form_upload($attribute['name'],'',$extra);
        return $this->create_control($attribute,$control);
    }

    function group_collapse_open($attribute = array('label'=>'','id'=>'','open'=>0)){
        $default = array(
            'label'=>'',
            'id'=>'',
            'open'=>0
        );
        _extend_attributes($attribute,$default);
        $str_toggle = $default['open'] ? 'in' : '';
        $html = '<div class="alert alert-info" data-toggle="collapse" data-target="#'.$default['id'].'">
                    <b>'.$default['label'].'</b>
                </div>';
        $html .= '<div id="'.$default['id'].'" class="group-collapse collapse '.$str_toggle.'">';
        return $html;
    }
    function group_collapse_close(){
        return '</div>';
    }
    function ajaxUploadForm($attribute = array('label'=>'','name'=>'','id'=>'','value'=>'','browse_id'=>'','viewer_id'=>'')){
        $extra = '';
        $html = '';
        $default = array(
            'label'=>'',
            'name'=>'',
            'id'=>'',
            'value'=>'',
            'browse_id'=>'',
            'viewer_id'=>''
        );
        _extend_attributes($attribute,$default);
        $html = '<div class="form-group">
                    <label class="control-label col-xs-2">'.$default['label'].'</label>
                    <div class="col-xs-10">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="file" id="'.$default['browse_id'].'"/>
                                <div class="margin-10b"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="hidden" name="'.$default['name'].'" id="'.$default['id'].'" value=""/>
                                <img src="'.$default['value'].'" alt="" id="'.$default['viewer_id'].'"/>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    UploaderScript.init({
                        browse_button : "'.$default['browse_id'].'",
                        image_wrapper : "'.$default['viewer_id'].'",
                        loading : "'.$default['viewer_id'].'"
                    },function(){
                        $("#'.$default['id'].'").val(UploaderScript.config.file_name);
                    });
                </script>
                ';
        return $html;
    }
    function ajaxUploadFile($attribute = array()){
        $default = array(
            'label'=>'',
            'name'=>'',
            'id'=>'',
            'value'=>'',
            'browser_id'=>'',
            'viewer_id'=>''
        );
        $html = '';
        _extend_attributes($attribute,$default);
        $html = '<div class="form-group">
                    <label class="control-label col-xs-3">'.$default['label'].'</label>
                    <div class="col-xs-9">
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="file" id="'.$default['browse_id'].'"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <input type="hidden" name="'.$default['name'].'" id="'.$default['id'].'" value=""/>
                                <img src="'.$default['value'].'" alt="" id="'.$default['viewer_id'].'"/>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    UploaderScript.init({
                        browse_button : "'.$default['browse_id'].'",
                        image_wrapper : "'.$default['viewer_id'].'",
                        loading : "'.$default['viewer_id'].'"
                    },function(){
                        $("#'.$default['id'].'").val(UploaderScript.config.file_name);
                    });
                </script>
                ';
        return $html;
    }
    function form_redirect($attribute = array('label'=>'Sau khi lưu dữ liệu','list'=>array('Thêm mới'=>'add.php','Danh sách'=>'listing.php'))){
        if(isset($_SERVER['HTTP_REFERER'])){
			$query_refer = $_SERVER['HTTP_REFERER'];
			$query_refer = parse_url($query_refer);
			if(isset($query_refer['query'])){
				$query_refer = $query_refer['query'];
			}else{
				$query_refer = '';
			}
		}else{
			$query_refer = '';
		}

		$default = array(
            'label'=>'Sau khi lưu dữ liệu',
            'list'=>array('Thêm mới'=>'add.php','Danh sách'=>'listing.php')
        );
        _extend_attributes($attribute,$default);
        $html = '<div class="form-group">
                    <label class="control-label col-xs-2">'.$default['label'].'</label>
                    <div class="col-xs-10">';
        foreach($default['list'] as $label=>$file_link){
			if($file_link == 'listing.php'){
				$file_link = $file_link . '?' . $query_refer;
			}
            $html .= '<div class="row col-xs-3">
                        <label class="label-radio"><input type="radio" name="form_redirect" value="'.$file_link.'"/>'.$label.
                     '</label></div>';
        }
        $html .= '</div></div>';
        return $html;
    }
    function form_group_custom($attribute = array()){
        if(is_string($attribute)){
            if($attribute == 'close')
                return '</div></div>';
            elseif ($attribute == 'open')
                $attribute = array('label'=>'','type' => 'open');
            else
                $attribute = array('label'=>$attribute,'type'=>'open');
        }

        $default = array(
            'type'=> 'open',
            'label'=> '',
            'label_width'=> 2,
            'control_width'=>10
        );
        _extend_attributes($attribute,$default);
        if($default['type'] == 'open'){
            return '<div class="form-group">
                        <label class="control-label col-xs-'.$default['label_width'].'">'.$default['label'].'</label>
                        <div class="col-xs-'.$default['control_width'].'">';
        }else{
            return '</div></div>';
        }
    }

    function form_action($attribute = array()){
        if(is_string($attribute['label'])){
            $attribute['label'] = array($attribute['label']);
        }
        if(is_string($attribute['type'])){
            $attribute['type'] = array($attribute['type']);
        }
        $form = '<div class="form-group form-action"><div class="col-xs-offset-3 col-xs-10"> ';
        $form .= form_hidden('action','execute');
        foreach($attribute['label'] as $key=>$btn){
            if($attribute['type'][$key] == 'submit'){
                $class = 'btn btn-default btn-xs';
                $type = 'submit';
            }
            if($attribute['type'][$key] == 'reset'){
                $class = 'btn btn-default btn-xs';
                $type = 'reset';
            }
			if(isset($attribute['extra'][$key]) && $attribute['extra'][$key]){
				$extra = $attribute['extra'][$key];
			}else{
				$extra = '';
			}
            $form .= '<button type="'.$type.'" class="'.$class.'" '.$extra.' >'.$btn.'</button>&nbsp;';
        }
        $form .= '</div></div>';
        return $form;
    }
    function form_margin($pixel){
        return '<div style="margin-bottom:'.$pixel.'px"></div>';
    }
}
?>