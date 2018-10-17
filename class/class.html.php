<?php
class HTML{

	/*
	-----------------------------------------------------------------*/
	function checkBrowser(){
		$browser=$_SERVER["HTTP_USER_AGENT"];
		if(strpos($browser,"Firefox")==true){
			return "FF";
		}
		if(strpos($browser,"MSIE")==true){
			return "IE";
		}
	}
	/*
	-----------------------------------------------------------------*/
	function docType(){
		echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Transitional//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd\">\n";
	}
	/*
	-----------------------------------------------------------------*/
	function noscript($url){
		echo "<noscript><meta http-equiv=\"refresh\" content=\"0;URL=$url\" /></noscript>\n";
	}
	function present($present){
		echo nl2br(stripslashes($present));
	}
	function title($title){
		echo "<title>$title</title>\n";		
	}
	function description($description){		
		echo "<meta name=\"description\" content=\"$description\" />\n";		
	}
	function keywords($keywords){		
		echo "<meta name=\"keywords\" content=\"$keywords\" />\n";	
	}
	function copyright($copyright){		
		echo "<meta name=\"copyright\" content=\"$copyright\" />\n";		
	}
	function openHead()
	{
		echo "\n<head>\n";
	}
	function closeHead()
	{
		echo "\n</head>\n";
	}
	function openScript()
	{
		echo "<script language=\"javascript\">";
		echo "<!--\n";
	}
	function closeScript()
	{
		echo "\n-->\n";
		echo "</script>";
	}
	function displayJavascript($contentScript)
	{
		$this->openScript();
		echo $contentScript;
		$this->closeScript();
	}
	//
	function displayHead($charset="utf-8",$second=3600,$title="",$keywords="",$description="",$copyright="",$linkicon="",$linkcss=null,$linkjs=null)
	{
		$this->title($title);		
		$this->metaEncoding($charset);
		$this->metaRefresh($second);		
		$this->keywords($keywords);
		$this->description($description);
		$this->copyright($copyright);
		$this->otherMetas();
		$this->linkIcon($linkicon);
		foreach($linkcss as $value) $this->linkCSS($value)."\n";
		foreach($linkjs as $value) $this->linkJS($value)."\n";		
		
	}
	//
	/*
	function noscript()
	{
		$str="<noscript>
		<div style='width:650px;position:relative;margin:0 auto;top:100px;border:1px solid #ccc'>
			<div style='width:600px;position:relative;margin:0 auto;margin-top:20px;'>
			<h3>Trình duyệt bạn đang xài không hỗ trợ Javascript.</h>
			<p>Vui lòng kiểm tra lại các mục sau đây:</p>
			<p class='ie'>Đối với IE:</p>
			<p>
				<ul>
					<li>Vui lòng vào mục:Tool->Internet option->Chọn tab Security->Chọn Default level</li>
					<li>Vui lòng vào mục:Tool->Internet option->Chọn tab Privacy->Hạ xuống mức Medium</li>
					<li>Nâng cấp IE6.0 trở lên</li>			
				</ul>		
			</p>
			<p class='ie'>Đối với FireFox:</p>
			<p>
				<ul>
					<li>Vui lòng vào mục:Tool->Options->Chọn tab Content-> Chọn Enable Javascript</li>
					<li>Nếu bạn xài Add-ons của Firefox(No Script,Yes script,...). Vui lòng disable chúng đi, vì đây là những chương trình không cho chạy javascript</li>			
					<li>Nâng cấp Firefox 1.0 trở lên</li>
				</ul>		
			</p>
			</div>	
			<div style='clear:both'></div>
			</div>
		</noscript>";
		return $str;
	}__________________________________________________________________________________________________________________________________________________REMOVED*/
		
