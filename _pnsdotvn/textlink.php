<?php require_once('index_table.php');
$col = array('id' => 'ID', 'name' => 'Tên liên kết', /*'clickurl' => 'Liên kết', 'target' => 'Đích đến',*/ 'status' => 'Trạng thái', /*'ordering' => 'Thứ tự'*/);
$iscatURL = isset($_GET['caturl']) && !empty($_GET['caturl']) ? $_GET['caturl'] : '';
$clickurl = '#';
$titleMenu = 'QUẢN LÝ LIÊN KẾT';

if($isDelete)
{
	$arrayid = substr($_POST['arrayid'], 0, -1);
	$id_array = explode(',', $arrayid);
	$affect = $dbf->deleteDynamic(prefixTable. 'textlink', 'id in (' . $arrayid . ')');
    if($affect > 0)
	$msg = 'Đã xóa (' . count($id_array) . ') dòng trong cơ sở dữ liệu.';
}

if($subInsert)
{
	$err = 0;
	if(!empty($_POST['name']) && !empty($_POST['content']) && !empty($_POST['position']))
	{
		$data['name'] 			= addslashes(trim($_POST['name']));
		#$data['clickurl'] 		= trim($_POST['clickurl']);
		#$data['target'] 		= in_array($_POST['target'], array_keys($arrayTarget)) ? $_POST['target'] : '_blank';
		#$data['nofollow'] 	   	= isset($_POST['nofollow']) && $_POST['nofollow'] == 1 ? (int) $_POST['nofollow'] : 0;
		#$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] > 1 ? (int) $_POST['ordering'] : 1;	
		$data['content']		= addslashes($dbf->compressHtml(trim($_POST['content'])));
		$data['position']    	= in_array($_POST['position'], array_keys($arrayTextlinkPosition)) ? $_POST['position'] : '';	
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? (int) $_POST['status'] : 0;
		$data['created'] 		= date('Y-m-d H:i:s');
		$data['created_by'] 	= 1;
		$data['modified'] 		= $data['created'];
		$data['modified_by'] 	= $data['created_by'];
		$affect = $dbf->insertTable(prefixTable . 'textlink', $data);
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
	if(isset($_GET['edit']) && !empty($_GET['edit']) && !empty($_POST['name']) && !empty($_POST['content']) && !empty($_POST['position']))
	{
		$_id					= (int) $_GET['edit'];
		$data['name'] 			= addslashes(trim($_POST['name']));
		#$data['clickurl'] 		= trim($_POST['clickurl']);
		#$data['target'] 		= in_array($_POST['target'], array_keys($arrayTarget)) ? $_POST['target'] : '_blank';
		#$data['nofollow'] 	   	= isset($_POST['nofollow']) && $_POST['nofollow'] == 1 ? (int) $_POST['nofollow'] : 0;
		#$data['ordering']    	= isset($_POST['ordering']) && $_POST['ordering'] > 1 ? (int) $_POST['ordering'] : 1;	
		$data['content']		= addslashes($dbf->compressHtml(trim($_POST['content'])));
		$data['position']    	= in_array($_POST['position'], array_keys($arrayTextlinkPosition)) ? $_POST['position'] : '';	
		$data['status'] 	   	= isset($_POST['status']) && $_POST['status'] == 1 ? (int) $_POST['status'] : 0;
		$data['modified'] 		= date('Y-m-d H:i:s');
		$data['modified_by'] 	= 1;	
		$affect = $dbf->updateTable(prefixTable. 'textlink', $data, 'id = ' . $_id);
		if($affect > 0) $msg = 'Đã cập nhật (' . $affect . ') dòng trong cơ sở dữ liệu.';
	} else
	{
		$msg = 'Vui lòng nhập đầy đủ dữ liệu.';
		$err = 1;
	}
}

if($isEdit)
{
	$rst = $dbf->getDynamic(prefixTable . 'textlink','id = '. (int) $_GET['edit'],'');
	if($rst)
	{
		$row 		= $dbf->nextData($rst);
		$id 		= $row['id'];
		$name 		= stripslashes($row['name']);
		#$clickurl 	= $row['clickurl'];
		#$target 	= $row['target'];
		$content 	= stripslashes($row['content']);
		$position 	= $row['position'];
		$status 	= $row['status'];
		#$nofollow 	= $row['nofollow'];
		#$ordering 	= $row['ordering'];
	}
} ?>
<script type="text/javascript" src="../themes/default/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.js"></script>
<script language="javascript" >
jQuery(document).ready(function() {  	
	jQuery('#frm').validate({
		rules: {			 
			name: {
				required: true 
			},
			/*clickurl: {
				required: true 
			},
			target: {
				required: true 
			}*/ 
			content: { required: true },
			position: { required: true }
	    },
	    messages: {	    	
			name: {
				required: "Nhập tên liên kết." 
			},
			/*clickurl: {
				required: "Nhập đường dẫn liên kết." 
			},
			target: {
				required: "Chọn đích đến." 
			}*/	
			content: { required: "Nhập nội dung." },		 
			position: { required: "Chọn vị trí banner." }		 
		}		
	});	
});
</script>
<?php $dbf->normalForm('frm', array('action' => '', 'method' => 'post', 'class' => 'validate')) ?>
<?php if($isEdit || $isInsert) { ?>
<script type="text/javascript" src="../plugins/editors2/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="../plugins/editors2/ckfinder/ckfinder.js"></script>
<!-- form -->
<div id="panelForm" class="panelForm">
  <table id="mainTable" cellpadding="0" cellspacing="0">
    <?php echo $dbf->returnTitleMenu($titleMenu) ?>
    <tr><td colspan="4" class="txtdo" align="center"><?php echo $msg ?></td></tr>
    <tr>
      <td class="boxGrey"><?php echo $col['name'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><input name="name" id="name" type="text" class="nd1" value="<?php echo isset($name) && !empty($name) ? $name : '' ?>" /></td>
    </tr>
    <tr>
      <td class="boxGrey">Nội dung <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2">
        <textarea name="content" id="content" cols="75" rows="20"><?php echo (isset($content) && !empty($content)) ? stripslashes($content) : '' ?></textarea>
		<script type="text/javascript">
        var editor = CKEDITOR.replace( 'content', {
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
      <td class="boxGrey">Vị trí <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><?php echo $dbf->SelectWithNormalArray($arrayTextlinkPosition, (isset($position) && !empty($position)) ? $position : '', 'position', array('firstText' => 'Chọn vị trí textlink', 'class' => 'cbo')) ?></td>
    </tr>  
    <!--<tr>
      <td class="boxGrey"><?php #echo $col['clickurl'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><input name="clickurl" id="clickurl" type="text" class="nd1" value="<?php #echo isset($clickurl) && !empty($clickurl) ? $clickurl : '' ?>" /> Vd. http://pns.vn</td>
    </tr>
    <tr>
      <td class="boxGrey"><?php #echo $col['target'] ?> <span style="color:#DA251C">(*)</span></td>
      <td class="boxGrey2"><?php #echo $dbf->SelectWithNormalArray($arrayTarget, (isset($target) && !empty($target) ? $target : ''), 'target', array('firstText' => 'Chọn loại đích đến', 'class' => 'cbo')) ?></td>
    </tr>
    <tr>
      <td class="boxGrey">Hỗ trợ SEO</td>
      <td width="379" class="boxGrey2"><input type="checkbox" name="nofollow" id="nofollow" value="1" <?php echo strtr((isset($nofollow) && $nofollow == 1 ? $status : 0), $statusChecked) ?> />  Nofollow?</td>
    </tr>
    <tr>
      <td class="boxGrey"><?php #echo $col['ordering'] ?></td>
      <td class="boxGrey2"><input maxlength="5" onKeyPress="return nhapso(event,'ordering');" name="ordering" id="ordering" type="text" class="nd" value="<?php #echo isset($ordering) && !empty($ordering) ? $ordering : 1 ?>" /></td>
    </tr>-->
    <tr>
      <td class="boxGrey"><?php echo $col['status'] ?></td>
      <td width="379" class="boxGrey2"><input type="checkbox" name="status" id="status" value="1" <?php echo strtr((isset($status) && $status == 1 ? $status : 0), $statusChecked) ?> />
        Kích hoạt? </td>
    </tr>
    <tr>
      <td class="boxGrey">&nbsp;</td>
      <td height="1" align="center" class="boxGrey2"><div id="insert"><?php echo (($isInsert) ? $dbf->stateInsert() : (($isEdit) ? $dbf->stateUpdate() : '')) ?></div></td>
    </tr>
    <tr>
      <td class="boxGrey" colspan="2"><span style="color:#DA251C">(*)</span> Bắt buộc nhập.</td>
    </tr>
  </table>
</div>
<?php if($isInsert && !empty($msg) && $err == 0) echo '<script type="text/javascript">huy();</script>' ?>
<!-- end Form-->
<?php } ?>
<?php if(!$isEdit && !$isInsert)
{
  echo $dbf->returnTitleMenuTable($titleMenu);
  $url = 'textlink.php?';
  $mang=$dbf->paging(prefixTable . 'textlink', '1=1', 'id', $url, $PageNo, $PageSize, $Pagenumber, $ModePaging);
  echo $dbf->panelAction($mang[1]) ?>
<!-- view -->
<div id="panelView" class="panelView"><?php $dbf->normalView($col, 'textlink.php', $mang, $statusAction, '&caturl=' . $iscatURL, $msg) ?></div>
<!-- end view-->
<?php } ?>
  <input type="hidden" name="arrayid" id="arrayid" />
</form>
</body></html><?php ob_end_flush() ?>