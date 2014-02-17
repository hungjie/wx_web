<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Container {

    var $_fields;

    function add_widget($widget) {
        $this->_fields[$widget->id] = $widget;
        if ($widget instanceof File)
            $this->enctype = "multipart/form-data";
        return $this;
    }

    function validate() {
        foreach ($this->_fields as $w) {
            if (!($w instanceof Widget))
                continue;
            if (!$w->validate()) {
                throw new Exception("Error: $w->name");
            }
        }
        return true;
    }

    function values() {
        $value = array();
        foreach ($this->_fields as $k => $w) {
            if (!($w instanceof Widget))
                continue;
            if ($w instanceof Button)
                continue;
            $value[$k] = $w->server_value();
        }
        return $value;
    }

    function __toString() {
        $str = '';
        if (!empty($this->_fields)) {
            foreach ($this->_fields as $k => $w) {
                if (isset($w->required) && $w->required)
                    $w->label .= '(*)';
                $str .= $w;
            }
        }
        return $str;
    }

}

class Form extends Container {

    var $id;
    var $action;
    var $class;
    var $ajax;
    var $_js;
    var $_legend;

    function Form($id, $action, $ajax = false, $ajaxCallback = 'null', $ajaxResponeTarget = 'null', $class = '') {
        $this->id = $id;
        $this->action = $action;
        $this->class = $class;
        $this->ajax = $ajax;

        global $config;
        if (!$ajax) {
            $js = <<<EOT
        $('#$id').validate();
EOT;
        } else {
            $js = <<<EOT
        $('#$id').validate({
            submitHandler:function(f){
                $(f).ajaxSubmit({
                    target: "$ajaxResponeTarget"
                    ,success: "$ajaxCallback"
                });
            }
        });
EOT;
        }

        $config['dom_ready'][] = $this->_js = $js;
    }

    function is_submit() {
        return !empty($_POST);
    }

    function __toString() {
        $islegendset = !empty($this->_legend);

        $enctype = '';
        if (isset($this->enctype))
            $enctype = "enctype='$this->enctype'";
        $str = "<form id='$this->id' action='$this->action' class='$this->class' method='post' $enctype>";
        if ($islegendset)
            $str .= "<fieldset><legend>{$this->_legend}</legend>";
        $str .= parent::__toString();
        if ($islegendset)
            $str .= "</fieldset>";
        $str .= "</form>";
        return $str;
    }

}

class Widget {

    var $id;
    var $name;
    var $value;
    var $label;
    var $required;
    var $min_length;
    var $max_length;
    var $other;
    var $class;
    var $last_error;

    function Widget($id, $label = '', $value = '', $class = '', $required = true, $min_length = 6, $max_length = 20, $other = '') {
        $this->id = $id;
        $this->value = $value;
        $this->label = $label;
        $this->required = $required;
        $this->min_length = $min_length;
        $this->max_length = $max_length;
        $this->other = $other;

        $this->name = $id;

        $this->class = $class;

        $this->last_error = '';
    }

    function on_class() {
        if ($this->required)
            return $this->class . " required";
        return $this->class;
    }

    function last_error() {
        return $this->last_error;
    }

    function __toString() {
        $class = $this->on_class();
        if ($class != '')
            $class = "class='$class'";

        $value = '';
        if ($this->value !== '')
            $value = "value='$this->value'";

        $minlength = '';
        if ($this->min_length != 0)
            $minlength = "minlength='$this->min_length'";

        $maxlength = '';
        if ($this->max_length != 0)
            $maxlength = "maxlength='$this->max_length'";

        $str = '';
        if ($this->label != '') {
            $str = "<label for='$this->name'>$this->label:</label>";
        }
        $str .= "<input id='$this->id' name='$this->name' type = '$this->type' $value $class $minlength $maxlength $this->other />";

        return $str;
    }

    function server_value() {
        if (isset($_POST[$this->id]))
            return $_POST[$this->id];
        return null;
    }

