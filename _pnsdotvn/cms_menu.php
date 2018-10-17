<?php 
define('MOD_DIR_UPLOAD', '/media/images/news/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/news/');
require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . "/plugins/upload/class.upload.php");
$utls = new Utilities();
$col = array('id' => 'ID', 'name' => 'Tên danh mục', 'parent_name' => 'Danh mục cha', 'display' => 'Dạng hiển thị', 'created' => 'Ngày tạo', 'modified' => 'Ngày sửa', 'status' => 'Trạng thái');
$iscatURL = isset($_GET['caturl']) && !empty($_GET['caturl']) ? $_GET['caturl'] : 0;
$titleMenu = 'QUẢN LÝ DANH MỤC BÀI VIẾT';

if($isDelete)
{
	$arrayid = substr($_POST['arrayid'], 0, -1);
	$id_array = explode(',',$arrayid);  
	if($dbf->chkMenuChild($id_array) === false) 
	{
	  $msg = "Không thể thực hiện! Bạn phải xóa các danh mục con & bài viết trước khi xóa danh mục này.";
	}
	else
	{ 
		$affect  = $dbf->deleteDynamic(prefixTable. "menu", "id in (" . $arrayid . ")");
		if($affect > 0) 
		{
			$dbf->deleteDynamic(prefixTable. "menu_desc", "id in (" . $arrayid . ")");
			$dbf->deleteDynamic(prefixTable. "menu_ordering", "menuid in (" . $arrayid . ")");
		  	$dbf->deleteDynamic(prefixTable. "url_alias", "route = 'cms' and type = 'all' and related_id in (" . $arrayid . ")");  
		  	$msg = "Đã xóa (" . count($id_array) . ") dòng trong cơ sở dữ liệu.";
		}	
	}
}

