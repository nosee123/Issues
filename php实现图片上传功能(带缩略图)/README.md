源码地址：[php实现图片上传功能(带缩略图)](https://github.com/nosee123/Issues/tree/master/php%E5%AE%9E%E7%8E%B0%E5%9B%BE%E7%89%87%E4%B8%8A%E4%BC%A0%E5%8A%9F%E8%83%BD(%E5%B8%A6%E7%BC%A9%E7%95%A5%E5%9B%BE))

# PicUpload
## Summary
**图片上传（带缩略图）**

该插件集成了图片上以及图片压缩的功能。

核心方法为：

`upload($folder, $thumb = true);`
第一个参数$folder为上传的文件到哪个目录的意思，第二个参数默认为true表示同时上传缩略图。

## Usage

上传图片：
```
function uploadPics($filename,$img_dir='test') {
        //上传图片部分
        $result = [];
        if(!empty($_FILES[$filename]['name'])) {
            $img = new UploadImg($filename);
            $img->upload($img_dir,false);
            $result['pic'] = str_replace(DS, '/', $img->path . '/' . $img->filename);
            $result['width'] = $img->width;
            $result['height'] = $img->height;
            $result['size'] = $img->size;
            unset($img);
        }
        return $result;
    }
```

上传图片（带缩略图）：
```
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
```
## Author

[nosee123](https://github.com/nosee123)

## License

MIT Public License


