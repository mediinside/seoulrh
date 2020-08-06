/* all page
#2010.05.22. Create
*/
/* 외부참조파일초기화 #2010.05.22. minify */
/*버젼시간은 CSS 수정시마다 변경할 것. all/main/sub 모두 확인( Ex. YYYYmmdd버젼 (20111205V1) )*/
//var nowString="";/* 현재시각 */
//function initNow(){var now=new Date();nowString=now.toString();while(nowString.indexOf(" ")!=-1||nowString.indexOf(":")!=-1||nowString.indexOf("+")!=-1){nowString=nowString.replace(" ", "");nowString=nowString.replace(":", "");nowString=nowString.replace("+", "");}}
function initExternalRef(){	var str='';
	str+='<link rel="stylesheet" type="text/css" href="/share/css/all.css?20111205V1">';
	str+='<script type="text/javascript" src="/share/js/iezn_embed_patch.js"></'+'script>';
	str+='<script type="text/javascript" src="/share/js/mticker.js?20111205V1"></'+'script>';
	document.writeln(str);
}initExternalRef();

/* Common Function ───── */

/* User Agent Bug Fix #2010.05.22. minify */
//IE Flicker Bug Fix
(function(){var ie6=document.uniqueID/*IE*/&&document.compatMode/*>=IE6*/&&!window.XMLHttpRequest/*<=IE6*/&& document.execCommand;	try{if(!!ie6){m("BackgroundImageCache", false, true)}}catch(oh){};})();
//IE6 png Bug Fix ex).png24{tmp:expression(setPng24(this));}
function setPng24(obj){obj.width=obj.height="1";obj.className=obj.className.replace(/\bpng24\b/i,"");obj.style.filter=	"progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');";obj.src="";return "";}

/* bg보정
= 브라우저 OK : IE8.0.6001.18702(+호환), IETester(IE7,IE6), IE6.0.2900, FF3.5.3, GC2.0.172.43, Sf4.0.3(531.9.1), Op10.00 
= IE, Sf(GC) 는 창 크기를 변경하고 있는 동안 onresize 이벤트 계속를 발생시킨다.
= FF, Op는 창 크기 변경을 멈추면 onresize 이벤트를 발생시킨다.
*/
function reStyle(){
	var windowWidth = document.documentElement.clientWidth || document.body.clientWidth;
	if(windowWidth<1000){
		document.getElementById('foot').style.backgroundPosition="-300px 0";
	}
	if(windowWidth>1000){
		document.getElementById('foot').style.backgroundPosition="50% 0";
	}
}
window.onresize=reStyle;

/* Image OnOff #2010.05.22. minify v2008.06.16. 기본 img.gif 활성 imgon.gif */
//ex)aEl.onmouseover=imgOver("이미지id");aEl.onmouseout=imgOut("이미지id");
//ex)imgEl.onmouseover=menuOver;aEl.onfocus=menuOver;imgEl.onmouseout=menuOver;aEl.onblur=menuOut;
function imgOver(imgEl){if(imgEl){_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);var where=imgEl.src.indexOf("on."+_imgtype,0);if(where==-1)imgEl.src=imgEl.src.replace("."+_imgtype,"on."+_imgtype);}}
function imgOut(imgEl){if(imgEl){_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);var where=imgEl.src.indexOf("on."+_imgtype,0);if(where!=-1)imgEl.src=imgEl.src.replace("on."+_imgtype,"."+_imgtype);}}
function menuOver(){var imgEl=(this.src)?this:this.getElementsByTagName("img")[0];	if(imgEl){_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);var where=imgEl.src.indexOf("on."+_imgtype,0);if(where==-1)imgEl.src=imgEl.src.replace("."+_imgtype,"on."+_imgtype);}}
function menuOut(){var imgEl=(this.src)?this:this.getElementsByTagName("img")[0];if(imgEl){_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);var where=imgEl.src.indexOf("on."+_imgtype,0);if(where!=-1)imgEl.src=imgEl.src.replace("on."+_imgtype,"."+_imgtype);}}

