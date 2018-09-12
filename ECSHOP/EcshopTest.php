<?php
/**
 * Created by PhpStorm.
 * User: Kevinsa
 * Date: 2018/9/12
 * Time: 上午11:25
 */



class EcshopTest {

    private function insert_mod($name)
    {

        list($fun, $para) = explode('|', $name);
        $para = unserialize($para);
        $fun = 'insert_' . $fun;
        
        return $this->$fun($para);
    }

    private function mysqli_conn($sql) {
        $mysqli = new mysqli('localhost','root','','test');

        if($mysqli->connect_errno) {
            echo 'mysql connect faile';
            exit();
        }

        if($result = $mysqli->query($sql)) {
            printf("row:",$result->num_rows);
        }else{
            echo $mysqli->error;
        }
    }

    private function insert_ads($arr) {

        $sql = "select * from test where id=". $arr['id'];
        $result = $this->mysqli_conn($sql);
        return $result;
    }

    public function SQliTest() {
        $var = $_SERVER['HTTP_REFERER'];
        //$var = 'ads|a:2:{s:2:"id";s:43:"1 and updatexml(1,concat(0x7e,version()),1)";s:3:"num";s:1:"1";}';
        $this->insert_mod($var);
    }

}

$a = new EcshopTest();
$a->SQliTest();
