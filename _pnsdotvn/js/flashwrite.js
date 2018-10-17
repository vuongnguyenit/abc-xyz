function flashWrite(url,w,h,id,bg,vars,win){var flashStr="<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' width='"+w+"' height='"+h+"' id='"+id+"' align='middle'>"+"<param name='allowScriptAccess' value='always' />"+"<param name='movie' value='"+url+"' />"+"<param name='FlashVars' value='"+vars+"' />"+"<param name='wmode' value='"+win+"' />"+"<param name='menu' value='false' />"+"<param name='quality' value='high' />"+"<param name='bgcolor' value='"+bg+"' />"+"<embed src='"+url+"' FlashVars='"+vars+"' wmode='"+win+"' menu='false' quality='high' bgcolor='"+bg+"' width='"+w+"' height='"+h+"' name='"+id+"' align='middle' allowScriptAccess='always' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />"+"</object>";document.write(flashStr);}
function openBox(fileSrc,winWidth,winHeight)
{var w=(screen.availWidth-winWidth)/2;var h=(screen.availHeight-winHeight)/2;newParameter="width="+winWidth+",height="+winHeight+",addressbar=no,scrollbars=yes,toolbar=no,top="+h+",left="+w+", resizable=no";newWindow=window.open(fileSrc,"a",newParameter);newWindow.focus();}
function modelessDialogShow(url,width,height)
{var w=(screen.availWidth-width)/2;var h=(screen.availHeight-height)/2;showModalDialog(url,'','dialogWidth:'+width+'px; dialogHeight:'+height+'px; center:1; dialogLeft:'+w+'px; dialogTop:'+h+'px; help:off; resizable:on; status:off;');}
function newWindow(mypage,myname,w,h,scrolla)
{var winl=(screen.availWidth-w)/2;var wint=(screen.availHeight-h)/2;winprops='height='+h+',width='+w+',top='+wint+',left='+winl+',scrollbars='+scrolla+',resizable=1,toolbar=yes,statusbar=yes'
win=window.open(mypage,myname,winprops)}
function Set_Cookie(name,value,expires,path,domain,secure){expires=expires*60*60*24*1000;var today=new Date();var expires_date=new Date(today.getTime()+(expires));var cookieString=name+"="+escape(value)+
((expires)?";expires="+expires_date.toGMTString():"")+
((path)?";path="+path:"")+
((domain)?";domain="+domain:"")+
((secure)?";secure":"");document.cookie=cookieString;}
function bookmark(title,url){if(window.sidebar){window.sidebar.addPanel(title,url,"");}
else if(window.external){window.external.AddFavorite(url,title);}
else if(window.opera&&window.print){return true;}}
function getCookie(Name){var search=Name+"="
var CookieString=document.cookie
var result=null
if(CookieString.length>0){offset=CookieString.indexOf(search)
if(offset!=-1){offset+=search.length
end=CookieString.indexOf(";",offset)
if(end==-1){end=CookieString.length}
result=unescape(CookieString.substring(offset,end))}}
return result;}
function deleteCookie(Name,Path){setCookie(Name,"Deleted",-1,Path)}
var arrayColor=new Array(5);arrayColor[0]="1";arrayColor[1]="2";arrayColor[2]="3";arrayColor[3]="4";arrayColor[4]="5";function ChangeColor(i)
{if(i<=5)
{var theExprire=1000*60*60*24;Set_Cookie("pageColor",arrayColor[i-1],theExprire);getColor();}}