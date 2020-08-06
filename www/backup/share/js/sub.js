/* sub page
#2010.05.22. Create
*/
/* 외부참조파일초기화 #2010.05.22. minify */
/*버젼시간은 CSS 수정시마다 변경할 것. all/main/sub 모두 확인( Ex. YYYYmmdd버젼 (20111205V1) )*/
function initExternalRefSub(){var str='';
	str+='<link rel="stylesheet" type="text/css" href="/share/css/sub.css?20111205V1">';
	str+='<link rel="stylesheet" type="text/css" href="/share/css/board.css?20111205V1">';
	str+='<link rel="stylesheet" type="text/css" href="/share/css/content.css?20111205V1">';
	document.writeln(str);
}initExternalRefSub();

/* Function for sub page ───── */

/* 현재위치 #2010.05.22. minify */
function onLocation(contentId){var obj=document.getElementById(contentId);
	if(obj){var objLastChild=obj.lastChild;
		if(objLastChild){while(objLastChild.nodeName!="A")objLastChild=objLastChild.previousSibling;
			objLastChild.className="on";
}}}

/* 메뉴클릭활성 ─────
#2010.09.30. oldClick 추가
필요)getUriRef();getUriPage();getElementsByClassName();
li의 첫자식요소를 onclick 하면 li에 class="on" 지정.
첫자식이 a요소라면 href로 이동하고 아니면 이동안함.
외부링크면 onclick="return false;" 안함.
ex)initClickOn("메뉴그룹id");//한페이지 - 메뉴그룹안에 콘텐츠그룹 포함. 클릭한 메뉴와콘텐츠on
ex)initClickOn("메뉴그룹id","활성메뉴id");//여러페이지이면 현재메뉴 활성. 한페이지이면 메뉴 클릭시 해당 콘텐츠로 이동.
ex)initClickOn("메뉴그룹id","활성메뉴id","콘텐츠그룹id");//한페이지, 메뉴와 콘텐츠그룹 분리.
*/
function initClickOn(menuGroupId,onMenuId,contentGroupId){
	var objArr=document.getElementById(menuGroupId).getElementsByTagName("li");
	var contentObj=getElementsByClassName(contentGroupId);
	var myUriRef=getUriRef(location.href);
	var contentObjActive=document.getElementById(myUriRef);
	contentObjActive=(contentObjActive)?contentObjActive:contentObj[0];//URI참조없으면첫콘텐츠활성
	var onFlag=false;//활성메뉴id 와 같은 메뉴id 가 있는지 표시.
	for(var i=0;i<objArr.length;i++){
		var objLi=objArr[i];
		var clickObj=objLi.getElementsByTagName("*")[0];
		if(clickObj.tagName!="A"){//첫자식이a요소가아니면
			var returnFlag=false;//retrun false;
			var targetNode=clickObj;
			var newNode=document.createElement("a");
			newNode.href="?";//href 존재해야 IE,FF 키보드운용가능
			objLi.insertBefore(newNode,targetNode);
			newNode.appendChild(targetNode);
			clickObj=newNode;
		}else{//a요소라면
			var returnFlag=(contentGroupId)?false:true;
		}
		if(contentObj[i])contentObj[i].style.display="none";
		var objAUriRef=getUriRef(clickObj.href);
		if(contentObjActive){
			if(objAUriRef==contentObjActive.id){//현재위치활성
				contentObjActive.style.display="block";
				objArr[i].className="on";
				onFlag=true;
			}
		}
		menuClick=function(){
			for(var i=0;i<objArr.length;i++){
				objArr[i].className="";
				if(contentObj[i])contentObj[i].style.display="none";
			}
			this.parentNode.className="on";
			var thisAUriRef=getUriRef(this.href);
			var contentObjActive=document.getElementById(thisAUriRef);
			if(contentObjActive)contentObjActive.style.display="block";
			var thisAUriPage=getUriPage(this.href);
			var myUriPage=getUriPage(location.href);
			var outLink=(thisAUriPage==myUriPage)?false:true;
			if(!outLink)return returnFlag;
		}
		if(objLi.id==onMenuId){//여러 페이지일 경우 현재위치 메뉴 활성
			objLi.className="on";
			onFlag=true;
		}
		if(typeof clickObj.onclick=="function"){clickObj.oldClick=clickObj.onclick;
			clickObj.onclick=function(){this.oldClick();menuClick;return false;}
		}else{clickObj.onclick=menuClick;}
	}
	if((!onMenuId||!onFlag)&&objArr[0]){//한 페이지일 때 초기화. 활성id가없고 li가 존재할 때만 실행. 또는 활성메뉴id 와 같은 메뉴id 가 없을 경우 추가.
		objArr[0].className="on";
	}
}
/* URI#참조리턴 #2010.05.22. minify v2008.09.02
ex)getUriRef("?ctabm=1#boardtemplate1");//"boardtemplate1"
ex)getUriPage("?ctabm=1#boardtemplate1");//"?ctabm=1"
*/
function getUriRef(uri){var myUriRef=uri.slice(uri.indexOf("#")+1,uri.length);	return myUriRef;}
function getUriPage(uri){var endIdx=uri.indexOf("#");endIdx=(endIdx!=-1)?endIdx:uri.length;var myUriRef=uri.slice(0,endIdx);return myUriRef;}
/* http://domscripting.com/book/*/
function getElementsByClassName(clsName){var arr=new Array();var elems=document.getElementsByTagName("*");	for(var i=0;(elem=elems[i]);i++){if(elem.className==clsName){arr[arr.length]=elem;}}return arr;}
/* /메뉴클릭활성 ───── */

