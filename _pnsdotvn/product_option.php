<?php 
require_once('index_table.php');
$col = array(
	'id' => 'ID', 
	'name' => 'Tên thuộc tính', 
	'ordering' => 'Thứ tự',
	'status' => 'Trạng thái');
$iscatURL = '';
$titleMenu = 'QUẢN LÝ THUỘC TÍNH SẢN PHẨM';
$arrayTypeData = array(
	'text' => 'Dạng TEXT', 
	'select' => 'Dạng SELECT'
);

#error_reporting(E_ALL);

if($isDelete)
{
	$arrayid = substr($_POST['arrayid'], 0, -1);
	$id_array = explode(',',$arrayid);
	$affect = $dbf->deleteDynamic(prefixTable . 'product_option', 'id in (' . $arrayid . ')');
    if($affect > 0) 
	{
		$dbf->deleteDynamic(prefixTable . 'product_option_desc', 'id in (' . $arrayid . ')');
		$msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu.';
	}
}

if($subInsert)
{
	$err = 0;
	if(!empty($_POST['name']) && in_array($_POST['type'], array('text', 'select')))
	{
		$data['code']        	= strip_tags(trim($_POST['code']));
		$data['name']        	= strip_tags(trim($_POST['name']));
		$data['type']        	= strip_tags(trim($_POST['type']));
		$data['filter'] 	   	= isset($_POST['filter']) && $_POST['filter'] == 1 ? 1 : 0;
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 1;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;	
		$data['created']     	= date('Y-m-d H:i:s');
		$data['created_by']  	= 1;
		$data['modified']    	= $data['created'];
		$data['modified_by'] 	= $data['created_by'];
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		#$value['vi-VN']['value'] 		= !empty($_POST['value_vi']) ? addslashes($utls->checkValues($_POST['value_vi'])) : '';	
																
		$affect = $dbf->insertTable(prefixTable . 'product_option', $data);		
		if($affect > 0)
		{
			$_id = $affect;
			
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$value[$lang]['id'] = $_id;
				$value[$lang]['lang'] = $lang;
				$dbf->insertTable(prefixTable . 'product_option_desc', $value[$lang]);				
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
	if(!empty($_POST['name']) && in_array($_POST['type'], array('text', 'select')))
	{
		$_id = (int) $_GET['edit'];
		$data['code']        	= strip_tags(trim($_POST['code']));
		$data['name']        	= strip_tags(trim($_POST['name']));
		$data['type']        	= strip_tags(trim($_POST['type']));
		$data['filter'] 	   	= isset($_POST['filter']) && $_POST['filter'] == 1 ? 1 : 0;
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 1;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;	
		$data['modified']    	= date('Y-m-d H:i:s');
		$data['modified_by'] 	= 1;
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		#$value['vi-VN']['value'] 		= !empty($_POST['value_vi']) ? addslashes($utls->checkValues($_POST['value_vi'])) : '';
												
		$affect = $dbf->updateTable(prefixTable . 'product_option', $data, 'id = ' . $_id);
		if($affect > 0)
		{
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$dbf->updateTable(prefixTable . 'product_option_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);
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
	$rst = $dbf->getDynamic(prefixTable . 'product_option', 'id = ' . (int) $_GET['edit'], '');
	if($rst)
	{
		$row 		= $dbf->nextData($rst);
		$id 		= $row['id'];
		$code 		= stripslashes($row['code']);
		$name 		= stripslashes($row['name']);
		$type 		= $row['type'];	
		$filter 		= $row['filter'];		
		$ordering 	= $row['ordering'];
		$status 	= $row['status'];
		
		$vi = $dbf->getArray(prefixTable . 'product_option_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
	}
} ?>
<script type="text/javascript" src="../themes/default/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" >
jQuery(document).ready(function() {  	
	jQuery('#frm').validate({
		rules: {			
			code: { required: true }, 
			name: { required: true },
			type: { required: true }
	    },
	    messages: {	    	
			code: { required: "Nhập code (tên duy nhất, không trùng)." },
			name: { required: "Nhập tên thuộc tính." },
			type: { required: "Chọn kiểu dữ liệu." }
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
      <td class="boxGrey">Code <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><input name="code" id="code" type="text" class="nd1" value="<?php echo (isset($code) && !empty($code)) ? $code : '' ?>" /></td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col['name'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><input name="name" id="name" type="text" class="nd1" value="<?php echo (isset($name) && !empty($name)) ? $name : '' ?>" /></td>
    </tr>
    <tr>
      <td class="boxGrey">Kiểu dữ liệu <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><?php echo $dbf->SelectWithNormalArray($arrayTypeData, (isset($type) && !empty($type)) ? $type : '', 'type', array('firstText' => 'Chọn kiểu dữ liệu', 'class' => 'cbo')) ?></td>
    </tr>
    <!--<tr>
      <td class="boxGrey">Giá trị mặc định</td>
      <td class="boxGrey2"><input name="value_vi" id="value_vi" type="text" class="nd1" value="<?php #echo isset($vi[0]->value) && !empty($vi[0]->value) ? stripslashes($vi[0]->value) : '' ?>" /></td>
    </tr>-->    
    <tr>
      <td class="boxGrey"><?php echo $col['ordering'] ?></td>
      <td class="boxGrey2"><input type="text" name="ordering" id="ordering" class="nd1" value="<?php echo isset($ordering) && !empty($ordering) ? $ordering : 1 ?>" maxlength="5" onKeyPress="return nhapso(event,'position');" /></td>
    </tr>
    <tr>
      <td class="boxGrey">Tùy chọn</td>
      <td class="boxGrey2"><input type="checkbox" name="filter" id="filter" value="1" <?php echo strtr((isset($filter) && $filter == 1 ? $filter : 0), $statusChecked) ?> /> Hiển thị bộ lọc?</td>
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
	$url = 'product_option.php?' . (!empty($iscatURL) ? '&caturl=' . $iscatURL : '') ;
	$mang = $dbf->paging(prefixTable . 'product_option', $condition, 'id desc', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
	echo $dbf->panelAction($mang[1]) 
?>
<!-- view -->
<div id="panelView" class="panelView">
  <?php $dbf->normalView($col, "product_option.php", $mang, $statusAction, "&caturl=" . $iscatURL, $msg) ?>
</div>
<!-- end view-->
<?php } ?>
<input type="hidden" name="arrayid" id="arrayid" />
</form>
</body></html><?php ob_end_flush() ?>