    function validate() {
        $value = $this->server_value();
        if ($this->required && !isset($value))
            return false;
        if (!$this->required && (!isset($value) || $value == ''))
            return true;
        $count = strlen($value);
        if ($this->min_length != 0 && $count < $this->min_length)
            return false;
        if ($this->max_length != 0 && $count > $this->max_length)
            return false;

        for ($i = 0; $i < $count; ++$i) {
            if (isset($this->whitelist)) {
                if (strpos($this->whitelist, $value[$i]) !== false)
                    continue;
            }

            if (ord($value[$i]) < 0X20 || $value[i] == '<' || $value[i] == '>')
                return false;
        }

//        static $pattern ="/select|update|delete|create|drop|--|script|click|error|focus|blur|load|evement|cookie|,/i" ;
//
//        if (1 == preg_match($pattern, $value))
//            return false;

        return true;
    }

}

class Label {

    var $id;
    var $text;
    var $class;

    function Label($id, $text, $class = '') {
        $this->id = $id;
        $this->text = $text;
        $this->class = $class;
    }

    function validate() {
        return true;
    }

    function __toString() {
        return "<label class='$this->class' for='$this->id'>$this->text</label>";
    }

}

class P {

    var $id;
    var $text;
    var $class;

    function P($id, $text, $class = '') {
        $this->id = $id;
        $this->text = $text;
        $this->class = $class;
    }

    function validate() {
        return true;
    }

    function __toString() {
        return "<p class='$this->class' id='$this->id'>$this->text</p>";
    }

}

class span {

    var $id;
    var $text;
    var $class;

    function span($id, $text, $class = '') {
        $this->id = $id;
        $this->text = $text;
        $this->class = $class;
    }

    function validate() {
        return true;
    }

    function __toString() {
        return "<span class='$this->class' id='$this->id'>$this->text</span>";
    }

}

class BR {

    var $id;

    function BR($id) {
        $this->id = $id;
    }

    function validate() {
        return true;
    }

    function __toString() {
        return "<br/>";
    }

}

class AButton {

    var $id;
    var $href;
    var $text;
    var $class;
    var $other;

    function AButton($id, $herf, $text, $class = '', $other = '') {
        $this->id = $id;
        $this->href = $herf;
        $this->text = $text;
        $this->class = $class;
        $this->other = $other;
    }

    function validate() {
        return true;
    }

    function __toString() {
        return "<a id='$this->id' href='$this->href' class= '$this->class' $this->other>$this->text</a>";
    }

}

class AjaxButton extends AButton {

    var $type = 'button';

    function AjaxButton($id, $href, $text, $did, $parameter = '', $success = '', $class = '', $other = '') {
        parent::AButton($id, $href, $text, $class, $other);

        $behaviour = 'html';

        if (!empty($href) && !empty($did)) {
            $have_var = 0;
            $vars = "new Array(";

            if ($parameter) {
                $have_var = count($parameter);

                foreach ($parameter as $key => $value) {
                    if ($key == 0)
                        $vars .= "\"" . $value . "\"";
                    else
                        $vars .= ",\"" . $value . "\"";
                }
            }

            $vars .= ");";

            if (empty($success)) {
                $success = "\$('#" . $did . "')." . $behaviour . "(data);";
            }

            $ajax = <<<EOT
            $('#$id').click(function(){
                var parameter = {};
                if($have_var)
                {
                    var paras = $vars;
                    for(x in paras)
                    {
                        var temp_label =  paras[x];
                        var temp_para = "#" + paras[x];
                        
                        parameter[temp_label] = $(temp_para).val();
                    }
                }          
                
                $.get("$href", parameter, function(data){
                    $success
                })
                return false;
            });
EOT;

            global $config;

            $config['dom_ready'][] = $ajax;
        }
    }

}

class DIV extends Container {

    var $id;
    var $text;
    var $class;
    var $other;

    function DIV($id, $text, $class = '', $other = '') {
        $this->id = $id;
        $this->text = $text;
        $this->class = $class;
        $this->other = $other;
    }

    function __toString() {
        return "<div id='$this->id'  class= '$this->class' $this->other>$this->text" . parent::__toString() . '</div>';
    }

}

class Input extends Widget {

    var $type = 'text';

}

class Mask extends Input {

