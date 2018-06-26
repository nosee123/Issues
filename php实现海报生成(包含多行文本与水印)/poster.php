<?php
/**
 * User: Chan
 * Date: 2018/6/25
 * Time: 17:12
 */

// picture
$file_bg = 'data/img/background.png';
$file_star = 'data/img/star.png';
$file_code = 'data/img/scan.png';

// font type
$font_msyh = 'data/fonts/msyh.ttc';
$font_simhei = 'data/fonts/simhei.ttf';

$img_bg = imagecreatefrompng($file_bg);//背景
$img_star = imagecreatefrompng($file_star);//水印1
$img_code = imagecreatefrompng($file_code);//水印2

$width = imagesx($img_bg);
$height = imagesy($img_bg);

//$im = $img_bg;
$im = imagecreatetruecolor($width, $height);  //创建一张与背景图同样大小的真彩色图像
imagecopy($im, $img_bg, 0, 0, 0, 0, $width, $height);

//text color
$color_white = ImageColorAllocate($im, 222, 222, 222);
$color_red = ImageColorAllocate($im, 255, 70, 70);

$test_r = '30';//文本右偏移

$text_title = '《遇见你之前》';
$v = imagettftext($im, 30, 0, $test_r, 250, $color_white, $font_msyh, $text_title);

$text = '《遇见你之前》是一部风格非常鲜明的英国爱情电影，温暖流畅的叙事节奏、明快动听的配乐和色彩明丽的美术效果是英国爱情电影里必不可少的元素，在本片中，这两个特点都被发挥到了极致。尤其是配乐和美术，在本片中简直完美到爆炸，几乎每一段BGM都可以被反复播放而不觉得厌倦，而色彩的选用上，导演也是独具匠心。';
$h = 350;
//多行文本渲染
foreach (str_split($text,72) as $key=>$val){
    $tmp_h = $h + $key * 90;
    imagettftext($im, 23, 0, $test_r, $tmp_h, $color_red, $font_simhei, $val);
}

//随机星星位置与数量
$star_num = rand(5,10);
$range_w = (int) $width;
$range_h = (int) ($height / 3);
for($i=1; $i <= $star_num; $i++){
    $tmp_w = $w + $i * 40;
    imagecopymerge($im, $img_star, rand(1,$range_w), rand(1,$range_h), 0, 0, imagesx($img_star), imagesy($img_star),rand(10,90));
}

//加载二维码图片
$width_code = imagesx($img_code);
$height_code = imagesy($img_code);
imagecopymerge($im, $img_code, $width-$width_code-30, $height-$height_code-50, 0, 0, $width_code, $height_code, 50);

header('Content-Type: image/png');
//最后处理 输出、销毁
Imagepng($im);
ImageDestroy($img_code);
ImageDestroy($img_star);
ImageDestroy($img_bg);
ImageDestroy($im);