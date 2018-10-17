//
 // JavaScript Document
// This function will format the number 1000000 to 1.000.000 or 1,000,000
function validTel(s)
{	
	var str="0123456789)(- .";
	if(s.length>=20||s.length<=5) return false;
	for(var i=0;i<s.length;i++)
	{
		if(str.indexOf(s.charAt(i))==-1)	return false;
	}
	return true;
	
}
function isEmail(s)
{   
  if (s=="") return false;
  if(s.indexOf(" ")>0) return false;
  var i = 1;
  var sLength = s.length;
  if (s.indexOf(".")==sLength) return false;
  if (s.indexOf(".")<=0) return false;
  if (s.indexOf("@")!=s.lastIndexOf("@")) return false;

  while ((i < sLength) && (s.charAt(i) != "@"))
  { i++
  }

  if ((i >= sLength) || (s.charAt(i) != "@")) return false;
  else i += 2;

  while ((i < sLength) && (s.charAt(i) != "."))
  { i++
  }

  if ((i >= sLength - 1) || (s.charAt(i) != ".")) return false;
   var str="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghikjlmnopqrstuvwxyz-@._"; 
   for(var j=0;j<s.length;j++)
	if(str.indexOf(s.charAt(j))==-1)
		return false;
   return true;
}
function addCommas(nStr)
{

	nStr += '';

	x = nStr.split('.');
	x1 = x[0];

	x2 = x.length > 1 ? '.' + x[1] : '';

	var rgx = /(\d+)(\d{3})/;

	while (rgx.test(x1))
	{
		x1 = x1.replace(rgx, '$1' + '.' + '$2');
	}

	return x1 + x2;
}

function removeCommas(theString)
{
	var rgx = /(\.)/g;

	return theString.replace(rgx, "");
}
//
function redirect(page)
{
    window.location=page;
}
function showControl(checked)
{
    if(checked) document.getElementById("groupPassword").style.display='';
    else document.getElementById("groupPassword").style.display='none';
}
function deleteCommon(pageCurrent)
{
	var len=document.frm.elements.length;
    if(len==3)
    {
        if(document.frm.chk.checked) ok=true;
        else ok=false;
    }else
    {
        var len=document.frm.chk.length;
    	if(len>1)
    	{
    		var ok=false;
    		var mang="";
    		i=0;
    		while((i<len) && !ok)
    		{
    			if(document.frm.chk[i].checked) ok=true;
    			i++;
    		}
    	}
    }
    if(ok){
    		if(confirm('Bạn có thật sự muốn xóa những dòng này không?'))
    		{
    			document.frm.action=pageCurrent+"&delete";
    			document.frm.submit();
    		}
  	}else
  	{
  		alert("Vui lòng chọn mục cần xóa!");
  	}
}

function getstring()
{
		var str="";
		var alen=document.frm.chk.length;
		
		if (alen>1)
		{
			for(var i=0;i<alen;i++)
				if(document.frm.chk[i].checked==true) str+=document.frm.chk[i].value+",";				
		}else
		{
			if(document.frm.chk.checked==true) str=document.frm.chk.value;
		}		
		
		document.frm.arrayid.value=str;
}


function docheck(status,from_)
	{
		var alen=document.frm.chk.length;

		if (alen>1)
		{
			for(var i=0;i<alen;i++)
				document.frm.chk[i].checked=status;
		}else
		{
				document.frm.chk.checked=status;
		}		
		if(from_>0)
			document.frm.chkall.checked=status;	
		getstring();
	}
	
	function docheckone()
	{
		var alen=document.frm.chk.length;
		var isChecked=true;
		if (alen>1)
		{
			for(var i=0;i<alen;i++)
				if(document.frm.chk[i].checked==false)
					isChecked=false;
		}else
		{
			if(document.frm.chk.checked==false)
				isChecked=false;
		}	
		document.frm.chkall.checked=isChecked;	
		getstring();
	}
