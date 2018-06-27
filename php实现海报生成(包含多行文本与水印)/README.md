源码地址：[实现海报生成(包含多行文本与水印)](https://github.com/nosee123/Issues/tree/master/php%E5%AE%9E%E7%8E%B0%E6%B5%B7%E6%8A%A5%E7%94%9F%E6%88%90(%E5%8C%85%E5%90%AB%E5%A4%9A%E8%A1%8C%E6%96%87%E6%9C%AC%E4%B8%8E%E6%B0%B4%E5%8D%B0))

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

**5) imagecopyresampled — 重采样拷贝部分图像并调整大小**

```
bool imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
```
`imagecopyresampled()`将一幅图像中的一块矩形区域拷贝到另一个图像中，平滑地插入像素值，因此，尤其是，减小了图像的大小而仍然保持了极大的清晰度。如果源和目标的宽度和高度不同，则会进行相应的图像收缩和拉伸。

相关函数有：

`imagecopyresized` — 拷贝部分图像并调整大小

**6) imagepng — 以 PNG 格式将图像输出到浏览器或文件**

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
![](https://upload-images.jianshu.io/upload_images/2021264-7e3211fdd4fe858c.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

## Author

[nosee123](https://github.com/nosee123)

## License

MIT Public License