	function geoMetas($geo = '', $lang = 'vi')
	{
		if(!empty($geo) && is_array($geo) && count($geo)>0) {
			echo "<meta name=\"geo.placename\" content=\"" . $geo['placename'] . "\" />\n";
			echo "<meta name=\"geo.position\" content=\"" . $geo['latitude'] . ";" . $geo['longitude'] . "\" />\n";
			echo "<meta name=\"geo.region\" content=\"" . $geo['region'] . "\" />\n";
			echo "<meta name=\"ICBM\" content=\"" . $geo['latitude'] . "," . $geo['longitude'] . "\" />\n";
			echo "<meta name=\"DC.title\" lang=\"" . $lang . "\" content=\"" . $geo['title'] . "\">\n";
			echo "<meta name=\"DC.description\" lang=\"" . $lang . "\" content=\"" . $geo['description'] . "\">\n";
		}
	}	
	
	function otherMetas()
	{
		echo "<meta name=\"robots\" content=\"noodp,index,follow\" />\n";
		echo "<meta name=\"author\" content=\"Phuong Nam Solution Co,. LTD\" />\n";
		echo "<meta name=\"geography\" content=\"50/2B Le Hoang Phai St, Ward 17, Go Vap District, Ho Chi Minh City, Viet Nam\" />\n";
		echo "<meta name=\"city\" content=\"Ho Chi Minh\" />\n";
		echo "<meta name=\"country\" content=\"Vietnam\" />\n";
		echo "<meta name=\"copyright\" content=\"www.pns.vn\" />\n";
		echo "<meta name=\"distribution\" content=\"Global\" />\n";
		//echo "<meta name=\"google-site-verification\" content=\"lEXkD_56Iz9A7Dmrq6lS9-fTgsqnlg-gZCw3oGsQOa4\" />\n";
        //echo "<meta name=\"alexaVerifyID\" content=\"efo5dU3GhLS8bMzo7FgiXpknZPA\" />\n"; /* Alexa Verify */
		//echo "<meta name=\"y_key\" content=\"50b2d7c456423ed0\" />\n"; /* Yahoo siteexplorer */
		//echo "<meta http-equiv=\"Content-Language\" content=\"en-us\" />\n";
		//echo "<meta http-equiv=\"Page-Exit\" content=\"blendTrans(Duration=0.7)\"/>\n";
		//echo "<meta http-equiv=\"Page-Enter\" content=\"blendTrans(Duration=0.7)\"/>\n";
	}
	
	/*
	-----------------------------------------------------------------*/
	function metaExpires($expire = null){
		echo "<meta http-equiv=\"Expires\" content=\"" . $expire . "\" />\n";
	}

	/*
	-----------------------------------------------------------------*/
	function metaEncoding($charset){		
		echo "<meta http-equiv=\"content-type\" content=\"text/html;charset=$charset\" />\n";		
	}

	/*
	-----------------------------------------------------------------*/
	function metaRefresh($second){
		echo "<meta http-equiv=\"refresh\" content=\"$second\" />\n";		
	}
	
	/*
	-----------------------------------------------------------------*/
	function metaContentLanguage($lang = 'vi'){
		echo "<meta http-equiv=\"Content-Language\" content=\"$lang\" />\n";		
	}
	
	/*
	-----------------------------------------------------------------*/
	function metaLanguage($lang = 'Vietnamese'){
		echo "<meta name=\"language\" content=\"$lang\" />\n";		
	}
	
	/*
	-----------------------------------------------------------------*/
	function linkIcon($url){
		echo "<link rel=\"icon\" type=\"image/x-icon\" href=\""./*HOST.*/"$url\" />\n";
		echo "<link rel=\"shortcut icon\" type=\"image/x-icon\" href=\""./*HOST.*/"/$url\" />\n";
	}

	/*
	-----------------------------------------------------------------*/
	function linkCSS($url){		
		echo "<link rel=\"stylesheet\" type=\"text/css\" href=\""./*HOST.*/"$url\" />\n";
	}

	/*
	-----------------------------------------------------------------*/
	function linkJS($url){		
		echo "<script type=\"text/javascript\" src=\""./*HOST.*/"$url\"></script>\n";
	}	
	/*
	-----------------------------------------------------------------*/
	function link($title,$url,$array=null)
	{
		$att = '';
		if(!empty($array) AND count($array)>0)
			foreach($array as $name => $value)
				$att .= ' ' . $name . '="' . $value . '"';
		$html = '<a href="' . $url . '"' . $att . '>' . stripcslashes($title) . '</a>';
		return $html;
	}


