<?php
/**
 * User: Chan [ 123nosee@gmail.com ]
 * Date: 2018/4/28
 * Time: 9:58
 */
use src\UploadPicture;

define('DS', DIRECTORY_SEPARATOR );
define('ROOT', dirname(__FILE__) .  DS);

require_once './src/UploadPicture.class.php';
require_once './src/UploadFile.class.php';
require_once './src/UploadImg.class.php';

$detail = '';
$post = $_POST;
$file = $_FILES;
$error = false;

//上传文件
if (!empty($post['submit']) && !empty($file['pic'])) {
    if (!empty($file['pic']['tmp_name'])) {
        //开始上传文件
        $pic = new UploadPicture();
        $result = $pic->uploadPics('pic');
        //上传完成
        include_once './finish.html';
        return;
    } else {
        $error = true;
    }
}

include_once './home.html';