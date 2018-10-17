<?
include("index_table.php");

if($_SESSION['admission']=="User")
{
	header("Location:index.php");
	exit;
}

$col=array("id"=>"Id","username"=>"Tên tài khoản","fullname"=>"Tên đầy đủ","email"=>"Email","questionaire"=>"Bạn thích gì nhất","level"=>"Quyền","status"=>"Trạng thái");
$iscatURL = (isset($_GET['caturl']) AND !empty($_GET['caturl'])) ? $_GET['caturl'] : "";
$manglevelAdmin=array("Admin"=>"selected='selected'");
$manglevelUser=array("User"=>"selected='selected'");

$titleMenu="QUẢN LÝ TÀI KHOẢN HỆ THỐNG";

if($isDelete) {
	$arrayid = substr($_POST["arrayid"], 0, -1);
	$id_array = explode(",",$arrayid);
	$affect = $dbf->deleteDynamic(prefixTable. "webmaster", "id in (" . $arrayid . ")");
    if($affect > 0)
	$msg = "Đã xóa (" . count($id_array) . ") dòng trong cơ sở dữ liệu.";
}

if($subInsert)
{
    $username=addslashes($_POST["username"]);
    $password=md5($_POST["password"]);
    $fullname=addslashes($_POST["fullname"]);
    $email=$_POST["email"];
    $level=$_POST["level"];
    $status=$_POST["status"];
	$questionaire=addslashes($_POST["questionaire"]);

    $affect=$dbf->insertTable(prefixTable."webmaster",array("username"=>$username,"password"=>$password,"fullname"=>$fullname,"email"=>$email,"questionaire"=>$questionaire,"level"=>$level,"status"=>$status));
    if($affect>0) $msg="Đã thêm dòng ($affect) trong cơ sở dữ liệu";
}

if($subUpdate)
{
    /*$username=addslashes($_POST["username"]);*/
    $password=md5($_POST["password"]);
    $fullname=addslashes($_POST["fullname"]);
    $email=$_POST["email"];
	$questionaire=addslashes($_POST["questionaire"]);
    $level=$_POST["level"];
    $status=$_POST["status"];
    $chkupdate=isset($_POST["chkupdate"]) ? (int) $_POST["chkupdate"] : 0;

    if($chkupdate==0)
    	$affect=$dbf->updateTable(prefixTable."webmaster",array("fullname"=>$fullname,"email"=>$email,"questionaire"=>$questionaire,"level"=>$level,"status"=>$status),"id='".$_GET['edit']."'");
    else
    	$affect=$dbf->updateTable(prefixTable."webmaster",array("password"=>$password,"fullname"=>$fullname,"email"=>$email,"questionaire"=>$questionaire,"level"=>$level,"status"=>$status),"id='".$_GET['edit']."'");

    if($affect>0) $msg="Đã cập nhật ($affect) dòng trong cơ sở dữ liệu";
}
if($isEdit)
{
	$rst=$dbf->getDynamic(prefixTable."webmaster","id='".$_GET['edit']."'","username asc");
	if($rst)
	{
		$row=$dbf->nextData($rst);
		$username=stripslashes($row['username']);
		$fullname=stripslashes($row['fullname']);
		$questionaire=stripslashes($row['questionaire']);
		$email=$row['email'];
		$level=$row['level'];		
		$status=(int)$row['status'];
	}
}
?>
<body>
<?php $dbf->normalForm("frm",array("action"=>"","method"=>"post","onsubmit"=>"return checkaccount('".(($isEdit)?$isEdit:"0")."');"));?>

