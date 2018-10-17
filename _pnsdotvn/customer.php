<?php
require_once("index_table.php");
@include("../class/language/lang.vi-VN.php");
$utls=new Utilities();
$col=array("id"=>"ID","name"=>"Thành viên","email"=>"Email","mobile" => "Di động","added"=>"Ngày đăng ký","updated"=>"Ngày cập nhật","status"=>"Trạng thái");
$iscatURL = (isset($_GET['caturl']) AND !empty($_GET['caturl'])) ? $_GET['caturl'] : 0;
$titleMenu = "QUẢN LÝ THÀNH VIÊN";

/*
if($isDelete)
{
}
*/

/*
if($subInsert)
{
}
*/

if($subUpdate)
{
  if (!empty($_POST['password']) && !empty($_POST['repassword']) && $_POST['password'] == $_POST['repassword']) {
	$data['salt']     = substr(md5(uniqid(rand(), true)), 0, 6);
	$data['password'] = sha1($data['salt'] . sha1($data['salt'] . sha1($_POST['password'])));
	$data['token']    = md5($data['password'] . $data['salt']);
  }
  $data["modified"] = date('Y-m-d H:i:s');
  $data["modified_by"] = $_SESSION["adminid"];
  $data["status"] = isset($_POST["status"]) ? (int) $_POST["status"] : 0;
  $affect = $dbf->updateTable(prefixTable . "customer", $data, "id='" . (int) $_GET['edit'] . "'");
  if($affect > 0) $msg="Đã cập nhật ($affect) dòng trong cơ sở dữ liệu.";
}

if($isEdit)
{
  $rst = $dbf->getDynamicJoin ( prefixTable . 'customer' , prefixTable . 'customer_address' , array( 'full_address' => 'full_address' ) , 'inner join' , 't1.id="' .(int) $_GET['edit']  . '"' , '' , 't2.csid = t1.id' );
  if($rst) {
	$row = $dbf->nextObject ( $rst );
	$id = $row->id;
	$salutation = $row->salutation;
	$name = stripslashes ( $row->name );	
	$email = $row->email;
	$phone = $row->phone;
	$mobile = $row->mobile;
	#$gender = $row->gender;
	$birthdate = $row->birthdate;
	#$point = $row->point;
	$full_address = stripslashes ( $row->full_address );
	$status = $row->status;
	$groupid = $row->groupid;	  
  }
}
?>
<body>
<?php $dbf->normalForm("frm",array("action"=>"","method"=>"post"));?>

