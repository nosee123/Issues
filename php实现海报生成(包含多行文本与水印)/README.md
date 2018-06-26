php实现海报生成(包含多行文本与水印)
## 场景
生成一张海报，海报内容可以渲染多行文本与水印。
 
## 解决方案
**1) imagecreatefrompng — 由文件或 URL 创建一个新图象**

```
resource imagecreatefrompng ( string $filename )
```
相关函数有：`imagecreatefromjpeg()`，`imagecreatefromgif()`

**2) imagecreatetruecolor — 新建一个真彩色图像**

```
resource imagecreatetruecolor ( int $width , int $height )
```

相关函数有：

`imagecreate()` - 新建一个基于调色板的图像

`imagedestroy()` - 销毁一图像

**3) imagettftext — 用 TrueType 字体向图像写入文本**

```
array imagettftext ( resource $image , float $size , float $angle , int $x , int $y , int $color , string $fontfile , string $text )
```

**4) imagecopy — 拷贝图像的一部分**

```
bool imagecopy ( resource $dst_im , resource $src_im , int $dst_x , int $dst_y , int $src_x , int $src_y , int $src_w , int $src_h )
```

相关函数有：

`imagecopymerge()` — 拷贝并合并图像的一部分


**5) imagepng — 以 PNG 格式将图像输出到浏览器或文件**

```
imagepng — 以 PNG 格式将图像输出到浏览器或文件
```
imagepng() 将 GD 图像流（image）以 PNG 格式输出到标准输出（通常为浏览器），或者如果用 filename 给出了文件名则将其输出到该文件。 

相关函数有：

`imagejpeg()` — 输出图象到浏览器或文件。

`imagegif()` — 输出图象到浏览器或文件。

## 案例

查看 poster.php

## 运行结果
![](https://upload-images.jianshu.io/upload_images/2021264-2e020abb006cbaf0.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

## 说明

本文的案例只论述了对png图片的处理，如果你要处理的图片不一定是png，你可以通过判断图片后缀来选择相应的方法。

## 参考

官方手册(GD库) http://php.net/manual/zh/book.image.php

## Author

[nosee123](https://github.com/nosee123)

