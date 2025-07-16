<?php
session_start();
$image = imagecreatetruecolor(100, 50);
$bgcolor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $bgcolor);
$content = "ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqretuvwxyz123456789";
$captcha = "";
for($i = 0; $i < 4; $i++){
    $fontsize = 10;
    $fontcolor = imagecolorallocate($image, mt_rand(0, 120), mt_rand(0, 120), mt_rand(0, 120));

    $fontcontent = substr($content, mt_rand(0, strlen($content)),1);
    $captcha .= $fontcontent;
    $x = ($i * 100 /4) + mt_rand(5,10);
    $y = mt_rand(5,10);

    imagestring($image, $fontsize, $x, $y, $fontcontent, $fontcolor);

}
$_SESSION["captcha"] = $captcha;
for($i = 0; $i < 200; $i++){
    $pointcolor = imagecolorallocate($image, mt_rand(50, 120), mt_rand(50, 120), mt_rand(50, 120));
    imagesetpixel($image, mt_rand(1, 99),mt_rand(1,29), $pointcolor);
}
for($i = 0; $i < 3; $i++){
    $linecolor = imagecolorallocate($image, mt_rand(50, 200), mt_rand(50, 200), mt_rand(50, 200));
    imageline($image,mt_rand(1,99),mt_rand(1,29),mt_rand(1,99),mt_rand(1,29),$linecolor);
}
header('Content-type: image/png');
imagepng($image);
imagedestroy($image);