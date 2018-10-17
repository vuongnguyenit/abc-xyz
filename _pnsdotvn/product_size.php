<?php 
define('MOD_DIR_UPLOAD', '/media/images/others/');
define('MOD_ROOT_URL', dirname(dirname(__FILE__)) . '/media/images/others/');

###include('../plugins/editors/ckeditor/ckeditor.php');
###$ckeditor = new plgEditorCkeditor();
require_once('index_table.php');
require_once(dirname(dirname(__FILE__)) . '/plugins/upload/class.upload.php');

$utls = new Utilities();
$col=array('id' => 'ID', 'name' => 'Tên kích thước', 'picture' => 'Hình', 'ordering' => 'Thứ tự', 'created' => 'Ngày tạo', 'modified' => 'Ngày sửa', 'status' => 'Trạng thái');
$iscatURL = isset($_GET['caturl']) && !empty($_GET['caturl']) ? (int) $_GET['caturl'] : 0;
$titleMenu = 'QUẢN LÝ DANH MỤC MÀU SẮC SẢN PHẨM';

if($isDelete)
{
  	$arrayid = substr($_POST['arrayid'], 0, -1);
  	$id_array = explode(',', $arrayid);  
	$affect  = $dbf->deleteDynamic(prefixTable . 'product_size', 'id in (' . $arrayid . ')');
	if($affect > 0) 
	{
		$dbf->deleteDynamic(prefixTable . 'psize_desc', 'id in (' . $arrayid . ')');			
		$msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu.';
	}
}

