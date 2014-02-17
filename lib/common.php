<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

function generate_css() {
    global $config;

    $res = '';
    foreach ($config['css']as $v) {
        $res .= "<link rel=\"stylesheet\" type=\"text/css\" href=\"$v\"/>";
    }
    return $res;
}

function block ($key, $view){
    return generate_html(call_block($key), $view);
}

function call_block($key) {
    global $config;
    @list($c, $f, $p) = @explode(".", $key, 3);
    if (file_exists($config['base_dir'] . "/block/$c.php"))
        require_once ($config['base_dir'] . "/block/$c.php");
    else
        return "";
    $cc = single($c);

    $params = array();
    if (isset($p))
        $params = explode('.', $p);

    if ($f == null)
        $f = 'index';
    return @call_user_func_array(array($cc, $f), $params);
}

function generate_js() {
    global $config;

    $res = '';
    foreach ($config['js']as $v) {
        $res .= "<script src=\"$v\" type=\"text/javascript\"></script>";
    }
    return $res;
}

function document($key) {
    global $config;
    if (isset($config['document'][$key]))
        return $config['document'][$key];
    else 
    {
        return block($key,$key);
    }
}

function register($key, $data, $view = null) {
    global $config;
    $config['document'][$key] = generate_html($data, $view);
}

function generate_html($data, $view = null) {
    global $config;
    if ($view) {
        $view_file = $config['base_dir'] . "/view/$view" . ".php";


        if (file_exists($view_file)) {
            ob_start();
            require($view_file);
            $value = ob_get_clean();
            return $value;
        } else {
            return $data;
        }
    } else {
        return $data;
    }
}

function widget($widget) {
    global $config;
    $widget_file = $config['base_dir'] . "/widget/$widget" . '.html';
    return file_get_contents($widget_file);
}

function content($data, $view = 'content') {
    register("content", $data, $view);
}

function layout($layout){
    global $config;
    $config['layout'] = $layout . '.php';
}

function theme($theme){
    global $config;
    $config['theme'] = $theme;
}

function add_js($js){
    global $config;
    $config['dom_ready'][] = $js;
}


function redirection($url) {
    //print $url;
    if (!empty($url)) {
        Header("HTTP/1.1 302 Temporarily Moved");
        Header("Location: $url");
        exit;
    }
}

function begin_cache($file) {
    // if cache file exist and enough fresh, redirection the cache file 
    clearstatcache();
    $cachename = "cache_" . basename($file) . ".html";
    $spit = '/';
    if (strpos($file, '/') == false)
        $spit = '\\';
    $cachename_all = dirname($file) . $spit . $cachename;
    if (file_exists($cachename_all)) {
        // redirection policy
        redirection($cachename);
    }
    ob_start();
}

function end_cache($file) {
    $s = ob_get_contents();

    // output $s to cache file
    $cachename = "cache_" . basename($file) . ".html";
    $spit = '/';
    if (strpos($file, '/') == false)
        $spit = '\\';
    $cachename_all = dirname($file) . $spit . $cachename;

    $h = fopen($cachename_all, "w+");
    fwrite($h, $s);
    fclose($h);

    ob_end_flush();
}


?>
