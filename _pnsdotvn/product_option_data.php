<?php 
require_once('index_table.php');
$col = array(
	'id' => 'ID', 
	'name' => 'Tên dữ liệu', 
	'oid' => 'Thuộc tính',
	'ordering' => 'Thứ tự',
	'status' => 'Trạng thái');
$iscatURL = isset($_GET['caturl']) && !empty($_GET['caturl']) ? (int) $_GET['caturl'] : '';
$titleMenu = 'QUẢN LÝ DỮ LIỆU THUỘC TÍNH SẢN PHẨM';

#error_reporting(E_ALL);

if($isDelete)
{
	$arrayid = substr($_POST['arrayid'], 0, -1);
	$id_array = explode(',',$arrayid);
	$affect = $dbf->deleteDynamic(prefixTable . 'product_option_data', 'id in (' . $arrayid . ')');
    if($affect > 0) 
	{
		$dbf->deleteDynamic(prefixTable . 'product_option_data_desc', 'id in (' . $arrayid . ')');
		$msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu.';
	}
}

if($subInsert)
{
	$err = 0;
	if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['oid']) && $_POST['oid'] > 0)
	{
		$data['name']        	= strip_tags(trim($_POST['name']));
		$data['oid']        	= strip_tags(trim($_POST['oid']));
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 1;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;	
		$data['created']     	= date('Y-m-d H:i:s');
		$data['created_by']  	= 1;
		$data['modified']    	= $data['created'];
		$data['modified_by'] 	= $data['created_by'];
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		#$value['vi-VN']['value'] 		= !empty($_POST['value_vi']) ? addslashes($utls->checkValues($_POST['value_vi'])) : '';	
																
		$affect = $dbf->insertTable(prefixTable . 'product_option_data', $data);		
		if($affect > 0)
		{
			$_id = $affect;
			
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$value[$lang]['id'] = $_id;
				$value[$lang]['lang'] = $lang;
				$dbf->insertTable(prefixTable . 'product_option_data_desc', $value[$lang]);				
			}			
			$msg = 'Đã thêm dòng (' . $affect . ') trong cơ sở dữ liệu.';
		}
		
	} else
	{
		$msg = 'Vui lòng nhập đầy đủ dữ liệu.';
		$err = 1;
	}
}

if($subUpdate)
{
	$err = 0;
	if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['oid']) && $_POST['oid'] > 0)
	{
		$_id = (int) $_GET['edit'];
		$data['name']        	= strip_tags(trim($_POST['name']));
		$data['oid']        	= strip_tags(trim($_POST['oid']));
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 1;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;	
		$data['modified']    	= date('Y-m-d H:i:s');
		$data['modified_by'] 	= 1;
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		#$value['vi-VN']['value'] 		= !empty($_POST['value_vi']) ? addslashes($utls->checkValues($_POST['value_vi'])) : '';
												
		$affect = $dbf->updateTable(prefixTable . 'product_option_data', $data, 'id = ' . $_id);
		if($affect > 0)
		{
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$dbf->updateTable(prefixTable . 'product_option_data_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);
			}			  			
			$msg = 'Đã cập nhật (' . $affect . ') dòng trong cơ sở dữ liệu.';
		}
	} else
	{
		$msg = 'Vui lòng nhập đầy đủ dữ liệu.';
		$err = 1;
	}
}

