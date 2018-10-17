<?php 
define('MOD_DIR_UPLOAD', '/media/images/others/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/others/');

#include('../plugins/editors/ckeditor/ckeditor.php');
#$ckeditor = new plgEditorCkeditor();

require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . "/plugins/upload/class.upload.php");

$utls = new Utilities();
$col=array('id' => 'ID', 'name' => 'Tên cung cấp', 'picture' => 'Hình', 'ordering' => 'Thứ tự', 'created' => 'Ngày tạo', 'modified' => 'Ngày sửa', 'status' => 'Trạng thái');
$iscatURL = isset($_GET['caturl']) && !empty($_GET['caturl']) ? (int) $_GET['caturl'] : 0;
$titleMenu = 'QUẢN LÝ DANH MỤC NHÀ CUNG CẤP';
#$brand_rewrite = array('vi-VN' => 'thuong-hieu', 'en-US' => 'brand');
$supplier_rewrite = array('vi-VN' => 'nha-cung-cap');

if($isDelete)
{
  	$arrayid = substr($_POST['arrayid'], 0, -1);
  	$id_array = explode(',', $arrayid);  
	$affect  = $dbf->deleteDynamic(prefixTable . 'supplier', 'id in (' . $arrayid . ')');
	if($affect > 0)
	{
		$dbf->deleteDynamic(prefixTable . 'supplier_desc', 'id in (' . $arrayid . ')');
		$dbf->deleteDynamic(prefixTable. 'url_alias', 'route = "supplier" and type = "detail" and related_id in (' . $arrayid . ')');  
		$msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu.';
	}
}

