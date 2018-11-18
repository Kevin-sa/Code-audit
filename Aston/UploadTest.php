<form action="" enctype="multipart/form-data" method="post" name="upload">
    file:
    <input type="file" name="file" />
    <input type="submit" value="upload">
</form>
<?php 
/**
 * Created by PhpStorm.
 * User: Kevinsa
 * Date: 2018/11/14
 * Time: 7:48 PM
 */

class UploadTest {

    var $file;
    var $file_name;
    var $file_size;
    var $file_type;
    var $file_error;
    var $savepath;
    var $savename;
    var $fileformat;
    var $ext;


    private function file_ext($filename) {
        if(strpos($filename, '.') === false) return '';
        $ext = strtolower(trim(substr(strrchr($filename, '.'), 1)));
        return preg_match("/^[a-z0-9]{1,10}$/", $ext) ? $ext : '';

    }

    private function is_allow() {
        if(!$this->fileformat) return false;
        if(!preg_match("/^(".$this->fileformat.")$/i", $this->ext)) return false;
        if(preg_match("/^(php|phtml|php3|php4|jsp|exe|dll|cer|shtml|shtm|asp|asa|aspx|asax|ashx|cgi|fcgi|pl)$/i", $this->ext)) return false;
        return true;
    }

    private function Upload($_file,$savepath,$savename='', $fileformat='') {
        foreach ($_file as $file) {
            $this->file = $file['tmp_name'];
            $this->file_name = $file['name'];
            $this->file_size = $file['size'];
            $this->file_type = $file['type'];
            $this->file_error = $file['error'];
        }


        $this->savepath = $savepath;
        $this->ext = $this->file_ext($this->file_name);
        $this->savename = $savename;
        $this->fileformat = $fileformat;
        //var_dump($this->file_ext($this->file_name));
        //var_dump($this->file_name);

    }
    private function save() {
        if(!$this->is_allow()) throw new Exception("Upload Test");
        move_uploaded_file($this->file, $this->savepath.'/'.$this->savename))
    }


    public function UploadVuln() {
        try {
            $ext = $this->file_ext($_FILES['file']['name']);
            $name = 'avatar.' . $ext;
            //echo $name;
            $this->Upload($_FILES, 'file/temp/', $name, 'jpg|jpeg|gif|png');
            $this->save();
        }catch (Exception $e) {
            echo $e->getMessage();
        }
    }


}

$a = new UploadTest();
$a->UploadVuln();
