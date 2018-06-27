<?php
namespace src;

class UploadFile {

    public $root_dir = 'uploads';//上传文件根目录
    public $folder;     //上传文件目录
    public $max_size;  //上传文件最大内存
    public $limit_ext;  //允许上传文件后缀

    public $size;  //文件大小
    public $filename;   //文件名字
    public $path;    //文件全路径

    private $_file;

    public function __construct($name,$exts='') {

    	if(empty($_FILES[$name])) return false;

        $this->_file =& $_FILES[$name];

        if($this->_file['error'] > 0) return false;

        $this->size = $this->_file['size'];
        
        $this->max_size = $this->size_bytes(ini_get('upload_max_filesize'));

        if(!$exts) $exts = 'rar zip 7z txt';

        $this->set_ext($exts);
    }

    private function set_ext($exts) {
        if(!$exts) return '';
        $exts = explode(' ', $exts);
        foreach($exts as $k => $v) {
            if(!$v) {
                unset($exts[$k]);
            } elseif(preg_match("/(php|inc|asp|jsp|aspx|shtml|vbs|do)/i",$v)) {
                unset($exts[$k]);
            } else {
                $exts[$k] = strtolower($v);
            }
        }
        if($exts) $this->limit_ext = $exts;
    }

    public function set_max_size($size) {
        $this->max_size = min($this->max_size, $this->size_bytes($size . 'k'));
    }

    //取得字节单位
    private function size_bytes($val) {
        $val = trim($val);
        $last = strtolower($val{strlen($val)-1});
        $val = intval($val);
        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }
        return $val;
    }

    function upload($folder, $subdir = 'MONTH') {

        $this->_check();

        $this->folder = $folder;
        $path = ROOT . $this->root_dir . DS . $this->folder;

        if(!@is_dir($path)){
            if(!@mkdir($path, 0777)) {
                return false;//抛出异常--待升级
            }
        }

        if($subdir == 'WEEK') {
            $subdir = date('Y', time()).'-week-'.date('W', time());
        } elseif($subdir == 'DAY') {
            $subdir = date('Y-m-d', time());
        } elseif(!$subdir || $subdir == 'MONTH') {
            $subdir = date('Y-m', time());
        }

        if($subdir) {
            $dirs = explode(DS, $subdir);
            foreach ($dirs as $val) {
                $path .= DS . $val;
                if(!@is_dir($path)) {
                    if(!@mkdir($path, 0777)) {
                        return false;//抛出异常--待升级
                    }
                }
            }
        }

        $fileinfo = pathinfo($this->_file['name']);
        $ext = strtolower($fileinfo["extension"]);

        PHP_VERSION < '4.2.0' && srand();
        $rand = rand(1, 100);
        $name = $rand . '_' . time() . '.' . $ext;
        unset($rand);

        $sorcuefile = $path . DS . $name;

        if (move_uploaded_file($this->_file['tmp_name'], $sorcuefile)) {
            $this->filename = $name;
            $this->path = str_replace(ROOT, '', $path);
            $this->delete_tmpfile();
            return TRUE;
        } else {
            return false; //抛出异常--待升级
        }
    }

    function delete_tmpfile() {
        @unlink(str_replace("\\\\", "\\", $this->_file['tmp_name']));
    }

    function delete_file() {
        @unlink($this->path.'/'.$this->filename);
    }

    function is_upfile($filename) {
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(!$ext) return FALSE;
        return in_array($ext, $this->limit_ext);
    }

    //上传文件检查
    function _check() {
        if(!is_uploaded_file($this->_file['tmp_name'])) {
            return false; //抛出异常--待升级
        } elseif(!$this->is_upfile($this->_file['name'])) {
            @unlink($this->_file['tmp_name']);
            return false; //抛出异常--待升级
        } elseif($this->_file['size'] > $this->max_size) {
            @unlink($this->_file['tmp_name']);
            return false; //抛出异常--待升级
        }
        return TRUE;
    }

    function get_filename() {
        $this->_check();
        $filename = str_replace("\\\\", "\\", $this->_file['tmp_name']);
        return $filename;
    }

    function get_contents() {
        $filename = $this->get_filename();
        return file_get_contents($filename);
    }

}