function docheckone2(obj,objchk)
{		
	getstring2(obj,objchk);
}
function getstring3(obj,objchk,defaultObj)
{
		var str=defaultObj;
		objctl=eval("document.frm."+obj);
		var alen=eval("document.frm."+objchk+".length");
		
		if (alen>1)
		{
			for(var i=0;i<alen;i++)
			{
				if(eval("document.frm."+objchk+"["+i+"].checked")==true) 
				str+=","+eval("document.frm."+objchk+"["+i+"].value");				
				else 
				str+=",0";
			}
		}else
		{
			objctl_chk=eval("document.frm."+objchk);
			if(objctl_chk.checked==true) str=eval("document.frm."+objchk+".value");
		}		
		
		objctl.value=str;
}
function getstring2(obj,objchk)
{
		var str="";
		objctl=eval("document.frm."+obj);
		var alen=eval("document.frm."+objchk+".length");
		
		if (alen>1)
		{
			for(var i=0;i<alen;i++)
				if(eval("document.frm."+objchk+"["+i+"].checked")==true) str+=eval("document.frm."+objchk+"["+i+"].value")+";";				
		}else
		{
			objctl_chk=eval("document.frm."+objchk);
			if(objctl_chk.checked==true) str=eval("document.frm."+objchk+".value");
		}		
		
		objctl.value=str;
}
	function nhapso(evt,objectid)
	{
		var key=(!window.ActiveXObject)?evt.which:window.event.keyCode;	
		var values=document.getElementById(objectid).value;
		if(values=="") document.getElementById(objectid).value="0";
		if(key==8)
		{			
			if(values.length<=1 || values=="")
			{				
				document.getElementById(objectid).value="0";
				document.getElementById(objectid).select();
				return false;
			}
			
		}else
		{			
			if((key<48 || key >57))
			{				
				return false;
			}			
		}
		return true;				
	}	
	

function init() {
	if (TransMenu.isSupported()) {
			TransMenu.initialize();
		}
	}
function getposition()
{
document.all['divposition'].style.position='absolute';
document.all['divposition'].style.width='50%';
document.all['divposition'].style.left=screen.availWidth/2-275;
document.all['divposition'].style.top=screen.availHeight/2-300;
document.all['divposition'].style.display='';
      document.frmlogin.protectioncode.focus();
}
function showPath(object,chkobject)
{
	if(object)
	{
		document.getElementById(chkobject).style.display='';
		document.getElementById(chkobject).focus();
		document.getElementById(chkobject).select();
	}else
	{
		document.getElementById(chkobject).style.display='none';
	}
}
function onlyNumber(evt,object)
{
	var code=(window.Event)?evt.which:evt.keyCode;
	if(object.value.length<=0) object.value="1";
	if(((code<48)||(code>57))&& (code!=8))
	{
		object.select();
		return false;
	}
}
function autoFocus(id)
{
	document.getElementById(id).focus();
}
function checklogin()
{
if(document.frmlogin.protectioncode.value=="")
{
	alert("Please enter protection code");
	document.frmlogin.protectioncode.focus();
	document.all['div'].style.visibility='visible';
	return false;
}else
{
	document.all['div'].style.visibility='hidden';
}
if(document.frmlogin.username.value=="")
{
	alert("Please enter your username");
	document.frmlogin.username.focus();
	document.all['lblusername'].style.visibility='visible';
	return false;
}else
{
	document.all['lblusername'].style.visibility='hidden';
}
if(document.frmlogin.password.value=="")
{
	alert("Please enter your password");
	document.frmlogin.password.focus();
	document.all['lblpassword'].style.visibility='visible';
	return false;
}else
{
	document.all['lblpassword'].style.visibility='hidden';
}

return true;
}
function checklogin2()
{
if(document.frmlogin.protectioncode.value=="")
{
	alert("Vui lòng nhập vào mã truy cập an toàn");
	document.frmlogin.protectioncode.focus();
	return false;
}
if(document.frmlogin.protectioncode.value!="<?=$_SESSION['sprotect']?>")
{
	alert("Mã truy cập an toàn sai");
	document.frmlogin.protectioncode.focus();
	return false;
}else
{
	document.frmlogin.username.focus();
}
if(document.frmlogin.username.value=="")
{
	document.all['lblusername'].innerHTML="*";
	document.all['lblusername'].style.visibility='visible';
	return false;
}else if(!isEmail(document.frmlogin.username.value))
{
	document.all['lblusername'].innerHTML="*";
	document.all['lblusername'].style.visibility='visible';
	return false;
}
else
{
	document.all['lblusername'].style.visibility='hidden';
}

return true;
}
function fo(object)
{
	object.style.backgroundColor='#f6f6f6';
	object.select();
}
function lo(object)
{
	object.style.backgroundColor='#fff';
}
function modelessDialogShow(url,width,height)
{
	if (document.all&&window.print) //if ie5
	eval('window.showModelessDialog(url,window,"dialogWidth:'+width+'px;dialogHeight:'+height+'px;edge:Raised;center:1;help:0;resizable:1;")');
	else
	eval('window.open(url,"a","width='+width+'px,height='+height+'px,resizable=1,scrollbars=1,copyhistory=yes")')
}