<?php
	if($isEdit) {
?>
<!-- form -->
<div id="panelForm" class="panelForm">
	  <table id="mainTable" cellpadding="0" cellspacing="0">
          <?php echo $dbf->returnTitleMenu($titleMenu)?>
          <tr>
            <td colspan="4" class="txtdo" align="center"><?php echo $msg?></td>
          </tr> 
          <tr>
            <td class="boxGrey">Họ & tên</td>
            <td class="boxGrey2"><?php echo !empty ( $name ) ? (!empty($salutation) ? $arraySalutation[$salutation] . '. ' : '') . $name : '-- Chưa cập nhật --' ?> [<?php echo isset($groupid) && $groupid == 2 ? 'Thành viên' : 'Khách lẻ'?>]</td>
          </tr>
          <?php if ($groupid == 2) :?>
          <tr>
            <td class="boxGrey" colspan="2">Nếu không cập nhật mật khẩu vui lòng để trống.</td>            
          </tr>
          <tr>
            <td class="boxGrey">Mật khẩu</td>
            <td class="boxGrey2"><input type="password" id="password" name="password" class="nd1" /></td>
          </tr>
          <tr>
            <td class="boxGrey">Xác nhận mật khẩu</td>
            <td class="boxGrey2"><input type="password" id="repassword" name="repassword" class="nd1" /></td>
          </tr> 
          <?php endif ?>        
          <tr>
            <td class="boxGrey">Email</td>
            <td class="boxGrey2"><?php echo $email ?></td>
          </tr>
          <tr>
            <td class="boxGrey">Ngày sinh</td>
            <td class="boxGrey2"><?php echo !empty ($birthdate) ? $birthdate : '-- Chưa cập nhật --' ?></td>
          </tr>
          <!--<tr>
            <td class="boxGrey">Giới tính</td>
            <td class="boxGrey2"><?php #echo ( $gender <> 'NA' ) ? $arrayGender[$gender] : '-- Chưa cập nhật --' ?></td>
          </tr>-->
          <tr>
            <td class="boxGrey">Điện thoại</td>
            <td class="boxGrey2"><?php echo !empty ( $phone ) ? $phone : '-- Chưa cập nhật --' ?></td>
          </tr>
          <tr>
            <td class="boxGrey">Di động</td>
            <td class="boxGrey2"><?php echo !empty ( $mobile ) ? $mobile : '-- Chưa cập nhật --' ?></td>
          </tr>
          <!--<tr>
            <td class="boxGrey">Điểm tích lũy</td>
            <td class="boxGrey2"><?php #echo !empty ( $point ) ? $point : '-- Chưa cập nhật --' ?></td>
          </tr>-->
          <tr>
            <td class="boxGrey">Địa chỉ</td>
            <td class="boxGrey2"><?php echo !empty ( $full_address ) ? $full_address : '-- Chưa cập nhật --' ?></td>
          </tr>
          <tr>
            <td class="boxGrey"><?php echo $col["status"]?></td>
            <td width="379" class="boxGrey2"><input type="checkbox" name="status" id="status" value="1" <?php echo strtr($status,$statusChecked)?> /> Kích hoạt?</td>
          </tr>
          <tr>
            <td class="boxGrey"></td>
            <td height="1" align="center" class="boxGrey2">
              <div id="insert"><?php echo (($isInsert)?$dbf->stateInsert():(($isEdit)?$dbf->stateUpdate():""));?></div>
            </td>
          </tr>
          <tr>
            <td class="boxGrey" colspan="2"><span style="color:#DA251C">(*)</span> Bắt buộc nhập</td>    
          </tr>
          <?php echo $dbf->returnTitleMenu('Đơn hàng đã đặt')?>
          <tr>
            <td class="boxGrey" colspan="2">
            
<?php
	/*$col=array("id"=>"ID","order_code"=>"Mã đơn hàng","billing_name"=>"Người đặt hàng","shipping_name"=>"Người nhận hàng","cost"=>"Tổng tiền","ordered"=>"Ngày đặt","status"=>"Trạng thái");	
	$url="order.php?" . ((!empty($iscatURL)) ? '&caturl=' . $iscatURL : '');
	$mang=$dbf->pagingJoin(prefixTable."order",prefixTable."order_status",array("name"=>"status"),"inner join",'1=1',"t1.ordered desc",$url,$PageNo,$PageSize,$Pagenumber,$ModePaging,"t1.status=t2.id");
	echo $dbf->panelAction($mang[1]);
	echo '<div id="panelView" class="panelView">';
	$dbf->normalView($col, "order.php", $mang, $statusAction, "&caturl=" . $iscatURL, $msg="");
	echo '</div>';*/
	
	$rst = $dbf->getDynamicJoin(prefixTable . 'order', prefixTable . 'order_status', array('name' => 'status'), 'INNER JOIN', 't1.csid = ' . (int) $_GET['edit'], 't1.ordered DESC', 't2.id = t1.status');
	if($dbf->totalRows($rst) > 0)
	{
		echo 
		'<table id="mainTable" cellpadding="0" cellspacing="0">
  <tbody>
    <tr>
      <td class="titleBottom" style="width:7%">STT</td>
      <td class="titleBottom">Mã đơn hàng</td>
      <td class="titleBottom">Người đặt hàng</td>
      <td class="titleBottom">Người nhận hàng</td>
      <td class="titleBottom">Tổng tiền</td>
      <td class="titleBottom">Ngày đặt</td>
	  <td class="titleBottom">Trạng thái</td>
    </tr>';
		$n = 1;
		while($row = $dbf->nextObject($rst))
		{
			echo 
			'<tr style="cursor:pointer">
      <td class="cell2">' . $n . '</td>
      <td class="cell2" onDblClick="redirect(\'order.php?edit=' . $row->id . '\');"><div class="inlinecell" style="cursor:pointer" onMouseOver="this.style.backgroundColor=\'\';" onMouseOut="this.style.backgroundColor=\'\';"><a id="itemText" href="order.php?edit=1&amp;&amp;caturl=0">' . $row->order_code . '</a></div></td>
      <td class="cell2" onDblClick="redirect(\'order.php?edit=' . $row->id . '\');"><div class="inlinecell" style="cursor:pointer" onMouseOver="this.style.backgroundColor=\'\';" onMouseOut="this.style.backgroundColor=\'\';"><a id="itemText" href="order.php?edit=1&amp;&amp;caturl=0">' . $row->billing_name . '</a></div></td>
      <td class="cell2" onDblClick="redirect(\'order.php?edit=' . $row->id . '\');"><div class="inlinecell" style="cursor:pointer" onMouseOver="this.style.backgroundColor=\'\';" onMouseOut="this.style.backgroundColor=\'\';"><a id="itemText" href="order.php?edit=1&amp;&amp;caturl=0">' . $row->shipping_name . '</a></div></td>
      <td class="cell2" onDblClick="redirect(\'order.php?edit=' . $row->id . '\');"><div class="inlinecell" style="cursor:pointer" onMouseOver="this.style.backgroundColor=\'\';" onMouseOut="this.style.backgroundColor=\'\';"><a id="itemText" href="order.php?edit=1&amp;&amp;caturl=0">' . number_format($row->cost,0,",",".") . ' VND</a></div></td>
      <td class="cell2" onDblClick="redirect(\'order.php?edit=' . $row->id . '\');"><div class="inlinecell" style="cursor:pointer" onMouseOver="this.style.backgroundColor=\'\';" onMouseOut="this.style.backgroundColor=\'\';"><a id="itemText" href="order.php?edit=1&amp;&amp;caturl=0">' . date('d/m/Y', $row->ordered) . '</a></div></td>
      <td class="cell2" onDblClick="redirect(\'order.php?edit=' . $row->id . '\');"><div class="inlinecell" style="cursor:pointer" onMouseOver="this.style.backgroundColor=\'\';" onMouseOut="this.style.backgroundColor=\'\';"><a id="itemText" href="order.php?edit=1&amp;&amp;caturl=0">' . $row->status . '</a></div></td>
    </tr>';
			$n++;
		}
		echo 
		'</tbody>
</table>';
	}
?>
            
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
	if(!$isEdit && !$isInsert) {
	echo $dbf->returnTitleMenuTable($titleMenu);
	$url = "customer.php";
    $mang=$dbf->paging(prefixTable . "customer",'1=1',"added desc",$url,$PageNo,$PageSize,$Pagenumber,$ModePaging)	
?>

<!-- view -->
<div id="panelView" class="panelView">
<?php $dbf->normalView($col, "customer.php", $mang, $statusAction, '', $msg="")?>
</div>
<!-- end view-->
<?php
}
?>
	<input type="hidden" name="arrayid" id="arrayid" />
  </form>
</body>
</html><?php ob_end_flush()?>