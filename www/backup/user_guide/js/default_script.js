// flashWrite(파일경로, 가로, 세로, 아이디, 배경색, 윈도우모드)
function flashWrite(url,w,h,id,bg,win){

    // 플래시 코드 정의
    var flashStr=
    "<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0' width='"+w+"' height='"+h+"' id='"+id+"' align='middle'>"+
    "<param name='movie' value='"+url+"' />"+
    "<param name='wmode' value='"+win+"' />"+
    "<param name='menu' value='false' />"+
    "<param name='quality' value='high' />"+
    "<param name='bgcolor' value='"+bg+"' />"+
    "<embed src='"+url+"' wmode='"+win+"' menu='false' quality='high' bgcolor='"+bg+"' width='"+w+"' height='"+h+"' name='"+id+"' align='middle' type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/go/getflashplayer' />"+
    "</object>";

    // 플래시 코드 출력
    document.write(flashStr);

}

function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}
function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}

function setPng24(obj) {
  obj.width=obj.height=1;
  obj.className=obj.className.replace(/\bpng24\b/i,'');
  obj.style.filter =
  "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');"
  obj.src='about:blank;';
  return '';
}

// 공지사항 롤오버메뉴
function latest(n,url) {
    for(var i = 1; i < 4; i++) {
        obj = document.getElementById('latest'+i);
        img = document.getElementById('latest_bt'+i);
		document.all.url.value = url;
		
        if ( n == i ) {
            obj.style.display = "block";
                       img.height = 24;
            img.src = "img/notimenu_ov_0"+i+".gif";    
			//document.all.url.value=url+i;
        } else {
            obj.style.display = "none";
                       img.height = 24;
            img.src = "img/notimenu_0"+i+".gif";
        }
    }
}




//팝업창
function pop_win(theURL,winName,features) { //v2.0
  window.open(theURL,winName,features);
}



//즐겨찾기
function bookmark(){
	window.external.AddFavorite('http://www.dreammoa.co.kr','이야기원격평생교육원 - 교육과학기술부 평가인정 교육기관')
}


//패밀리 사이트 바로가기
function getStyle(el, style) {
	var value = el.style[style];
	if(!value)	{
		if(document.defaultView && document.defaultView.getComputedStyle) {
			var css = document.defaultView.getComputedStyle(el, null);
			value = css ? css[style] : null;
		}
		else if (el.currentStyle) value = el.currentStyle[style];
	}
	return value == 'auto' ? null : value;
}

function familyToggle(i) {
	var familyButton = document.getElementById('familyToggle'+i);
	var familyListCon = document.getElementById('familyList'+i);
	familyListCon.style.visibility = "hidden";
	document.documentElement.onclick = function() {
		familyListCon.style.visibility = 'hidden'
	}
	familyButton.onclick = function(e) {
		var event = window.event || e;
		if(event.preventDefault){event.preventDefault(); event.stopPropagation();}
		else {event.returnValue = false; event.cancelBubble = true;}
		familyListCon.style.visibility=(getStyle(familyListCon,'visibility')=='hidden') ? 'visible':'hidden';
	}
	return false;
}


// 이미지 변경
function swapImg(obj, img) {
	var oldImg = obj.src;
	obj.onmouseout = function(){ obj.src = oldImg; }
	obj.src = img;
}

// 메인 네비게이션
$(document).ready(function () {
	$('#header #mainNavi').hover(
		function () {
			$('#header #mainNavi .smenu').stop().slideDown(150);
		}, 
		function () {
			$('#header #mainNavi .smenu').stop().slideUp(150);			
		}
	);
});
