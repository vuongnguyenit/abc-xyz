function checkaccount(state)
{

    if(document.frm.username.value=="")
	{
		alert("Vui lòng nhập vào tên tài khoản");
		document.frm.username.focus();
		return false;
	}
    if((state=="1" && document.frm.chkupdate.checked)||(state=="0"))
    {
    	if(document.frm.password.value=="")
    	{
    		alert("Vui lòng nhập vào mật khẩu");
    		document.frm.password.focus();
    		return false;
    	}
    	if(document.frm.password.value.length<=5)
    	{
    		alert("Vui lòng nhập vào mật khẩu từ 6 kí tự trở lên");
    		document.frm.password.focus();
    		return false;
    	}
    	if(document.frm.confirmpassword.value=="")
    	{
    		alert("Vui lòng nhập vào mật khẩu xác nhận");
    		document.frm.confirmpassword.focus();
    		return false;
    	}
    	if(document.frm.confirmpassword.value!=document.frm.password.value)
    	{
    		alert("Mật khẩu không trùng khớp");
    		document.frm.password.value="";
    		document.frm.confirmpassword.value="";
    		document.frm.password.focus();
    		return false;
    	}
    }
	if(document.frm.fullname.value=="")
	{
		alert("Vui lòng nhập vào tên đầy đủ");
		document.frm.fullname.focus();
		return false;
	}
	if(document.frm.email.value=="")
	{
		alert("Vui lòng nhập vào địa chỉ mail");
		document.frm.email.focus();
		return false;
	}
	if(!isEmail(document.frm.email.value))
	{
		alert("Vui lòng nhập vào đúng định dạng mail");
		document.frm.email.focus();
		document.frm.email.select();
		return false;
	}

	return true;
}
//

function checkthanhvien(state)
{

    if(document.frm.username.value=="")
	{
		alert("Vui lòng nhập vào tên tài khoản");
		document.frm.username.focus();
		return false;
	}
    if((state=="1" && document.frm.chkupdate.checked)||(state=="0"))
    {
    	if(document.frm.password.value=="")
    	{
    		alert("Vui lòng nhập vào mật khẩu");
    		document.frm.password.focus();
    		return false;
    	}
    	if(document.frm.password.value.length<=5)
    	{
    		alert("Vui lòng nhập vào mật khẩu từ 6 kí tự trở lên");
    		document.frm.password.focus();
    		return false;
    	}
    	if(document.frm.confirmpassword.value=="")
    	{
    		alert("Vui lòng nhập vào mật khẩu xác nhận");
    		document.frm.confirmpassword.focus();
    		return false;
    	}
    	if(document.frm.confirmpassword.value!=document.frm.password.value)
    	{
    		alert("Mật khẩu không trùng khớp");
    		document.frm.password.value="";
    		document.frm.confirmpassword.value="";
    		document.frm.password.focus();
    		return false;
    	}
    }
	if(document.frm.fullname.value=="")
	{
		alert("Vui lòng nhập vào tên đầy đủ");
		document.frm.fullname.focus();
		return false;
	}
	if(document.frm.email.value=="")
	{
		alert("Vui lòng nhập vào địa chỉ mail");
		document.frm.email.focus();
		return false;
	}
	if(!isEmail(document.frm.email.value))
	{
		alert("Vui lòng nhập vào đúng định dạng mail");
		document.frm.email.focus();
		document.frm.email.select();
		return false;
	}

	return true;
}