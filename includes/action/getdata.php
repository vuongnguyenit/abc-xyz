<?php ob_start();
@session_start();
$arraymsg["code"] = "fail";
$data=$_POST;
if(isset($_SESSION['member']) && count($_SESSION['member']) > 0) 
{
  include str_replace("\\", "/", dirname(dirname(dirname(__FILE__)))) . '/_pnsdotvn/defineConst.php';
  
  if (!MEMBER_SYS)
		die();  
  
  include str_replace("\\", "/", dirname(dirname(dirname(__FILE__)))) . '/class/class.BUL.php';
  include str_replace("\\", "/", dirname(dirname(dirname(__FILE__)))) . '/class/class.utls.php';  
  $dbf->queryEncodeUTF8();
  
  $rst = $dbf->getDynamicJoin(prefixTable . 'customer', prefixTable . 'customer_address', array('address' => 'address', 'district_name' => 'district', 'city_name' => 'city', 'country_name' => 'country'), 'inner join', "groupid = 2 AND t1.status = 1 and t1.id = " . $_SESSION['member']['id'], '', 't2.id = t1.address');
  if($dbf->totalRows($rst) == 1)
  {
    $member = $dbf->nextObject($rst);  
    $arraymsg['member']['name']     = stripslashes($member->name);
	$arraymsg['member']['birthdate']= $member->birthdate;
	#$arraymsg['member']['gender']   = $member->gender;
	$arraymsg['member']['salutation']   = $member->salutation;
	#$arraymsg['member']['pin']    = $member->pin;	
	$arraymsg['member']['phone']    = $member->phone;
	$arraymsg['member']['phone']    = $member->phone;
	$arraymsg['member']['mobile']   = $member->mobile;
	$arraymsg['member']['country']  = $member->country; 
	$arraymsg['member']['city']     = $member->city; 
	$arraymsg['member']['district'] = $member->district; 
	$arraymsg['member']['address']  = stripslashes($member->address);		
	$arraymsg["code"] = "success";
  }
}
echo json_encode($arraymsg);
ob_end_flush() ?>