/* display #2010.05.22. displayToggle 최초display값없을때고려 */
//보이기감추기 인수개수무관 ex)displayOn('id1','id2');displayOff('id1','id2');
function displayOn(){var i,j,a=displayOn.arguments;for(var i=0;i<a.length;i++){var obj=document.getElementById(a[i]);if(obj){obj.style.display="block";}}}
function displayOff(){var i,j,a=displayOff.arguments;for(var i=0;i<a.length;i++){var obj=document.getElementById(a[i]);if(obj){obj.style.display="none";}}}
//하나만보이기 ex)ex)displayOnly('id문자열공통부분',전체수,활성순번,속성값);displayOnly('view',20,1,'table-row');
function displayOnly(coId,num,curr,value){for(var i=0;i<=num;i++){var obj=document.getElementById(coId+i);if(obj){obj.style.display="none";}}var obj=document.getElementById(coId+curr);if(obj){obj.style.display=(value)?value:"block";}}
//보이기감추기 토글 ex)displayToggle('id');displayToggle('id',this);
function displayToggle(id,myObj){var obj=document.getElementById(id);
	if(obj){if(obj.style.display=="none")obj.style.display="block";else obj.style.display="none";}
	if(myObj){if(myObj.parentNode.className=="on")myObj.parentNode.className="";else myObj.parentNode.className="on";}
}

/* active Menu #2010.05.22. minify v2008.04.02 */
//활성상태표시 ex)activeOnly('id문자열공통부분',전체수,활성순번);//class 속성에 "on" 추가)
function activeOnly(coId,num,curr){
	var aa=0;	var re=/(^|\s)on$/;//"on", " on" 매칭//"onx", " onx", "xonx", "xon", "xon " 매칭제외.
	for(var i=1;i<=num;i++){var obj=document.getElementById(coId+i);if(obj){if(re.test(obj.className))obj.className=obj.className.replace(re,"");}}
	var obj=document.getElementById(coId+curr);	if(obj){obj.className=(obj.className)?obj.className+" on":"on";}
}

/* assign Class #2010.05.22. minify v2009.05.27
a 롤오버(포커싱)하면 조부모(ul 또는 dl)에 a의 class값을 할당한다.
<div id="containerId"><ul><li><a href="?" class="m1">menu</a></li></ul></div>
<div id="containerId"><dl><dt><a href="?" class="m1">menu</a></dt><dd></dd></ul></div>
ex)initParentClass("containerId");
*/
function initParentClass(containerId){var obj=document.getElementById(containerId);var aArr=obj.getElementsByTagName("a");
	for(var i=0;i<aArr.length;i++){var aEl=aArr[i];
		if(aEl!=""){var defaultParentClass=aEl.parentNode.parentNode.className;
			aEl.onmouseover=aEl.onfocus=function(){this.parentNode.parentNode.className=this.parentNode.className;}
			aEl.onmouseout=aEl.onblur=function(){this.parentNode.parentNode.className=defaultParentClass;}
}}}