function isEmail(s)
{
  if (s=="") return false;
  if(s.indexOf(" ")>0) return false;
  var i = 1;
  var sLength = s.length;
  if (s.indexOf(".")==sLength) return false;
  if (s.indexOf(".")<=0) return false;
  if (s.indexOf("@")!=s.lastIndexOf("@")) return false;

  while ((i < sLength) && (s.charAt(i) != "@"))
  { i++
  }

  if ((i >= sLength) || (s.charAt(i) != "@")) return false;
  else i += 2;

  while ((i < sLength) && (s.charAt(i) != "."))
  { i++
  }

  if ((i >= sLength - 1) || (s.charAt(i) != ".")) return false;
   var str="0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghikjlmnopqrstuvwxyz-@._";
   for(var j=0;j<s.length;j++)
	if(str.indexOf(s.charAt(j))==-1)
		return false;
   return true;
}
function validTel(s)
{
	var str="0123456789)(- .";
	if(s.length>=20||s.length<=5) return false;
	for(var i=0;i<s.length;i++)
	{
		if(str.indexOf(s.charAt(i))==-1)	return false;
	}
	return true;

}
function showhide(thecell)
{
		if(theoldcell == thecell){
			eval('document.all.'+thecell).style.display = 'none'
			eval('document.all.'+theoldcell).style.display = 'none'
			theoldcell = ""
		}else{
			if(theoldcell != thecell){
				if(theoldcell != "")
				eval('document.all.'+theoldcell).style.display = 'none'
				eval('document.all.'+thecell).style.display = ''
				theoldcell = thecell
			}
		}
}
//flashwrite.js
function flashWrite(url,w,h,id,bg,vars,win){

	var flashStr=
	"<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' width='"+w+"' height='"+h+"' id='"+id+"' align='middle'>"+
	"<param name='allowScriptAccess' value='always' />"+
	"<param name='movie' value='"+url+"' />"+
	"<param name='FlashVars' value='"+vars+"' />"+
	"<param name='wmode' value='"+win+"' />"+
	"<param name='menu' value='false' />"+
	"<param name='quality' value='high' />"+
	"<param name='bgcolor' value='"+bg+"' />"+
	"<embed src='"+url+"' FlashVars='"+vars+"' wmode='"+win+"' menu='false' quality='high' bgcolor='"+bg+"' width='"+w+"' height='"+h+"' name='"+id+"' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />"+
	"</object>";

	document.write(flashStr);

}
/* =================================================================================================
 * ============================================================================================== */