if($subInsert)
{
	$err = 0;
	if(isset($_POST['name']) && !empty($_POST['name']))
	{
		$data['name'] 	   		= strip_tags(trim($_POST['name']));
		#$data['picture'] 	   	= $_POST['pictureText'];
		#$data['top'] 	   		= isset($_POST['top']) && $_POST['top'] == 1 ? 1 : 0;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 1;		
		$data['created'] 	   	= date('Y-m-d H:i:s');
		$data['created_by']  	= 1;
		$data['modified']    	= $data['created'];
		$data['modified_by'] 	= $data['created_by'];
		
		
		// BEGIN: UPLOAD PICTURE								
		if(isset($_FILES['picture']['name']) && is_array($_FILES['picture']['name']) && count($_FILES['picture']['name']) > 0)	
		{
			$dir = $dbf->pnsdotvn_get_dir_upload(3);
			
			$arr_pic = array ();
			foreach ($_FILES['picture'] as $k => $l) 
			{
				foreach ($l as $i => $v) 
				{
					if (!array_key_exists($i, $arr_pic)) 
						$arr_pic[$i] = array();				
					$arr_pic[$i][$k] = $v;
				}
			}
			
			for($i = 0; $i < count($_FILES['picture']['name']); $i++)
			{
				if(!empty($_FILES['picture']['name'][$i]))
				{
					$pic['type']				= 'image';
					$pic['path'] 				= MOD_ROOT_URL;
					$pic['dir']					= $dir;
					$pic['file']				= $arr_pic[$i];		
					$pic['change_name'] 		= true;	
					$pic['w']					= 1200;
					$pic['thum']				= true;			
					$pic['change_name_thum'] 	= false;
					$pic['w_thum']				= 120;			
					$upload = new Upload($pic['file']);
					$result = $dbf->pnsdotvn_upload_file($pic, $upload, $utls);								
					if(empty($result['err'])) 
					{
						$_picture[$i] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
						#$data['picture'] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
						#$data['filetype'] = $result['type'];
						#$data['filesize'] = $result['size'];
						/*if (in_array($result['type'], array('jpg','jpeg','png')))
						{
							$utls->watermark(str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 str_replace("\\", "/", dirname(dirname(__FILE__))) . "/themes/default/images/watermark.png",
											 300,
											 $result['type']);
						}*/
					}
				}
			}
			
			#echo '<pre>';
			#print_r($_picture);
			#echo '</pre>';
			#exit;
					
		}		
		$data['picture'] = isset($_picture) && !empty($_picture) && is_array($_picture) && count($_picture) > 0 ? implode(';', $_picture) : '';
		
		#print_r($data['picture']);
		#exit;
		// END: UPLOAD PICTURE
		
		
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		$value['vi-VN']['description']	= !empty($_POST['description_vi']) ? (trim($_POST['description_vi'])) : '';
		$value['vi-VN']['metatitle']	= !empty($_POST['metatitle_vi']) ? ($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metakey']		= !empty($_POST['metakey_vi']) ? ($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metadesc']		= !empty($_POST['metadesc_vi']) ? ($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];
		
		#$value['en-US']['name'] 		= !empty($_POST['name_en']) ? strip_tags(trim($_POST['name_en'])) : $data['name'];
		#$value['en-US']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['en-US']['name']));
		#$value['en-US']['description'] 	= !empty($_POST['description_en']) ? (trim($_POST['description_en'])) : '';
		#$value['en-US']['metatitle']	= !empty($_POST['metatitle_en']) ? ($utls->checkValues($_POST['metatitle_en'])) : $value['en-US']['name'];
		#$value['en-US']['metakey']		= !empty($_POST['metakey_en']) ? ($utls->checkValues($_POST['metakey_en'])) : $value['en-US']['name'];
		#$value['en-US']['metadesc']		= !empty($_POST['metadesc_en']) ? ($utls->checkValues($_POST['metadesc_en'])) : $value['en-US']['name'];
		
		$affect = $dbf->insertTable(prefixTable . 'supplier', $data);
		if($affect > 0)
		{			
			$_id = $affect;			
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$value[$lang]['id'] = $_id;
				$value[$lang]['lang'] = $lang;
				$dbf->insertTable(prefixTable . 'supplier_desc', $value[$lang]);
				
				#$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $supplier_rewrite[$lang] . '/' . $_id . '-' . $value[$lang]['rewrite']) . EXT;	
				$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $supplier_rewrite[$lang] . '/' . $value[$lang]['rewrite'] . '-' . $_id) . EXT;				
				$dbf->insertTable(prefixTable . 'url_alias', array('url_alias' => $_url_alias, 'route' => 'supplier', 'type' => 'detail', 'related_id' => $affect, 'lang' => $lang));
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
	if(isset($_GET['edit']) && !empty($_GET['edit']) && isset($_POST['name']) && !empty($_POST['name']))
	{
		$_id					= (int) $_GET['edit'];
		$data['name'] 	   		= strip_tags(trim($_POST['name']));
		#$data['picture']		= $_POST['pictureText'];
		#$data['top'] 	   		= isset($_POST['top']) && $_POST['top'] == 1 ? 1 : 0;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 1;		
		$data['modified']    	= date('Y-m-d H:i:s');
		$data['modified_by'] 	= 1;
		
		
		// BEGIN: UPLOAD PICTURE
		if(isset($_FILES['picture']['name']) && is_array($_FILES['picture']['name']) && count($_FILES['picture']['name']) > 0)	
		{
			/*echo '<pre>';
			print_r($_FILES['picture']['name']);
			echo '</pre>';			
			exit;*/
			
			/*echo '<pre>';
			print_r($_POST['picture_name']);
			echo '</pre>';*/
			
			$dir = $dbf->pnsdotvn_get_dir_upload(3);
			
			$arr_pic = array ();
			foreach ($_FILES['picture'] as $k => $l) 
			{
				foreach ($l as $i => $v) 
				{
					if (!array_key_exists($i, $arr_pic)) 
						$arr_pic[$i] = array();				
					$arr_pic[$i][$k] = $v;
				}
			}
			
			for($i = 0; $i < count($_FILES['picture']['name']); $i++)
			{
				if(!empty($_FILES['picture']['name'][$i]))
				{
					$pic['type']				= 'image';
					$pic['path'] 				= MOD_ROOT_URL;
					$pic['dir']					= $dir;
					$pic['file']				= $arr_pic[$i];		
					$pic['change_name'] 		= true;	
					$pic['w']					= 1200;
					$pic['thum']				= true;			
					$pic['change_name_thum'] 	= false;
					$pic['w_thum']				= 120;			
					$upload = new Upload($pic['file']);
					$result = $dbf->pnsdotvn_upload_file($pic, $upload, $utls);								
					if(empty($result['err'])) 
					{
						$_picture[$i] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
						#$data['picture'] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
						#$data['filetype'] = $result['type'];
						#$data['filesize'] = $result['size'];
						/*if (in_array($result['type'], array('jpg','jpeg','png')))
						{
							$utls->watermark(str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 str_replace("\\", "/", dirname(dirname(__FILE__))) . "/themes/default/images/watermark.png",
											 300,
											 $result['type']);
						}*/						
					}
				}
			}
			
			/*echo '<pre>';
			print_r($_picture);
			echo '</pre>';*/
			#exit;
			
		}
		
		if (isset($_POST['picture_name']) && !empty($_POST['picture_name']) && is_array($_POST['picture_name']) && count($_POST['picture_name']) > 0)
		{
			foreach ($_POST['picture_name'] as $_k => $_v)
				if (!empty($_POST['picture_name'][$_k]))
					$_picture_name[] = $_v;
		}
		$data['picture'] = isset($_picture_name) && is_array($_picture_name) && count($_picture_name) > 0 ? implode(';', $_picture_name) : '';
		
		if (isset($_picture) && !empty($_picture) && is_array($_picture) && count($_picture) > 0)
		{
			foreach ($_picture as $k => $v)
			{
				if (isset($_picture[$k]))
					$_picture_name[$k] = $v;
			}
			$data['picture'] = implode(';', $_picture_name);
			
			/*echo '<pre>';
			print_r($_picture_name);
			echo '</pre>';*/
		}
		
		#print_r($data['picture']);
		#exit;
		// END: UPLOAD PICTURE
		
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		$value['vi-VN']['description']	= !empty($_POST['description_vi']) ? (trim($_POST['description_vi'])) : '';
		$value['vi-VN']['metatitle']	= !empty($_POST['metatitle_vi']) ? ($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metakey']		= !empty($_POST['metakey_vi']) ? ($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metadesc']		= !empty($_POST['metadesc_vi']) ? ($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];
		
		#$value['en-US']['name'] 		= !empty($_POST['name_en']) ? strip_tags(trim($_POST['name_en'])) : $data['name'];
		#$value['en-US']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['en-US']['name']));
		#$value['en-US']['description'] 	= !empty($_POST['description_en']) ? (trim($_POST['description_en'])) : '';
		#$value['en-US']['metatitle']	= !empty($_POST['metatitle_en']) ? ($utls->checkValues($_POST['metatitle_en'])) : $value['en-US']['name'];
		#$value['en-US']['metakey']		= !empty($_POST['metakey_en']) ? ($utls->checkValues($_POST['metakey_en'])) : $value['en-US']['name'];
		#$value['en-US']['metadesc']		= !empty($_POST['metadesc_en']) ? ($utls->checkValues($_POST['metadesc_en'])) : $value['en-US']['name'];
		
		$affect = $dbf->updateTable(prefixTable . 'supplier', $data, 'id = ' . $_id);
		if($affect > 0)
		{			
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$dbf->updateTable(prefixTable . 'supplier_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);
				
				#$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $supplier_rewrite[$lang] . '/' . $_id . '-' . $value[$lang]['rewrite']) . EXT;
				$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $supplier_rewrite[$lang] . '/' . $value[$lang]['rewrite'] . '-' . $_id) . EXT;
				$dbf->updateTable(prefixTable . 'url_alias', array('url_alias' => $_url_alias), 'route = "supplier" and type = "detail" and lang = "' . $lang . '" and related_id = ' . $_id);
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
  	$rst = $dbf->getDynamic(prefixTable . 'supplier', 'id = ' . (int) $_GET['edit'], '');
  	if($rst)
  	{
		$row 		 	= $dbf->nextData($rst);
		$id 	 	 	= $row['id'];
		$name 		 	= stripslashes($row['name']);
		$mangpicture 	= explode(';', $row['picture']);
		$ordering 	 	= $row['ordering'];
		$status 	 	= $row['status'];
		#$top 	 		= $row['top'];
		
		$vi = $dbf->getArray(prefixTable . 'supplier_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
		#$en = $dbf->getArray(prefixTable . 'supplier_desc', 'id = "' . $id . '" and lang = "en-US"', '', 'stdObject');
  	}
} ?>
<script type="text/javascript" src="../plugins/editors2/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../plugins/editors2/ckfinder/ckfinder.js"></script>
<script type="text/javascript" src="../themes/default/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" >
jQuery(document).ready(function() {  	
	jQuery('#frm').validate({
		rules: {			 
			name: {
				required: true 
			} 
	    },
	    messages: {	    	
			name: {
				required: "Nhập tên nhà cung cấp." 
			} 
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
      <td  class="boxGrey"><?php echo $col['name'] ?> <!--[Tiếng Việt]--> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><input name="name" id="name" type="text" class="nd1" value="<?php echo (isset($vi[0]->name) && !empty($vi[0]->name)) ? $vi[0]->name : ''?>" /></td>
    </tr>
    <!--<tr>
      <td  class="boxGrey"><?php #echo $col['name'] ?> [Tiếng Anh]</td>
      <td class="boxGrey2"><input name="name_en" id="name_en" type="text" class="nd1" value="<?php #echo (isset($en[0]->name) && !empty($en[0]->name)) ? $en[0]->name : ''?>" /></td>
    </tr>-->
    <tr>
      <td class="boxGrey" colspan="2">Mô tả <!--[Tiếng Việt]--></td>
    </tr>
    <tr>
      <td colspan="2" class="boxGrey2"><?php #echo $ckeditor->doDisplay('description_vi', (isset($vi[0]->description) && !empty($vi[0]->description) ? stripslashes($vi[0]->description) : ''), '100%', '100', 'Normal') ?>
        <textarea name="description_vi" id="description_vi" cols="75" rows="20"><?php echo (isset($vi[0]->description) && !empty($vi[0]->description)) ? stripslashes($vi[0]->description) : '' ?></textarea>
		<script type="text/javascript">
        var editor = CKEDITOR.replace( 'description_vi', {
            language : 'vi',
            toolbar : 'Normal',
            height : '100px',
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
    <!--<tr>
      <td class="boxGrey" colspan="2">Mô tả thương hiệu [Tiếng Anh]</td>
    </tr>
    <tr>
      <td colspan="2" class="boxGrey2"><?php #echo $ckeditor->doDisplay('description_en', (isset($en[0]->description) && !empty($en[0]->description) ? stripslashes($en[0]->description) : ''), '100%', '100', 'Normal') ?></td>
    </tr>-->
    <tr>
      <td class="boxGrey"><?php echo $col['picture'] ?></td>
      <td class="boxGrey2"><?php #echo $dbf->generateChoicePicture('picture', 'pictureText', $picture) ?>
        <?php echo isset($mangpicture[0]) && !empty($mangpicture[0]) ? '<img src="resize.php?from=..' . $mangpicture[0] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
        <input type="file" name="picture[]" id="picture" style="width:250px;" />
        <input type="hidden" name="picture_name[]" id="picture_name" value="<?php echo isset($mangpicture[0]) && !empty($mangpicture[0]) ? $mangpicture[0] : '' ?>" /><br />Kích thước 367x196px
      </td>
    </tr>
    <tr>
      <td  class="boxGrey"><?php echo $col['ordering'] ?></td>
      <td class="boxGrey2"><input maxlength="5" onKeyPress="return nhapso(event,'ordering');" name="ordering" id="ordering" type="text" class="nd" value="<?php echo isset($ordering) && $ordering >= 0 ? $ordering : 999 ?>" /></td>
    </tr>
    <!--<tr>
      <td class="boxGrey">Tùy chọn</td>
      <td width="379" class="boxGrey2"><input type="checkbox" name="top" id="top" value="1" <?php #echo strtr((isset($top) && $top == 1 ? $top : 0), $statusChecked) ?> /> Thương hiệu hàng đầu?</td>
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
	$url = 'supplier.php?';
    $mang = $dbf->paging(prefixTable . 'supplier', '1=1', 'ordering, id', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
	echo $dbf->panelAction($mang[1]) ?>
<!-- view -->
<div id="panelView" class="panelView"><?php $dbf->normalView($col, 'supplier.php', $mang, $statusAction, '&caturl=' . $iscatURL, $msg = '') ?></div>
<!-- end view-->
<?php } ?>
  <input type="hidden" name="arrayid" id="arrayid" />
</form>
</body>
</html><?php ob_end_flush() ?>