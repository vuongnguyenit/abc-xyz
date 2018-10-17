<?php
class Utilities
{
	function checkValues($value)
	{
	   	// Use this function on all those values where you want to check for both sql injection and cross site scripting
		//Trim the value
		$value = trim($value);
		 
		// Stripslashes
		if (get_magic_quotes_gpc()) {
			$value = stripslashes($value);
		}
		
		// Convert all &lt;, &gt; etc. to normal html and then strip these
		$value = strtr($value,array_flip(get_html_translation_table(HTML_ENTITIES)));
		
		// Strip HTML Tags
		$value = strip_tags($value);
		
		// Quote the value
		$value = mysql_real_escape_string($value);
		/*$value = htmlspecialchars ($value);*/
		return $value;		
	}
	
	function getCookie($name, $defaultValue='')
    {
        if(!isset($_COOKIE[$name]) || empty($_COOKIE[$name])) {
			setcookie($name, $defaultValue, time()+31536000);
            return $defaultValue;
		}
        return $_COOKIE[$name];
    }
	function generate_url_from_text($strText)
    {
      $strText = $this->stripUnicode($strText);
      $strText = preg_replace('/[^A-Za-z0-9-]/', ' ', $strText);
      $strText = preg_replace('/ +/', ' ', $strText);
      $strText = trim($strText);
      $strText = str_replace(' ', '-', $strText);
      $strText = preg_replace('/-+/', '-', $strText);
      $strText=  preg_replace("/-$/","",$strText);
      return $strText;
    }
	/*
    *******************************************************************/
    function stripUnicode($str){
        if(!$str) return false;
        $str=strip_tags($str);
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
         );
        foreach($unicode as $nonUnicode=>$uni) $str = preg_replace("/($uni)/i",$nonUnicode,$str);
        return $str;
    }
    //=====================
    function writeLog()
    {
      // Getting the information
      $ipaddress = $_SERVER['REMOTE_ADDR'];
      $host= urlencode($_SERVER['HTTP_HOST']);
      $referrer = urlencode($_SERVER['REQUEST_URI']);
      //
      $datetime = mktime();
      $useragent = $_SERVER['HTTP_USER_AGENT'];
      $remotehost = @getHostByAddr($ipaddress);
      $user=$_SESSION["user"];

      $delete=0;

      if($_GET['edit']!="" && (isset($_POST['subupdate'])||isset($_POST['subinsert'])))
      {
      	$action="View, Edit";
      	$delete=2;
      }
      else if($_GET['del']!="")
      {
      	$action="View, Delete";

      	$delete=1;

      }else if(isset($_POST['subinsert']))
      {
      	$action="View, Insert";
      }
      else
      {
      	$action="View only";
      }
      $page = "http://{$_SERVER['HTTP_HOST']}{$_SERVER['PHP_SELF']}";
      $page .= $this->iif(!empty($_SERVER['QUERY_STRING']), "?{$_SERVER['QUERY_STRING']}", "");
      $page = urlencode($page);
      // Create log line
      $logline = $ipaddress . '|' .$referrer . '|' . $datetime . '|' . $useragent . '|' . $remotehost . '|' . $action.'|'.$user .'|'.$host.'|'.$delete ."\n";
      // Write to log file:
      $logfile = 'log.txt';
      // Open the log file in "Append" mode
      if (!$handle = fopen($logfile, 'a+')) {
      	die("Failed to open log file");
      }
      // Write $logline to our logfile.
      if (fwrite($handle, $logline) === FALSE) {
      	die("Failed to write to log file");
      }
      fclose($handle);

    }

    function iif($expression, $returntrue, $returnfalse = '')
    {
	    return ($expression ? $returntrue : $returnfalse);
    }
	//====================== Tạo mật khẩu ngẩu nhiên ===================================
function make_pass()
	{
	$pass = "";
	$chars = array(
		"1","2","3","4","5","6","7","8","9","0",
		"a","A","b","B","c","C","d","D","e","E","f","F","g","G","h","H","i","I","j","J",
		"k","K","l","L","m","M","n","N","o","O","p","P","q","Q","r","R","s","S","t","T",
		"u","U","v","V","w","W","x","X","y","Y","z","Z");
		
		$count = count($chars) - 1;
		srand((double)microtime()*1000000);
		for($i = 0; $i < 6; $i++)
				$pass .= $chars[rand(0, $count)];
		
	for($i=0;$i<6;$i++)
		{
		if(is_numeric(substr($pass,$i,1)))
			break;
		}
	if($i==6)
		$pass=substr($pass,0,2).rand(0,9).substr($pass,3,3);

	return($pass);
	}