/* 부메뉴 ─────
#20110421. focus추가
필요)menuOver();menuOut();
3차 이미지, 텍스트 선택가능
숫자최대값 루프적용
side2Menu 자손 <a>innerHTML</a> → <a><span>innerHTML<span></a>
side3Menu 자손 첫<li> → <li class="first">, 끝<li> → <li class="last">
ex)initSideMenu(<%=d2n%>,<%=d3n%>);//텍스트-텍스트
ex)initSideMenu(<%=d2n%>,<%=d3n%>,<%=d1n%>);//이미지-텍스트
ex)initSideMenu(<%=d2n%>,<%=d3n%>,<%=d1n%>,"/img/inc/n/s");//이미지-텍스트
ex)initSideMenu(<%=d2n%>,<%=d3n%>,<%=d1n%>,"/img/inc/n/s",3);//이미지-이미지
*/
function initSideMenu(a,b,d1n,imgPath,depth){//2,3차메뉴초기화,이벤트할당
	var imgPath=(imgPath)?imgPath:"/img/inc/n/s";
	var sideMenu=document.getElementById("sidemenu");
	sideMenu.tags=sideMenu.getElementsByTagName("*");
	var re1=/[0-9]+/g,sn,snMax=snOld=0;
	for(var i=0;i<sideMenu.tags.length;i++){
		var sn=sideMenu.tags[i].id;
		if(re1.test(sn)==true){sn=sn.match(re1);
			snOld=snMax;
			snMax=parseInt(sn[sn.length-1]);
			if(snOld>snMax)snMax=snOld;
		}
	}
	for(var i=0;i<=snMax;i++){
		var side2Menu=document.getElementById("side2m"+i);
		var side3Menu=document.getElementById("side3m"+i);
		if(side2Menu){
			var inn=(i<10)?"0"+i:""+i;
			if(d1n){//
				d1nn=(d1n<10)?"0"+d1n:""+d1n;
				if(side2Menu.firstChild.firstChild.tagName!="IMG"){//이미지아니면
					
					side2Menu.firstChild.innerHTML='<img src="'+imgPath+d1nn+'_'+inn+'.gif" alt="'+side2Menu.firstChild.innerHTML+'" title=""/>';//이미지요소로대체
				}
			}
			side2MenuA=side2Menu.getElementsByTagName("a")[0];
			if(side2MenuA){
				if(side2MenuA.firstChild.tagName!="SPAN"&&side2MenuA.firstChild.tagName!="IMG"){
					side2MenuA.innerHTML='<span>'+side2MenuA.innerHTML+'</span>';//디자인용
				}
			}
		}
		if(side3Menu){
			side3MenuLi=side3Menu.getElementsByTagName("li");
			for(var j=0;j<side3MenuLi.length;j++){
				if(side3MenuLi[j].firstChild.firstChild.tagName!="IMG"&&depth==3){//이미지아니면
					var sn=side3MenuLi[j].id;sn=sn.match(re1);
					var d2nn=(sn[1]<10)?"0"+sn[1]:""+sn[1];
					var d3nn=(sn[2]<10)?"0"+sn[2]:""+sn[2];
					side3MenuLi[j].firstChild.innerHTML='<img src="'+imgPath+d1nn+'_'+d2nn+'_'+d3nn+'.gif" alt="'+side3MenuLi[j].firstChild.innerHTML+'" title=""/>';//이미지요소로대체
				}
			}
			side3Menu.style.display="none";//비활성
			var side3MenuLastChild=side3Menu.lastChild;//끝li
			if(side3MenuLastChild){
				while(side3MenuLastChild.nodeName!="LI")side3MenuLastChild=side3MenuLastChild.previousSibling;
				side3MenuLastChild.className="last";
			}
			var side3MenuFirstChild=side3Menu.firstChild;//첫li
			if(side3MenuFirstChild){
				while(side3MenuFirstChild.nodeName!="LI")side3MenuFirstChild=side3MenuFirstChild.nextSibling;
				side3MenuFirstChild.className="first";
			}
			/* 3차메뉴 span추가 */
			side3MenuA=side3Menu.getElementsByTagName("a");
			for(var k=0;k<side3MenuA.length;k++){
				if(side3MenuA){
					if(side3MenuA[k].firstChild.tagName!="SPAN"){
						side3MenuA[k].innerHTML='<span>'+side3MenuA[k].innerHTML+'</span>';//디자인용 사용안하면 숨김
					}
				}
			}
		}
	}
	if(a<10){ann="0"+a;}else{ann=""+a;}
	if(b<10){bnn="0"+b;}else{bnn=""+b;}
	side2MenuCurr=document.getElementById("side2m"+a);
	side3Menu=document.getElementById("side3m"+a);
	side3MenuCurr=document.getElementById("side3m"+a+"m"+b);
	if(side2MenuCurr){
		side2MenuCurr.firstChild.className="on";
		if(side2MenuCurr.firstChild.firstChild.tagName=="IMG"){//이미지일경우
			side2MenuCurr.firstChild.firstChild.src=imgPath+d1nn+"_"+ann+"on.gif";
		}
	}
	if(side3Menu){side3Menu.style.display="block";side3Menu.style.fontWeight="normal";}
	if(side3MenuCurr){
		side3MenuCurr.firstChild.className="on";
		if(side3MenuCurr.firstChild.firstChild.tagName=="IMG"){//이미지일경우
			side3MenuCurr.firstChild.firstChild.src=imgPath+d1nn+"_"+ann+"_"+bnn+"on.gif";
		}
	}
	function initSideMenuImg(){//이미지온오프이벤트할당
		sideMenu.img=sideMenu.getElementsByTagName("img");
		for(var i=0;i<sideMenu.img.length;i++){
			if(/on\./.test(sideMenu.img[i].src)==false){//on이미지아닐때
				sideMenu.img[i].parentNode.onmouseover=sideMenu.img[i].parentNode.onfocus=menuOver;
				sideMenu.img[i].parentNode.onmouseout=sideMenu.img[i].parentNode.onblur=menuOut;
			}
		}
	}initSideMenuImg();
	function menuAll(a){//메뉴전체보이기감추기
		for(var i=0;i<snMax;i++){
			side3Menu=document.getElementById("side3m"+i);
			if(side3Menu)side3Menu.style.display=a;
		}
	}
}
/* /부메뉴 ───── */