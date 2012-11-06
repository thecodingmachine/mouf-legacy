<?php
chdir(ROOT_PATH.'plugins/utils/captcha/Securimage/2.0.1-beta');
include_once ROOT_PATH.'plugins/utils/captcha/Securimage/2.0.1-beta/securimage.php';

$instance = MoufManager::getMoufManager()->getInstance($_GET['instance_name']);
/* @var $instance SecurimageCaptchaWidget */

$img = new securimage();
$img->image_width = $instance->imageWidth;
$img->image_height = $instance->imageHeight;
$img->perturbation = $instance->perturbation;
$img->num_lines = $instance->num_lines;


// Change some settings
//$img->image_width = 275;
//$img->image_height = 90;
//$img->perturbation = 0.9; // 1.0 = high distortion, higher numbers = more distortion
//$img->image_bg_color = new Securimage_Color("#0099CC");
//$img->text_color = new Securimage_Color("#EAEAEA");
//$img->text_transparency_percentage = 65; // 100 = completely transparent
//$img->num_lines = 8;
//$img->line_color = new Securimage_Color("#0000CC");
//$img->signature_color = new Securimage_Color(rand(0, 64), rand(64, 128), rand(128, 255));
//$img->image_type = SI_IMAGE_PNG;


$img->show(); // alternate use:  $img->show('/path/to/background_image.jpg');