    function Mask($id, $label = '更新/获取 验证码', $value = '', $class = '', $required = true, $min_length = 3, $max_length = 6, $other = '') {
        $this->mask = $label;
        parent::Widget($id, '', $value, $class, $required, $min_length, $max_length);
        global $config;
        $config['dom_ready'][] = <<< EOT
            $('#a_mask_$this->id').click(a_mask_$this->id);
                function a_mask_$this->id(){
                $('#' + 'mask_$this->id').html("<img src='"
                    + '/util/check_mask.php?'
                    + parseInt(new Date().getTime() / 2000)
                    + "' alt='mask'></img>");
            }
            a_mask_$this->id();
EOT;
    }

    function validate() {
        return strcasecmp($this->server_value(), $_SESSION['mask']) == 0;
    }

    function __toString() {
        return "<a href='#' id='a_mask_$this->id'>$this->mask</a><div id='mask_$this->id'></div>" . parent::__toString();
    }

}

class Number extends Widget {

    var $type = 'text';

    function Number($id, $label = '', $value = '', $class = '', $required = true, $other = '') {
        parent::Widget($id, $label, $value, $class, $required, 0, 10, $other);
    }

    function on_class() {
        return parent::on_class() . " digits";
    }

    function server_value() {
        return (int) (parent::server_value() + 0);
    }

    function validate() {
        $value = $this->server_value();
        if (!$this->required && (($value == null) || $value == ''))
            return true;
        return (preg_match("/^\d+$/", $value) == 1);
    }

}

class RealNumber extends Widget {

    var $type = 'text';

    function RealNumber($id, $label = '', $value = '', $class = '', $required = true, $other = '') {
        parent::Widget($id, $label, $value, $class, $required, 0, 10, $other);
    }

    function on_class() {
        return parent::on_class() . " number";
    }

    function server_value() {
        return parent::server_value() + 0.0;
    }

    function validate() {
        $value = $this->server_value();
        return (preg_match("/^-?(?:\d+)(?:\.\d+)?$/", $value) == 1);
    }

}

class Email extends Widget {

    var $type = 'text';
    var $email = true;

    function on_class() {
        return parent::on_class() . " email";
    }

    function validate() {
        $value = $this->server_value();
        return  // parent::validate() && 
                (preg_match("/^[\w-]+@[\w-]+\.[\w\.-]+$/", $value) == 1);
    }

}

class Date extends Widget {

    var $type = 'text';
    var $date = true;

    function Date($id, $label = '', $value = '', $class = '', $required = true, $other = '') {
        $other .= " style='width:11em'";
        parent::Widget($id, $label, $value, $class, $required, 0, 0, $other);
        global $config;

        $config['dom_ready'][] = <<<EOT
   $("#$id").datepicker("option", $.datepicker.regional['zh-CN']).datepicker({numberOfMonths: 3,
			showButtonPanel: true});
   
EOT;
    }

    function on_class() {
        return parent::on_class() . " dateISO";
    }

    function validate() {
        $value = $this->server_value();
        return (preg_match("/^\d{4}-\d{2}-\d{2}$/", $value) == 1);
    }

}

class DateTimeUI extends Widget {

    var $date;
    var $hh;
    var $ii;
    var $ss;

    function DateTimeUI($id, $label = '', $value = '', $class = '', $required = true, $min_length = 6, $max_length = 20, $other = '') {
        parent::Widget($id, $label, $value, $class, $required, $min_length, $max_length, $other);
        $this->date = new Date($id . '_year', '', $value, $class, $required, $other);
        $values = array();
        for ($i = 0; $i < 24; ++$i) {
            $ii = sprintf('%02d', $i);
            $values[$ii] = $ii;
        }

        $other = "style='width:4em'";
        $this->hh = new Choice($id . '_hh', '', $values, false, false, $class, $required, $other);

        $values = array();
        for ($i = 0; $i < 60; ++$i) {
            $ii = sprintf('%02d', $i);
            $values[$ii] = $ii;
        }
        $this->ii = new Choice($id . '_ii', '', $values, false, false, $class, $required, $other);
        $this->ss = new Choice($id . '_ss', '', $values, false, false, $class, $required, $other);
    }

    function __toString() {
        $str = $this->date . $this->hh . ':' . $this->ii . ':' . $this->ss;
        return "<label>$this->label</label><div id='$this->id'>$str</div>";
    }

    function server_value() {
        return $this->date->server_value() . ' '
                . $this->hh->server_value() . ':'
                . $this->ii->server_value() . ':'
                . $this->ss->server_value();
    }