	/*
	-----------------------------------------------------------------*/
	function image($url,$array=null,$br=null){
		?>
		<img src="<?php echo $url; ?>"
			<?php
			if($array["width"]!=""){
				?>
				width="<?php echo $array["width"]; ?>"
				<?php
			}
			if($array["height"]!=""){
				?>
				height="<?php echo $array["height"]; ?>"
				<?php
			}
			?>
			id="<?php echo $array["id"]; ?>"
			name="<?php echo $array["name"]; ?>"
			class="<?php echo $array["class"]; ?>"
			alt="<?php echo $array["alt"]; ?>"
			onclick="<?php echo $array["onclick"]; ?>"
			onmouseover="<?php echo $array["onmouseover"]; ?>"
			onmouseout="<?php echo $array["onmouseout"]; ?>"
		/><?php echo $br; ?>
		<?php
	}

	/*
	-----------------------------------------------------------------*/
	function imageLink($image,$url,$array=null){
		?>
		<a href="<?php echo $url; ?>"
			id="<?php echo $array["id"]; ?>"
			class="<?php echo $array["class"]; ?>"
			target="<?php echo $array["target"]; ?>">
			<?php
			$value=array(
				"width"=>$array["width"],
				"height"=>$array["height"],
				"alt"=>$array["alt"],
				"class"=>$array["classImg"],
				"onmouseover"=>$array["onmouseover"],
				"onmouseout"=>$array["onmouseout"]
			);
			$this->image($image,$value);
			?>
		</a>
		<?php
	}

	/*	-----------------------------------------------------------------*/
	function normalForm($idName, $array = '', $html = '')
	{
		$html = '<form id="' . $idName . '" name="' . $idName . '" method="post"' . (isset($array['action']) && !empty($array['action']) ? ' action="' . $array['action'] . '"' : '') . ' enctype="application/x-www-form-urlencoded"' . (isset($array['class']) && !empty($array['class']) ? ' class="' . $array['class'] . '"' : '') . (isset($array['onsubmit']) && !empty($array['onsubmit']) ? ' onsubmit="' . $array['onsubmit'] . '"' : '') . '>';
		echo $html;		
	}
	
	function FormUpload($idName,$array=null)
	{
		$html = '<form id="' . $idName . '" name="' . $idName . '" method="post"' . (isset($array['action']) && !empty($array['action']) ? ' action="' . $array['action'] . '"' : '') . ' enctype="multipart/form-data" ' . (isset($array['class']) && !empty($array['class']) ? ' class="' . $array['class'] . '"' : '') . (isset($array['onsubmit']) && !empty($array['onsubmit']) ? ' onsubmit="' . $array['onsubmit'] . '"' : '') . '>';
		echo $html;		
	}
	
	function closeForm()
	{		
		echo '</form>';		
	}
	
	/*	-----------------------------------------------------------------*/
	function input($idName, $array = '')
	{
		$att = '';
		if(!empty($array) && is_array($array) && count($array) > 0)
		  foreach($array as $name => $value)
			$att .= ' ' . $name . '="' . $value . '"';		 
		$html = '<input id="' . $idName . '" name="' . $idName . '"' . $att . ' />';
		return $html;		
	}
	
	//	

	/*
	-----------------------------------------------------------------*/
	function select($idName,$array=null){
		?>
		<select id="<?php echo $idName; ?>" name="<?php echo $idName; ?>" class="<?php echo $array["class"]; ?>">
			<?php
			$numOption=($array["class"]=="")?count($array):count($array)-1;
			for($i=1;$i<=$numOption;$i++){
				?>
				<option value="<?php echo $array["value".$i]; ?>"/><?php echo $array["value".$i]; ?></option>
				<?php
			}
			?>
		</select>
		<?php
	}
	
