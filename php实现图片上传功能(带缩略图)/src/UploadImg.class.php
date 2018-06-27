<?php
namespace src;

class UploadImg extends UploadFile {

    public $mment              = null;    //上传图片最大尺寸
    public $use_mment          = true;  //最大尺寸限制
    public $limit_size         = array(); //上传图片规定尺寸
    public $useSizelimit       = true;     //限制图片最大尺寸

    public $width              = 0;      //上传图片信息0
    public $height             = 0;     //上传图片信息1
    public $type               = 0;     //上传图片信息2
    public $attr               = '';    //上传图片信息3

    public $thumbs             = array();  //需要缩略图的规格列表[数组]
    public $thumb_mod          = 0;        //缩略图方式，默认为等比例缩放，1表示按比例裁剪
    public $thumb_level        = 80;    //缩略图质量等级
    public $thumb_filenames    = array();  //缩略图的规格列表[数组]

    public $errormsg           = '';        //错误信息

    public function __construct($name, $exts = '') {
        if(!$exts) $exts = 'jpg jpeg png gif';//默认允许图片后缀
        parent::__construct($name, $exts);

        $this->set_picture_max_size(800, 600);
    }

    //设置图片最大尺寸
    private function set_picture_max_size($width,$height) {
        $this->mment['width'] = (int)$width;
        $this->mment['height'] = (int)$height;
    }

    //设置缩略
    public function add_thumb($keyname, $prefix, $width, $height, $level = 0) {
        $this->thumbs[$keyname] = array(
            'prefix' => $prefix,
            'width' => (int)$width,
            'height' => (int)$height,
            'level' => $level>0 ? $level : $this->thumb_level,
        );
    }

    //上传图片
    public function upload($folder, $thumb = true, $subdir = 'MONTH', $mment = NULL, $delete_sorcue = FALSE) {

        parent::upload($folder, $subdir); //完成上传图片

        if ($thumb){
            //开始上传缩略图
            $sorcuefile = ROOT . $this->path . DS . $this->filename;//原图
            if(function_exists('getimagesize') && !@getimagesize($sorcuefile)) {
                $this->delete_file();
                return false;
            }

            list($this->width, $this->height, $this->type, $this->attr) = @getimagesize($sorcuefile);

            //如果上传的图片比指定的最小上传尺寸小，则中断
            if($this->limit_size) {
                if($this->width < $this->limit_size['width'] || $this->height < $this->limit_size['height']) {
                    $this->errormsg = 'global_upload_image_limit_size_invalid';
                    return false;
                }
            }

            $thumbfunc = $this->thumb_mod ? 'create_thumb2' : 'create_thumb';

            if($this->thumbs) foreach($this->thumbs as $key => $val) {

                $savefile = ROOT . $this->path . DS . $val['prefix'] . $this->filename;//缩略图全路径

                //上传缩略图
                $this->$thumbfunc($sorcuefile, $savefile, $val['width'], $val['height'], $val['level']);

                $this->thumb_filenames[$key]['filename'] = $val['prefix'] . $this->filename;
                list($this->thumb_filenames[$key]['width'], $this->thumb_filenames[$key]['height'], $this->thumb_filenames[$key]['type'], $this->thumb_filenames[$key]['attr']) = @getimagesize($savefile);
            }

            //new size
            if(!$this->limit_size) {
                //自动限制最大图片尺寸，防止用户传超大图片
                if($this->useSizelimit) {
                    $mment = $mment ? $mment : ($this->mment && $this->use_mment ? $this->mment : null);
                    if($mment && ($this->width>$mment['width']||$this->height>$mment['height'])) {
                        $this->$thumbfunc($sorcuefile, $sorcuefile, $mment['width'], $mment['height'], $this->thumb_level);
                    }
                }
            } elseif($this->limit_size['width'] < $this->width || $this->limit_size['height'] < $this->height) {
                $this->$thumbfunc($sorcuefile, $sorcuefile, $this->limit_size['width'], $this->limit_size['height'], $this->thumb_level);
            }

            if($delete_sorcue) {
                $this->delete_file();        //删除源图片
            }
        }

        return TRUE;
    }

