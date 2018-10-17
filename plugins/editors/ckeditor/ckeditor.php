<?php

class plgEditorCkeditor {

	
	/**
	 * Method to handle the onInitEditor event.
	 *  - Initializes the fckeditor Lite WYSIWYG Editor
	 *
	 * @access public
	 * @return string JavaScript Initialization string
	 * @since 1.5
	 */
	function doInit()
	{
		
	}

	/**
	 * fckeditor Lite WYSIWYG Editor - get the editor content
	 *
	 * @param string 	The name of the editor
	 */
	function doGetContent( $editor ) {
			
		return " CKEDITOR.instances.$editor.getData(); ";
	}

	/**
	 * fckeditor Lite WYSIWYG Editor - set the editor content
	 *
	 * @param string 	The name of the editor
	 */
	function doSetContent( $editor, $html ) {
		//return " oFCKeditor.InsertHtml = '" .  htmlentities($html) . "';alert('".$html."');";
	}

	/**
	 * fckeditor Lite WYSIWYG Editor - copy editor content to form field
	 *
	 * @param string 	The name of the editor
	 */
	function doSave( $editor ) { /* We do not need to test for anything */	}

	/**
	 * fckeditor Lite WYSIWYG Editor - display the editor
	 *
	 * @param string The name of the editor area
	 * @param string The content of the field
	 * @param string The name of the form field
	 * @param string The width of the editor area
	 * @param string The height of the editor area
	 * @param int The number of columns for the editor area
	 * @param int The number of rows for the editor area
	 * @param mixed Can be boolean or array.
	 */
	function doDisplay( $name, $content, $width, $height, $toolbar = "Default" )
	{
		$html = '';
      /* Generate the Output */
		if (is_numeric( $width )) 	{		$width .= 'px';		}
		if (is_numeric( $height )) 	{		$height .= 'px';	}		
		
		$html .= "\n".'<textarea name="'.$name.'" id="'.$name.'" cols="75" rows="20" style="width:'.$width.'; height:'.$height.';" >' .$content.   '</textarea>';
		 		
		$html .= "\n"."<script type='text/javascript'> 
		var text_{$name} = CKEDITOR.replace( '{$name}',
		{
			skin : 'moono',
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
?>