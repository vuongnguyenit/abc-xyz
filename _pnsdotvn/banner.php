<?php 
define('MOD_DIR_UPLOAD', '/media/images/advertising/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/advertising/');

require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . "/plugins/upload/class.upload.php");

$col = array('id' => 'ID', 'name' => 'Tên banner', 'bannerurl' => 'Banner', 'clickurl' => 'Liên kết', 'target' => 'Đích đến', 'position' => 'Vị trí', 'status' => 'Trạng thái', 'ordering' => 'Thứ tự');
$iscatURL = isset($_GET['caturl']) && !empty($_GET['caturl']) ? $_GET['caturl'] : '';
$clickurl = '#';
$titleMenu = 'QUẢN LÝ DANH SÁCH BANNER';

error_reporting(E_ALL);

if($isDelete)
{
	$arrayid = substr($_POST['arrayid'], 0, -1);
	$id_array = explode(',',$arrayid);
	$affect = $dbf->deleteDynamic(prefixTable . 'banner', 'id in (' . $arrayid . ')');
    if($affect > 0) $msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu.';
}

if($subInsert)
{
	$err = 0;
	if(!empty($_POST['name']) && !empty($_POST['target']) && !empty($_POST['position']))
	{
		$data['name']        	= strip_tags(trim($_POST['name']));
		###$data['bannerurl']	= $_POST['pictureText'];
		$data['clickurl']    	= trim($_POST['clickurl']);
		$data['target']      	= in_array($_POST['target'], array_keys($arrayTarget)) ? $_POST['target'] : '_blank';
		$data['position']    	= in_array($_POST['position'], array_keys($arrayBannerPosition)) ? $_POST['position'] : '';
		#$data['type']        	= 'image';
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 1;
		$data['nofollow'] 	   	= isset($_POST['nofollow']) && $_POST['nofollow'] == 1 ? 1 : 0;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;	
		$data['created']     	= date('Y-m-d H:i:s');
		$data['created_by']  	= 1;
		$data['modified']    	= $data['created'];
		$data['modified_by'] 	= $data['created_by'];			
		
		$dir = $dbf->pnsdotvn_get_dir_upload();				
		if(!empty($_FILES['banner']) && $_FILES['banner']['name'] != '')
		{
			$pic['type']				= 'image';
			$pic['path'] 				= MOD_ROOT_URL;
			$pic['dir']					= $dir;
			$pic['file']				= $_FILES['banner'];		
			$pic['change_name'] 		= true;	
			$pic['w']					= 1200;
			#$pic['thum']				= false;			
			#$pic['change_name_thum'] 	= true;
			#$pic['w_thum']				= 120;			
			$upload = new Upload($pic['file']);
			$result = $dbf->pnsdotvn_upload_file($pic, $upload, $utls);
			if(empty($result['err'])) 
			{
				$data['bannerurl'] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
				#$data['filetype'] = $result['type'];
				#$data['filesize'] = $result['size'];
			}
		}
												
		$affect = $dbf->insertTable(prefixTable . 'banner', $data);
		if($affect > 0) $msg = 'Đã thêm dòng (' . $affect . ') trong cơ sở dữ liệu.';
	} else
	{
		$msg = 'Vui lòng nhập đầy đủ dữ liệu.';
		$err = 1;
	}
}

if($subUpdate)
{
	$err = 0;
	if(!empty($_POST['name']) && !empty($_POST['target']) && !empty($_POST['position']))
	{
		$data['name']        	= strip_tags(trim($_POST['name']));
		###$data['bannerurl'] 	= $_POST['pictureText'];
		$data['clickurl']    	= trim($_POST['clickurl']);
		$data['target']      	= in_array($_POST['target'], array_keys($arrayTarget)) ? $_POST['target'] : '_blank';
		$data['position']    	= in_array($_POST['position'], array_keys($arrayBannerPosition)) ? $_POST['position'] : '';
		#$data['type']        	= 'image';
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 1;
		$data['nofollow'] 	   	= isset($_POST['nofollow']) && $_POST['nofollow'] == 1 ? 1 : 0;
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;	
		$data['modified']    	= date('Y-m-d H:i:s');
		$data['modified_by'] 	= 1;
								
		if(!empty($_FILES['banner']) && $_FILES['banner']['name'] != '')
		{
			$dir = $dbf->pnsdotvn_get_dir_upload();
			$pic['type']				= 'image';
			$pic['path'] 				= MOD_ROOT_URL;
			$pic['dir']					= $dir;
			$pic['file']				= $_FILES['banner'];		
			$pic['change_name'] 		= true;	
			$pic['w']					= 1200;
			#$pic['thum']				= false;			
			#$pic['change_name_thum'] 	= true;
			#$pic['w_thum']				= 120;			
			$upload = new Upload($pic['file']);
			$result = $dbf->pnsdotvn_upload_file($pic, $upload, $utls);
			if(empty($result['err'])) 
			{
				$data['bannerurl'] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
				#$data['filetype'] = $result['type'];
				#$data['filesize'] = $result['size'];
			}
		}
		
		$affect = $dbf->updateTable(prefixTable . 'banner', $data, 'id = ' . (int) $_GET['edit']);
		if($affect > 0) $msg = 'Đã cập nhật (' . $affect . ') dòng trong cơ sở dữ liệu.';
	} else
	{
		$msg = 'Vui lòng nhập đầy đủ dữ liệu.';
		$err = 1;
	}
}