if($subInsert)
{
  	$err = 0;	
	if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['parentid']) && $_POST['parentid'] >= 0 && in_array($_POST['display'], array_keys($arrayDisplay)))
  	{	
		$data['name']        	= addslashes($utls->checkValues($_POST['name']));
		###$data['alias']       	= strtolower($utls->generate_url_from_text($data['name']));
		$data['parentid']    	= isset($_POST['parentid']) && $_POST['parentid'] >= 0 ? (int) $_POST['parentid'] : 0;
		#$data['picture']     	= $utls->checkValues($_POST['pictureText']);
		$data['display'] 	   	= in_array($_POST['display'], array_keys($arrayDisplay)) ? $_POST['display'] : 'LIST';
		#$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] > 0 ? (int) $_POST['ordering'] : 1;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
		###$data['metatitle'] 	   	= !empty($_POST['metatitle']) ? addslashes($utls->checkValues($_POST['metatitle'])) : $data['name'];
		###$data['metakey'] 	   	= !empty($_POST['metakey']) ? addslashes($utls->checkValues($_POST['metakey'])) : $data['name'];
		###$data['metadesc'] 	   	= !empty($_POST['metadesc']) ? addslashes($utls->checkValues($_POST['metadesc'])) : $data['name'];
		$data['created']     	= date('Y-m-d H:i:s');
		$data['created_by']  	= 1;
		$data['modified']    	= $data['created'];
		$data['modified_by'] 	= $data['created_by'];	
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		$value['vi-VN']['html'] 		= !empty($_POST['html_vi']) ? addslashes(trim($_POST['html_vi'])) : '';
		$value['vi-VN']['metatitle']	= !empty($_POST['metatitle_vi']) ? addslashes($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metakey']		= !empty($_POST['metakey_vi']) ? addslashes($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metadesc']		= !empty($_POST['metadesc_vi']) ? addslashes($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];
			
		$affect = $dbf->insertTable(prefixTable . 'menu', $data);
		if($affect > 0)
		{			
			$_id 	= $affect;
			
			if(isset($_POST['mainmenu']) && $_POST['mainmenu'] == 1)
			{
				$ordering1 = isset($_POST['ordering1']) && $_POST['ordering1'] > 0 ? (int) $_POST['ordering1'] : 1;
				$dbf->insertTable(prefixTable . 'menu_ordering', array('menuid' => $_id, 'typeid' => 1, 'ordering' => $ordering1));
			}
			
			/*if(isset($_POST['footermenu']) && $_POST['footermenu'] == 2)
			{
				$ordering2 = isset($_POST['ordering2']) && $_POST['ordering2'] > 0 ? (int) $_POST['ordering2'] : 1;
				$dbf->insertTable(prefixTable . 'menu_ordering', array('menuid' => $_id, 'typeid' => 2, 'ordering' => $ordering2));
			}*/
			
			/*if(isset($_POST['leftmenu']) && $_POST['leftmenu'] == 6)
			{
				$ordering2 = isset($_POST['ordering6']) && $_POST['ordering6'] > 0 ? (int) $_POST['ordering6'] : 1;
				$dbf->insertTable(prefixTable . 'menu_ordering', array('menuid' => $_id, 'typeid' => 6, 'ordering' => $ordering6));
			}*/
			
			/*if(isset($_POST['productdetail']) && $_POST['productdetail'] == 7)
			{
				$ordering2 = isset($_POST['ordering7']) && $_POST['ordering7'] > 0 ? (int) $_POST['ordering7'] : 1;
				$dbf->insertTable(prefixTable . 'menu_ordering', array('menuid' => $_id, 'typeid' => 7, 'ordering' => $ordering7));
			}*/
			
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$value[$lang]['id'] = $_id;
				$value[$lang]['lang'] = $lang;
				$dbf->insertTable(prefixTable . 'menu_desc', $value[$lang]);
				
				$_data['url_alias'] 	= substr($lang, 0, -3) . '/' . $value[$lang]['rewrite'] . EXT;
				$_data['route'] 		= 'cms';
				$_data['type'] 			= 'all';
				$_data['related_id'] 	= $_id;
				$_data['lang']			= $lang;
				$dbf->insertTable(prefixTable . 'url_alias', $_data);												
			}
			
			$msg = 'Đã thêm dòng (' . $_id . ') trong cơ sở dữ liệu.';
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
	if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['parentid']) && $_POST['parentid'] >= 0 && in_array($_POST['display'], array_keys($arrayDisplay)))
  	{	
		$_id					= (int) $_GET['edit'];
		
		$_url_alias = '';
		$_menu = $dbf->getArray(prefixTable . 'menu', 'status = 1 and id = ' . $_id, '', 'stdObject');
		if(!empty($_menu) && count($_menu) > 0)
		{
			$_related_id = $_menu[0]->id;
			$_route = 'cms';
			$_type = 'all';			
			$_url = $dbf->getArray(prefixTable . 'url_alias', 'route = "' . $_route . '" and type = "' . $_type . '" and related_id = ' . $_related_id . ' AND lang = "vi-VN"' , '', 'stdObject');
			if(!empty($_url) && count($_url) == 1) $_url_alias = $_url[0]->url_alias;
		}
		
		$data['name']        	= addslashes($utls->checkValues($_POST['name']));
		###$data['alias']       	= strtolower($utls->generate_url_from_text($data['name']));
		$data['parentid']    	= isset($_POST['parentid']) && $_POST['parentid'] >= 0 ? (int) $_POST['parentid'] : 0;
		#$data['picture']     	= $utls->checkValues($_POST['pictureText']);
		$data['display'] 	   	= in_array($_POST['display'], array_keys($arrayDisplay)) ? $_POST['display'] : 'LIST';
		#$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] > 0 ? (int) $_POST['ordering'] : 1;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
		###$data['metatitle'] 	   	= !empty($_POST['metatitle']) ? addslashes($utls->checkValues($_POST['metatitle'])) : $data['name'];
		###$data['metakey'] 	   	= !empty($_POST['metakey']) ? addslashes($utls->checkValues($_POST['metakey'])) : $data['name'];
		###$data['metadesc'] 	   	= !empty($_POST['metadesc']) ? addslashes($utls->checkValues($_POST['metadesc'])) : $data['name'];
		$data['modified']    	= date('Y-m-d H:i:s');
		$data['modified_by'] 	= 1;
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));				
		$value['vi-VN']['html'] 		= !empty($_POST['html_vi']) ? addslashes(trim($_POST['html_vi'])) : '';
		$value['vi-VN']['metatitle']	= !empty($_POST['metatitle_vi']) ? addslashes($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metakey']		= !empty($_POST['metakey_vi']) ? addslashes($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metadesc']		= !empty($_POST['metadesc_vi']) ? addslashes($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];
		
		$affect = $dbf->updateTable(prefixTable . 'menu', $data, 'display in ("FIRST", "LIST", "NEWS") and id = ' . $_id);
		if($affect > 0)
		{			
			$chk = $dbf->getArray(prefixTable . 'menu_ordering', 'menuid = ' . $_id . ' and typeid = 1', '');
			if(!empty($chk) && count($chk) == 1)
			{
				if(isset($_POST['mainmenu']) && $_POST['mainmenu'] == 1)
				{
					$ordering1 = isset($_POST['ordering1']) && $_POST['ordering1'] > 0 ? (int) $_POST['ordering1'] : 1;
					$dbf->updateTable(prefixTable . 'menu_ordering', array('ordering' => $ordering1), 'menuid = ' . $_id . ' and typeid = 1');
				} else
				{
					$dbf->deleteDynamic(prefixTable . 'menu_ordering', 'menuid = ' . $_id . ' and typeid = 1');
				}
			} else
			{
				if(isset($_POST['mainmenu']) && $_POST['mainmenu'] == 1)
				{
					$ordering1 = isset($_POST['ordering1']) && $_POST['ordering1'] > 0 ? (int) $_POST['ordering1'] : 1;
					$dbf->insertTable(prefixTable . 'menu_ordering', array('menuid' => $_id, 'typeid' => 1, 'ordering' => $ordering1));
				}
			}
			
			/*$chk2 = $dbf->getArray(prefixTable . 'menu_ordering', 'menuid = ' . $_id . ' and typeid = 2', '');
			if(!empty($chk2) && count($chk2) == 1)
			{
				if(isset($_POST['footermenu']) && $_POST['footermenu'] == 2)
				{
					$ordering2 = isset($_POST['ordering2']) && $_POST['ordering2'] > 0 ? (int) $_POST['ordering2'] : 1;
					$dbf->updateTable(prefixTable . 'menu_ordering', array('ordering' => $ordering2), 'menuid = ' . $_id . ' and typeid = 2');
				} else
				{
					$dbf->deleteDynamic(prefixTable . 'menu_ordering', 'menuid = ' . $_id . ' and typeid = 2');
				}
			} else
			{
				if(isset($_POST['footermenu']) && $_POST['footermenu'] == 2)
				{
					$ordering2 = isset($_POST['ordering2']) && $_POST['ordering2'] > 0 ? (int) $_POST['ordering2'] : 1;
					$dbf->insertTable(prefixTable . 'menu_ordering', array('menuid' => $_id, 'typeid' => 2, 'ordering' => $ordering2));
				}
			}*/
			
			/*$chk6 = $dbf->getArray(prefixTable . 'menu_ordering', 'menuid = ' . $_id . ' and typeid = 6', '');
			if(!empty($chk6) && count($chk6) == 1)
			{
				if(isset($_POST['leftmenu']) && $_POST['leftmenu'] == 6)
				{
					$ordering6 = isset($_POST['ordering6']) && $_POST['ordering6'] > 0 ? (int) $_POST['ordering6'] : 1;
					$dbf->updateTable(prefixTable . 'menu_ordering', array('ordering' => $ordering6), 'menuid = ' . $_id . ' and typeid = 6');
				} else
				{
					$dbf->deleteDynamic(prefixTable . 'menu_ordering', 'menuid = ' . $_id . ' and typeid = 6');
				}
			} else
			{
				if(isset($_POST['leftmenu']) && $_POST['leftmenu'] == 6)
				{
					$ordering6 = isset($_POST['ordering6']) && $_POST['ordering6'] > 0 ? (int) $_POST['ordering6'] : 1;
					$dbf->insertTable(prefixTable . 'menu_ordering', array('menuid' => $_id, 'typeid' => 6, 'ordering' => $ordering6));
				}
			}*/
			
			/*$chk7 = $dbf->getArray(prefixTable . 'menu_ordering', 'menuid = ' . $_id . ' and typeid = 7', '');
			if(!empty($chk7) && count($chk7) == 1)
			{
				if(isset($_POST['productdetail']) && $_POST['productdetail'] == 7)
				{
					$ordering7 = isset($_POST['ordering7']) && $_POST['ordering7'] > 0 ? (int) $_POST['ordering7'] : 1;
					$dbf->updateTable(prefixTable . 'menu_ordering', array('ordering' => $ordering7), 'menuid = ' . $_id . ' and typeid = 7');
				} else
				{
					$dbf->deleteDynamic(prefixTable . 'menu_ordering', 'menuid = ' . $_id . ' and typeid = 7');
				}
			} else
			{
				if(isset($_POST['productdetail']) && $_POST['productdetail'] == 7)
				{
					$ordering7 = isset($_POST['ordering7']) && $_POST['ordering7'] > 0 ? (int) $_POST['ordering7'] : 1;
					$dbf->insertTable(prefixTable . 'menu_ordering', array('menuid' => $_id, 'typeid' => 7, 'ordering' => $ordering7));
				}
			}*/							
			
			
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$dbf->updateTable(prefixTable . 'menu_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);				
				
				$_data['url_alias'] = substr($lang, 0, -3) . '/' . $value[$lang]['rewrite'] . EXT;
				$_data['route'] 	= 'cms';
				$_data['type'] 		= 'all';								  
		  		$dbf->updateTable(prefixTable . 'url_alias', $_data, 'url_alias = "' . $_url_alias . '" and related_id = ' . $_id . ' AND lang = "' . $lang . '"');
				
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
  	$rst = $dbf->getDynamic(prefixTable . 'menu', 'display in ("FIRST", "LIST", "NEWS", "CUSTOM") and id = ' . (int) $_GET['edit'], '');
  	if($dbf->totalRows($rst))
  	{
		$row 		= $dbf->nextObject($rst);
		$id 	 	= $row->id;
		$name 		= stripslashes($row->name);
		$parentid 	= $row->parentid;	
		#$mangpicture 	= $row->picture;
		$display 	= $row->display;		
		#$ordering 	= $row->ordering;
		$status 	= $row->status;
		#$metatitle	= stripslashes($row->metatitle);
		#$metakey 	= stripslashes($row->metakey);
		#$metadesc 	= stripslashes($row->metadesc);
		
		$vi = $dbf->getArray(prefixTable . 'menu_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
		#$en = $dbf->getArray(prefixTable . 'menu_desc', 'id = "' . $id . '" and lang = "en-US"', '', 'stdObject');
		
		$mainmenu 	= $dbf->getDynamicJoin(prefixTable . 'menu_ordering', prefixTable . 'menu', array(), 'inner join', 't2.id = ' . $id . ' and t1.typeid = 1', '', 't2.id = t1.menuid');
		if($t1 = $dbf->totalRows($mainmenu) == 1)
  		{
			$row1 = $dbf->nextObject($mainmenu);
			$ordering1 = $row1->ordering;			
		}
		
		/*$footermenu = $dbf->getDynamicJoin(prefixTable . 'menu_ordering', prefixTable . 'menu', array(), 'inner join', 't2.id = ' . $id . ' and t1.typeid = 2', '', 't2.id = t1.menuid');	
		if($t2 = $dbf->totalRows($footermenu) == 1)
  		{
			$row2 = $dbf->nextObject($footermenu);
			$ordering2 = $row2->ordering;			
		}*/	
		
		/*$leftmenu = $dbf->getDynamicJoin(prefixTable . 'menu_ordering', prefixTable . 'menu', array(), 'inner join', 't2.id = ' . $id . ' and t1.typeid = 6', '', 't2.id = t1.menuid');	
		if($t6 = $dbf->totalRows($leftmenu) == 1)
  		{
			$row6 = $dbf->nextObject($leftmenu);
			$ordering6 = $row6->ordering;			
		}*/
		
		/*$productdetail = $dbf->getDynamicJoin(prefixTable . 'menu_ordering', prefixTable . 'menu', array(), 'inner join', 't2.id = ' . $id . ' and t1.typeid = 7', '', 't2.id = t1.menuid');	
		if($t7 = $dbf->totalRows($productdetail) == 1)
  		{
			$row7 = $dbf->nextObject($productdetail);
			$ordering7 = $row7->ordering;			
		}*/
  	}
} ?>
<script type="text/javascript" src="../plugins/editors2/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../plugins/editors2/ckfinder/ckfinder.js"></script>
<script type="text/javascript" src="../themes/default/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" >
jQuery(document).ready(function() {  	
	jQuery('#frm').validate({
		rules: {		 
			name: {
				required: true 
			},
			display: {
				required: true 
			} 
	    },
	    messages: {   	
			name: {
				required: "Nhập tên danh mục." 
			},
			display: {
				required: "Chọn dạng hiển thị." 
			} 
		}		
	});	
});
function toggle(idName, obj) {
    var $input = $(obj);
    if ($input.prop('checked')) $(idName).show();
    else $(idName).hide();
}
</script>
<?php $dbf->normalForm('frm', array('action' => '', 'method' => 'post', 'class' => 'validate')) ?>
<?php if($isEdit || $isInsert) { ?>
<!-- form -->
<div id="panelForm" class="panelForm">
  <table id="mainTable" cellpadding="0" cellspacing="0">
    <?php echo $dbf->returnTitleMenu($titleMenu) ?>
    <tr><td colspan="4" class="txtdo" align="center"><?php echo $msg ?></td></tr>
    <?php if ($isEdit) : ?>
    <tr>
      <td class="boxGrey">Liên kết</td>
      <td class="boxGrey2"><input type="text" class="nd3" value="<?php echo (isset($vi[0]->rewrite) && !empty($vi[0]->rewrite) ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/' . $vi[0]->rewrite . '.html' : '') ?>" readonly="readonly" /></td>
    </tr>
    <?php endif ?>
    <tr>
      <td class="boxGrey">Danh mục cha</td>
      <td class="boxGrey2"><?php echo $dbf->generateRecursiveSelect3('parentid', 'name', 'id', (isset($parentid) && $parentid !== null) ? $parentid : $iscatURL, prefixTable . 'menu', 'display in ("FIRST", "LIST", "NEWS", "CUSTOM")')?></td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col["name"]?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><input name="name" id="name" type="text" class="nd1" value="<?php echo isset($name) && !empty($name) ? $name : '' ?>" /></td>
    </tr>
    <tr>
      <td class="boxGrey">Hiển thị - Dạng <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><?php echo $dbf->SelectWithNormalArray($arrayDisplay, (isset($display) && !empty($display)) ? $display : '', 'display', array('firstText' => 'Chọn dạng hiển thị', 'class' => 'nd1')) ?></td>
    </tr>
    <tr>
      <td class="boxGrey">Nội dung HTML</td>
      <td class="boxGrey2">
	    <textarea name="html_vi" id="html_vi" cols="75" rows="20"><?php echo (isset($vi[0]->html) && !empty($vi[0]->html)) ? stripslashes($vi[0]->html) : '' ?></textarea>
		<script type="text/javascript">
        var editor = CKEDITOR.replace( 'html_vi', {
            language : 'vi',
            toolbar : 'Default',
            height : '300px',
            width : '100%' ,
            filebrowserBrowseUrl : '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html',
            filebrowserImageBrowseUrl : '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Images',
            filebrowserFlashBrowseUrl : '<?php echo HOST ?>/plugins/editors2/ckfinder/ckfinder.html?type=Flash',
            /*filebrowserUploadUrl : '<?php #echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',*/
            filebrowserImageUploadUrl : '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
            filebrowserFlashUploadUrl : '<?php echo HOST ?>/plugins/editors2/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash'
        });
        CKFinder.setupCKEditor( editor, '../' );
        </script>
      </td>
    </tr>
    <tr>
      <td class="boxGrey">Hiển thị - Vị trí</td>
      <td class="boxGrey2">
        <input type="checkbox" name="mainmenu" id="mainmenu" value="1" <?php echo strtr((isset($mainmenu) && $dbf->totalRows($mainmenu) == 1 ? 1 : 0), $statusChecked) ?> onclick="toggle('#ordering1', this)" /> Menu chính <br />
        <span id="ordering1" style="display:<?php echo isset($t1) && $t1 == 1 ? 'block' : 'none' ?>">Thứ tự <input name="ordering1" id="ordering1" type="text" class="" value="<?php echo isset($ordering1) && !empty($ordering1) ? $ordering1 : 1 ?>" maxlength="5" onKeyPress="return nhapso(event,'ordering');" style="width:50px" /></span> <br />
		<!--<input type="checkbox" name="footermenu" id="footermenu" value="2" <?php #echo strtr((isset($footermenu) && $dbf->totalRows($footermenu) == 1 ? 1 : 0), $statusChecked) ?> onclick="toggle('#ordering2', this)" /> Menu chân trang<br />
        <span id="ordering2" style="display:<?php #echo isset($t2) && $t2 == 1 ? 'block' : 'none' ?>">Thứ tự <input name="ordering2" id="ordering2" type="text" class="" value="<?php #echo isset($ordering2) && !empty($ordering2) ? $ordering2 : 1 ?>" maxlength="5" onKeyPress="return nhapso(event,'ordering');" style="width:50px" /></span> <br />-->
        <!--<input type="checkbox" name="leftmenu" id="leftmenu" value="6" <?php #echo strtr((isset($leftmenu) && $dbf->totalRows($leftmenu) == 1 ? 1 : 0), $statusChecked) ?> onclick="toggle('#ordering6', this)" /> Menu trái (Trợ giúp)<br />
        <span id="ordering6" style="display:<?php #echo isset($t6) && $t6 == 1 ? 'block' : 'none' ?>">Thứ tự <input name="ordering6" id="ordering6" type="text" class="" value="<?php #echo isset($ordering6) && !empty($ordering6) ? $ordering6 : 1 ?>" maxlength="5" onKeyPress="return nhapso(event,'ordering');" style="width:50px" /></span> <br />-->
        <!--<input type="checkbox" name="productdetail" id="productdetail" value="7" <?php #echo strtr((isset($productdetail) && $dbf->totalRows($productdetail) == 1 ? 1 : 0), $statusChecked) ?> onclick="toggle('#ordering7', this)" /> Chi tiết sản phẩm <br />
        <span id="ordering7" style="display:<?php #echo isset($t7) && $t7 == 1 ? 'block' : 'none' ?>">Thứ tự <input name="ordering7" id="ordering7" type="text" class="" value="<?php #echo isset($ordering7) && !empty($ordering7) ? $ordering7 : 1 ?>" maxlength="7" onKeyPress="return nhapso(event,'ordering');" style="width:50px" /></span> <br />-->
	  </td>
    </tr>
    <!--<tr>
      <td class="boxGrey">Hình ảnh</td>
      <td class="boxGrey2"><?php #echo $dbf->generateChoicePicture('picture', 'pictureText', isset($mangpicture) && !empty($mangpicture) ? $mangpicture : $picture) ?></td>
    </tr>-->
    <!--<tr>
      <td class="boxGrey"><?php #echo $col['ordering'] ?></td>
      <td class="boxGrey2"><input name="ordering" id="ordering" type="text" class="nd1" value="<?php #echo isset($ordering) && !empty($ordering) ? $ordering : 1 ?>" maxlength="5" onKeyPress="return nhapso(event,'ordering');" /></td>
    </tr>-->
    <tr>
      <td class="boxGrey">Tiêu đề [Meta Title]</td>
      <td class="boxGrey2"><input name="metatitle_vi" id="metatitle_vi" type="text" class="nd3" value="<?php echo isset($vi[0]->metatitle) && !empty($vi[0]->metatitle) ? stripslashes($vi[0]->metatitle) : '' ?>" /></td>
    </tr>
    <tr>
      <td class="boxGrey">Từ khóa [Meta Keywords]</td>
      <td class="boxGrey2"><textarea name="metakey_vi" id="metakey_vi" type="text" class="nd3" /><?php echo isset($vi[0]->metakey) && !empty($vi[0]->metakey) ? stripslashes($vi[0]->metakey) : '' ?></textarea></td>
    </tr>
    <tr>
      <td class="boxGrey">Mô tả [Meta Description]</td>
      <td class="boxGrey2"><textarea name="metadesc_vi" id="metadesc_vi" type="text" class="nd3" /><?php echo isset($vi[0]->metadesc) && !empty($vi[0]->metadesc) ? stripslashes($vi[0]->metadesc) : '' ?></textarea></td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col['status'] ?></td>
      <td width="379" class="boxGrey2"><input type="checkbox" name="status" id="status" value="1" <?php echo strtr((isset($status) && $status == 1 ? $status : 0), $statusChecked) ?> /> Kích hoạt?</td>
    </tr>
    <tr>
      <td class="boxGrey"></td>
      <td height="1" align="center" class="boxGrey2"><div id="insert"><?php echo (($isInsert) ? $dbf->stateInsert() : (($isEdit) ? $dbf->stateUpdate() : '')) ?></div></td>
    </tr>
    <tr><td class="boxGrey" colspan="2"><span style="color:#DA251C">(*)</span> Bắt buộc nhập</td></tr>
  </table>
</div>
<?php if($isInsert && !empty($msg) && $err == 0) echo '<script type="text/javascript">huy();</script>' ?>
<!-- end Form-->
<?php } ?>
<?php if(!$isEdit && !$isInsert)
{
  echo $dbf->returnTitleMenuTable($titleMenu);
  $url = "cms_menu.php?" . (!empty($iscatURL) ? '&caturl=' . $iscatURL : '');
  if($iscatURL > 0)
  {
	$mang = $dbf->pagingJoin(prefixTable . 'menu', prefixTable . 'menu', array('name' => 'parent_name'), 'inner join', 't1.display in ("FIRST", "LIST", "NEWS", "CUSTOM") and t1.parentid = ' . $iscatURL, 't1.ordering, t1.id', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging, 't2.id = t1.parentid');	  
  } else
  {
	$mang = $dbf->paging(prefixTable . 'menu', 'display in ("FIRST", "LIST", "NEWS", "CUSTOM") and parentid = 0', 'ordering, id', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);	
  }      
  
  echo $dbf->panelAction($mang[1]);
  echo $dbf->selectFilterCMSMenu('cbokho', 'name', 'id', $iscatURL, 'Chọn loại mục:', prefixTable . 'menu', 'status = 1 and display in ("FIRST", "LIST", "NEWS", "CUSTOM")', 'id', array('firstText' => 'Chọn loại mục', 'onchange' => "redirect('" . $_SERVER['PHP_SELF'] . "?&caturl='+this.value);")) ?>
<!-- view -->
<div id="panelView" class="panelView"><?php $dbf->normalView($col, 'cms_menu.php', $mang, $statusAction, '&caturl=' . $iscatURL, $msg, $arrayDisplay)?></div>
<!-- end view-->
<?php } ?>
  <input type="hidden" name="arrayid" id="arrayid" />
</form>
</body>
</html><?php ob_end_flush() ?>