//====================== Tạo hình có mã bảo vệ ===================================
function make_protect_image()
{
	$pass = "";
	
	$chars = array("1","2","3","4","5","6","7","8","9");
		$count = count($chars) - 1;
		srand((double)microtime()*1000000);
		for($i = 0; $i < 5; $i++)
				$pass .= $chars[rand(0, $count)];
		
	$img=imagecreatefromjpeg("images/patten.jpg");
	$text_color = imagecolorallocate($img, 000,000 ,000);
	$w=10;
	srand((double)microtime()*1000000);
	for($i=0;$i<strlen($pass);$i++)
		{
		$a=rand(0,0);
		$t=substr($pass,$i,1);
		imagettftext($img, 20, $a, $w,30,$text_color,"images/impact.ttf",$t);
		$w=$w+30;
		}
		imagejpeg($img,"images/protect.jpg");
		@imagedestroy($img);
		return($pass);
}
function make_protect_image2()
{
	$pass = "";
	
	$chars = array("1","2","3","4","5","6","7","8","9");
		$count = count($chars) - 1;
		srand((double)microtime()*1000000);
		for($i = 0; $i < 5; $i++)
				$pass .= $chars[rand(0, $count)];
		
	$img=imagecreatefromjpeg("images/patten2.jpg");
	$text_color = imagecolorallocate($img, 119,142 ,164);
	$w=10;
	srand((double)microtime()*1000000);
	for($i=0;$i<strlen($pass);$i++)
		{
		$a=rand(0,-0);
		$t=substr($pass,$i,1);
		imagettftext($img, 20, $a, $w,30,$text_color,"images/impact.ttf",$t);
		$w=$w+20;
		}
		imagejpeg($img,"images/protect2.jpg");
		@imagedestroy($img);
		return($pass);
}
	//======================== Tạo hình cho title ========================
function create_title_image($str)
	{
	$im=imagecreatefromjpeg("title_bg.jpg");
	$img=imagecreatetruecolor(400,32);
	imagecopyresized($img,$im,0,0,0,0,400,32,311,34);
	
	$color = imagecolorallocate($im, 10,167 ,146);
	imagettftext($img, 24, 0, 10, 25,$color,"slipstrm.ttf",$str);	
	
	$color = imagecolorallocate($img, 255,255 ,255);
	imagettftext($img, 24, 0, 13, 25,$color,"slipstrm.ttf",$str);
	
	imagejpeg($img,$str.".jpg",100);
	@imagedestroy($img);
	}