    function validate() {
        return $this->date->validate()
                && $this->hh->validate()
                && $this->ii->validate()
                && $this->ss->validate();
    }

}

class Password extends Widget {

    var $type = 'password';

}

class Url extends Widget {

    var $type = 'text';
    var $url = true;

    function on_class() {
        return parent::on_class() . " url";
    }

    function validate() {
        $value = $this->server_value();
        return //parent::validate() && 
                (preg_match("/^http(?:s)?:\/\/[\w\.-]+$/", $value) == 1);
    }

}

class File extends Widget {

    var $type = 'file';
    var $accept;
    var $maxsize;

    function File($id, $label, $maxsize = '2M', $accept = null) {
        parent::Widget($id, $label, '', '', false, 0, 0);
        $this->accept = $accept;
        switch (strtoupper(substr($maxsize, -1))) {
            case 'M':
                $this->maxsize = (int) substr($maxsize, 0, -1) * 1024 * 1024;
                break;
            case 'K':
                $this->maxsize = (int) substr($maxsize, 0, -1) * 1024;
                break;
            default:
                $this->maxsize = (int) $maxsize;
        }
    }

    function on_class() {
        $str = parent::on_class();
        if ($this->accept)
            $str .= " accept-$this->accept";
        return $str;
    }

    function process_upload($savepath = null) {
        $file = $this->post_value();
        if ($file && isset($file['tmp_name']) && $file['tmp_name'] != '')
            return $this->do_process_upload($file["tmp_name"], $file['name'], $savepath);
        return false;
    }

    function do_process_upload($tmpfile, $oldfile, $savepath = null) {
//        $count = strlen($oldfile);
        $ext = strrchr($oldfile, '.');
//        for ($i = $count - 1; $i >= 0 && $i >= $count - 8; --$i) {
//            if (ord($oldfile[$i]) < 0X20)
//                return false;
//            if ($oldfile[$i] == '.') {
//                $ext = substr($oldfile, $i);
//                break;
//            }
//        }

        list($usec, $sec) = explode(" ", microtime());
        $microtime = $sec . ($usec * 1000000);
        $filename = $microtime . $ext;

        if (!$savepath) {
            global $config;
            $savepath = $config['upload_path'];
            $sub = "/";
            if ($config['upload_path_nums'] > 1)
                $sub = "/" . mt_rand(1, $config['upload_path_nums']) . "/";
            if (!file_exists($savepath . $sub))
                mkdir($savepath . $sub);
            $filename = $sub . $filename;
        }

        if (move_uploaded_file($tmpfile, $savepath . "/" . $filename))
            return array('name' => $filename, 'real_name' => $savepath . "/" . $filename);

        return false;
    }

    function post_value() {
        if (isset($_FILES[$this->id]) && isset($_FILES[$this->id]['tmp_name']) && !empty($_FILES[$this->id]['tmp_name']))
            return $_FILES[$this->id];
        return null;
    }

    function server_value() {
        if (isset($this->_server_value))
            return $this->_server_value;
        $res = $this->process_upload();
        if ($res) {
            if (isset($res['name']))
                $this->_server_value = $res['name'];
            else {
                $strs = '';
                foreach ($res as $f) {
                    $strs .= $f['name'] . '|';
                }
                $strs = substr($strs, 0, -1);
                $this->_server_value = $strs;
            }
        } else {
            $this->_server_value = '';
        }
        return $this->_server_value;
    }

    function validate() {
        $value = $this->post_value();
        if ($value) {
            if ($value['error'] != 0)
                return false;
            if ($this->maxsize > 0 && $value['size'] > $this->maxsize)
                return false;
            if ($this->accept)
                if (preg_match("/\.(?:$this->accept)$/", $value['name']) != 1)
                    return false;
        }
        return true;
    }

}

class Files extends File {

    function Files($id, $label, $maxsize = '2M', $accept = null, $max_file = 0) {
        parent::File($id, $label, $maxsize, $accept);
        $this->max_file = $max_file;
        $this->name = $id . "[]";
    }

    function on_class() {
        $str = parent::on_class() . ' multi';
        if ($this->max_file)
            $str .= " max-$this->max_file";
        return $str;
    }

