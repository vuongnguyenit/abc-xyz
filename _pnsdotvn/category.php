<?php 
define('MOD_DIR_UPLOAD', '/media/images/others/catalog/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/others/catalog/');

#include('../plugins/editors/ckeditor/ckeditor.php');
#$ckeditor = new plgEditorCkeditor();

require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . "/plugins/upload/class.upload.php");

$utls = new Utilities();
$col=array('id' => 'ID', 'name' => 'Danh mục SP', /*'picture' => 'Hình',*/ 'parentid' => 'Danh mục gốc', 'ordering' => 'Thứ tự', 'created' => 'Ngày tạo', 'modified' => 'Ngày sửa', 'status' => 'Trạng thái');
$iscatURL = (isset($_GET['caturl']) && !empty($_GET['caturl'])) ? (int) $_GET['caturl'] : 0;
$titleMenu = 'QUẢN LÝ DANH MỤC LOẠI SẢN PHẨM';

if($isDelete)
{
  	$arrayid = substr($_POST['arrayid'], 0, -1);
  	$id_array = explode(',', $arrayid);  
  	if($dbf->chkChild($id_array) === false) 
  	{
		$msg = 'Không thể thực hiện! Bạn phải xóa các danh mục con & sản phẩm trước khi xóa danh mục này.';
  	} else
  	{ 
		$affect  = $dbf->deleteDynamic(prefixTable . 'category', 'id in (' . $arrayid . ')');
		if($affect > 0)
		{
			$dbf->deleteDynamic(prefixTable . 'category_desc', 'id in (' . $arrayid . ')');
	  		$dbf->deleteDynamic(prefixTable. 'url_alias', 'route = "category" and related_id in (' . $arrayid . ')');  
	  		$msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu.';
		}	
  	}
}

