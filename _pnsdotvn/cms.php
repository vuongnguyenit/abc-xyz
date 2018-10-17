<?php 
define('MOD_DIR_UPLOAD', '/media/images/news/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/news/');

#include('../plugins/editors/ckeditor/ckeditor.php');
#$ckeditor = new plgEditorCkeditor();

require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . "/plugins/upload/class.upload.php");

$utls = new Utilities();
$col = array('id' => 'ID', 'name' => 'Tên bài', 'picture' => 'Hình', 'mid' => 'Loại mục', 'ordering' => 'Thứ tự', 'created' => 'Ngày tạo', 'modified' => 'Ngày sửa', 'status' => 'Trạng thái');
$picture='/media/images/others/imagehere.png';
$iscatURL = isset($_GET['caturl']) && !empty($_GET['caturl']) ? $_GET['caturl'] : 0;
$titleMenu = 'QUẢN LÝ DANH SÁCH BÀI VIẾT';

if($isDelete)
{
  	$arrayid = substr($_POST['arrayid'], 0, -1);
  	$id_array = explode(',', $arrayid);
  	$affect = $dbf->deleteDynamic(prefixTable . 'cms', 'id in (' . $arrayid . ')');
  	if($affect > 0)
  	{
		$dbf->deleteDynamic(prefixTable . 'cms_desc', 'id in (' . $arrayid . ')');
		$dbf->deleteDynamic(prefixTable . 'url_alias', 'route = "cms" and type = "detail" and related_id in (' . $arrayid . ')');  
    	$msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu';
  	}
}

