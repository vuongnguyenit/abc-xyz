<?php
require_once("index_table.php");
@include("../class/language/lang.vi-VN.php");
$utls=new Utilities();
$col=array("id"=>"ID","content"=>"Bình luận", "added"=>"Ngày đăng", "approve"=>"Trạng thái");
$iscatURL = (isset($_GET['caturl']) AND !empty($_GET['caturl'])) ? $_GET['caturl'] : 0;
$titleMenu = "QUẢN LÝ BÌNH LUẬN";

if($isDelete)
{
  	$arrayid = substr($_POST['arrayid'], 0, -1);
  	$id_array = explode(',', $arrayid);
  	$affect = $dbf->deleteDynamic(prefixTable . 'comment', 'id in (' . $arrayid . ')');
  	if($affect > 0)
  	{		
    	$msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu';
  	}
  	
}

if($subInsert)
{
	
}

if($subUpdate)
{
	$err = 0;	
	if(isset($_GET['edit']) && !empty($_GET['edit']))
	{
		$_id				= (int) $_GET['edit'];	 
		$data['approve']	= $_POST['approve'] == 1 ? 1 : 0;
		$data['approved']	= time();	
		
		$affect = $dbf->updateTable(prefixTable . 'comment', $data, 'id = ' . $_id);
		if($affect > 0) $msg = 'Đã cập nhật (' . $affect . ') dòng trong cơ sở dữ liệu.';		
	} else
	{
		$msg = 'Vui lòng nhập đầy đủ dữ liệu.';
		$err = 1;
	}
}

if($isEdit)
{
	$rst = $dbf->Query('SELECT t1.*, t3.name as customer
		FROM dynw_comment t1 		
		INNER JOIN dynw_customer t3 ON t3.id = t1.member 		
		WHERE t1.id = ' . (int) $_GET["edit"] . '
		LIMIT 1');
	if($dbf->totalRows($rst))
	{
		$row	= $dbf->nextObject($rst);
		$id 	= $row->id;
		$member	= $row->member;
		$item	= $row->item;
		$type	= $row->type;
		$name = '';	
		if ($type == 1) {
			$q1 = 	$dbf->Query('SELECT t2.name FROM dynw_product t1 INNER JOIN dynw_product_desc t2 ON t2.id = t1.id WHERE t1.id = ' . $item . ' LIMIT 1');
			$r1 = $dbf->nextObject($q1);
			$name = $r1->name;
		}
		if ($type == 2) {
			$q2 = 	$dbf->Query('SELECT t2.name FROM dynw_cms t1 INNER JOIN dynw_cms_desc t2 ON t2.id = t1.id WHERE t1.id = ' . $item . ' LIMIT 1');
			$r2 = $dbf->nextObject($q2);
			$name = $r2->name;
		}
	}
} ?>
<script type="text/javascript" src="../themes/default/js/jquery-1.7.2.min.js"></script>
<?php $dbf->FormUpload('frm', array('action' => '', 'method' => 'post', 'class' => 'validate')) ?>
<?php if($isEdit/* || $isInsert*/) { ?>
<!-- form -->
<div id="panelForm" class="panelForm">
  <table id="mainTable" cellpadding="0" cellspacing="0">
    <?php echo $dbf->returnTitleMenu($titleMenu) ?>
    <tr>
      <td class="txtdo" colspan="2" align="center"><?php echo $msg ?></td>
    </tr>
    <tr>
      <td class="boxGrey">Thành viên</td>
	  <td class="boxGrey2"><a href="/_pnsdotvn/customer.php?edit=<?php echo $member ?>"><strong><?php echo (isset($row->customer) ? $row->customer : '') . ' [ID: ' . $member . ']' ?></strong></a></td>
    </tr>
    <tr>
      <td class="boxGrey">Nguồn</td>
	  <td class="boxGrey2"><a href="/_pnsdotvn/<?php echo $type == 1 ? 'product' : 'cms' ?>.php?edit=<?php echo $item ?>"><strong><?php echo (isset($name) ? $name : '') . ' [ID: ' . $item . ']' ?></strong></a></td>
    </tr>
    <tr>
      <td class="boxGrey">Nội dung</td>
	  <td class="boxGrey2"><div style="min-height:100px; max-height:100px; overflow-x:scroll; background-color:#FFF;"><?php echo isset($row->content) && !empty($row->content) ? stripslashes($row->content) : '' ?></div></td>
    </tr>
    <tr>
      <td class="boxGrey">Thông tin</td>
	  <td class="boxGrey2">
        <strong>IP</strong>: <?php echo isset($row->ip) && !empty($row->ip) ? stripslashes($row->ip) : '-' ?><br />       
        <strong>Ngày đăng</strong>: <?php echo isset($row->added) && !empty($row->added) ? date("d-m-Y", $row->added) : '-' ?><br />        
      </td>
    </tr>          
    <tr>
      <td class="boxGrey">Trạng thái</td>
      <td class="boxGrey2">
        <input type="checkbox" name="approve" id="approve" value="1" <?php echo strtr((isset($row->approve)) ? $row->approve : '', $statusChecked) ?> /> Duyệt đăng ?
      </td>
    </tr>   
    <tr>
      <td class="boxGrey"></td>
      <td height="1" align="center" class="boxGrey2"><div id="insert"><?php echo ($isInsert) ? $dbf->stateInsert() : (($isEdit) ? $dbf->stateUpdate() : '') ?></div></td>
    </tr>
    <tr>
      <td class="boxGrey" colspan="2"><span style="color:#DA251C">(*)</span> Bắt buộc nhập</td>
    </tr>
 </table>
</div>
<!-- end Form-->
<?php } ?>
<?php if(!$isEdit && !$isInsert)
{
  echo $dbf->returnTitleMenuTable($titleMenu);	
  $url = 'comment.php?caturl='. ((!empty($iscatURL)) ? '&caturl=' . (int) $iscatURL : '');
  $condition = '1=1';
  $mang = $dbf->pagingJoin(prefixTable . 'comment', prefixTable . 'customer', array(), 'inner join', $condition, 't1.id desc', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging, 't1.member = t2.id');
  echo $dbf->panelAction($mang[1]); 
?>
<!-- view -->
<div id="panelView" class="panelView">
  <?php $dbf->normalView($col, 'comment.php', $mang, $statusAction, '&caturl=' . $iscatURL, $msg = '') ?>
</div>
<!-- end view-->
<?php } ?>
<input type="hidden" name="arrayid" id="arrayid" />
</form>
</body></html><?php ob_end_flush()?>