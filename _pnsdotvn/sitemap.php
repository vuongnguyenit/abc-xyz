<?php
require_once("index_table.php");
if(!empty($_POST["submit"]))
{
  /* Writting Sitemap here...
  **************************/
  $infor = $dbf->buildXMLSitemap();
  if(count($infor) > 0) {
	$xml=new XML();
	$xml->CreateNode("urlset", array("xmlns" => "http://www.sitemaps.org/schemas/sitemap/0.9",
									 "xmlns:xsi" => "http://www.w3.org/2001/XMLSchema-instance",
									 "xsi:schemaLocation" => "http://www.sitemaps.org/schemas/sitemap/0.9\n http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"));	
	foreach($infor as $info)
	{
	  $xml->CreateNode("url",array());
		$xml->AppendNode("loc", array(), $info["loc"]);
		$xml->AppendNode("lastmod", array(), $info["lastmod"]);
		$xml->AppendNode("changefreq", array(), $info["changefreq"]);
		$xml->AppendNode("priority", array(), $info["priority"]);
	  $xml->CloseNode("url");	  
	}
	$xml->CloseNode("urlset");
	$affect = $xml->Save(dirname(dirname(__FILE__)) . "/media/xml/sitemap.xml");
	if($affect > 0) {
?>
  <h3 style="font-size:16px">Sitemap đã được cập nhât lại thành công!!!</h3>
  <p>Đường dẫn file sitemap được đặt ở thư mục: <a target="_blank" title="Tải về" href="<?php echo HOST?>/media/xml/sitemap.xml"><?php echo HOST?>/media/xml/sitemap.xml</a></p>
  <!--<input type="button" name="close" id="close" value="Đóng cửa sổ" onclick="window.close();" />-->
<?php
	}
  }
}else{
?>
  <form name="frm" id="frm" action="" method="post">
    <h3>Cập nhật cấu trúc sitemap lại cho website!!!</h3>
    <p>Sitemap là file XML chứa tất cả các link bên trong website, dùng cho Google, Yahoo! and MSN.</p>
    <input name="submit" id="submit" type="submit" value="Cập nhật lại" />
  </form>
<?php
}
?>
</body>
</html>