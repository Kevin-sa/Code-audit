<?php
/**
 * Created by PhpStorm.
 * https://www.cnblogs.com/iamstudy/articles/discuz_x34_ssrf_1.html
 * Date: 2018/12/10
 * Time: 8:42 PM
 */

Class DiscuzSSRF {

    public function SSRFTes($ource) {
        $prefix = '/';
        //$source = $prefix . $_GET["cuting"];
        $source = $prefix . $ource;
        $parse = parse_url($source);
        //var_dump($parse);
        if(isset($parse['host'])) {
            $scheme = '';
            $host = $parse["host"];
            $path = $parse['path'] ? $parse['path'] . '' : '/';
            $port = !empty($parse['port']) ? $parse['port'] : ($scheme == 'http' ? '80' : '');

            if(function_exists('curl_init') && function_exists('curl_exec')) {
                $ch = curl_init();

                //curl_setopt($ch, CURLOPT_URL, $scheme.'://'. $host . ($port ? ':' . $port : '') . $path);
                $url = "http://://baidu.com/";
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER ,false);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
                curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
                curl_setopt($ch,CURLOPT_HEADER,1);


                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,15);
                curl_setopt($ch,CURLOPT_TIMEOUT,15);
                $data = curl_exec($ch);
                $status = curl_getinfo($ch);
                $errno = curl_errno($ch);
                curl_close($ch);

                //var_dump($status);
                if($errno || $status['http_code'] != 200) {
                    return;
                }else{
                    return $data;
                }
            }
        }
    }

    public function dreferer() {
        $referer = !empty($_GET['referer']) ? $_GET['referer'] : $_SERVER['HTTP_REFERER'];
        $referer = substr($_GET['referer'],-1) == '?' ? substr($referer, 0, -1) : $referer;

        $reurl = parse_url($referer);

        if(!$reurl || (isset($reurl['scheme']) && !in_array(strtolower($reurl['scheme']), array('http', 'https')))) {
            $referer = '';
        }

        if(!empty($reurl['host']) && !in_array($reurl['host'] , array($_SERVER['HTTP_HOST'], 'www.' . $_SERVER['HTTP_HOST'])) && !in_array($_SERVER['HTTP_HOST'], array($reurl['host'], 'wwww.' . $reurl['host']))) {
            $referer = '';
        }elseif(empty($reurl['host'])) {
            $referer = '';
        }

        $referer = urlencode($referer);
        return $referer;
    }

    public function HeaderTest() {

        if($_GET['header']) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location:".$this->dreferer());
        }
    }
}


$a = new DiscuzSSRF();


/*
$url = $_GET['url'];
header("Location:" . $url);
 */