/* 선택메뉴
#2010.06.22.
<select><option>유사동작. CSS장식가능. 이동버튼 불필요. 목록 click 하면 감춤.
ex)
<div id="selectId" class="mselect">
<h3>관련 사이트 바로가기</h3>
<div id="optionId" class="moption">
<ul>
<li><a href="http://naver.com" onclick="window.open(this.href);return false;">네이버 [새 창]</a></li>
<li><a href="http://naver.com">네이버</a></li>
</ul>
</div>
</div>
<script type="text/javascript">initmSelect("selectId","optionId");</script>
*/
var _mselect=new Array();
function initmSelect(selectId,optionId){
	var selectEl=document.getElementById(selectId);
	_mselect[_mselect.length]=selectEl;
	selectEl.first=selectEl.getElementsByTagName("*")[0];/* <h3 /> */
	if(selectEl.first.tagName!="A")	selectEl.first.innerHTML='<a href="#'+optionId+'"><span>'+selectEl.first.innerHTML+'</span></a>';
	selectEl.Aarr=selectEl.getElementsByTagName("a");
	selectEl.option=document.getElementById(optionId);
	_selectClick=0;
	var hideOption=function(){for(i=0;i<_mselect.length;i++){_mselect[i].option.style.visibility="hidden";}}
	var showOption=function(){hideOption();selectEl.option.style.visibility="visible";}
	hideOption();
	selectEl.Aarr[0].onclick=function(){
		if(selectEl.option.style.visibility=="hidden"){showOption();}
		else if(selectEl.option.style.visibility=="visible"){hideOption();}
		_selectClick=1;
		return false;
	}
	selectEl.Aarr[selectEl.Aarr.length-1].onblur=hideOption;
	for(var i=1;i<=selectEl.Aarr.length-1;i++){//i=0은<h3 />자식<a />이므로제외
		if(typeof selectEl.Aarr[i].onclick=="function"){selectEl.Aarr[i].oldClick=selectEl.Aarr[i].onclick;}
		selectEl.Aarr[i].onclick=function(){
			selectEl.Aarr[0].innerHTML="<span>"+this.innerHTML+"</span>";//선택된태그를표시
			if(this.oldClick){this.oldClick();hideOption();return false;
			}
		}
	}
	selectEl.option.onclick=function(){hideOption();}
	document.onclick=function(){
		if(_selectClick==0)hideOption();_selectClick=0;
	}
}

