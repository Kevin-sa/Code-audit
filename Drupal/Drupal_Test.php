<?php
/**
 * Created by PhpStorm.
 * User: didi
 * Date: 2018/9/25
 * Time: 下午8:25
 */

class DrupalTest {
    private function set_error($element = '', $message = '', $limit_error = NULL) {
        if (isset($limit_error)) {
            $sections = $limit_error;
        }
        if (isset($message)) {

            $record = TRUE;
            if (isset($sections)) {
                $record = FALSE;
            }
        }
        if ($record) {
            $form[$message] = $message;
        } else {
            $form = $element;
        }
        return $form;
    }

    private function test_render($element) {
        if (isset($element['render'])) {
            foreach($element['render'] as $function) {
                if (function_exists($function)) {
                    $function($element['children'],$element);
                }
            }
        }
    }
    /**
    * Drupal 7.x RCE测试
    */
    public function drupal7_rce($element) {
        if (!isset($element['message'])) {
            $form = $this->set_error($element=$element);
        } else {
            $form = $this->set_error($element,'render',array());

        }
        $this->test_render($form);
    }
    
    /**
     * Drupal 8.x rce test
     * @param $elements
     */

    public function Drupal8_rce($elements)
    {
        foreach($elements['render'] as $callable) {
            if (isset($callable)) {
                $elements = call_user_func($callable, $elements['children']);
            }
        }
    }


}

//$element = array('render'=>array('exec','passthru'),'children'=>'echo $PATH','message'=>1);
$element = array('render'=>array('system','passthru'),'children'=>'whoami','message'=>1);
$a = new DrupalTest();
//$a->Drupal8_rce($element);
$a->drupal7_rce($element);
