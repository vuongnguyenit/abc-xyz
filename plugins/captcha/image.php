<?php
// Begin the session
@session_start();
// If the session is not present, set the variable to an error message
if(!isset($_SESSION['captcha_id'])) $str = 'ERROR!';
// Else if it is present, set the variable to the session contents
else $str = $_SESSION['captcha_id'];
// Set the content type
header('Content-type: image/jpeg');
/*header('Cache-control: no-cache');*/
// Create an image from button.png
$image = imagecreatefromjpeg(str_replace("\\","/",dirname(__FILE__)) . '/button.jpg');
// Set the font colour
$colour = imagecolorallocate($image, 000, 000, 000);
// Set the font
$font = str_replace("\\","/",dirname(__FILE__)) . '/verdana.ttf';
// Set a random integer for the rotation between -15 and 15 degrees
$rotate = rand(-0, 0);
// Create an image using our original image and adding the detail
imagettftext($image,14,$rotate,0,18,$colour,$font,$str);
// Output the image as a png
imagepng($image)
?>