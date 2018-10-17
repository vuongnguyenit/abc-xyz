<?php
error_reporting(0);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
ini_set('session.gc_maxlifetime', 60*60*5);
date_default_timezone_set('Asia/Saigon');
ob_start('ob_gzhandler');

    session_start();
	require_once("class/class.admin.php");
	require_once("../class/class.utls.php");
	require_once("defineConst.php");
	$dbf->queryEncodeUTF8();
	
    $msg = "";
    if(empty($_SESSION["user"]) OR (!isset($_SESSION["user"]) OR !isset($_SESSION["admission"])))
    {			
    	/*echo "<script>window.location='login.jsp?returnURL=".base64_encode($pageAdmin)."';</script>";*/
    	@header("Location:login.jsp?returnURL=".base64_encode($pageAdmin));
		exit;
    }    
    require_once("../class/class.utls.php");
	require_once("../class/class.xml.php");
    #require_once("spawInnova/spaw.inc.php");
    #$utls->writeLog();
	 
	if($dbf->checkBrowser() == "FF") 
	$dbf->docType();	
	$dbf->openHead();
	$titleWebsite="Administrator";
	$arrayCSS=array("/_pnsdotvn/style/styleadmin.css");
	//../prototype/prototype.js
	$arrayJS=array("/_pnsdotvn/js/adminLib.js","/_pnsdotvn/js/jsForm.js");
	$dbf->displayHead("utf-8","3600",$titleWebsite,"","","","../images/logo.jpg",$arrayCSS,$arrayJS);
   
	$contentScript="function huy()\n";
	$contentScript.="{\n";
	$idurl=(isset($_GET['idurl'])&&($_GET['idurl']!=""))?"&idurl=".$_GET['idurl']:"";
	$caturl=(isset($_GET['caturl'])&&($_GET['caturl']!=""))?"&caturl=".$_GET['caturl']:"";		
	$pageurl=(isset($_GET['PageNo'])&&($_GET['PageNo']!=""))?"&PageNo=".$_GET['PageNo']:"";
	$contentScript.="window.location='".$_SERVER['PHP_SELF']."?".$caturl.$idurl.$pageurl."';";
	$contentScript.="\n}\n";
	$dbf->displayJavascript($contentScript);	
	
	$dbf->closeHead()
?>
<body onLoad="init();" oncontextmenu="return false">
<?php echo $dbf->generateHeader($pageAdmin) ?>