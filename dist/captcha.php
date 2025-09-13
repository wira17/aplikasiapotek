<?php
session_start();

// buat teks CAPTCHA random 5 karakter
$captcha_text = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890"), 0, 5);
$_SESSION['captcha'] = $captcha_text;

// buat gambar
$width = 120;
$height = 40;
$image = imagecreate($width, $height);

// warna background & text
$bg_color = imagecolorallocate($image, 255, 255, 255); // putih
$text_color = imagecolorallocate($image, 0, 0, 0);     // hitam
$line_color = imagecolorallocate($image, 64, 64, 64);  // garis acak

// garis acak
for ($i = 0; $i < 5; $i++) {
    imageline($image, 0, rand()%$height, $width, rand()%$height, $line_color);
}

// tulis teks
imagestring($image, 5, 15, 10, $captcha_text, $text_color);

// output
header("Content-type: image/png");
imagepng($image);
imagedestroy($image);
?>