/* 주메뉴 ─────
#20120130. top2menuViewAll ★추가. 1차오버시 2차메뉴 전체뷰
#20111219. MoonYoungShin. 2차메뉴bg 비고정폭png24.
#20110422. MoonYoungShin. focus추가
2차메뉴 이미지 가능하게 수정.
텍스트, 이미지 무관 사용, imgPath 인수 추가
top2Menu 자손 <a>innerHTML</a> → <a><span>innerHTML</span></a>
top2Menu 자손 첫<li> → <li class="first">, 끝<li> → <li class="last">
ex)<div id="top1menu"></div>
<script type="text/javascript">initTopMenu(<%=d1n%>,<%=d2n%>);</script>
ex)initTopMenu(<%=d1n%>,<%=d2n%>,"/img/inc/top1m");
*/
function top2menuView(a){//2차메뉴보기
	if(this.id){
		eidStr=this.id;
		eidNum=eidStr.substring(eidStr.lastIndexOf("m",eidStr.length)+1,eidStr.length);
		a=parseInt(eidNum);
	}
	//top2menuHideAll();//-20120130
	top2menuViewAll();//★추가.20120130
	top1Menu=document.getElementById("top1m"+a);
	top2Menu=document.getElementById("top2m"+a);
	ann=(a<10)?"0"+a:""+a;
	if(a==0){//메인은2차메뉴활성화안함
	}else{
		if(top1Menu){top1Menu.parentNode.className="on";
			var imgEl=top1Menu.childNodes[0]
			if(imgEl.src){
				_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);
				var where=imgEl.src.indexOf("on."+_imgtype,0);
				if(where==-1)imgEl.src=imgEl.src.replace("."+_imgtype,"on."+_imgtype);
			}
			if(top2Menu){top2Menu.style.display="block";}
		}
	}
}
function top2menuHide(a){//2차메뉴감추기
	if(this.id){
		eidStr=this.id;
		eidNum=eidStr.substring(eidStr.lastIndexOf("m",eidStr.length)+1,eidStr.length);
		a=parseInt(eidNum);
	}
	//top2menuHideAll();//-20120130
	top2menuHideAll();//★추가.20120130
	top1Menu=document.getElementById("top1m"+a);
	top2Menu=document.getElementById("top2m"+a);
	top1MenuCurr=document.getElementById("top1m"+d1n);
	top2MenuCurr=document.getElementById("top2m"+d1n);
	ann=(a<10)?"0"+a:""+a;
	if(top1Menu){	top1Menu.parentNode.className=""	;
		var imgEl=top1Menu.childNodes[0]
		if(imgEl.src){
			_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);
			var where=imgEl.src.indexOf("on."+_imgtype,0);
			if(where!=-1)imgEl.src=imgEl.src.replace("on."+_imgtype,"."+_imgtype);
		}
		if(top2Menu){
			top2Menu.style.display="none";
		}
		if(top1MenuCurr){top1MenuCurr.parentNode.className="on";
			var imgEl=top1MenuCurr.childNodes[0]
			if(imgEl.src){
				_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);
				var where=imgEl.src.indexOf("on."+_imgtype,0);
				if(where==-1)imgEl.src=imgEl.src.replace("."+_imgtype,"on."+_imgtype);
			}
		}
		//if(top2MenuCurr){top2MenuCurr.style.display="block";}//-20120130
	}
}
function top2menuHideAll()//2차메뉴모두감추기
{
	top1menuEl=document.getElementById("top1menu").childNodes;
	top2bgEl=document.getElementById("top2bg");//★추가.20120130
	if(top2bgEl){top2bgEl.style.display="none";}//★추가.20120130
	for(i=1;i<=11;i++){//1차메뉴수11이하
		top1Menu=document.getElementById("top1m"+i);
		top2Menu=document.getElementById("top2m"+i);
		inn=(i<10)?"0"+i:""+i;
		if(top1Menu){top1Menu.parentNode.className="";
			var imgEl=top1Menu.childNodes[0]
			if(imgEl.src){
				_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);
				var where=imgEl.src.indexOf("on."+_imgtype,0);
				if(where!=-1)imgEl.src=imgEl.src.replace("on."+_imgtype,"."+_imgtype);
			}
			if(top2Menu){top2Menu.style.display="none";}
		}
	}
}
function top2menuViewAll()//2차메뉴모두보이기//★추가.20120130
{
	top1menuEl=document.getElementById("top1menu").childNodes;
	top2bgEl=document.getElementById("top2bg");
	if(top2bgEl){top2bgEl.style.display="block";}
	for(i=1;i<=11;i++){//1차메뉴수11이하
		top1Menu=document.getElementById("top1m"+i);
		top2Menu=document.getElementById("top2m"+i);
		inn=(i<10)?"0"+i:""+i;
		if(top1Menu){top1Menu.parentNode.className="";
			var imgEl=top1Menu.childNodes[0]
			if(imgEl.src){
				_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);
				var where=imgEl.src.indexOf("on."+_imgtype,0);
				//if(where==-1)imgEl.src=imgEl.src.replace("."+_imgtype,"on."+_imgtype);
			}
			if(top2Menu){top2Menu.style.display="block";}
		}
	}
}
function initTopMenu(d1,d2,imgPath){//1,2차메뉴초기화,이벤트할당
	d1n=d1;d2n=d2;//전역변수
	d1nn=(d1n<10)?"0"+d1n:""+d1n;
	d2nn=(d2n<10)?"0"+d2n:""+d2n;
	top1menuEl=document.getElementById("top1menu").childNodes;
	top2MenuCurrAct=document.getElementById("top2m"+d1+"m"+d2);
	for(i=1;i<=11;i++){//1차메뉴수11이하
		top1Menu=document.getElementById("top1m"+i);
		top2Menu=document.getElementById("top2m"+i);
		if(top1Menu){
			//var spanEl=document.createElement("span");top1Menu.insertBefore(spanEl,top1Menu.childNodes[0]);//이미지대치기법용
			inn=(i<10)?"0"+i:""+i;
			if(top1Menu.firstChild.tagName!="IMG"){//이미지아니면
				var imgPath=(imgPath)?imgPath:"/img/inc/top1m";
				top1Menu.innerHTML='<img src="'+imgPath+inn+'.gif" alt="'+top1Menu.innerHTML+'" title=""/>';//이미지요소로대체, [IE6].png24오류
			}
			top1Menu.style.textIndent="0";//스타일원상태복구.
			top1Menu.onmouseover=top1Menu.onfocus=top2menuView;
			top1Menu.onmouseout=top2menuHide;//onblur하면 IE에서 2차메뉴선택불가
			if(top2Menu){
				for (a=1;a<=10;a++){//2차메뉴이미지로대체
					var ann = (a<10)?"0"+a:""+a;
					top2MenuLi=document.getElementById("top2m"+i+"m"+a);
					if(top2MenuLi){
						top2MenuA=document.getElementById("top2m"+i+"m"+a).getElementsByTagName('a')[0];
						if(top2MenuA.firstChild.tagName!="IMG"){//이미지가아니면
							//top2MenuA.innerHTML='<img src="/img/inc/top2/m'+inn+'_'+ann+'.gif" alt="'+top2MenuA.innerHTML+'" />';//이미지요소로대체
						}
						if((top2MenuA)&&(top2MenuLi!=top2MenuCurrAct)){//현재메뉴가아닐때
							top2MenuLi.onmouseover=top2MenuLi.firstChild.onfocus=menuOver
							top2MenuLi.onmouseout=top2MenuLi.firstChild.onblur=menuOut;
						}
					}
				}
				top2Menu.style.display="none";
				var top2MenuLastChild=top2Menu.lastChild;//끝li
				if(top2MenuLastChild){
					while(top2MenuLastChild.nodeName!="LI")top2MenuLastChild=top2MenuLastChild.previousSibling;
					top2MenuLastChild.className=(top2MenuLastChild.className)? top2MenuLastChild.className+" last":"last";
					top2MenuLastChild.innerHTML='<span class="bgm">'+top2MenuLastChild.innerHTML+'</span><span class="bgr"></span>';//비고정폭png24용.
					//alert(top2MenuLastChild.innerHTML);
				}
				var top2MenuFirstChild=top2Menu.firstChild;//첫li
				if(top2MenuFirstChild){
					while(top2MenuFirstChild.nodeName!="LI")top2MenuFirstChild=top2MenuFirstChild.nextSibling;
					top2MenuFirstChild.className=(top2MenuFirstChild.className)? top2MenuFirstChild.className+" first":"first";
					top2MenuFirstChild.innerHTML='<span class="bgl"></span><span class="bgm">'+top2MenuFirstChild.innerHTML+'</span>';//비고정폭png24용.
				}
				top2Menu.onmouseover=top2Menu.onfocus=top2menuView;
				top2Menu.onmouseout=top2Menu.onblur=top2menuHide;
				top2MenuAs=top2Menu.getElementsByTagName("a");
				if(top2MenuAs){
					for(j=0;j<top2MenuAs.length;j++){
						top2MenuAs[j].innerHTML='<span>'+top2MenuAs[j].innerHTML+'</span>';//장식용
					}
				}
			}
		}
	}
	//2차메뉴활성
	if(top2MenuCurrAct){
		top2MenuCurrAct.getElementsByTagName("a")[0].className="on";//텍스트용
		var imgEl=top2MenuCurrAct.getElementsByTagName("img")[0];
		if(imgEl){
			_imgtype=imgEl.src.substr(imgEl.src.length-3,imgEl.src.length-1);
			var where=imgEl.src.indexOf("on."+_imgtype,0);
			if(where==-1)imgEl.src=imgEl.src.replace("."+_imgtype,"on."+_imgtype);
		}
	}
	top2menuHide(d1);//메뉴활성초기화
}
/* /주메뉴 ───── */