<?php
	if($isEdit || $isInsert){
?>
<!-- form -->
<div id="panelForm" class="panelForm">
	  <table id="mainTable" cellpadding="0" cellspacing="0">
          <?php
            echo $dbf->returnTitleMenu($titleMenu);
          ?>
        <tr>
              <td colspan="4" class="txtdo" align="center"><?=$msg?></td>
        </tr>
          <tr>
            <td  class="boxGrey"><?php echo $col["username"]?></td>
            <td class="boxGrey2">
              <input name="username" <?php echo $disabledControl;?> id="username" type="text" <?php echo $dbf->focusText();?>  class="nd1" value="<?php echo (isset($username) AND !empty($username)) ? $username : ''?>" />
            </td>
          </tr>
          <tr style="<?php echo $showControl;?>">
            <td  class="boxGrey">Sửa mật khẩu</td>
            <td class="boxGrey2"><div><?php echo $dbf->checkbox("chkupdate",1,"Cập nhật mật khẩu?",array("onclick"=>"showControl(this.checked);"))?></div>
            </td>
          </tr>
          <tr>
          <td colspan="2"><div id="groupPassword" style="<?php echo $hideControl?>">
            <table border="0" cellpadding="0" cellspacing="0" width="100%">
              <tr>
                <td class="boxGrey">Mật khẩu</td>
                <td class="boxGrey2"><input name="password" id="password" type="password" <?php echo $dbf->focusText();?> class="nd1" />
                </td>
              </tr>
    		   <tr>
                <td  class="boxGrey">Xác nhận mật khẩu</td>
                <td class="boxGrey2"><input name="confirmpassword" id="confirmpassword" type="password" <?php echo $dbf->focusText();?> class="nd1" />
                </td>
              </tr>
            </table>
            </div>
          </td></tr>
          <tr>
            <td  class="boxGrey"><?php echo $col["fullname"]?></td>
            <td class="boxGrey2"><input name="fullname" id="fullname" type="text" <?php echo $dbf->focusText()?> class="nd1" value="<?php echo (isset($fullname) AND !empty($fullname)) ? $fullname : ''?>"   />
            </td>
          </tr>
          <tr>
            <td  class="boxGrey"><?php echo $col["email"]?></td>
            <td class="boxGrey2"><input name="email" id="email" type="text" <?php echo $dbf->focusText()?> class="nd1" value="<?php echo (isset($email) AND !empty($email)) ? $email : ''?>"  />
            </td>
          </tr>
		   <tr>
            <td  class="boxGrey"><?php echo $col["questionaire"]?></td>
            <td class="boxGrey2"><input name="questionaire" id="questionaire" type="text" <?php echo $dbf->focusText()?> class="nd1" value="<?php echo (isset($questionaire) AND !empty($questionaire)) ? $questionaire : ''?>"  />
            </td>
          </tr>
          <tr>
            <td  class="boxGrey"><?php echo $col["level"]?></td>
            <td class="boxGrey2">
            <select name="level" id="level" class="cbo">
                <option value="User" <?php echo strtr((isset($level) AND !empty($level)) ? $level : '',$manglevelUser)?>>User</option>
                <option value="Admin" <?php echo strtr((isset($level) AND !empty($level)) ? $level : '',$manglevelAdmin)?>>Administrator</option>
              </select>
            </td>
          </tr>
          <tr>
            <td   class="boxGrey"><?php echo $col["status"]?></td>
            <td width="379" class="boxGrey2">
            <input type="checkbox" name="status" id="status" value="1" <?php echo strtr($status,$statusChecked)?> />
              Kích hoạt? </td>
          </tr>
          <tr>
            <td class="boxGrey"></td>
            <td height="1" align="center" class="boxGrey2">
              <div id="insert"><?php echo (($isInsert)?$dbf->stateInsert():(($isEdit)?$dbf->stateUpdate():""));?></div>              
            </td>
          </tr>
        </table>
</div>
<?php
if($isInsert && !empty($msg)) echo "<script type=\"text/javascript\">huy(); </script>"
?>
<!-- end Form-->
<?php
}
?>


<?php

	if(!$isEdit && !$isInsert){
	echo $dbf->returnTitleMenuTable($titleMenu);
    $url="admin.php?";
    $mang=$dbf->paging(prefixTable."webmaster","","username asc",$url,$PageNo,$PageSize,$Pagenumber,$ModePaging);
	echo $dbf->panelAction($mang[1]);

?>
<!-- view -->
<div id="panelView" class="panelView">
<?php
	$dbf->normalView($col,"admin.php",$mang,$statusAction,"&caturl=" . $iscatURL,$msg);
?>
</div>
<!-- end view-->
<?php
}
?>
	<input type="hidden" name="arrayid" id="arrayid" />
  </form>
  <?PHP
  ob_end_flush();
  ?>
</body>
</html>