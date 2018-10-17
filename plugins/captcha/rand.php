<?php
/*$charText=strtoupper(substr(str_shuffle('abcdefghjkmnpqrstuvwxyz'),0,4));
$charNum=strtoupper(substr(str_shuffle('123456789'),0,2));
$strcaptcha=$charNum.$charText;*/
$strcaptcha=substr(sha1(uniqid(rand(), true)), 0, 6)
?>