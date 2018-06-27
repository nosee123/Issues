<?php
/**
 * Created by PhpStorm.
 * User: Chan
 * Date: 2018/4/28
 * Time: 16:13
 */
namespace src;

class UploadPicture
{
    // 上传图片
    function uploadPics($filename,$img_dir='test') {
        //上传图片部分
        $result = [];
        if(!empty($_FILES[$filename]['name'])) {

            $img = new UploadImg($filename);
            $thumb_w = 160;//缩略图宽
            $thumb_h = 100;//缩略图高
            $img->add_thumb('thumb', 'thumb_', $thumb_w, $thumb_h);
            $img->use_mment = false; // 关闭最大尺寸限制
            $img->upload($img_dir);

            $result['pic'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
            $result['thumb'] = str_replace(DS, '/', $img->path . '/' . $img->thumb_filenames['thumb']['filename']);
            $result['width'] = $img->width;
            $result['height'] = $img->height;
            $result['size'] = $img->size;
            unset($img);
        }
        return $result;
    }

}