if($subInsert)
{
	$err = 0;
	if(isset($_POST['name']) && !empty($_POST['name']))
	{
		$data['name'] 	   		= $utls->checkValues($_POST['name']);
		#$data['picture'] 	   	= $_POST['pictureText'];
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 999;		
		$data['created'] 	   	= date('Y-m-d H:i:s');
		$data['created_by']  	= 1;
		$data['modified']    	= $data['created'];
		$data['modified_by'] 	= $data['created_by'];
		
		$dir = $dbf->pnsdotvn_get_dir_upload();				
		if(!empty($_FILES['picture']) && $_FILES['picture']['name'] != '')
		{
			$pic['type']				= 'image';
			$pic['path'] 				= MOD_ROOT_URL;
			$pic['dir']					= $dir;
			$pic['file']				= $_FILES['picture'];		
			$pic['change_name'] 		= true;	
			$pic['w']					= 120;
			#$pic['thum']				= false;			
			#$pic['change_name_thum'] 	= true;
			#$pic['w_thum']				= 120;			
			$upload = new Upload($pic['file']);
			$result = $dbf->pnsdotvn_upload_file($pic, $upload, $utls);
			if(empty($result['err'])) 
			{
				$data['picture'] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
				#$data['filetype'] = $result['type'];
				#$data['filesize'] = $result['size'];
			}
		}
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		
		#$value['en-US']['name'] 		= !empty($_POST['name_en']) ? $utls->checkValues($_POST['name_en']) : $value['vi-VN']['name'];
		#$value['en-US']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['en-US']['name']));
		
		$affect = $dbf->insertTable(prefixTable . 'product_size', $data);
		if($affect > 0) 
		{
			$_id = $affect;			
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$value[$lang]['id'] = $_id;
				$value[$lang]['lang'] = $lang;
				$dbf->insertTable(prefixTable . 'psize_desc', $value[$lang]);
			}		
			$code = $value['vi-VN']['rewrite'] . '-' . $_id;
			$dbf->updateTable(prefixTable . 'product_size', array('code' => $code), 'id = ' . $_id);		
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
		$data['name'] 	   		= $utls->checkValues($_POST['name']);
		#$data['picture']		= $_POST['pictureText'];
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? 1 : 0;
		$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] >= 0 ? (int) $_POST['ordering'] : 999;		
		$data['modified']    	= date('Y-m-d H:i:s');
		$data['modified_by'] 	= 1;				
		
		if(!empty($_FILES['picture']) && $_FILES['picture']['name'] != '')
		{
			$dir = $dbf->pnsdotvn_get_dir_upload();
			$pic['type']				= 'image';
			$pic['path'] 				= MOD_ROOT_URL;
			$pic['dir']					= $dir;
			$pic['file']				= $_FILES['picture'];		
			$pic['change_name'] 		= true;	
			$pic['w']					= 120;
			#$pic['thum']				= false;			
			#$pic['change_name_thum'] 	= true;
			#$pic['w_thum']				= 120;			
			$upload = new Upload($pic['file']);
			$result = $dbf->pnsdotvn_upload_file($pic, $upload, $utls);
			if(empty($result['err'])) 
			{
				$data['picture'] = MOD_DIR_UPLOAD . $dir . '/' . $result['link'];
				#$data['filetype'] = $result['type'];
				#$data['filesize'] = $result['size'];
			}
		}
		
		$value['vi-VN']['name'] 		= $data['name'];
		$value['vi-VN']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['vi-VN']['name']));
		
		#$value['en-US']['name'] 		= !empty($_POST['name_en']) ? $utls->checkValues($_POST['name_en']) : $value['vi-VN']['name'];
		#$value['en-US']['rewrite'] 		= strtolower($utls->generate_url_from_text($value['en-US']['name']));
		
		$affect = $dbf->updateTable(prefixTable . 'product_size', $data, 'id = ' . $_id);
		if($affect > 0)
		{
			$langs = array_keys($value);
	  		foreach($langs as $lang)
			{
				$dbf->updateTable(prefixTable . 'psize_desc', $value[$lang], 'lang = "' . $lang . '" and id = ' . $_id);			
			}			
			$code = $value['vi-VN']['rewrite'] . '-' . $_id;
			$dbf->updateTable(prefixTable . 'product_size', array('code' => $code), 'id = ' . $_id);
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
  	$rst = $dbf->getDynamic(prefixTable . 'product_size', 'id = ' . (int) $_GET['edit'], '');
  	if($rst)
  	{
		$row 		 	= $dbf->nextData($rst);
		$id 	 	 	= $row['id'];
		#$name 		 	= stripslashes($row['name']);
		$picture 	 	= $row['picture'];		
		$ordering 	 	= $row['ordering'];
		$status 	 	= $row['status'];
		
		$vi = $dbf->getArray(prefixTable . 'psize_desc', 'id = "' . $id . '" and lang = "vi-VN"', '', 'stdObject');
		#$en = $dbf->getArray(prefixTable . 'psize_desc', 'id = "' . $id . '" and lang = "en-US"', '', 'stdObject');
  	}
} ?>
<!--<script type="text/javascript" src="../plugins/editors/ckeditor/ckeditor.js"></script>-->
<script type="text/javascript" src="../themes/default/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" >
jQuery(document).ready(function() {  	
	jQuery('#frm').validate({
		rules: {			 
			name: { required: true },
			image: { required: true } 
	    },
	    messages: {	    	
			name: { required: "Nhập tên màu." },
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
              </table>
            </div>
            <!--<div id="tieng-anh">
              <table id="mainTable" cellpadding="0" cellspacing="0">
                <tr>
                  <td class="boxGrey"><?php #echo $col['name'] ?></td>
                  <td class="boxGrey2"><input name="name_en" id="name_en" type="text" class="nd1" value="<?php #echo isset($en[0]->name) && !empty($en[0]->name) ? stripslashes($en[0]->name) : '' ?>" /></td>
                </tr>                
              </table>
            </div>-->
          </div>
        </div>
      </td>
    </tr>
    <tr>
      <td class="boxGrey"><?php echo $col['picture'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><?php #echo $dbf->generateChoicePicture('picture', 'pictureText', $picture) ?>
        <?php echo isset($picture) && !empty($picture) ? '<img src="resize.php?from=..' . $picture . '&w=120&h=120" style="margin-bottom:5px;" /><br />' : '' ?>
        <input type="file" name="picture" id="picture" style="width:250px;" />
      </td>
    </tr>
    <tr>
      <td  class="boxGrey"><?php echo $col['ordering'] ?></td>
      <td class="boxGrey2"><input maxlength="5" onKeyPress="return nhapso(event,'ordering');" name="ordering" id="ordering" type="text" class="nd" value="<?php echo isset($ordering) && !empty($ordering) ? $ordering : 999 ?>" /></td>
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
	$url = 'product_size.php?';
    $mang = $dbf->paging(prefixTable . 'product_size', '1=1', 'ordering, id', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
	echo $dbf->panelAction($mang[1]) ?>
<!-- view -->
<div id="panelView" class="panelView"><?php $dbf->normalView($col, 'product_size.php', $mang, $statusAction, '&caturl=' . $iscatURL, $msg = '') ?></div>
<!-- end view-->
<?php } ?>
  <input type="hidden" name="arrayid" id="arrayid" />
</form>
</body>
</html><?php ob_end_flush() ?>