if($isEdit)
{
	$rst = $dbf->getDynamic(prefixTable . 'banner', 'id = ' . (int) $_GET['edit'], '');
	if($rst)
	{
		$row 		= $dbf->nextData($rst);
		$id 		= $row['id'];
		$name 		= stripslashes($row['name']);
		$bannerurl 	= $row['bannerurl'];
		$clickurl 	= $row['clickurl'];
		$target 	= $row['target'];
		$position 	= $row['position'];		
		$ordering 	= $row['ordering'];
		$nofollow 	= $row['nofollow'];
		$status 	= $row['status'];
	}
} ?>
<script type="text/javascript" src="../themes/default/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" >
jQuery(document).ready(function() {  	
	jQuery('#frm').validate({
		rules: {			 
			name: { required: true },
			position: { required: true },
			target: { required: true },
			image: { required: true } 
	    },
	    messages: {	    	
			name: { required: "Nhập tên banner." },
			position: { required: "Chọn vị trí banner." },
			target: { required: "Chọn loại đích đến." }, 
			image: { required: "Chọn banner." }
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
      <td class="boxGrey"><?php echo $col['position'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><?php echo $dbf->SelectWithNormalArray($arrayBannerPosition, (isset($position) && !empty($position)) ? $position : '', 'position', array('firstText' => 'Chọn vị trí banner', 'class' => 'cbo')) ?></td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col["clickurl"] ?></td>
      <td class="boxGrey2"><input name="clickurl" id="clickurl" type="text" class="nd1" value="<?php echo isset($clickurl) && !empty($clickurl) ? $clickurl : '#' ?>" /> Vd: http://www.pns.vn </td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col['target'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><?php echo $dbf->SelectWithNormalArray($arrayTarget, (isset($target) && !empty($target) ? $target : ''), 'target', array('firstText' => 'Chọn loại đích đến', 'class' => 'cbo')) ?></td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col['bannerurl'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2">
	    <?php #echo $dbf->generateChoicePicture('bannerurl', 'pictureText', (isset($bannerurl) && !empty($bannerurl)) ? $bannerurl: $picture) ?>
        <?php echo isset($bannerurl) && !empty($bannerurl) ? '<img src="resize.php?from=..' . $bannerurl . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
        <input type="file" name="banner" id="banner" style="width:250px;" />
      </td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col['ordering'] ?></td>
      <td class="boxGrey2"><input type="text" name="ordering" id="ordering" class="nd1" value="<?php echo isset($ordering) && !empty($ordering) ? $ordering : 1 ?>" maxlength="5" onKeyPress="return nhapso(event,'position');" /></td>
    </tr>
    <tr>
      <td class="boxGrey">Hỗ trợ SEO</td>
      <td width="379" class="boxGrey2"><input type="checkbox" name="nofollow" id="nofollow" value="1" <?php echo strtr((isset($nofollow) && $nofollow == 1 ? $status : 0), $statusChecked) ?> />  Nofollow?</td>
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
	$url = 'banner.php?' . (!empty($iscatURL)) ? '&caturl=' . $iscatURL : '';
	$condition = !empty($iscatURL) ? 'position = "' . $iscatURL . '"' : '1=1';
	$mang = $dbf->paging(prefixTable . 'banner', $condition, 'id desc', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
	echo $dbf->panelAction($mang[1]);
	echo $dbf->displaySelect($arrayBannerPosition, $iscatURL, 'cbotype', array('text' => 'Vị trí', 'onchange' => "redirect('".$_SERVER['PHP_SELF']."?&caturl='+this.value);", 'firstText' => 'Chọn vị trí banner')) ?>
<!-- view -->
<div id="panelView" class="panelView">
  <?php $dbf->normalView($col, "banner.php", $mang, $statusAction, "&caturl=" . $iscatURL, $msg) ?>
</div>
<!-- end view-->
<?php } ?>
<input type="hidden" name="arrayid" id="arrayid" />
</form>
</body></html><?php ob_end_flush() ?>