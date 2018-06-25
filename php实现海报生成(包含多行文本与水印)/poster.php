<?php
/**
 * User: Chan
 * Date: 2018/6/25
 * Time: 17:12
 */

header('Content-Type: image/png');

// picture
$bg_file = 'data/img/background.png';
$star = 'data/img/star.png';
$p_file = 'data/img/star.png';
$qr_code = 'data/img/scan.png';

// font type
$font_path = 'data/fonts/msyh.ttc';

$img = imagecreatefrompng($bg_file);//背景
$star = imagecreatefrompng($star);//水印1
$p_pic = imagecreatefrompng($p_file);//水印2
$qr = imagecreatefrompng($qr_code);//水印3

$im = imagecreatetruecolor(imagesx($img), imagesy($img));//创建一张与背景图同样大小的真彩色图像
imagecopymerge($im, $img, 0, 0, 0, 0, imagesx($img), imagesy($img), 100);
//以上，相当于：  imagecopy($im, $img, 0, 0, 0, 0, imagesx($img), imagesy($img));

Imagepng($im);exit;

imagesavealpha($img,true);


//常用颜色
$black=ImageColorAllocate($img, 30, 30, 30);
$text_color_r = ImageColorAllocate($img, 255, 70, 70);

$test_r = '30';

$fh_name = $return_data['name'];
$vs_1 = $nick_name . ' VS ' . $fh_name;
imagettftext($im, 25, 0, $test_r, 80, $black, $font_path, $vs_1);

$str = '相似度：';
imagettftext($im, 25, 0, $test_r, 135, $black, $font_path, $str);
$str = $return_data['rate'];
imagettftext($im, 25, 0, 190, 136, $black, $font_path, $str);
$str = '%';
imagettftext($im, 25, 0, 245, 136, $black, $font_path, $str);

$str = '父爱指数：';
imagettftext($im, 25, 0, $test_r, 190, $black, $font_path, $str);
$star_num = $return_data['level'];
$w = 175;
for($i=1; $i<=$star_num; $i++){
    $tmp_w = $w + $i * 40;
    imagecopy($im, $star, $tmp_w, 165, 0, 0, 25, 26);
}

$ln = '你的教育观念是：';
imagettftext($im, 25, 0, $test_r, 245, $black, $font_path, $ln);


$h = 300;
foreach (str_split($return_data['text'],54) as $key=>$val){
    $tmp_h = $h + ($key) * 45;
    imagettftext($im, 24, 0, $test_r, $tmp_h, $text_color_r, $font_path, $val);
}

//加载人物图片
imagecopymerge($im, $p_pic, 70, 487, 0, 0, 337, 465, 100);
//加载二维码图片
imagecopymerge($im, $qr, 380, 497, 0, 0, 222, 222, 100);

//最后处理 输出、销毁
Imagepng($im);
ImageDestroy($img);
ImageDestroy($p_pic);
ImageDestroy($qr);
ImageDestroy($star);