TransMenu.spacerGif="img/x.gif";TransMenu.dingbatOn="img/submenu-on.gif";TransMenu.dingbatOff="img/submenu-off.gif";TransMenu.dingbatSize=14;TransMenu.menuPadding=6;TransMenu.itemPadding=3;TransMenu.shadowSize=2;TransMenu.shadowOffset=3;TransMenu.shadowColor="#000";TransMenu.shadowPng="img/grey-40.png";TransMenu.backgroundColor="#4477aa";TransMenu.backgroundPng="img/white-90.png";TransMenu.hideDelay=300;TransMenu.slideTime=200;TransMenu.reference={topLeft:1,topRight:2,bottomLeft:3,bottomRight:4};TransMenu.direction={down:1,right:2};TransMenu.registry=[];TransMenu._maxZ=100;TransMenu.isSupported=function(){var ua=navigator.userAgent.toLowerCase();var pf=navigator.platform.toLowerCase();var an=navigator.appName;var r=false;if(ua.indexOf("gecko")>-1&&navigator.productSub>=20020605)r=true;else if(an=="Microsoft Internet Explorer"){if(document.getElementById){if(pf.indexOf("mac")==0){r=/msie (\d(.\d*)?)/.test(ua)&&Number(RegExp.$1)>=5.1;}
else r=true;}}
return r;}
TransMenu.initialize=function(){for(var i=0,menu=null;menu=this.registry[i];i++){menu.initialize();}}
TransMenu.renderAll=function(){var aMenuHtml=[];for(var i=0,menu=null;menu=this.registry[i];i++){aMenuHtml[i]=menu.toString();}
document.write(aMenuHtml.join(""));}
function TransMenu(oActuator,iDirection,iLeft,iTop,iReferencePoint,parentMenuSet){this.addItem=addItem;this.addMenu=addMenu;this.toString=toString;this.initialize=initialize;this.isOpen=false;this.show=show;this.hide=hide;this.items=[];this.onactivate=new Function();this.ondeactivate=new Function();this.onmouseover=new Function();this.onqueue=new Function();this.ondequeue=new Function();this.index=TransMenu.registry.length;TransMenu.registry[this.index]=this;var id="TransMenu"+this.index;var contentHeight=null;var contentWidth=null;var childMenuSet=null;var animating=false;var childMenus=[];var slideAccel=-1;var elmCache=null;var ready=false;var _this=this;var a=null;var pos=iDirection==TransMenu.direction.down?"top":"left";var dim=null;
function addItem(sText,sUrl,blank){
	var item=new TransMenuItem(sText,sUrl,this);
	item._index=this.items.length;
	this.items[item._index]=item;
}
function addMenu(oMenuItem){if(!oMenuItem.parentMenu==this)throw new Error("Cannot add a menu here");if(childMenuSet==null)childMenuSet=new TransMenuSet(TransMenu.direction.right,-5,2,TransMenu.reference.topRight);var m=childMenuSet.addMenu(oMenuItem);childMenus[oMenuItem._index]=m;m.onmouseover=child_mouseover;m.ondeactivate=child_deactivate;m.onqueue=child_queue;m.ondequeue=child_dequeue;return m;}
function initialize(){initCache();initEvents();initSize();ready=true;}
function show(){if(ready){_this.isOpen=true;animating=true;setContainerPos();elmCache["clip"].style.visibility="visible";elmCache["clip"].style.zIndex=TransMenu._maxZ++;slideStart();_this.onactivate();}}
function hide(){if(ready){_this.isOpen=false;animating=true;for(var i=0,item=null;item=elmCache.item[i];i++)dehighlight(item);if(childMenuSet)childMenuSet.hide();slideStart();_this.ondeactivate();}}
function setContainerPos(){var sub=oActuator.constructor==TransMenuItem;var act=sub?oActuator.parentMenu.elmCache["item"][oActuator._index]:oActuator;var el=act;var x=0;var y=0;var minX=0;var maxX=(window.innerWidth?window.innerWidth:document.body.clientWidth)-parseInt(elmCache["clip"].style.width);var minY=0;var maxY=(window.innerHeight?window.innerHeight:document.body.clientHeight)-parseInt(elmCache["clip"].style.height);while(sub?el.parentNode.className.indexOf("transMenu")==-1:el.offsetParent){x+=el.offsetLeft;y+=el.offsetTop;if(el.scrollLeft)x-=el.scrollLeft;if(el.scrollTop)y-=el.scrollTop;el=el.offsetParent;}
if(oActuator.constructor==TransMenuItem){x+=parseInt(el.parentNode.style.left);y+=parseInt(el.parentNode.style.top);}
switch(iReferencePoint){case TransMenu.reference.topLeft:break;case TransMenu.reference.topRight:x+=act.offsetWidth;break;case TransMenu.reference.bottomLeft:y+=act.offsetHeight;break;case TransMenu.reference.bottomRight:x+=act.offsetWidth;y+=act.offsetHeight;break;}
x+=iLeft;y+=iTop;x=Math.max(Math.min(x,maxX),minX);y=Math.max(Math.min(y,maxY),minY);elmCache["clip"].style.left=x+"px";elmCache["clip"].style.top=y+"px";}
function slideStart(){var x0=parseInt(elmCache["content"].style[pos]);var x1=_this.isOpen?0:-dim;if(a!=null)a.stop();a=new Accelimation(x0,x1,TransMenu.slideTime,slideAccel);a.onframe=slideFrame;a.onend=slideEnd;a.start();}
function slideFrame(x){elmCache["content"].style[pos]=x+"px";}
function slideEnd(){if(!_this.isOpen)elmCache["clip"].style.visibility="hidden";animating=false;}
function initSize(){var ow=elmCache["items"].offsetWidth;var oh=elmCache["items"].offsetHeight;var ua=navigator.userAgent.toLowerCase();elmCache["clip"].style.width=ow+TransMenu.shadowSize+2+"px";elmCache["clip"].style.height=oh+TransMenu.shadowSize+2+"px";elmCache["content"].style.width=ow+TransMenu.shadowSize+"px";elmCache["content"].style.height=oh+TransMenu.shadowSize+"px";contentHeight=oh+TransMenu.shadowSize;contentWidth=ow+TransMenu.shadowSize;dim=iDirection==TransMenu.direction.down?contentHeight:contentWidth;elmCache["content"].style[pos]=-dim-TransMenu.shadowSize+"px";elmCache["clip"].style.visibility="hidden";if(ua.indexOf("mac")==-1||ua.indexOf("gecko")>-1){elmCache["background"].style.width=ow+"px";elmCache["background"].style.height=oh+"px";elmCache["background"].style.backgroundColor=TransMenu.backgroundColor;elmCache["shadowRight"].style.left=ow+"px";elmCache["shadowRight"].style.height=oh-(TransMenu.shadowOffset-TransMenu.shadowSize)+"px";elmCache["shadowRight"].style.backgroundColor=TransMenu.shadowColor;elmCache["shadowBottom"].style.top=oh+"px";elmCache["shadowBottom"].style.width=ow-TransMenu.shadowOffset+"px";elmCache["shadowBottom"].style.backgroundColor=TransMenu.shadowColor;}
else{elmCache["background"].firstChild.src=TransMenu.backgroundPng;elmCache["background"].firstChild.width=ow;elmCache["background"].firstChild.height=oh;elmCache["shadowRight"].firstChild.src=TransMenu.shadowPng;elmCache["shadowRight"].style.left=ow+"px";elmCache["shadowRight"].firstChild.width=TransMenu.shadowSize;elmCache["shadowRight"].firstChild.height=oh-(TransMenu.shadowOffset-TransMenu.shadowSize);elmCache["shadowBottom"].firstChild.src=TransMenu.shadowPng;elmCache["shadowBottom"].style.top=oh+"px";elmCache["shadowBottom"].firstChild.height=TransMenu.shadowSize;elmCache["shadowBottom"].firstChild.width=ow-TransMenu.shadowOffset;}}
function initCache(){var menu=document.getElementById(id);var all=menu.all?menu.all:menu.getElementsByTagName("*");elmCache={};elmCache["clip"]=menu;elmCache["item"]=[];for(var i=0,elm=null;elm=all[i];i++){switch(elm.className){case"items":case"content":case"background":case"shadowRight":case"shadowBottom":elmCache[elm.className]=elm;break;case"item":elm._index=elmCache["item"].length;elmCache["item"][elm._index]=elm;break;}}
_this.elmCache=elmCache;}
function initEvents(){for(var i=0,item=null;item=elmCache.item[i];i++){item.onmouseover=item_mouseover;item.onmouseout=item_mouseout;item.onclick=item_click;}
if(typeof oActuator.tagName!="undefined"){oActuator.onmouseover=actuator_mouseover;oActuator.onmouseout=actuator_mouseout;}
elmCache["content"].onmouseover=content_mouseover;elmCache["content"].onmouseout=content_mouseout;}
function highlight(oRow){oRow.className="item hover";if(childMenus[oRow._index])oRow.lastChild.firstChild.src=TransMenu.dingbatOn;}
function dehighlight(oRow){oRow.className="item";if(childMenus[oRow._index])oRow.lastChild.firstChild.src=TransMenu.dingbatOff;}
function item_mouseover(){if(!animating){highlight(this);if(childMenus[this._index])childMenuSet.showMenu(childMenus[this._index]);else if(childMenuSet)childMenuSet.hide();}}
function item_mouseout(){if(!animating){if(childMenus[this._index])childMenuSet.hideMenu(childMenus[this._index]);else dehighlight(this);}}

function item_click(){
	//if(!animating){
		//if(_this.items[this._index].url)location.href=_this.items[this._index].url;
	//}
	if(_this.items[this._index].url){
		str_url = _this.items[this._index].url ;
		if (str_url.indexOf("http")==-1){
			location.href=_this.items[this._index].url;
		}else{
			winprops = 'height=600,width=700,resizable=yes,toolbar=yes,status=yes,scrollbars=yes';
	 	    window.open(_this.items[this._index].url, null, winprops);
		}
	}

}
function actuator_mouseover(){parentMenuSet.showMenu(_this);}
function actuator_mouseout(){parentMenuSet.hideMenu(_this);}
function content_mouseover(){if(!animating){parentMenuSet.showMenu(_this);_this.onmouseover();}}
function content_mouseout(){if(!animating){parentMenuSet.hideMenu(_this);}}
function child_mouseover(){if(!animating){parentMenuSet.showMenu(_this);}}
function child_deactivate(){for(var i=0;i<childMenus.length;i++){if(childMenus[i]==this){dehighlight(elmCache["item"][i]);break;}}}
function child_queue(){parentMenuSet.hideMenu(_this);}
function child_dequeue(){parentMenuSet.showMenu(_this);}
function toString(){
	var aHtml=[];
var sClassName="transMenu"+(oActuator.constructor!=TransMenuItem?" top":"");
for(var i=0,item=null;item=this.items[i];i++){
	aHtml[i]=item.toString(childMenus[i]);
}
return'<div id="'+id+'" class="'+sClassName+'">'+'<div class="content"><table class="items" cellpadding="0" cellspacing="0" border="0">'+'<tr><td colspan="2"><img src="'+TransMenu.spacerGif+'" width="1" height="'+TransMenu.menuPadding+'"></td></tr>'+aHtml.join('')+'<tr><td colspan="2"><img src="'+TransMenu.spacerGif+'" width="1" height="'+TransMenu.menuPadding+'"></td></tr></table>'+'<div class="shadowBottom"><img src="'+TransMenu.spacerGif+'" width="1" height="1"></div>'+'<div class="shadowRight"><img src="'+TransMenu.spacerGif+'" width="1" height="1"></div>'+'<div class="background"><img src="'+TransMenu.spacerGif+'" width="1" height="1"></div>'+'</div></div>';}}
TransMenuSet.registry=[];function TransMenuSet(iDirection,iLeft,iTop,iReferencePoint){this.addMenu=addMenu;this.showMenu=showMenu;this.hideMenu=hideMenu;this.hide=hide;this.hideCurrent=hideCurrent;var menus=[];var _this=this;var current=null;this.index=TransMenuSet.registry.length;TransMenuSet.registry[this.index]=this;function addMenu(oActuator){var m=new TransMenu(oActuator,iDirection,iLeft,iTop,iReferencePoint,this);menus[menus.length]=m;return m;}
function showMenu(oMenu){if(oMenu!=current){if(current!=null)hide(current);current=oMenu;oMenu.show();}
else{cancelHide(oMenu);}}
function hideMenu(oMenu){if(current==oMenu&&oMenu.isOpen){if(!oMenu.hideTimer)scheduleHide(oMenu);}}
function scheduleHide(oMenu){oMenu.onqueue();oMenu.hideTimer=window.setTimeout("TransMenuSet.registry["+_this.index+"].hide(TransMenu.registry["+oMenu.index+"])",TransMenu.hideDelay);}
function cancelHide(oMenu){if(oMenu.hideTimer){oMenu.ondequeue();window.clearTimeout(oMenu.hideTimer);oMenu.hideTimer=null;}}
function hide(oMenu){if(!oMenu&&current)oMenu=current;if(oMenu&&current==oMenu&&oMenu.isOpen){hideCurrent();}}
function hideCurrent(){if (null != current){cancelHide(current);current.hideTimer=null;current.hide();current=null;}}}
function TransMenuItem(sText,sUrl,oParent){
	this.toString=toString;
	this.text=sText;
	this.url=sUrl;
	this.parentMenu=oParent;
	function toString(bDingbat){
		var sDingbat=bDingbat?TransMenu.dingbatOff:TransMenu.spacerGif;
		var iEdgePadding=TransMenu.itemPadding+TransMenu.menuPadding;
		var sPaddingLeft="padding:"+TransMenu.itemPadding+"px; padding-left:"+iEdgePadding+"px;"
var sPaddingRight="padding:"+TransMenu.itemPadding+"px; padding-right:"+iEdgePadding+"px;"
return'<tr class="item"><td nowrap style="'+sPaddingLeft+'">'+sText+'</td><td width="14" style="'+sPaddingRight+'">'+'<img src="'+sDingbat+'" width="14" height="14"></td></tr>';
	}
}
	function Accelimation(from,to,time,zip){if(typeof zip=="undefined")zip=0;if(typeof unit=="undefined")unit="px";this.x0=from;this.x1=to;this.dt=time;this.zip=-zip;this.unit=unit;this.timer=null;this.onend=new Function();this.onframe=new Function();}