//============================= Kiểm tra tính hợp lệ của email
function chk_email($email){
	if(!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {
		return false ;
	}
	return true;
}  
//============================= Kiểm tra tính hợp lệ của ngày tháng năm
function valid_date($date, $format = 'YYYY-MM-DD', $type = '')
{
    if(strlen($date) >= 8 && strlen($date) <= 10){
        $separator_only = str_replace(array('M','D','Y'),'', $format);
        $separator = $separator_only[0];
        if($separator){
            $regexp = str_replace($separator, "\\" . $separator, $format);
            $regexp = str_replace('MM', '(0[1-9]|1[0-2])', $regexp);
            $regexp = str_replace('M', '(0?[1-9]|1[0-2])', $regexp);
            $regexp = str_replace('DD', '(0[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('D', '(0?[1-9]|[1-2][0-9]|3[0-1])', $regexp);
            $regexp = str_replace('YYYY', '\d{4}', $regexp);
            $regexp = str_replace('YY', '\d{2}', $regexp);
            if($regexp != $date && preg_match('/'.$regexp.'$/', $date)){
                foreach (array_combine(explode($separator,$format), explode($separator,$date)) as $key=>$value) {
                    if ($key == 'YY') $year = '20'.$value;
                    if ($key == 'YYYY') $year = $value;
                    if ($key[0] == 'M') $month = $value;
                    if ($key[0] == 'D') $day = $value;
                }
                if (checkdate($month,$day,$year) AND $type=='birthdate' AND (date('Y')-100)<=$year AND  $year<=date('Y')) return true;
				if (checkdate($month,$day,$year) AND $type=='') return true;
            }
        }
    }
    return false;
}
//Tao watermark bao ve hinh	
function watermark($srcfilename, $newname, $watermark, $quality, $ext = 'jpg') {
	$imageInfo = getimagesize($srcfilename);
	$width = $imageInfo[0];
	$height = $imageInfo[1];
	$logoinfo = getimagesize($watermark);
	$logowidth = $logoinfo[0];
	$logoheight = $logoinfo[1];
	$horizextra =$width - $logowidth;
	$vertextra =$height - $logoheight;
	$horizmargin = round($horizextra / 2);
	$vertmargin = round($vertextra / 2);
	switch($ext)
	{
		case 'jpg': $photoImage = ImageCreateFromJPEG($srcfilename); break;
		case 'png': $photoImage = ImageCreateFromPNG($srcfilename); break;
		default: $photoImage = ImageCreateFromJPEG($srcfilename); break;
	}
	ImageAlphaBlending($photoImage, true);
	$logoImage = ImageCreateFromPNG($watermark);
	$logoW = ImageSX($logoImage);
	$logoH = ImageSY($logoImage);
	#ImageCopy($photoImage, $logoImage, $horizmargin, $vertmargin, 0, 0, $logoW, $logoH);
	//ImageJPEG($photoImage,"",$quality); // output to browser 
	ImageCopy($photoImage, $logoImage, $horizextra - 15, $vertextra - 35, 0, 0, $logoW, $logoH);
	//uncomment next line to save the watermarked image to a directory. need write access(changed directory to anything)
	//ImageJPEG($photoImage, "../stock_photos/" . $newname, $quality);
	ImageJPEG($photoImage, $newname, $quality);
	ImageDestroy($photoImage);
	ImageDestroy($logoImage);
}
//============================== Tạo header cho mail =============================
function getheader($fromname,$fromemail,$reply)
	{
	$headers .= "X-Priority: 3\nX-MSMail-Priority: Normal\nX-Mailer: PHP\nX-MimeOLE: Sa Lan I.T. \n";
	$headers .= "MIME-Version: 1.0\nContent-type: text/html; charset=utf-8\nContent-transfer-encoding: 8bit\n";
	$headers  = "From:$fromname < $fromemail >\n"; 
	$headers .= "Reply-To: $reply \n";
	$headers .= "Date:".date("d/m/Y H:i:s")."\n"; 
	$headers .= "Content-Type: text/html; charset=utf-8";
	return $headers;
}
	//=============== Chuyển định dạng ngày từ yyyy-mm-dd sang dd/mm/yyyy===============
	function dbtotext($value)
	{
		$arr=explode("-",trim($value));
		return "$arr[2]/$arr[1]/$arr[0]";
	}
	/*
	-----------------------------------------------------------------*/
	function encodeHTML($sHTML){
		$sHTML=stripcslashes($sHTML);
		$sHTML=ereg_replace("&","&amp;",$sHTML);
		$sHTML=ereg_replace("<","&lt;",$sHTML);
		$sHTML=ereg_replace(">","&gt;",$sHTML);
		return $sHTML;
	}	
	//
	function myEncodeHTML($sHTML){
		$sHTML=stripcslashes($sHTML);
		$sHTML=ereg_replace("<DIV>","",$sHTML);
		$sHTML=ereg_replace("</DIV>","",$sHTML);
		$sHTML=ereg_replace("<div>","",$sHTML);
		$sHTML=ereg_replace("</div>","",$sHTML);

		$sHTML=ereg_replace("<P>","",$sHTML);
		$sHTML=ereg_replace("</P>","",$sHTML);
		$sHTML=ereg_replace("<p>","",$sHTML);
		$sHTML=ereg_replace("</p>","",$sHTML);

		$sHTML=ereg_replace("<UL>","",$sHTML);
		$sHTML=ereg_replace("</UL>","",$sHTML);
		$sHTML=ereg_replace("<ul>","",$sHTML);
		$sHTML=ereg_replace("</ul>","",$sHTML);

		$sHTML=ereg_replace("<LI>","",$sHTML);
		$sHTML=ereg_replace("</LI>","",$sHTML);
		$sHTML=ereg_replace("<li>","",$sHTML);
		$sHTML=ereg_replace("</li>","",$sHTML);

		$sHTML=ereg_replace("<BR>","",$sHTML);
		$sHTML=ereg_replace("<BR/>","",$sHTML);
		$sHTML=ereg_replace("<br>","",$sHTML);
		$sHTML=ereg_replace("<br/>","",$sHTML);

		$sHTML=ereg_replace("<B>","",$sHTML);
		$sHTML=ereg_replace("</B>","",$sHTML);
		$sHTML=ereg_replace("<b>","",$sHTML);
		$sHTML=ereg_replace("</b>","",$sHTML);

		$sHTML=ereg_replace("<U>","",$sHTML);
		$sHTML=ereg_replace("</U>","",$sHTML);
		$sHTML=ereg_replace("<u>","",$sHTML);
		$sHTML=ereg_replace("</u>","",$sHTML);

		$sHTML=ereg_replace("<I>","",$sHTML);
		$sHTML=ereg_replace("</I>","",$sHTML);
		$sHTML=ereg_replace("<i>","",$sHTML);
		$sHTML=ereg_replace("</i>","",$sHTML);

		$sHTML=ereg_replace("<TABLE>","",$sHTML);
		$sHTML=ereg_replace("</TABLE>","",$sHTML);
		$sHTML=ereg_replace("<table>","",$sHTML);
		$sHTML=ereg_replace("</table>","",$sHTML);

		$sHTML=ereg_replace("<TR>","",$sHTML);
		$sHTML=ereg_replace("</TR>","",$sHTML);
		$sHTML=ereg_replace("<tr>","",$sHTML);
		$sHTML=ereg_replace("</tr>","",$sHTML);

		$sHTML=ereg_replace("<TD>","",$sHTML);
		$sHTML=ereg_replace("</TD>","",$sHTML);
		$sHTML=ereg_replace("<td>","",$sHTML);
		$sHTML=ereg_replace("</td>","",$sHTML);

		$sHTML=ereg_replace("<IMG>","",$sHTML);
		$sHTML=ereg_replace("</IMG>","",$sHTML);
		$sHTML=ereg_replace("<img>","",$sHTML);
		$sHTML=ereg_replace("</img>","",$sHTML);

		$sHTML=ereg_replace("<IMG","",$sHTML);
		$sHTML=ereg_replace("</IMG>","",$sHTML);
		$sHTML=ereg_replace("<img","",$sHTML);
		$sHTML=ereg_replace("</img>","",$sHTML);

		return $sHTML;
	}

	/*
	-----------------------------------------------------------------*/
	function encodeQuote($sHTML){
		$sHTML=stripcslashes($sHTML);
		$sHTML=ereg_replace("'","\'",$sHTML);
		$sHTML=ereg_replace('"','\"',$sHTML);
		return $sHTML;
	}
    function sqlInjection($str)
    {
        $str=mysql_real_escape_string($str);
        $str= ereg_replace("union","",$str);
        $str= ereg_replace("UNION","",$str);
        return $str;
    }
	/*
	-----------------------------------------------------------------*/
	function getMyDate(){
		$date=getdate();
		return $date["year"].".".$date["mon"].".".$date["mday"];
	}
	/*
	-----------------------------------------------------------------*/
	function formatMyDate($stringFormat,$stringDate){
		return date($stringFormat,strtotime($stringDate));
	}
	/*
	-----------------------------------------------------------------*/
	function takeShortText($longText,$numWords){
		$ret="";
		if($longText!=""){
			$longText=trim($longText);
			$longText=$this->myEncodeHTML($longText);
			$longText=strip_tags($longText);
			
			if(str_word_count($longText)>$numWords){
				$arrayText=split(" ",$longText,$numWords);
				for($i=0;$i<$numWords-1;$i++){
					$ret.=$arrayText[$i]." ";
				}
				$ret=trim($ret)."... ";
				return $ret;
			}
			else{
				return $longText;
			}
		}
	}
	/*
	-----------------------------------------------------------------*/
	function takeShortTextByChar($longText,$numChars){
		if($longText!=""){
			$longText=trim($longText);
			$longText=$this->deleteHashBlank($this->encodeHTML($longText));
			if(strlen($longText)<=$numChars){
				return $longText;
			}
			else{
				return substr($longText,0,$numChars)."... ";
			}
		}
	}
	/*
	-----------------------------------------------------------------*/
	function deleteHashChar($filename){
		$ret=str_replace("_","",$filename);
		$ret=str_replace("-","",$ret);
		$ret=str_replace("%","",$ret);
		$ret=str_replace("?","",$ret);
		$ret=str_replace(" ","",$ret);
		return $ret;
	}
	/*
	-----------------------------------------------------------------*/
	function deleteHashBlank($longText){
		$longText=trim($longText);
		$arrayText=split(" ",$longText);
		$ret="";
		for($i=0;$i<count($arrayText);$i++){
			$ret.=" ".$arrayText[$i];
		}
		return trim($ret);
	}
	/*
	-----------------------------------------------------------------*/
	function getFileExtension($file){
		$pos=strrpos($file,".");
		$ret=substr($file,-(strlen($file)-$pos));
		return strtolower($ret);
	}
	/*
	-----------------------------------------------------------------*/
	function getFilename($file){
		$pos=strrpos($file,".");
		$ret=substr($file,0,$pos);
		return strtolower($ret);
	}
	/*
	-----------------------------------------------------------------*/
	function myGetFilenameFromURL($url){
		$url=trim($url);
		$url=str_replace("\\","/",$url);
		$pos=strrpos($url,"/");
		$ret=substr($url,$pos+1,strlen($url));
		return $ret;
	}
	/*
	-----------------------------------------------------------------*/
	function getFilenameFromURL($url){
		return basename($url);
	}
	/*
	-----------------------------------------------------------------*/
	function getUniqueFilename($dir,$name){
		$name=strtolower($name);
		$filename=$this->deleteHashChar($name);
		$ret=$filename;
		$ext=$this->getFileExtension($filename);
		$filename=$this->getFilename($filename);
		$i=1;
		$tmpName=$filename;
		$tmp=realpath($dir.$tmpName.$ext);
		if(file_exists($tmp)){
			while(true){
				$tmp=realpath($dir.$tmpName.$ext);
				if(file_exists($tmp)){
					$tmpName=$filename."-".$i;
					$i++;
				}
				else{
					$ret=$tmpName.$ext;
					break;
				}
			}
		}
		return $ret;
	}
	/*
	-----------------------------------------------------------------*/
	function fileExtension($filename){
    	$pathInfo=pathinfo($filename);
    	return $pathInfo["extension"];
	}
	/*
	-----------------------------------------------------------------*/
	function getImage($image){
		switch($image){
			case "file":
				return base64_decode("R0lGODlhEQANAJEDAJmZmf///wAAAP///yH5BAHoAwMALAAAAAARAA0AAAItnIGJxg0B42rsiSvCA/REmXQWhmnih3LUSGaqg35vFbSXucbSabunjnMohq8CADsA");
				break;
			case "folder":
				return base64_decode("R0lGODlhEQANAJEDAJmZmf///8zMzP///yH5BAHoAwMALAAAAAARAA0AAAIqnI+ZwKwbYgTPtIudlbwLOgCBQJYmCYrn+m3smY5vGc+0a7dhjh7ZbygAADsA");
				break;
			case "hiddenFile":
				return base64_decode("R0lGODlhEQANAJEDAMwAAP///5mZmf///yH5BAHoAwMALAAAAAARAA0AAAItnIGJxg0B42rsiSvCA/REmXQWhmnih3LUSGaqg35vFbSXucbSabunjnMohq8CADsA");
				break;
			case "link":
				return base64_decode("R0lGODlhEQANAKIEAJmZmf///wAAAMwAAP///wAAAAAAAAAAACH5BAHoAwQALAAAAAARAA0AAAM5SArcrDCCQOuLcIotwgTYUllNOA0DxXkmhY4shM5zsMUKTY8gNgUvW6cnAaZgxMyIM2zBLCaHlJgAADsA");
				break;
			case "smiley":
				return base64_decode("R0lGODlhEQANAJECAAAAAP//AP///wAAACH5BAHoAwIALAAAAAARAA0AAAIslI+pAu2wDAiz0jWD3hqmBzZf1VCleJQch0rkdnppB3dKZuIygrMRE/oJDwUAOwA=");
				break;
			case "arrow":
				return base64_decode("R0lGODlhEQANAIABAAAAAP///yH5BAEKAAEALAAAAAARAA0AAAIdjA9wy6gNQ4pwUmav0yvn+hhJiI3mCJ6otrIkxxQAOw==");
				break;
		}
	}
}
$utls=new Utilities();
?>