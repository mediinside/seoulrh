	var bFlashLoaded = false;
	var bWebMode = true;
	
	function init(){
	
		setFlashVars('fbook_2225897353','Config/Config.htm');
	}
	
	function eBookHelp(){
	    var wndHelp = window.open("./help/help.htm", "eBookHelp", "scrollbars=no,status=no,toolbar=no,resizable=no,location=no,menu=no, width=500,height=650");
	    wndHelp.focus();
	}
	
	function thisMovie(movieName){
	    if (navigator.appName.indexOf("Microsoft") != -1) {
	        return window[movieName];
	    }
	    else {
	        return document[movieName];
	    }
	}
	
	function setFirstPage(){
		var search = document.location.search;
		var inx1 = search.indexOf("?");
		var inx2 = search.indexOf("=");
		var fillter = search.substr(inx2+1,search.length-inx2-1);
		
		if(!isInteger(fillter,0)){
		return;
		}
		if(search.substr(inx1+1,inx2-1)=="page"){
			var num = eval(search.substr(inx2+1,search.length-inx2-1));
			thisMovie("EBOOK").Book_goInputPage(num);
		}
	}
	
	function isInteger(contents, mode){
	var isNum = true;
	
		if(contents =="")
			return false;
		for (j=0; (j<contents.length); j++){
		if(mode == 0)
			{
			if((contents.charAt(j) < "0")||(contents.charAt(j) > "9")){
				isNum = false;
			}
		} else {
		if((contents.charAt(j) < "0" || contents.charAt(j) > "9") && contents.charAt(j) != ','){
			isNum=false;
				}
			}
		}
		return isNum;
		}
	function FlashLoaded(){
	
		bFlashLoaded = true;
	}
	
	function debug(dbg){
		alert(dbg);
	}
	
	function APILoaded(){
		thisMovie("EBOOK").Viewport_maxScale(500);
		thisMovie("EBOOK").Viewport_initScale(300);
		setFirstPage();
	}
	
	function setFlashVars(BOOK_ID, CONFIG_URL){
		var isInternetExplorer = navigator.appName.indexOf("Microsoft") != -1; 
		PageObj = isInternetExplorer ? document.all.EBOOK : document.EBOOK; 
		PageObj.SetVariable("BOOK_ID",BOOK_ID);
		PageObj.SetVariable("CONFIG_URL",CONFIG_URL);
	
	}
	
	function loadEBook(){
		if (navigator.appName.indexOf("Microsoft") == -1) {
		  document.writeln("		<embed name=\"EBOOK\" src=\"EBook.swf\" ");
		  document.writeln("			quality=\"high\" bgcolor=\"#ffffff\" ");
		  document.writeln("			width=\"100%\" ");
		 document.writeln("			height=\"100%\" ");
		  document.writeln("			align=\"middle\" ");
		  document.writeln("			wmode=\"transparent\" ");
		  document.writeln("			swLiveConnect=\"true\" ");
		  document.writeln("			allowScriptAccess=\"always\" ");
		  document.writeln("			type=\"application/x-shockwave-flash\" ");
		  document.writeln("			pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />");
		}else{
		  document.writeln("<object id=\"EBOOK\" classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\"");
		  document.writeln(" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" ");
		  document.writeln(" width=\"100%\" height=\"100%\" align=\"middle\">");
		  document.writeln("		<param name=\"wmode\" value=\"transparent\" />");
		  document.writeln("		<param name=\"allowScriptAccess\" value=\"always\" />");
		  document.writeln("		<param name=\"quality\" value=\"high\" />");
		  document.writeln("		<param name=\"bgcolor\" value=\"#ffffff\" />");
		  document.writeln("		<param name=\"movie\" value=\"EBook.swf\" />");
		  document.writeln("	</object>");
		}
	}
	document.onmousemove = function(){
		var obj = document.getElementById("TooltipLayer");
		if(obj.style.visibility){
			obj.style.left = window.event.clientX+15;
			obj.style.top = window.event.clientY+15;
		}		
	}

	function showTooltip(cinfo,_x,_y){
		var obj = document.getElementById("TooltipLayer");		
		if(cinfo.tooltip=="null") return;
		var msg = cinfo.tooltip.replace("\n","<BR>");		
		obj.innerHTML = "<p style='font-size:11px'>"+msg+"</p>";
		obj.style.visibility = "visible";
	}	

	function hideTooltip(msg){				
		var obj = document.getElementById("TooltipLayer");
		obj.style.visibility = "hidden";				
	}
	function popupWindow(_x,_y,_w,_h,_wname,_addr,_fullscreen,_resizable,_centerAlign){
		var channelMode = "";
		var resizable = "";
		if("true"==_fullscreen){
			channelMode = ",channelMode";
	    }
		 if("true"==_resizable){
		   resizable = ",resizable=yes";
		 }
		 if("true"==_centerAlign){
		 	_x = (screen.availWidth - _w)/2.0;
		 	_y = (screen.availHeight - _h) / 2.0;
		}
	    //웹 모드가 아니고 플래시 파일일 경우 뷰어에서 플래시를 띄워준다.
	    var _addr2=_addr;
		if(_addr.indexOf('?')>0){
			_addr2 = _addr.substring(0,_addr.lastIndexOf('?'));
		}
	    if(".swf"==_addr2.substr(_addr.lastIndexOf('.'))
	    &&!bWebMode){
			CmdToApp("ext_flash",_x+"#"+_y+"#"+_w+"#"+_h+"#"+_wname+"#"+_addr.replace('?','$')+"#"+_fullscreen+"#"+_resizable);
			return;
	    }
			if(_wname=="null"){
	  			window.open(_addr,"_blank","left="+_x+", top="+_y+", width="+_w+", height="+_h+" "+channelMode+resizable);
			}else{
	      	window.open(_addr,_wname,"left="+_x+", top="+_y+", width="+_w+", height="+_h+" "+channelMode+resizable);
			}		
		}
	function popupWindow2(param){
		var _x = param.split(",")[0];
		var _y = param.split(",")[1];
		var _w = param.split(",")[2];
		var _h = param.split(",")[3];
		var _addr = param.split(",")[4];
		window.open(_addr,"_blank","left="+_x+", top="+_y+", width="+_w+", height="+_h);
	}
	//App에서 JS 호출
	function CmdFromApp(str_cmd, str_param){
		if(str_cmd=="notify"){
			if(str_param=="app"){
			bWebMode = false;
			}
		}
	}
	function GetWebMode(){
		return bWebMode;
	}
	//JS에서 App 호출
	function CmdToApp(str_cmd, str_param){	
	/******************************************************************************
	*
	*	App에 전달되는 프로토타입(proto type) : #app:명령어@파라미터1#파라미터2...
	*	ex)#app:Exit@param1#param2
	*	ex)#app:1004@param1#param2
	******************************************************************************/
if(str_cmd =="Exit")
{
CmdFromFlash('exit');
window.open("About:blank","_self");
opener=window;
window.close();
}
		var strCallType = "#app:";
		strCallType+=str_cmd;
		strCallType+="@";
		strCallType+=str_param;
		location.href = strCallType;
	}
	function CmdFromFlash2(param){
		var _cmd = param.split(",")[0];
		var _param = param.split(",")[1];
		if(!bWebMode){
		CmdFromFlash(_cmd,_param);
		}
		else{
		if(_cmd=="link"){
		location.href = _param;
		}
		}
	}
	//Flash로부터의 커맨드
	function CmdFromFlash(str_cmd, str_param){	
		if(!bWebMode){
		if(str_cmd=="link"){
			var idx = str_param.indexOf("?");
			var http = str_param.indexOf("http://");
			var html = str_param.indexOf(".htm");
			if(str_param.substr(idx+1,4)=="page" || (http==-1 && html!=-1)){
				location.href=str_param;
				return;
			}
		}
		var strCallType = "#app:";
		strCallType+=str_cmd;
		strCallType+="@";
		strCallType+=str_param;
		location.href = strCallType;
		}
	}
