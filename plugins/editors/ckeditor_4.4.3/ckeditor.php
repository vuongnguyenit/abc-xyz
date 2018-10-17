<?php
class plgEditorCkeditor 
{
	function doDisplay( $name, $content, $width, $height, $toolbar = "Default" )
	{
		$html = '';
		if (is_numeric( $width )) 	{ $width .= 'px'; }
		if (is_numeric( $height )) 	{ $height .= 'px'; }		
		
		$html .= "\n".'<textarea name="'.$name.'" id="'.$name.'" cols="75" rows="20" style="width:'.$width.'; height:'.$height.';" >' .$content.   '</textarea>';
		 		
		$html .= "\n"."<script type='text/javascript'> 
		var text_{$name} = CKEDITOR.replace( '{$name}',
		{
			skin : 'v2',
			language : 'vi',
			toolbar : '".$toolbar."',
			height : '".$height."',
			width : '".$width."' ,
			
			filebrowserBrowseUrl : '" . "http://" . $_SERVER['HTTP_HOST'] . "/_pnsdotvn/popup_media.php?type=editor&eType=ckeditor&obj=file',
			filebrowserImageBrowseUrl : '" . "http://" . $_SERVER['HTTP_HOST'] . "/_pnsdotvn/popup_media.php?type=editor&eType=ckeditor&obj=image&folder=images',
			filebrowserFlashBrowseUrl : '" . "http://" . $_SERVER['HTTP_HOST'] . "/_pnsdotvn/popup_media.php?type=editor&eType=ckeditor&obj=flash&folder=flash'
			
		});
		</script> " ;		 

		return $html;
	}
	

}