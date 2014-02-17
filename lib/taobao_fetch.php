<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class taobao {
    var $history_view;
    var $current_view;
    
    function taobao_fetch($url) {
        $ch = curl_init();
//$fp = fopen("example_homepage.txt", "w");
//$url = "http://item.taobao.com/item.htm?spm=a2106.m895.1000384.d19.0YNq8h&id=17829525968&scm=1029.newlist-0.1.50040965&ppath=&sku=";
//$url = 'http://detail.tmall.com/item.htm?spm=1003.2.1000024.N.7WgRmL&id=16761844823&scm=1003.3.03037.0&acm=03037.1003.244.203.16761844823_1';
        curl_setopt($ch, CURLOPT_URL, $url);
//curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
//curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
//curl_setopt($ch, CURLOPT_MAXREDIRS, 5);
//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($s, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.3) Gecko/20100401'); //'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1');

        $html = curl_exec($ch);

        curl_close($ch);

        $json_echo = array();

//echo $html;
        $pos_s = strpos($html, '<title>') + 7;
        $pos_e = strpos($html, '</title>', $pos_s);
        $title_1 = substr($html, $pos_s, $pos_e - $pos_s);
        if ($title_1) $json_echo['title'] = $title_1;
        
//        $title = array();
//        preg_match('/<title>(.*)<\/title>/imus', $html, $title);
//
//        if (count($title) == 2) {
//            $json_echo['title'] = $title[1];
//        }

        $out = array();
        preg_match_all('/\{\s*"skuId".*?\}/ims', $html, $out);

        $price = 0;
        if (count($out) == 1) {
            foreach ($out[0] as $o) {
//        $o = trim($o, " \r\n\t\0");
//        $o = json_decode_nice($o);
//        echo $o;
                $o = str_replace(array("\n", "\0", "\n", "\r"), "", $o);
                $jo = json_decode($o);
                if ($jo) {
                    if (isset($jo->price) && $jo->price > $price) {
                        $price = $jo->price;
                    }
                }
            }
        }
        if ($price == 0){
           preg_match('/price:(.*?),/ims', $html, $out);
           $price = $out[1];
        }
        $json_echo['price'] = $price;

        $out2 = array();
        $pos = strpos($html, "tb-gallery");
        if (!$pos) {
            $json_echo['gallery_picture'] = "";
        } else {
            $second_pos = strpos($html, '</a>', $pos);
            $html2 = substr($html, $pos, $second_pos - $pos);
            preg_match('/(?:(?:data-src)|(?:background-image)|(?:src)).*?(http:[^")]+)/ims', $html2, $out2);
        }

        $json_echo['gallery_picture'] = $out2[1];

//    print_r($json_echo);
        return $json_echo;
    }
    
    function generate_short_cart_html(){
        
    }
}
?>
