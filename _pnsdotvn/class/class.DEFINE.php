<?php
  /* Section of paging
  *************************************************************/
  $QUERY_STRING=$_SERVER['QUERY_STRING'];
  $empty="-";
  $QUERY_STRING=preg_replace("/&{2,100}/","&",$QUERY_STRING);
  $QUERY_STRING=preg_replace("/^&/","",$QUERY_STRING);
  $QUERY_STRING=preg_replace("/&$/","",$QUERY_STRING);

  $urltinhtrang=preg_replace("/status=[0-9|a-z|A-Z|.]*/","",$QUERY_STRING);
  $urlnoidathang=preg_replace("/place=[0-9|a-z|A-Z|.]*/","",$QUERY_STRING);
  $urlbaocaoboi=preg_replace("/reporter=[0-9|a-z|A-Z|.]*/","",$QUERY_STRING);
  $urlfrom=preg_replace("/from=[0-9|a-z|A-Z|.|-]*/","",$QUERY_STRING);
  $urlto=preg_replace("/to=[0-9|a-z|A-Z|.|-]*/","",$QUERY_STRING);

  $arrayTinhtrang=array("Finished" => "Đã giao hàng","Processing" => "Đang chờ","Cancelled" => "Hủy","Other" => "Lý do khác");
  $arrayUpload=array("zoneUpload" => "files/");
  $arrayExt=array(".doc",".docx",".rar",".zip",".pdf",".xls",".xlsx");
  $arrayErrorFile=array("-1" => "File đính kèm bắt buộc","2" => "File upload không thành công","3" => "File không đúng định dạng (*.doc,*.docx,*.pdf,*.rar,*.zip,*.xls,*.xlsx)","4" => "Kích thước file lớn hơn 3 Mb. Vui lòng chọn file khác");
  
  $arrPMethod=array(1=>"Giao hàng tận nơi và thu tiền.",2=>"Thanh toán qua tài khoản ngân hàng.");
?>