if($subInsert)
{
	$err = 0;
	if(isset($_POST['name']) && !empty($_POST['name']) && isset($_POST['mid']) && $_POST['mid'] > 0)
	{
		$data['name']        	= addslashes($utls->checkValues($_POST['name']));
		#$data['picture']     	= $utls->checkValues($_POST['pictureText']);
		$data['mid']      		= (int) $_POST['mid'];   
		#$data['promo'] 	   		= isset($_POST['promo']) && $_POST['promo'] == 1 ? 1 : 0;
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] > 0 ? (int) $_POST['ordering'] : 0;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
		$data['created']     	= date('Y-m-d H:i:s');
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
		$data['picture'] = isset($_picture) && !empty($_picture) && is_array($_picture) && count($_picture) > 0 ? implode(';', $_picture) : '/media/images/others/imagehere.png';
		
		#print_r($data['picture']);
		#exit;
		// END: UPLOAD PICTURE
		
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		$value['vi-VN']['introtext'] 	= !empty($_POST['introtext_vi']) ? addslashes(trim($_POST['introtext_vi'])) : '';
		$value['vi-VN']['content'] 		= !empty($_POST['content_vi']) ? addslashes(trim($_POST['content_vi'])) : '';
		$value['vi-VN']['html1'] 		= !empty($_POST['html1_vi']) ? addslashes(trim($_POST['html1_vi'])) : '';
		$value['vi-VN']['html2'] 		= !empty($_POST['html2_vi']) ? addslashes(trim($_POST['html2_vi'])) : '';
		$value['vi-VN']['metatitle']	= !empty($_POST['metatitle_vi']) ? addslashes($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metakey']		= !empty($_POST['metakey_vi']) ? addslashes($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metadesc']		= !empty($_POST['metadesc_vi']) ? addslashes($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['related_key'] = !empty($_POST['related_key_vi']) ? addslashes($utls->checkValues($_POST['related_key_vi'])) : '';
		
		#$value['en-US']['name'] 		= !empty($_POST['name_en']) ? addslashes($utls->checkValues($_POST['name_en'])) : $value['vi-VN']['name'];
		#$value['en-US']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['en-US']['name']));
		#$value['en-US']['introtext'] 	= !empty($_POST['introtext_en']) ? addslashes(trim($_POST['introtext_en'])) : '';
		#$value['en-US']['content'] 		= !empty($_POST['content_en']) ? addslashes(trim($_POST['content_en'])) : '';
		#$value['en-US']['metatitle']	= !empty($_POST['metatitle_en']) ? addslashes($utls->checkValues($_POST['metatitle_en'])) : $value['en-US']['name'];
		#$value['en-US']['metakey']		= !empty($_POST['metakey_en']) ? addslashes($utls->checkValues($_POST['metakey_en'])) : $value['en-US']['name'];
		#$value['en-US']['metadesc']		= !empty($_POST['metadesc_en']) ? addslashes($utls->checkValues($_POST['metadesc_en'])) : $value['en-US']['name'];
		
		$affect = $dbf->insertTable(prefixTable . 'cms', $data);
		if($affect > 0)
		{
			$_id 	= $affect;
			
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$value[$lang]['id'] = $_id;
				$value[$lang]['lang'] = $lang;
				$dbf->insertTable(prefixTable . 'cms_desc', $value[$lang]);
				
				$menua 	= $dbf->getMenuAliasArray($data['mid'], $lang);
				#$alias 	= substr($lang, 0, -3) . '/' . implode('/', $menua) . '/' . $_id . '-' . $value[$lang]['rewrite'];
				$alias 	= substr($lang, 0, -3) . '/' . implode('/', $menua) . '/' . $value[$lang]['rewrite'] . '-' . $_id;
				$dbf->insertTable(prefixTable . 'url_alias', array('url_alias' => strtolower($alias) . EXT, 'route' => 'cms', 'type' => 'detail', 'related_id' => $affect, 'lang' => $lang));
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
	if(isset($_GET['edit']) && !empty($_GET['edit']) && !empty($_POST['name']) && $_POST['mid'] > 0)
	{
		$_id					= (int) $_GET['edit'];
		$data['name']        	= addslashes($utls->checkValues($_POST['name']));
		#$data['picture']     	= $utls->checkValues($_POST['pictureText']);
		$data['mid']      		= (int) $_POST['mid'];   
		#$data['promo'] 	   		= isset($_POST['promo']) && $_POST['promo'] == 1 ? 1 : 0;
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] > 0 ? (int) $_POST['ordering'] : 0;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
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
		$value['vi-VN']['introtext'] 	= !empty($_POST['introtext_vi']) ? addslashes(trim($_POST['introtext_vi'])) : '';
		$value['vi-VN']['content'] 		= !empty($_POST['content_vi']) ? addslashes(trim($_POST['content_vi'])) : '';
		$value['vi-VN']['html1'] 		= !empty($_POST['html1_vi']) ? addslashes(trim($_POST['html1_vi'])) : '';
		$value['vi-VN']['html2'] 		= !empty($_POST['html2_vi']) ? addslashes(trim($_POST['html2_vi'])) : '';
		$value['vi-VN']['metatitle']	= !empty($_POST['metatitle_vi']) ? addslashes($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metakey']		= !empty($_POST['metakey_vi']) ? addslashes($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metadesc']		= !empty($_POST['metadesc_vi']) ? addslashes($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['related_key'] = !empty($_POST['related_key_vi']) ? addslashes($utls->checkValues($_POST['related_key_vi'])) : '';
		
		#$value['en-US']['name'] 		= !empty($_POST['name_en']) ? addslashes($utls->checkValues($_POST['name_en'])) : $value['vi-VN']['name'];
		#$value['en-US']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['en-US']['name']));
		#$value['en-US']['introtext'] 	= !empty($_POST['introtext_en']) ? addslashes(trim($_POST['introtext_en'])) : '';
		#$value['en-US']['content'] 		= !empty($_POST['content_en']) ? addslashes(trim($_POST['content_en'])) : '';
		#$value['en-US']['metatitle']	= !empty($_POST['metatitle_en']) ? addslashes($utls->checkValues($_POST['metatitle_en'])) : $value['en-US']['name'];
		#$value['en-US']['metakey']		= !empty($_POST['metakey_en']) ? addslashes($utls->checkValues($_POST['metakey_en'])) : $value['en-US']['name'];
		#$value['en-US']['metadesc']		= !empty($_POST['metadesc_en']) ? addslashes($utls->checkValues($_POST['metadesc_en'])) : $value['en-US']['name'];
		
		$affect = $dbf->updateTable(prefixTable. 'cms', $data, 'id = ' . $_id);
		if($affect > 0)
		{
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$dbf->updateTable(prefixTable . 'cms_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);				
				
				$menua = $dbf->getMenuAliasArray($data['mid'], $lang);
				#$alias = substr($lang, 0, -3) . '/' . implode('/', $menua) . '/' . $_id . '-' . $value[$lang]['rewrite'];  
				$alias = substr($lang, 0, -3) . '/' . implode('/', $menua) . '/' . $value[$lang]['rewrite'] . '-' . $_id;  
				$dbf->updateTable(prefixTable. 'url_alias', array('url_alias' => strtolower($alias) . EXT), 'route = "cms" and type = "detail" and related_id = ' . $_id . ' and lang = "' . $lang . '"');
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
	$rst = $dbf->getDynamic(prefixTable . 'cms', 'id = ' . (int) $_GET['edit'], '');
	if($dbf->totalRows($rst))
	{
		$row 		 	= $dbf->nextObject($rst);
		$id 	 	 	= $row->id;
		#$name 			= stripslashes($row->name);
		$mangpicture 	= explode(';', $row->picture);
		$mid 			= $row->mid;
		#$promo 			= $row->promo;
		$status 		= $row->status;
		$ordering 		= $row->ordering;
		
		$vi = $dbf->getArray(prefixTable . 'cms_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
		#$en = $dbf->getArray(prefixTable . 'cms_desc', 'id = "' . $id . '" and lang = "en-US"', '', 'stdObject');
		
		$ua = $dbf->getArray(prefixTable . 'url_alias', 'route = "cms" AND type = "detail" AND related_id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
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
			menuid: {
				required: true 
			},			 
			name: {
				required: true 
			} 
	    },
	    messages: {	
			menuid: {
				required: "Chọn danh mục bài viết." 
			},   	
			name: {
				required: "Nhập tên bài viết." 
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
    <?php if ($isEdit) : ?>
    <tr>
      <td class="boxGrey">Liên kết</td>
      <td class="boxGrey2"><input type="text" class="nd3" value="<?php echo (isset($ua[0]->url_alias) && !empty($ua[0]->url_alias) ? (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . '/' . str_replace('vi/', '', $ua[0]->url_alias) : '') ?>" readonly="readonly" /></td>
    </tr>
    <?php endif ?>
    <tr>
      <td class="boxGrey"><?php echo $col['mid'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><?php echo $dbf->buildDropdownCMSMenu('mid', 'name', 'id', (isset($mid) && $mid !== null ? $mid : $iscatURL), prefixTable. 'menu', 'status = 1 and display in ("FIRST","NEWS","LIST","VIDEO")', 'id', 0, array('firstText' => 'Chọn loại mục'))?></td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col['picture'] ?></td>
      <td class="boxGrey2"><?php #echo $dbf->generateChoicePicture('picture', 'pictureText', isset($mangpicture) && !empty($mangpicture) ? $mangpicture : $picture) ?>
        <?php echo isset($mangpicture[0]) && !empty($mangpicture[0]) ? '<img src="resize.php?from=..' . $mangpicture[0] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
        <input type="file" name="picture[]" id="picture" style="width:250px;" />
        <input type="hidden" name="picture_name[]" id="picture_name" value="<?php echo isset($mangpicture[0]) && !empty($mangpicture[0]) ? $mangpicture[0] : '' ?>" />
      </td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col['ordering'] ?></td>
      <td class="boxGrey2"><input name="ordering" id="ordering" type="text" class="nd1" value="<?php echo isset($ordering) && !empty($ordering) ? $ordering : 0 ?>" maxlength="5" onKeyPress="return nhapso(event,'ordering');" /> Lưu ý: Các danh mục được thiết lập hiển thị <strong>[Bài viết đầu tiên]</strong> thì chỉ có duy nhất 01 bài viết và thứ tự phải có giá trị = 0.</td>
    </tr>
   <!-- <tr>
      <td class="boxGrey">Tùy chọn</td>
      <td width="379" class="boxGrey2"><input type="checkbox" name="promo" id="promo" value="1" <?php #echo strtr((isset($promo) && $promo == 1 ? 1 : 0), $statusChecked) ?> /> Tin khuyến mãi?</td>
    </tr>-->
    <tr>
      <td class="boxGrey"><?php echo $col['status'] ?></td>
      <td width="379" class="boxGrey2"><input type="checkbox" name="status" id="status" value="1" <?php echo strtr((isset($status) && $status == 1 ? $status : 0), $statusChecked) ?> /> Kích hoạt?</td>
    </tr>        
    <tr>
      <td colspan="2" class="boxGrey2">                      
        <link rel="stylesheet" type="text/css" href="style/jquery-ui.css" />
		<script type="text/javascript" src="js/jquery-ui.js"></script> 
        <script type="text/javascript">$(function() { $(".tabs").tabs();});</script>
        <div id="ui-tabs">
          <div class="tabs">
            <ul id="tab-container-1-nav">
              <!--<li><a href="#tieng-viet"><span>Tiếng Việt (*)</span></a></li>-->
              <!--<li><a href="#tieng-anh"><span>Tiếng Anh</span></a></li>-->
            </ul>
            <div id="tieng-viet">
              <table id="mainTable" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="boxGrey"><?php echo $col['name'] ?> <span style="color:#DA251C">(*)</span></td>
                  <td class="boxGrey2"><input name="name" id="name" type="text" class="nd1" value="<?php echo isset($vi[0]->name) && !empty($vi[0]->name) ? stripslashes($vi[0]->name) : '' ?>" /></td>
                </tr>
                <tr>
                  <td colspan="2" class="boxGrey2">
                    <div id="ui-tabs">
                      <div class="tabs">
                        <ul id="tab-container-1-nav">
                          <li><a href="#mo-ta-ngan-vi-VN"><span>Mô tả ngắn</span></a></li>
                          <li><a href="#mo-ta-chi-tiet-vi-VN"><span>Mô tả chi tiết</span></a></li>
                          <li><a href="#html1-vi-VN"><span>HTML 1</span></a></li>
                          <li><a href="#html2-vi-VN"><span>HTML 2</span></a></li>
                        </ul>
                        <div id="mo-ta-ngan-vi-VN"><?php #echo $ckeditor->doDisplay('introtext_vi', (isset($vi[0]->introtext) && !empty($vi[0]->introtext) ? stripslashes($vi[0]->introtext) : ''), '100%', '100', 'Normal') ?>
                          <textarea name="introtext_vi" id="introtext_vi" cols="75" rows="20"><?php echo (isset($vi[0]->introtext) && !empty($vi[0]->introtext)) ? stripslashes($vi[0]->introtext) : '' ?></textarea>
						  <script type="text/javascript">
                          var editor = CKEDITOR.replace( 'introtext_vi', {
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
                        </div>
                        <div id="mo-ta-chi-tiet-vi-VN"><?php #echo $ckeditor->doDisplay('content_vi', (isset($vi[0]->content) && !empty($vi[0]->content) ? stripslashes($vi[0]->content) : ''), '100%', '300', 'Default') ?>
                          <textarea name="content_vi" id="content_vi" cols="75" rows="20"><?php echo (isset($vi[0]->content) && !empty($vi[0]->content)) ? stripslashes($vi[0]->content) : '' ?></textarea>
						  <script type="text/javascript">
                          var editor = CKEDITOR.replace( 'content_vi', {
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
                        </div>
                        <div id="html1-vi-VN">
                          <textarea name="html1_vi" id="html1_vi" cols="75" rows="20"><?php echo (isset($vi[0]->html1) && !empty($vi[0]->html1)) ? stripslashes($vi[0]->html1) : '' ?></textarea>
						  <script type="text/javascript">
                          var editor = CKEDITOR.replace( 'html1_vi', {
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
                        </div>
                        <div id="html2-vi-VN">
                          <textarea name="html2_vi" id="html2_vi" cols="75" rows="20"><?php echo (isset($vi[0]->html2) && !empty($vi[0]->html2)) ? stripslashes($vi[0]->html2) : '' ?></textarea>
						  <script type="text/javascript">
                          var editor = CKEDITOR.replace( 'html2_vi', {
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
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
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
                  <td class="boxGrey">Từ khóa liên quan</td>
                  <td class="boxGrey2"><textarea name="related_key_vi" id="related_key_vi" type="text" class="nd3" /><?php echo isset($vi[0]->related_key) && !empty($vi[0]->related_key) ? stripslashes($vi[0]->related_key) : '' ?></textarea></td>
                </tr>
              </table>
            </div>
            <!--<div id="tieng-anh">
              <table id="mainTable" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="boxGrey"><?php #echo $col['name'] ?></td>
                  <td class="boxGrey2"><input name="name_en" id="name_en" type="text" class="nd1" value="<?php #echo isset($en[0]->name) && !empty($en[0]->name) ? stripslashes($en[0]->name) : '' ?>" /></td>
                </tr>
                <tr>
                  <td colspan="2" class="boxGrey2">
                    <div id="ui-tabs">
                      <div class="tabs">
                        <ul id="tab-container-1-nav">
                          <li><a href="#mo-ta-ngan-en-US"><span>Mô tả ngắn</span></a></li>
                          <li><a href="#mo-ta-chi-tiet-en-US"><span>Mô tả chi tiết</span></a></li>
                        </ul>
                        <div id="mo-ta-ngan-en-US"><?php #echo $ckeditor->doDisplay('introtext_en', (isset($en[0]->introtext) && !empty($en[0]->introtext) ? stripslashes($en[0]->introtext) : ''), '100%', '100', 'Normal') ?></div>
                        <div id="mo-ta-chi-tiet-en-US"><?php #echo $ckeditor->doDisplay('content_en', (isset($en[0]->content) && !empty($en[0]->content) ? stripslashes($en[0]->content) : ''), '100%', '300', 'Default') ?></div>
                      </div>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="boxGrey">Tiêu đề [Meta Title]</td>
                  <td class="boxGrey2"><input name="metatitle_en" id="metatitle_en" type="text" class="nd3" value="<?php #echo isset($en[0]->metatitle) && !empty($en[0]->metatitle) ? stripslashes($en[0]->metatitle) : '' ?>" /></td>
                </tr>
                <tr>
                  <td class="boxGrey">Từ khóa [Meta Keywords]</td>
                  <td class="boxGrey2"><textarea name="metakey_en" id="metakey_en" type="text" class="nd3" /><?php #echo isset($en[0]->metakey) && !empty($en[0]->metakey) ? stripslashes($en[0]->metakey) : '' ?></textarea></td>
                </tr>
                <tr>
                  <td class="boxGrey">Mô tả [Meta Description]</td>
                  <td class="boxGrey2"><textarea name="metadesc_en" id="metadesc_en" type="text" class="nd3" /><?php #echo isset($en[0]->metadesc) && !empty($en[0]->metadesc) ? stripslashes($en[0]->metadesc) : '' ?></textarea></td>
                </tr>
              </table>
            </div>-->
          </div>
        </div>
      </td>
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
  $url = 'cms.php?caturl='. ((!empty($iscatURL)) ? '&caturl=' . (int) $iscatURL : '');
  $condition = ($iscatURL > 0) ? 'mid = ' . (int) $iscatURL : '1=1';
  $mang = $dbf->pagingJoin(prefixTable . 'cms', prefixTable . 'menu', array('name' => 'mid'), 'inner join', $condition, 'id desc', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging, 't1.mid = t2.id');
  echo $dbf->panelAction($mang[1]);
  echo $dbf->selectFilterCMSMenu('cbokho', 'name', 'id', $iscatURL, 'Chọn loại mục:', prefixTable . 'menu', 'status = 1 and display  in ("FIRST","NEWS","LIST","VIDEO")', 'id', array('firstText' => 'Chọn loại mục', 'onchange' => "redirect('" . $_SERVER['PHP_SELF'] . "?&caturl='+this.value);")) ?>
<!-- view -->
<div id="panelView" class="panelView"><?php $dbf->normalView($col, 'cms.php', $mang, $statusAction, '&caturl=' . $iscatURL, $msg = '') ?></div>
<!-- end view-->
<?php } ?>
  <input type="hidden" name="arrayid" id="arrayid" />
</form>
</body>
</html><?php ob_end_flush()?>