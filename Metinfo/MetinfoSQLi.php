<?php
/**
 * Created by PhpStorm.
 * User: Kevinsa
 * Date: 2018/10/15
 * Time: 下午8:26
 */

class MetinfoTest {


    private function create(){
        define('in_array',true);
    }



    private function daddslashes($string, $force = 0) {
        !defined("MAGIC_QUOTES_GPC") && define("MAGIC_QUOTES_GPC",get_magic_quotes_gpc());
        if(!MAGIC_QUOTES_GPC || $force) {
            if(is_array($string)) {
                foreach ($string as $key=>$value) {
                    $string[$key] = $this->daddslashes($value);
                }
            }else{
                if(!defined("in_array")) {
                    $string = trim(addslashes($this->sqlinsert($string)));
                }else{
                    $string = trim(addslashes($string));
                }
            }
        }
        return $string;
    }

    private function sqlinsert($string) {
        if(is_array($string)) {
            foreach ($string as $key=>$value) {
                $string[$key] = $this->sqlinsert(value);
            }
        }else {
            $string_old = $string;
            $string = str_ireplace("\\", "/", $string);
            $string = str_ireplace("\"", "/", $string);
            $string = str_ireplace("'", "/", $string);
            $string = str_ireplace("*", "/", $string);
            $string = str_ireplace("%5C", "/", $string);
            $string = str_ireplace("%22", "/", $string);
            $string = str_ireplace("%27", "/", $string);
            $string = str_ireplace("%2A", "/", $string);
            $string = str_ireplace("~", "/", $string);
            $string = str_ireplace("select", "\sel\ect", $string);
            $string = str_ireplace("insert", "\ins\ert", $string);
            $string = str_ireplace("update", "\up\date", $string);
            $string = str_ireplace("delete", "\de\lete", $string);
            $string = str_ireplace("union", "\un\ion", $string);
            $string = str_ireplace("into", "\in\to", $string);
            $string = str_ireplace("load_file", "\load\_\file", $string);
            $string = str_ireplace("outfile", "\out\file", $string);
            $string = str_ireplace("sleep", "\sle\ep", $string);
            $string = strip_tags($string);
            if ($string_old != $string) {
                $string = '';
            }
            $string = trim($string);
        }
        return $string;
    }

    public function SQLiTest() {
        $mysqli = new mysqli("localhost",'root','','test');
        if(!$mysqli->connect_errno) {
        }else{
            exit();
        }
        $switch = @$_GET['switch'];
        if($switch) {
            $this->create();
        }
        $username = @$_GET['username']?$this->daddslashes($_GET['username']):'admin';
        $id = @$_GET['id'] ?$this->daddslashes($_GET['id']):8;

        $sql = "select * from test where username='{$username}' and id={$id}";
        if($result=$mysqli->query($sql)) {
            while($row=$result->fetch_array(MYSQLI_ASSOC)) {
                var_dump($row);
            }
        }else{
            var_dump($mysqli->error);
        }

    }
}

$a = new MetinfoTest();
$a->SQLiTest();