    //创建缩略图 ，等比例缩放
    function create_thumb($source_img_file, $dest_img_file, $new_width, $new_height, $level = 80) {
        $path_parts = pathinfo($source_img_file);
        $ext_name = strtolower($path_parts['extension']);
        $img = null;
        $img = $this->imagecreatefromimg($source_img_file);
        if ($img) {
            $imgcreate_fun = function_exists('ImageCreateTrueColor') ? 'ImageCreateTrueColor' : 'ImageCreate';
            $source_img_width = imagesx ($img);
            $source_img_height = imagesy ($img);
            if($source_img_width < $new_width || $source_img_height < $new_height) {
                $new_img_width = min($new_width,$source_img_width);
                $new_img_height = min($new_height,$source_img_height);
                $src_x = $source_img_width > $new_width ? (int) (($source_img_width - $new_width) / 2) : 0;
                $src_y = $source_img_height > $new_height ? (int) (($source_img_height - $new_height) / 2) : 0;
                $new_img = $imgcreate_fun ($new_img_width , $new_img_height);
                imagecopyresized($new_img ,$img, 0 ,0 ,$src_x ,$src_y ,$new_img_width, $new_img_height, $new_img_width , $new_img_height);
            } else {
                $w = $source_img_width / $new_width;
                if($source_img_height/$w >= $new_height) {
                    $new_img_width = $new_width;
                    $new_img_height = (int)($source_img_height / $source_img_width * $new_width);
                } else {
                    $new_img_height = $new_height;
                    $new_img_width = (int)($source_img_width / $source_img_height * $new_height);
                }
                $new_img = $imgcreate_fun ($new_width , $new_height);
                $new_img2 = $imgcreate_fun ($new_img_width , $new_img_height);
                ImageCopyResampled($new_img2, $img, 0, 0, 0, 0, $new_img_width, $new_img_height, $source_img_width, $source_img_height);
                $src_x = $new_img_width > $new_width ? (int) (($new_img_width - $new_width) / 2) : 0;
                $src_y = $new_img_height > $new_height ? (int) (($new_img_height - $new_height) / 2) : 0;
                imagecopyresized($new_img ,$new_img2, 0 ,0 ,$src_x ,$src_y , $new_width, $new_height , $new_width , $new_height);
                imagedestroy($new_img2);
            }
            $fun = $this->imagecreatefun($ext_name);
            if($fun=='imagejpeg') {
                $fun( $new_img, $dest_img_file, $level);
            } else {
                if($fun=='imagegif') {
                    $bgcolor = ImageColorAllocate($new_img ,0, 0, 0);
                    $bgcolor = ImageColorTransparent($new_img, $bgcolor);
                }
                $fun( $new_img, $dest_img_file);
            }
            imagedestroy($img);
            imagedestroy($new_img);
        } else {
            return false; //抛出异常--待升级
        }
    }

    /*
    按比例裁剪
    按比例，优先使用 new_width 生成，
    new_height 在 $source_img_width > $source_img_height 的情况下 使用
    */
    function create_thumb2($source_img_file, $dest_img_file, $new_width, $new_height, $level = 80) {
        $path_parts = pathinfo($source_img_file);
        $ext_name = strtolower($path_parts['extension']);
        $img = null;
        $img = $this->imagecreatefromimg($source_img_file);
        if ($img) {
            $source_img_width = imagesx ($img);
            $source_img_height = imagesy ($img);
            if ($source_img_width > $source_img_height) {
                $new_img_width = $new_width;
                $new_img_height = (int)($source_img_height / $source_img_width * $new_width);
            } else {
                $new_img_height = $new_height;
                $new_img_width = (int)($source_img_width / $source_img_height * $new_height);
            }
            $imgcreate_fun = function_exists('ImageCreateTrueColor') ? 'ImageCreateTrueColor' : 'ImageCreate';
            $new_img = $imgcreate_fun($new_img_width, $new_img_height);
            ImageCopyResampled($new_img, $img, 0, 0, 0, 0, $new_img_width, $new_img_height, $source_img_width, $source_img_height);
            $fun = $this->imagecreatefun($ext_name);
            if($fun=='imagejpeg') {
                $fun( $new_img, $dest_img_file, $level);
            } else {
                if($fun=='imagegif') {
                    $bgcolor = ImageColorAllocate($new_img ,0, 0, 0);
                    $bgcolor = ImageColorTransparent($new_img, $bgcolor);
                }
                $fun( $new_img, $dest_img_file);
            }
            imagedestroy($img);
            imagedestroy($new_img);
        } else {
            //error
            redirect('global_upload_image_dnot_support');
        }
    }

    //返回有效的img资源句柄
    function imagecreatefromimg($img) {
        $info = getimagesize($img);
        if(!$info) return false;
        switch(strtolower($info['mime'])) {
            case 'image/gif':
                return function_exists('imagecreatefromgif') ? imagecreatefromgif($img) : false;
                break;
            case 'image/jpeg':
            case 'image/jpe':
            case 'image/jpg':
                return function_exists('imagecreatefromjpeg') ? imagecreatefromjpeg($img) : false;
                break;
            case 'image/png':
                return function_exists('imagecreatefrompng') ? imagecreatefrompng($img) : false;
                break;
            default:
                return false;
        }
    }

    //返回创建图片的函数
    function imagecreatefun($ext) {
        switch($ext) {
            case 'gif':
                return 'imagegif';
            case 'png':
                return 'imagepng';
            default:
                return 'imagejpeg';
        }
    }

    //设置图片最小上传尺寸
    function set_picture_limit_size($width,$height) {
        $this->limit_size['width'] = (int)$width;
        $this->limit_size['height'] = (int)$height;
    }

}