Accelimation.prototype.start=function(){this.t0=new Date().getTime();this.t1=this.t0+this.dt;var dx=this.x1-this.x0;this.c1=this.x0+((1+this.zip)*dx/3);this.c2=this.x0+((2+this.zip)*dx/3);Accelimation._add(this);}
Accelimation.prototype.stop=function(){Accelimation._remove(this);}
Accelimation.prototype._paint=function(time){if(time<this.t1){var elapsed=time-this.t0;this.onframe(Accelimation._getBezier(elapsed/this.dt,this.x0,this.x1,this.c1,this.c2));}
else this._end();}
Accelimation.prototype._end=function(){Accelimation._remove(this);this.onframe(this.x1);this.onend();}
Accelimation._add=function(o){var index=this.instances.length;this.instances[index]=o;if(this.instances.length==1){this.timerID=window.setInterval("Accelimation._paintAll()",this.targetRes);}}
Accelimation._remove=function(o){for(var i=0;i<this.instances.length;i++){if(o==this.instances[i]){this.instances=this.instances.slice(0,i).concat(this.instances.slice(i+1));break;}}
if(this.instances.length==0){window.clearInterval(this.timerID);this.timerID=null;}}
Accelimation._paintAll=function(){var now=new Date().getTime();for(var i=0;i<this.instances.length;i++){this.instances[i]._paint(now);}}
Accelimation._B1=function(t){return t*t*t}
Accelimation._B2=function(t){return 3*t*t*(1-t)}
Accelimation._B3=function(t){return 3*t*(1-t)*(1-t)}
Accelimation._B4=function(t){return(1-t)*(1-t)*(1-t)}
Accelimation._getBezier=function(percent,startPos,endPos,control1,control2){return endPos*this._B1(percent)+control2*this._B2(percent)+control1*this._B3(percent)+startPos*this._B4(percent);}
Accelimation.instances=[];Accelimation.targetRes=10;Accelimation.timerID=null;
if(window.attachEvent){var cearElementProps=['data','onmouseover','onmouseout','onmousedown','onmouseup','ondblclick','onclick','onselectstart','oncontextmenu'];window.attachEvent("onunload", function() {var el;for(var d=document.all.length;d--;){el=document.all[d];for(var c=cearElementProps.length;c--;){el[cearElementProps[c]] = null;}}});}

//
//Openbox
function openBox(winWidth, winHeight, fileSrc) {
	var w=(screen.availWidth-winWidth)/2;
	var h=(screen.availHeight-winHeight)/2;
	newParameter = "width=" + winWidth + ",height=" + winHeight + ",addressbar=no,scrollbars=yes,toolbar=no,top="+h+",left="+w+", resizable=no";
    newWindow = window.open (fileSrc, "a", newParameter);
	newWindow.focus();
}
//
var xmlhttp;
function getXMLHTTP()
{
var xmlhttp=null;
if(window.XMLHttpRequest)
{
	xmlhttp=new XMLHttpRequest();
}else if(window.ActiveXObject)
{
	try
	{
		xmlhttp=new ActiveXObject("Msxml2.XMLHTTP");
	}catch(e)
	{
		try
		{
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}catch(e)
		{
			alert('This browser does not support');
			return;
		}
	}

}else
{
	alert('This browser does not support!');
	return;
}
return xmlhttp;
}
//
