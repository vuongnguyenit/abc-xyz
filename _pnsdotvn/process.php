<?php
error_reporting(0);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);

ob_start("zlib output compression");
session_start();

header("Cache-Control: no-cache, must-revalidate");
header("Expires:-1");

require_once str_replace('\\','/',dirname(__FILE__))."/class/class.admin.php";
require_once str_replace('\\','/',dirname(__FILE__))."/defineConst.php";

$msg="fail";
$username=$_POST["username"];
$username=$dbf->escapeStr($username);
$password=$_POST["password"];
$password=$dbf->escapeStr($password);
$password=md5($password);
$returnURL=base64_decode($_POST["returnURL"]);
if(empty($returnURL)) $returnURL="index.php";

if(!isset($_SESSION["numLogin"]) OR empty($_SESSION["numLogin"])) $_SESSION["numLogin"]=0;
if($_SESSION["numLogin"]>=5)
{
	$msg="locked_anonymous";
	$affect=$dbf->updateTable(prefixTable."webmaster",array("locked"=>"1"),"username='$username' and status=1");
} else
{
	$result=$dbf->getDynamic(prefixTable."webmaster","username='$username' and password='$password' and status=1","");
	if($dbf->totalRows($result)>0) {
			$row=$dbf->nextData($result);
			if($row["locked"]==0)
			{
				$_SESSION["adminid"]=$row["id"];
				$_SESSION["user"]=stripslashes($username);
				$_SESSION['admission']=$row["level"];
				$msg="success+".$returnURL;
				unset($_SESSION["numLogin"]);
			} else
			{
			   	$msg="locked_account";
			}
	} else
	{
	  	++$_SESSION["numLogin"];
	}
}
echo $msg;exit;
?>