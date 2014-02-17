<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
global $config;
include_once ($config['base_dir'] . '/dbschema.php');

class Model {

    var $__table;

    function Model($table) {
        $this->__table = $table;
    }

    function generate_form($action) {
        $schema = single('DBSchema');
        $form = form($this->__table, $action);
        $table = $this->__table;
        foreach ($schema->$table as $field => $detail) {
            if ($field == 'id')
                continue;
            $widget = null;
            $require = !$detail['null'];
            $maxlength = preg_match('/\((\d+)\)$/', $detail['type'], $match);
            if ($maxlength)
                $maxlength = $match[1];
            if (isset($detail['max_length']))
                $maxlength = $detail['max_length'];

            switch ($field) {
                case 'password':
                    $widget = new Password($field, $detail['name']);
                    break;
                case 'url':
                    $widget = new Url($field, $detail['name']);
                    break;
                case 'email':
                    $widget = new Email($field, $detail['name']);
                    break;
                default :
                    break;
            }
            if (is_null($widget)) {
                $type = $detail['type'];
                if (isset($detail['meta']['type']))
                    $type = $detail['meta']['type'];

                if (strncasecmp($type, 'varchar', 7) == 0) {
                    $widget = new Input($field, $detail['name']);
                } else if (strncasecmp($type, 'int', 3) == 0) {
                    $widget = new Number($field, $detail['name']);
                } else if (strncasecmp($type, 'tinyint', 7) == 0) {
                    $widget = new Number($field, $detail['name']);
                } else if (strncasecmp($type, 'float', 5) == 0) {
                    $widget = new RealNumber($field, $detail['name']);
                } else if (strncasecmp($type, 'datetime', 8) == 0) {
                    $widget = new DateTimeUI($field, $detail['name']);
                } else if (strncasecmp($type, 'date', 4) == 0) {
                    $widget = new Date($field, $detail['name']);
                } else if (strncasecmp($type, 'text', 4) == 0) {
                    $widget = new Textarea($field, $detail['name']);
                } else if (strncasecmp($type, 'files', 5) == 0) {
                    $widget = new Files($field, $detail['name']);
                } else if (strncasecmp($type, 'file', 4) == 0) {
                    $widget = new File($field, $detail['name']);
                } else if (strncasecmp($type, 'images', 6) == 0) {
                    $widget = new Files($field, $detail['name'], '200K', 'jpg|jpeg|png|gif');
                } else if (strncasecmp($type, 'image', 5) == 0) {
                    $widget = new File($field, $detail['name'], '200K', 'jpg|jpeg|png|gif');
                }
            }
            $widget->required = $require;
            if ($maxlength && $maxlength > $widget->min_length) {
                $widget->max_length = $maxlength;
            }
            $form->add_widget($widget);
        }

        $form->add_widget(new BR(0));
        $form->add_widget(new Submit('submit', 'Submit', 'submit', 'btn btn-primary'));

        return $form;
    }

}

?>
