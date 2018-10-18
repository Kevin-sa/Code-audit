<?php
/**
 * Created by PhpStorm.
 * User: Kevinsa
 * Date: 2018/10/16
 * Time: 下午8:15
 */


class DiscuzXss {

    function dhtmlspecialchars($string, $flags = null) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = $this->dhtmlspecialchars($val, $flags);
            }
        } else {
            if($flags === null) {
                $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
                if(strpos($string, '&amp;#') !== false) {
                    $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);
                }
            } else {
                if(PHP_VERSION < '5.4.0') {
                    $string = htmlspecialchars($string, $flags);
                } else {
                    if(strtolower(CHARSET) == 'utf-8') {
                        $charset = 'UTF-8';
                    } else {
                        $charset = 'ISO-8859-1';
                    }
                    $string = htmlspecialchars($string, $flags, $charset);
                }
            }
        }
        return $string;
    }

    function XssTest() {
        $payload = @$_GET['payload']?:"<svg/onload=alert(1)>";
        $payload = $this->dhtmlspecialchars($payload);
        return $payload;
    }

}
$a =new DiscuzXss(); echo $a->XssTest();
?>
<html>
<header>
    <script>
        function xsstest() {
            var div = document.createElement("div");
            first = document.getElementById("first");
            div.innerHTML = '<div class="tip_c">' + first.getAttribute("tip") + '</div>';
            document.getElementsByTagName('body')[0].appendChild(div);
        }
    </script>
</header>
<body>
<a id="first" onmouseover="xsstest()" tip="<?php $a =new DiscuzXss(); echo $a->XssTest();?>">X</a>
</body>
</html>