    function process_upload($savepath = null) {
        $file = $this->post_value();
        if ($file) {
            $res = array();
            foreach ($file['tmp_name'] as $k => $v) {
                if (!isset($v) || $v == '')
                    continue;
                $newfile = $this->do_process_upload($v, $file['name'][$k], $savepath);
                if ($newfile)
                    $res[] = $newfile;
                else {
                    foreach ($res as $f) {
                        unlink($f['real_name']);
                    }
                    return false;
                }
            }
            return $res;
        }
        return false;
    }

    function validate() {
        $value = $this->post_value();
        if ($value) {
            foreach ($value['error'] as $k => $err) {
                if (!isset($value['tmp_name'][$k]) || empty($value['tmp_name'][$k]))
                    continue;
                if ($err != 0)
                    return false;
                if ($this->maxsize > 0 && $value['size'][$k] > $this->maxsize)
                    return false;
                if ($this->accept)
                    if (preg_match("/\.(?:$this->accept)$/", $value['name'][$k]) == 0)
                        return false;
            }
        }
        return true;
    }

}

class Choice extends Widget {

    var $multiple;
    var $expend;

    function Choice($id, $label, $values, $multiple = false, $expend = true, $class = '', $required = true, $other = '') {
        parent::Widget($id, $label, $values, $class, $required, 0, 0, $other);
        if ($multiple)
            $this->name = $id . "[]";
        $this->multiple = $multiple;
        $this->expend = $expend;

        if ($expend) {
            $this->type = $multiple ? "checkbox" : "radio";
        } else {
            $this->type = "select";
        }
    }

    function __toString() {
        $class = $this->class;
        if ($this->required)
            $class .= " required";
        $class = "class='$class'";

        $str = "";
        if ($this->expend) {
            $type = $this->type;
            foreach ($this->value as $l => $v) {
                $str .= "<label>$l<input id='$this->id' name='$this->name' type='$type' value='$v' $class $this->other/></label><br/>";

                $class = $this->class == '' ? '' : "class='$this->class'";
            }
            $str = "<fieldset><legend>$this->label</legend>$str</fieldset>";
        } else {
            $multiple = $this->multiple ? "multiple='MULTIPLE'" : "";
            foreach ($this->value as $l => $v) {
                $str .= "<option value='$v'>$l</option>";
            }
            $str = "<select id='$this->id' name='$this->name' $class $multiple $this->other>$str</select>";
            if ($this->label != '')
                $str = "<label for='$this->id'>$this->label:</label>" . $str;
        }

        return $str;
    }

    function validate() {
        $value = $this->server_value();
        if ($this->multiple) {
            $res = array_diff($value, $this->value);
            return empty($res);
        }
        return in_array($value, $this->value);
    }

}

class Textarea extends Widget {

    var $type = 'textarea';
    var $whitelist = "\r\n\t";

    function Textarea($id, $label = '', $value = '', $class = '', $required = true, $min_length = 6, $max_length = 300, $other = '') {
        parent::Widget($id, $label, $value, $class, $required, $min_length, $max_length, $other);
        $this->other = 'rows=3 cols=50';
    }

    function __toString() {
        $class = $this->on_class();
        $str = '';
        if ($this->label != '') {
            $str = "<label for='$this->name'>$this->label:</label>";
        }
        $str .= "<textarea id='$this->id' name='$this->name' class='$class' minlength='$this->min_length' maxlength='$this->max_length' $this->other>$this->value</textarea>";
        return $str;
    }

}

class Button extends Widget {

    var $type = 'button';

    function Button($id, $label = '', $value = '', $class = '', $other = '') {
        parent::Widget($id, $label, $value, $class, false, 0, 0, $other);
    }

    function on_class() {
        return parent::on_class() . " button";
    }

    function validate() {
        return true;
    }

    function __toString() {
        $value = $this->value;
        if ($value != '')
            $value = "value='$value'";
        $class = $this->on_class();
        $class = "class='$class'";
        $str = "<input type = '$this->type' $value $class  $this->other />";
        return $str;
    }

}

class Reset extends Button {

    var $type = 'reset';

}

class Submit extends Button {

    var $type = 'submit';

}

class Hidden extends Widget {

    var $type = 'hidden';

    function Hidden($id, $label = '', $value = '', $class = '', $other = '') {
        parent::Widget($id, $label, $value, $class, false, 0, 0, $other);
    }

}

?>