if($isEdit)
{
	$rst = $dbf->getDynamic(prefixTable . 'product_option_data', 'id = ' . (int) $_GET['edit'], '');
	if($rst)
	{
		$row 		= $dbf->nextData($rst);
		$id 		= $row['id'];
		$name 		= stripslashes($row['name']);
		$oid 		= $row['oid'];		
		$ordering 	= $row['ordering'];
		$status 	= $row['status'];
		
		$vi = $dbf->getArray(prefixTable . 'product_option_data_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
	}
} ?>
<script type="text/javascript" src="../themes/default/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" >
jQuery(document).ready(function() {  	
	jQuery('#frm').validate({
		rules: {			 
			name: { required: true },
			oid: { required: true }
	    },
	    messages: {	    	
			name: { required: "Nhập tên dữ liệu." },
			oid: { required: "Chọn thuộc tính." }
		}		
	});	
});
</script>
<?php $dbf->FormUpload('frm', array('action' => '', 'method' => 'post', 'class' => 'validate')) ?>
<?php if($isEdit || $isInsert) { ?>
<!-- form -->
<div id="panelForm" class="panelForm">
  <table id="mainTable" cellpadding="0" cellspacing="0">
    <?php echo $dbf->returnTitleMenu($titleMenu) ?>
    <tr>
      <td colspan="4" class="txtdo" align="center"><?php echo $msg ?></td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col['name'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><input name="name" id="name" type="text" class="nd1" value="<?php echo (isset($name) && !empty($name)) ? $name : '' ?>" /></td>
    </tr>
    <tr>
      <td class="boxGrey">Thuộc tính <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><?php echo $dbf->SelectWithTable(prefixTable . 'product_option', 'status = 1 AND type = "select"', 'ordering, name', 'oid', 'name', 'id', (isset($oid) && !empty($oid) ? $oid : ''), array('firstText' => 'Chọn thuộc tính', 'class' => 'cbo')) ?></td>
    </tr>    
    <tr>
      <td class="boxGrey"><?php echo $col['ordering'] ?></td>
      <td class="boxGrey2"><input type="text" name="ordering" id="ordering" class="nd1" value="<?php echo isset($ordering) && !empty($ordering) ? $ordering : 1 ?>" maxlength="5" onKeyPress="return nhapso(event,'position');" /></td>
    </tr>    
    <tr>
      <td class="boxGrey"><?php echo $col['status'] ?></td>
      <td width="379" class="boxGrey2"><input type="checkbox" name="status" id="status" value="1" <?php echo strtr((isset($status) && $status == 1 ? $status : 0), $statusChecked) ?> /> Kích hoạt?</td>
    </tr>
    <tr>
      <td class="boxGrey"></td>
      <td height="1" align="center" class="boxGrey2"><div id="insert"><?php echo (($isInsert) ? $dbf->stateInsert() : (($isEdit) ? $dbf->stateUpdate() : '')) ?></div></td>
    </tr>
    <tr>
      <td class="boxGrey" colspan="2"><span style="color:#DA251C">(*)</span> Bắt buộc nhập</td>
    </tr>
  </table>
</div>
<?php if($isInsert && !empty($msg) && $err == 0) echo '<script type="text/javascript">huy();</script>' ?>
<!-- end Form-->
<?php } ?>
<?php if(!$isEdit && !$isInsert) {
	echo $dbf->returnTitleMenuTable($titleMenu);
	$url = 'product_option_data.php?' . (!empty($iscatURL) ? '&caturl=' . $iscatURL : '');
	$mang = $dbf->pagingJoin(prefixTable . 'product_option_data', prefixTable . 'product_option', array('name' => 'oid'), 'inner join', 't2.type = "select"' . (!empty($iscatURL) ? ' AND oid = ' . (int) $iscatURL : ''), 't1.ordering, t1.id', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging, 't2.id = t1.oid');
	echo $dbf->panelAction($mang[1]);
	echo $dbf->selectFilter3('cbokho', 'name', 'id', $iscatURL, 'Chọn thuộc tính:', 'status = 1 and type = "select"', 'ordering, name', prefixTable . 'product_option',array('firstText' => 'Chọn thuộc tính', 'onchange' => "redirect('" . $_SERVER['PHP_SELF'] . "?&caturl='+this.value);"));
?>
<!-- view -->
<div id="panelView" class="panelView">
  <?php $dbf->normalView($col, "product_option_data.php", $mang, $statusAction, "&caturl=" . $iscatURL, $msg) ?>
</div>
<!-- end view-->
<?php } ?>
<input type="hidden" name="arrayid" id="arrayid" />
</form>
</body></html><?php ob_end_flush() ?>