	function checkbox($idName,$value,$text,$array=null)
	{
		$att = '';
		$checked = '';
		if(!empty($array) AND count($array)>0)
		  foreach($array as $name => $val)
			if($name=='checked') $checked = ' checked="checked"';
			else $att .= ' ' . $name . '="' . $val . '"';
		$html = '<input type="checkbox" name="' . $idName . '" id="' . $idName . '" value="' . $value . '"' . $checked . $att . ' />';
		$html .= ' ' . $text;
		return $html;		
	}
	
	function radio($idName,$value,$text,$array=null)
	{		
		$str="<input type=\"radio\" 
		name=\"".$idName."\" 
		id=\"".$idName."\" 
		value=\"".$value."\" 
		onclick=\"".$array["onclick"]."\" ".(($array["checked"]=="checked")?"checked='checked'":"")."' 
        style=\"".$array["style"]."\" 
		class=\"".$array["class"]."\"/>";
		$str.=" ".$text;
		return $str;		
	}
	
	function radio_input($id,$name,$value,$text,$array=null)
	{		
		$str="<input type=\"radio\" 
		name=\"".$name."\" 
		id=\"".$id."\" 
		value=\"".$value."\" 
		onclick=\"".$array["onclick"]."\" ".(($array["checked"]=="checked")?"checked='checked'":"")."' 
        style=\"".$array["style"]."\" 
		class=\"".$array["class"]."\"/>";
		$str.=" ".$text;
		return $str;		
	}

	/*	-----------------------------------------------------------------*/
	function textArea($idName, $array='')
	{
		$att = '';
		$txt = '';
		if(!empty($array) AND count($array)>0)
			foreach($array as $name => $value)
				if($name=='value') $txt = $value;
				else $att .= ' ' . $name . '="' . $value . '"';
		$html = '<textarea id="' . $idName . '" name="' . $idName . '"' . $att . '>' . $txt . '</textarea>';
		return $html;		
	}

	/*	-----------------------------------------------------------------*/
	function redirectProtocol($protocol="http://",$url){	
		echo "<script type=\"text/javascript\">window.location='".$protocol.$url."';</script>";}
	
	/*	-----------------------------------------------------------------*/
	
	function redirectURL($url){	
		echo "<script type=\"text/javascript\">window.location='".$url."';</script>";	}
	
	/*	-----------------------------------------------------------------*/
	
	function QueryString(){
		return (string)$_SERVER['QUERY_STRING'];}
	
	/*	-----------------------------------------------------------------*/
	
	function currentPage(){
		return (string)$_SERVER['PHP_SELF'];}

	/*	----------------------------------------------------------------- */
	
	function getFullURL(){
		return (string)$_SERVER["REQUEST_URI"];	}
	/*	----------------------------------------------------------------- */
	function getHost(){
		return (string)$_SERVER['HTTP_HOST'];}
	/*	----------------------------------------------------------------- */
	function getProtocol()
	{
		return (string)$_SERVER['SERVER_PROTOCOL'];
	}
	function linkHost()
	{
		return "http://".$this->getHost().$this->getFullURL();
	}
	function linkHostEncoding()
	{
		return base64_encode("http://".$this->getHost().$this->getFullURL());
	}
	/*	----------------------------------------------------------------- */
	
	function getLastURL(){
		return (string)$_SERVER["HTTP_REFERER"]; }

	/*	----------------------------------------------------------------- */
	
	function reloadPage(){		
		echo "<script type=\"text/javascript\">window.location.reload();</script>";}

	/*	----------------------------------------------------------------- */
	
	function htmlWrite($htmlStr){
		echo $htmlStr;
	}
	/*	-----------------------------------------------------------------*/
	
	function alert($str){
		echo "<script type=\"text/javascript\">alert('".$str."');	</script>";}
		
	function confirm($str,$default=""){
		echo "<script type=\"text/javascript\">confirm('".$str."','".$default."');</script>";
	}
	//	
	function focus($str)
	{
		echo "<script type=\"text/javascript\">document.getElementById('$str').select();</script>";
	}//
	function closeWindow()
	{
		echo "<script type=\"text/javascript\">window.close();</script>";
	}
}

//$html=new HTML();
?>
