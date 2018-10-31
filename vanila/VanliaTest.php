<?php
/**
 * Created by PhpStorm.
 * User: Kevinsa
 * Date: 2018/10/30
 * Time: 8:38 PM
 */

class VanilaTest {
    private function getConversationMembers($conversation){
        if(in_array($conversation)) {
            $where = $conversation;
        } else {
            $where = ['conversation' => $conversation];
        }

        return $this->getWhere($where);
    }

    private function getWhere( $where = false) {
        if($where != '') {
            foreach ($where as $subField => $subValue) {
                if (is_array($subValue)) {
                    if (count($subValue) == 1) {
                        $firstVal = reset($subValue);
                        $field = [$subField => $firstVal];
                    }
                }
            }
        }
        return $field;
    }

    function SQLTest() {
        $id = $_GET['id'];
        $_where = getConversationMembers($id);
        //$where = array('id'=>array('id'=>1));
        //$_where = $this->getWhere($where);

        $dbh = new PDO("mysql:dbname=test;host:127.0.0.1;",'root','');
        $sql = "select * from test where".array_keys($_where) ."=".array_values($_where);
        $statement = $dbh->prepare($sql);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
        
    }
}


$a = new VanilaTest();
$a->SQLTest();
