<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class cachepageMiddleWare {
    var $tmp_filename;

    function init() {
        $this->tmp_filename = $_SERVER['DOCUMENT_ROOT'] . '/cache/' . md5($_SERVER['REQUEST_URI']) . '.html';
    
        $catch = $this->get_cache();
        if ($catch) {
            echo $catch;
            return false;
        }
        return true;
    }


    function get_cache() {
        $filename = $this->tmp_filename;
        if (file_exists($filename)) {
            return file_get_contents($filename);
        }
        return false;
    }

    function fini() {
            $filename = $this->tmp_filename;
            if (!file_exists($filename)) {
                $output = ob_get_contents();
                file_put_contents($filename, $output);
            }
    }

}

?>