if($subInsert)
{
	$err = 0;
	if(isset($_POST['name']) && !empty($_POST['name']))
	{
		$data['parentid']    	= (int) $_POST['parentid'];
		$data['name'] 	   		= addslashes(trim($_POST['name']));
		#$data['picture'] 	   	= trim($_POST['pictureText']);
		$data['showmain']   	= $data['parentid'] == 0 && isset($_POST['showmain']) && $_POST['showmain'] == 1 ? 1 : 0;	
		$data['showindex']   	= isset($_POST['showindex']) && $_POST['showindex'] == 1 ? 1 : 0;		
		#$data['showmenu']   	= $data['parentid'] == 0 && isset($_POST['showmenu']) && $_POST['showmenu'] == 1 ? 1 : 0;				
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] > 1 ? (int) $_POST['ordering'] : 1;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? (int) $_POST['status'] : 0;
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
						/*if (WATERMARK && in_array($result['type'], array('jpg','jpeg','png')))
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
		
		
		
		// BEGIN: UPLOAD PICTURE								
		/*if(isset($_FILES['icon']['name']) && is_array($_FILES['icon']['name']) && count($_FILES['icon']['name']) > 0)	
		{
			$dir = $dbf->pnsdotvn_get_dir_upload(3);			
			$arr_pic = array ();
			foreach ($_FILES['icon'] as $k => $l) 
			{
				foreach ($l as $i => $v) 
				{
					if (!array_key_exists($i, $arr_pic)) 
						$arr_pic[$i] = array();				
					$arr_pic[$i][$k] = $v;
				}
			}			
			for($i = 0; $i < count($_FILES['icon']['name']); $i++)
			{
				if(!empty($_FILES['icon']['name'][$i]))
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
						#if (in_array($result['type'], array('jpg','jpeg','png')))
						#{
							#$utls->watermark(str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 #str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 #str_replace("\\", "/", dirname(dirname(__FILE__))) . "/themes/default/images/watermark.png",
											 #300,
											 #$result['type']);
						#}
					}
				}
			}		
		}		
		$data['icon'] = isset($_picture) && !empty($_picture) && is_array($_picture) && count($_picture) > 0 ? implode(';', $_picture) : '/media/images/others/imagehere.png';*/
		// END: UPLOAD PICTURE
		
		
		
		$jbanner['banner1']['name'] = isset($_POST['banner_name']) && !empty($_POST['banner_name']) ? addslashes(trim($_POST['banner_name'])) : '';
		$jbanner['banner1']['href'] = isset($_POST['banner_name']) && !empty($_POST['banner_name']) ? trim($_POST['banner_clickurl']) : '#';
		$jbanner['banner1']['target'] = in_array($_POST['banner_target'], array_keys($arrayTarget)) ? $_POST['banner_target'] : '_blank';
		$jbanner['banner1']['nofollow'] = isset($_POST['banner_nofollow']) && $_POST['banner_nofollow'] == 1 ? 1 : 0;
		$jbanner['banner1']['status'] = isset($_POST['banner_status']) && $_POST['banner_status'] == 1 ? 1 : 0;
		if(isset($_FILES['banner_picture']['name']) && is_array($_FILES['banner_picture']['name']) && count($_FILES['banner_picture']['name']) > 0) {
			$dir = $dbf->pnsdotvn_get_dir_upload(3);			
			$arr_pic = array ();
			foreach ($_FILES['banner_picture'] as $k => $l) {
				foreach ($l as $i => $v) {
					if (!array_key_exists($i, $arr_pic)) 
						$arr_pic[$i] = array();				
					$arr_pic[$i][$k] = $v;
				}
			}			
			for($i = 0; $i < count($_FILES['banner_picture']['name']); $i++) {
				if(!empty($_FILES['banner_picture']['name'][$i])) {
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
					if(empty($result['err'])) {
						$_banner_picture[$i] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
						#if (in_array($result['type'], array('jpg','jpeg','png')))
						#{
							#$utls->watermark(str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 #str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 #str_replace("\\", "/", dirname(dirname(__FILE__))) . "/themes/default/images/watermark.png",
											 #300,
											 #$result['type']);
						#}
					}
				}
			}
		}		
		$jbanner['banner1']['src'] = isset($_banner_picture) && !empty($_banner_picture) && is_array($_banner_picture) && count($_banner_picture) > 0 ? implode(';', $_banner_picture) : '';
		$data['jbanner'] = serialize($jbanner);
		
		
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		$value['vi-VN']['description']	= addslashes($_POST['description_vi']);
		$value['vi-VN']['html']	= addslashes($_POST['html_vi']);
		$value['vi-VN']['metatitle']	= !empty($_POST['metatitle_vi']) ? addslashes($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metakey']		= !empty($_POST['metakey_vi']) ? addslashes($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metadesc']		= !empty($_POST['metadesc_vi']) ? addslashes($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];
		
		#$value['en-US']['name'] 		= !empty($_POST['name_en']) ? addslashes($utls->checkValues($_POST['name_en'])) : $value['vi-VN']['name'];
		#$value['en-US']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['en-US']['name']));
		#$value['en-US']['description']	= !empty($_POST['description_en']) ? addslashes($_POST['description_en']) : $value['vi-VN']['description'];
		#$value['en-US']['metatitle']	= !empty($_POST['metatitle_en']) ? addslashes($utls->checkValues($_POST['metatitle_en'])) : $value['en-US']['name'];
		#$value['en-US']['metakey']		= !empty($_POST['metakey_en']) ? addslashes($utls->checkValues($_POST['metakey_en'])) : $value['en-US']['name'];
		#$value['en-US']['metadesc']		= !empty($_POST['metadesc_en']) ? addslashes($utls->checkValues($_POST['metadesc_en'])) : $value['en-US']['name'];
		
		$affect = $dbf->insertTable(prefixTable . 'category', $data);
		if($affect > 0)
		{
			$_id = $affect;
			
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$value[$lang]['id'] = $_id;
				$value[$lang]['lang'] = $lang;
				$dbf->insertTable(prefixTable . 'category_desc', $value[$lang]);				
				#$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $_id . '-' . $value[$lang]['rewrite']) . EXT;
				$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $value[$lang]['rewrite']) . '-' . $_id . EXT;
				$dbf->insertTable(prefixTable . 'url_alias', array('url_alias' => $_url_alias, 'route' => 'category', 'related_id' => $affect, 'lang' => $lang));
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
		$data['parentid']    	= (int) $_POST['parentid'];
		$data['name'] 	   		= addslashes(trim($_POST['name']));
		#$data['picture']		= trim($_POST['pictureText']);		
		$data['showmain']   	= $data['parentid'] == 0 && isset($_POST['showmain']) && $_POST['showmain'] == 1 ? 1 : 0;			
		$data['showindex']   	= isset($_POST['showindex']) && $_POST['showindex'] == 1 ? 1 : 0;		
		#$data['showmenu']   	= $data['parentid'] == 0 && isset($_POST['showmenu']) && $_POST['showmenu'] == 1 ? 1 : 0;	
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] > 1 ? (int) $_POST['ordering'] : 1;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? (int) $_POST['status'] : 0;
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
						/*if (WATERMARK && in_array($result['type'], array('jpg','jpeg','png')))
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
		$data['picture'] = implode(';', $_picture_name);
		
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
		
		
		
		// BEGIN: UPLOAD PICTURE
		/*if(isset($_FILES['icon']['name']) && is_array($_FILES['icon']['name']) && count($_FILES['icon']['name']) > 0)	
		{			
			$dir = $dbf->pnsdotvn_get_dir_upload(3);			
			$arr_pic = array ();
			foreach ($_FILES['icon'] as $k => $l) 
			{
				foreach ($l as $i => $v) 
				{
					if (!array_key_exists($i, $arr_pic)) 
						$arr_pic[$i] = array();				
					$arr_pic[$i][$k] = $v;
				}
			}			
			for($i = 0; $i < count($_FILES['icon']['name']); $i++)
			{
				if(!empty($_FILES['icon']['name'][$i]))
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
						#if (in_array($result['type'], array('jpg','jpeg','png')))
						#{
							#$utls->watermark(str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 str_replace("\\", "/", dirname(dirname(__FILE__))) . "/themes/default/images/watermark.png",
											 300,
											 $result['type']);
						#}					
					}
				}
			}
		}		
		if (isset($_POST['icon_name']) && !empty($_POST['icon_name']) && is_array($_POST['icon_name']) && count($_POST['icon_name']) > 0)
		{
			foreach ($_POST['icon_name'] as $_k => $_v)
				if (!empty($_POST['icon_name'][$_k]))
					$_picture_name[] = $_v;
		}
		$data['icon'] = isset($_picture_name) && is_array($_picture_name) && count($_picture_name) > 0 ? implode(';', $_picture_name) : '';		
		if (isset($_picture) && !empty($_picture) && is_array($_picture) && count($_picture) > 0)
		{
			foreach ($_picture as $k => $v)
			{
				if (isset($_picture[$k]))
					$_picture_name[$k] = $v;
			}
			$data['icon'] = implode(';', $_picture_name);			
		}*/
		// END: UPLOAD PICTURE
		
		
		
		$jbanner['banner1']['name'] = isset($_POST['banner_name']) && !empty($_POST['banner_name']) ? addslashes(trim($_POST['banner_name'])) : '';
		$jbanner['banner1']['href'] = isset($_POST['banner_clickurl']) && !empty($_POST['banner_clickurl']) ? trim($_POST['banner_clickurl']) : '#';
		$jbanner['banner1']['target'] = in_array($_POST['banner_target'], array_keys($arrayTarget)) ? $_POST['banner_target'] : '_blank';
		$jbanner['banner1']['nofollow'] = isset($_POST['banner_nofollow']) && $_POST['banner_nofollow'] == 1 ? 1 : 0;
		$jbanner['banner1']['status'] = isset($_POST['banner_status']) && $_POST['banner_status'] == 1 ? 1 : 0;
		if(isset($_FILES['banner_picture']['name']) && is_array($_FILES['banner_picture']['name']) && count($_FILES['banner_picture']['name']) > 0) {						
			$dir = $dbf->pnsdotvn_get_dir_upload(3);			
			$arr_pic = array ();
			foreach ($_FILES['banner_picture'] as $k => $l) {
				foreach ($l as $i => $v) {
					if (!array_key_exists($i, $arr_pic)) 
						$arr_pic[$i] = array();				
					$arr_pic[$i][$k] = $v;
				}
			}			
			for($i = 0; $i < count($_FILES['banner_picture']['name']); $i++) {
				if(!empty($_FILES['banner_picture']['name'][$i])) {
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
					if(empty($result['err'])) {
						$_banner_picture[$i] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
						#if (in_array($result['type'], array('jpg','jpeg','png')))
						#{
							#$utls->watermark(str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 #str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 #str_replace("\\", "/", dirname(dirname(__FILE__))) . "/themes/default/images/watermark.png",
											 #300,
											 #$result['type']);
						#}						
					}
				}
			}			
		}				
		if (isset($_POST['banner_picture_name']) && !empty($_POST['banner_picture_name']) && is_array($_POST['banner_picture_name']) && count($_POST['banner_picture_name']) > 0) {
			foreach ($_POST['banner_picture_name'] as $_k => $_v)
				if (!empty($_POST['banner_picture_name'][$_k]))
					$_banner_picture_name[] = $_v;
		}
		$jbanner['banner1']['src'] = isset($_banner_picture_name) && is_array($_banner_picture_name) && count($_banner_picture_name) > 0 ? implode(';', $_banner_picture_name) : '';		
		if (isset($_banner_picture) && !empty($_banner_picture) && is_array($_banner_picture) && count($_banner_picture) > 0) {
			foreach ($_banner_picture as $k => $v) {
				if (isset($_banner_picture[$k]))
					$_banner_picture_name[$k] = $v;
			}
			$jbanner['banner1']['src'] = implode(';', $_banner_picture_name);
		}				
		$jbanner['banner2']['name'] = isset($_POST['banner_name2']) && !empty($_POST['banner_name2']) ? addslashes(trim($_POST['banner_name2'])) : '';
		$jbanner['banner2']['href'] = isset($_POST['banner_clickurl2']) && !empty($_POST['banner_clickurl2']) ? trim($_POST['banner_clickurl2']) : '#';
		$jbanner['banner2']['target'] = in_array($_POST['banner_target2'], array_keys($arrayTarget)) ? $_POST['banner_target2'] : '_blank';
		$jbanner['banner2']['nofollow'] = isset($_POST['banner_nofollow2']) && $_POST['banner_nofollow2'] == 1 ? 1 : 0;
		$jbanner['banner2']['status'] = isset($_POST['banner_status2']) && $_POST['banner_status2'] == 1 ? 1 : 0;
		if(isset($_FILES['banner_picture2']['name']) && is_array($_FILES['banner_picture2']['name']) && count($_FILES['banner_picture2']['name']) > 0) {						
			$dir = $dbf->pnsdotvn_get_dir_upload(3);			
			$arr_pic = array ();
			foreach ($_FILES['banner_picture2'] as $k => $l) {
				foreach ($l as $i => $v) {
					if (!array_key_exists($i, $arr_pic)) 
						$arr_pic[$i] = array();				
					$arr_pic[$i][$k] = $v;
				}
			}			
			for($i = 0; $i < count($_FILES['banner_picture2']['name']); $i++) {
				if(!empty($_FILES['banner_picture2']['name'][$i])) {
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
					if(empty($result['err'])) {
						$_banner_picture2[$i] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
						#if (in_array($result['type'], array('jpg','jpeg','png')))
						#{
							#$utls->watermark(str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 #str_replace("\\", "/", dirname(dirname(__FILE__))) . MOD_DIR_UPLOAD . $dir . '/' . $result['link'],
											 #str_replace("\\", "/", dirname(dirname(__FILE__))) . "/themes/default/images/watermark.png",
											 #300,
											 #$result['type']);
						#}						
					}
				}
			}			
		}				
		if (isset($_POST['banner_picture_name2']) && !empty($_POST['banner_picture_name2']) && is_array($_POST['banner_picture_name2']) && count($_POST['banner_picture_name2']) > 0) {
			foreach ($_POST['banner_picture_name2'] as $_k => $_v)
				if (!empty($_POST['banner_picture_name2'][$_k]))
					$_banner_picture_name2[] = $_v;
		}
		$jbanner['banner2']['src'] = isset($_banner_picture_name2) && is_array($_banner_picture_name2) && count($_banner_picture_name2) > 0 ? implode(';', $_banner_picture_name2) : '';		
		if (isset($_banner_picture2) && !empty($_banner_picture2) && is_array($_banner_picture2) && count($_banner_picture2) > 0) {
			foreach ($_banner_picture2 as $k => $v) {
				if (isset($_banner_picture2[$k]))
					$_banner_picture_name2[$k] = $v;
			}
			$jbanner['banner2']['src'] = implode(';', $_banner_picture_name2);
		}
		$data['jbanner'] = serialize($jbanner);
		
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		$value['vi-VN']['description']	= addslashes($_POST['description_vi']);
		$value['vi-VN']['html']	= addslashes($_POST['html_vi']);
		$value['vi-VN']['metatitle']	= !empty($_POST['metatitle_vi']) ? addslashes($utls->checkValues($_POST['metatitle_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metakey']		= !empty($_POST['metakey_vi']) ? addslashes($utls->checkValues($_POST['metakey_vi'])) : $value['vi-VN']['name'];
		$value['vi-VN']['metadesc']		= !empty($_POST['metadesc_vi']) ? addslashes($utls->checkValues($_POST['metadesc_vi'])) : $value['vi-VN']['name'];
		
		#$value['en-US']['name'] 		= !empty($_POST['name_en']) ? addslashes($utls->checkValues($_POST['name_en'])) : $value['vi-VN']['name'];
		#$value['en-US']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['en-US']['name']));
		#$value['en-US']['description']	= !empty($_POST['description_en']) ? addslashes($_POST['description_en']) : $value['vi-VN']['description'];
		#$value['en-US']['metatitle']	= !empty($_POST['metatitle_en']) ? addslashes($utls->checkValues($_POST['metatitle_en'])) : $value['en-US']['name'];
		#$value['en-US']['metakey']		= !empty($_POST['metakey_en']) ? addslashes($utls->checkValues($_POST['metakey_en'])) : $value['en-US']['name'];
		#$value['en-US']['metadesc']		= !empty($_POST['metadesc_en']) ? addslashes($utls->checkValues($_POST['metadesc_en'])) : $value['en-US']['name'];
		
		$affect = $dbf->updateTable(prefixTable . 'category', $data, 'id = ' . $_id);
		if($affect > 0)
		{
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$dbf->updateTable(prefixTable . 'category_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);
				
				#$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $_id . '-' . $value[$lang]['rewrite']) . EXT;
				$_url_alias = strtolower(substr($lang, 0, -3) . '/' . $value[$lang]['rewrite']) . '-' . $_id . EXT;
				$dbf->updateTable(prefixTable . 'url_alias', array('url_alias' => $_url_alias), 'route = "category" and lang = "' . $lang . '" and related_id = ' . $_id);
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
  	$rst = $dbf->getDynamic(prefixTable . 'category', 'id = ' . (int) $_GET['edit'], '');
  	if($dbf->totalRows($rst))
  	{
		$row 		 	= $dbf->nextObject($rst);
		$id 	 	 	= $row->id;
		#$name 		 	= stripslashes($row->name);
		#$picture 	 	= $row->picture;
		$mangpicture	= explode(';', $row->picture);
		#$mangicon 		= explode(';', $row->icon);
		$parentid 	 	= $row->parentid;
		$showmain		= $row->showmain;
		$showindex		= $row->showindex;
		#$showmenu		= $row->showmenu;
		$ordering 	 	= $row->ordering;		
		$status 	 	= $row->status;		
		$jbanner 	 	= unserialize($row->jbanner);
		
		$vi = $dbf->getArray(prefixTable . 'category_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
		#$en = $dbf->getArray(prefixTable . 'category_desc', 'id = "' . $id . '" and lang = "en-US"', '', 'stdObject');
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
				required: "Nhập tên danh mục sản phẩm." 
			} 
		}		
	});	
});
</script>
<?php $dbf->FormUpload('frm', array('action' => '', 'method' => 'post', 'class' => 'validate')) ?>
<?php if($isEdit || $isInsert) { ?>
<!-- form --> 
<link rel="stylesheet" type="text/css" href="style/jquery-ui.css" />
<script type="text/javascript" src="js/jquery-ui.js"></script>
<div id="panelForm" class="panelForm">
  <table id="mainTable" cellpadding="0" cellspacing="0">
    <?php echo $dbf->returnTitleMenu($titleMenu) ?>
    <tr>
      <td colspan="4" class="txtdo" align="center"><?php echo $msg ?></td>
    </tr>
    <tr>
      <td class="boxGrey" colspan="2">
        <script type="text/javascript">$(function() { $(".tabs2").tabs();});</script>
        <div id="ui-tabs">
          <div class="tabs2">
            <ul id="tab-container-1-nav">
              <li><a href="#danh-muc"><span>Danh mục (*)</span></a></li>
              <li><a href="#banner"><span>Banner1</span></a></li>
              <li><a href="#banner2"><span>Banner2</span></a></li>
            </ul>
            <div id="danh-muc">
              <table id="mainTable2" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="boxGrey"><?php echo $col['parentid'] ?></td>
                  <td class="boxGrey2"><?php echo $dbf->generateRecursiveSelect('parentid', 'name', 'id', (isset($parentid) && $parentid !== '') ? $parentid : $iscatURL, prefixTable . 'category') ?></td>
                </tr>    
                <tr>
                  <td class="boxGrey">Banner </td>
                  <td class="boxGrey2">
                    <?php echo isset($mangpicture[0]) && !empty($mangpicture[0]) ? '<img src="resize.php?from=..' . $mangpicture[0] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                    <input type="file" name="picture[]" id="picture" style="width:250px;" />
                    <input type="hidden" name="picture_name[]" id="picture_name" value="<?php echo isset($mangpicture[0]) && !empty($mangpicture[0]) ? $mangpicture[0] : '' ?>" />
                  </td>
                </tr>
                <!--<tr>
                  <td class="boxGrey">Icon </td>
                  <td class="boxGrey2"><?php ##echo $dbf->generateChoicePicture('picture', 'pictureText', $picture) ?> 
                    <?php #echo isset($mangicon[0]) && !empty($mangicon[0]) ? '<img src="resize.php?from=..' . $mangicon[0] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                    <input type="file" name="icon[]" id="icon" style="width:250px;" />
                    <input type="hidden" name="icon_name[]" id="icon_name" value="<?php #echo isset($mangicon[0]) && !empty($mangicon[0]) ? $mangicon[0] : '' ?>" /><br />(80x50px)
                  </td>
                </tr>-->
                <tr>
                  <td class="boxGrey">Hiển thị tại menu chính</td>
                  <td width="379" class="boxGrey2"><input type="checkbox" name="showmain" id="showmain" value="1" <?php echo strtr((isset($showmain) && $showmain == 1 ? $showmain : 0), $statusChecked) ?> />* Chỉ áp dụng cho Danh mục gốc là ROOT</td>
                </tr>
                <tr>
                  <td class="boxGrey">Hiển thị tại trang chủ</td>
                  <td width="379" class="boxGrey2"><input type="checkbox" name="showindex" id="showindex" value="1" <?php echo strtr((isset($showindex) && $showindex == 1 ? $showindex : 0), $statusChecked) ?> /></td>
                </tr>
                <!--<tr>
                  <td class="boxGrey">Hiển thị tại menu trang chủ</td>
                  <td width="379" class="boxGrey2"><input type="checkbox" name="showmenu" id="showmenu" value="1" <?php #echo strtr((isset($showmenu) && $showmenu == 1 ? $showmenu : 0), $statusChecked) ?> /> <span>* Chỉ áp dụng cho Danh mục gốc là ROOT</span></td>
                </tr>-->
                <tr>
                  <td class="boxGrey"><?php echo $col['ordering'] ?></td>
                  <td class="boxGrey2"><input maxlength="5" onKeyPress="return nhapso(event,'ordering');" name="ordering" id="ordering" type="text" class="nd" value="<?php echo isset($ordering) && !empty($ordering) ? $ordering : 1 ?>" /></td>
                </tr>    
                <tr>
                  <td class="boxGrey"><?php echo $col['status'] ?></td>
                  <td width="379" class="boxGrey2"><input type="checkbox" name="status" id="status" value="1" <?php echo strtr((isset($status) && $status == 1 ? $status : 0), $statusChecked) ?> /> Kích hoạt?</td>
                </tr>
                <tr>
                  <td colspan="2" class="boxGrey2">                     
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
                              <td class="boxGrey">Mô tả danh mục</td>
                              <td class="boxGrey2"><?php #echo $ckeditor->doDisplay('description_vi', (isset($vi[0]->description) && !empty($vi[0]->description)) ? stripslashes($vi[0]->description) : '', '100%', '100', 'Normal') ?>
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
                            <tr>
                              <td class="boxGrey">HTML danh mục</td>
                              <td class="boxGrey2">
                                <textarea name="html_vi" id="html_vi" cols="75" rows="20"><?php echo (isset($vi[0]->html) && !empty($vi[0]->html)) ? stripslashes($vi[0]->html) : '' ?></textarea>
                                <script type="text/javascript">
                                var editor = CKEDITOR.replace( 'html_vi', {
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
                          </table>
                        </div>
                        <!--<div id="tieng-anh">
                          <table id="mainTable" cellpadding="0" cellspacing="0">
                            <tr>
                              <td class="boxGrey"><?php #echo $col['name'] ?></td>
                              <td class="boxGrey2"><input name="name_en" id="name_en" type="text" class="nd1" value="<?php #echo isset($en[0]->name) && !empty($en[0]->name) ? stripslashes($en[0]->name) : '' ?>" /></td>
                            </tr> 
                            <tr>
                              <td class="boxGrey">Mô tả danh mục</td>
                              <td class="boxGrey2"><?php #echo $ckeditor->doDisplay('description_en', (isset($en[0]->description) && !empty($en[0]->description)) ? stripslashes($en[0]->description) : '', '100%', '100', 'Normal') ?></td>
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
              </table>
            </div>
            <div id="banner">
              <table id="mainTable3" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="boxGrey">Tên banner</td>
                  <td class="boxGrey2"><input name="banner_name" id="banner_name" type="text" class="nd1" value="<?php echo (isset($jbanner['banner1']['name']) && !empty($jbanner['banner1']['name'])) ? $jbanner['banner1']['name'] : '' ?>" /></td>
                </tr>
                <tr>
                  <td class="boxGrey">Liên kết</td>
                  <td class="boxGrey2"><input name="banner_clickurl" id="banner_clickurl" type="text" class="nd1" value="<?php echo isset($jbanner['banner1']['href']) && !empty($jbanner['banner1']['href']) ? $jbanner['banner1']['href'] : '#' ?>" /> Vd: http://www.pns.vn </td>
                </tr>
                <tr>
                  <td class="boxGrey">Đích đến</td>
                  <td class="boxGrey2"><?php echo $dbf->SelectWithNormalArray($arrayTarget, (isset($jbanner['banner1']['target']) && !empty($jbanner['banner1']['target']) ? $jbanner['banner1']['target'] : '_self'), 'banner_target', array('firstText' => 'Chọn loại đích đến', 'class' => 'cbo')) ?></td>
                </tr>
                <tr>
                  <td class="boxGrey">Banner</td>
                  <td class="boxGrey2">
                    <?php echo isset($jbanner['banner1']['src']) && !empty($jbanner['banner1']['src']) ? '<img src="resize.php?from=..' . $jbanner['banner1']['src'] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                    <input type="file" name="banner_picture[]" id="banner_picture" style="width:250px;" />
                    <input type="hidden" name="banner_picture_name[]" id="banner_picture_name" value="<?php echo isset($jbanner['banner1']['src']) && !empty($jbanner['banner1']['src']) ? $jbanner['banner1']['src'] : '' ?>" /><br />(375x225px)
                  </td>
                </tr>
                <tr>
                  <td class="boxGrey">Thuộc tính Nofollow</td>
                  <td width="379" class="boxGrey2"><input type="checkbox" name="banner_nofollow" id="banner_nofollow" value="1" <?php echo strtr((isset($jbanner['banner1']['nofollow']) && $jbanner['banner1']['nofollow'] == 1 ? 1 : 0), $statusChecked) ?> /> Kích hoạt?</td>
                </tr>
                <tr>
                  <td class="boxGrey">Trạng thái</td>
                  <td width="379" class="boxGrey2"><input type="checkbox" name="banner_status" id="banner_status" value="1" <?php echo strtr((isset($jbanner['banner1']['status']) && $jbanner['banner1']['status'] == 1 ? 1 : 0), $statusChecked) ?> /> Kích hoạt?</td>
                </tr>
              </table>
            </div>
            <div id="banner2">
              <table id="mainTable3" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="boxGrey">Tên banner</td>
                  <td class="boxGrey2"><input name="banner_name2" id="banner_name2" type="text" class="nd1" value="<?php echo (isset($jbanner['banner2']['name']) && !empty($jbanner['banner2']['name'])) ? $jbanner['banner2']['name'] : '' ?>" /></td>
                </tr>
                <tr>
                  <td class="boxGrey">Liên kết</td>
                  <td class="boxGrey2"><input name="banner_clickurl2" id="banner_clickurl2" type="text" class="nd1" value="<?php echo isset($jbanner['banner2']['href']) && !empty($jbanner['banner2']['href']) ? $jbanner['banner2']['href'] : '#' ?>" /> Vd: http://www.pns.vn </td>
                </tr>
                <tr>
                  <td class="boxGrey">Đích đến</td>
                  <td class="boxGrey2"><?php echo $dbf->SelectWithNormalArray($arrayTarget, (isset($jbanner['banner2']['target']) && !empty($jbanner['banner2']['target']) ? $jbanner['banner2']['target'] : '_self'), 'banner_target2', array('firstText' => 'Chọn loại đích đến', 'class' => 'cbo')) ?></td>
                </tr>
                <tr>
                  <td class="boxGrey">Banner</td>
                  <td class="boxGrey2">
                    <?php echo isset($jbanner['banner2']['src']) && !empty($jbanner['banner2']['src']) ? '<img src="resize.php?from=..' . $jbanner['banner2']['src'] . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
                    <input type="file" name="banner_picture2[]" id="banner_picture2" style="width:250px;" />
                    <input type="hidden" name="banner_picture_name2[]" id="banner_picture_name2" value="<?php echo isset($jbanner['banner2']['src']) && !empty($jbanner['banner2']['src']) ? $jbanner['banner2']['src'] : '' ?>" /><br />(765x225px)
                  </td>
                </tr>
                <tr>
                  <td class="boxGrey">Thuộc tính Nofollow</td>
                  <td width="379" class="boxGrey2"><input type="checkbox" name="banner_nofollow2" id="banner_nofollow2" value="1" <?php echo strtr((isset($jbanner['banner2']['nofollow']) && $jbanner['banner2']['nofollow'] == 1 ? 1 : 0), $statusChecked) ?> /> Kích hoạt?</td>
                </tr>
                <tr>
                  <td class="boxGrey">Trạng thái</td>
                  <td width="379" class="boxGrey2"><input type="checkbox" name="banner_status2" id="banner_status2" value="1" <?php echo strtr((isset($jbanner['banner2']['status']) && $jbanner['banner2']['status'] == 1 ? 1 : 0), $statusChecked) ?> /> Kích hoạt?</td>
                </tr>
              </table>
            </div>
          </div>
        </div>
      </td>
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
	$url = 'category.php?' . (!empty($iscatURL) ? '&caturl=' . $iscatURL : '');
	$condition = ($iscatURL > 0) ? 'parentid = ' . $iscatURL : 'parentid = 0';
    $mang = $dbf->paging(prefixTable.'category',$condition,'ordering, id',$url,$PageNo,$PageSize,$Pagenumber,$ModePaging);
	echo $dbf->panelAction($mang[1]);
	echo $dbf->selectFilter('cboparent', 'name', 'id', $iscatURL, 'Chọn loại danh mục:', prefixTable . 'category', array('onchange' => "redirect('" . $_SERVER['PHP_SELF'] . "?&caturl='+this.value);", 'firstText' => 'Danh mục gốc')) ?>
<!-- view -->
<div id="panelView" class="panelView"><?php $dbf->normalView($col, 'category.php', $mang, $statusAction, '&caturl=' . $iscatURL, $msg) ?></div>
<!-- end view-->
<?php } ?>
<input type="hidden" name="arrayid" id="arrayid" />
</form>
</body>
</html><?php ob_end_flush() ?>