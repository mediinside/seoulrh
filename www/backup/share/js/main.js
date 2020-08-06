/* main page
#2010.05.22. Create
*/
/* 외부참조파일초기화 #2010.05.22. minify */
/*버젼시간은 CSS 수정시마다 변경할 것. all/main/sub 모두 확인( Ex. YYYYmmdd버젼 (20111205V1) )*/
function initExternalRefMain(){var str='';
	str+='<link rel="stylesheet" type="text/css" href="/share/css/main.css">';
	str+='<link rel="stylesheet" type="text/css" href="/share/css/g.css">';
	str+='<script type="text/javascript" href="/share/js/mticker.js"></script>';
	document.writeln(str);
}
initExternalRefMain();

/* Function for main page ───── */

/* 탭메뉴콘텐츠
#2010.05.22. upgrade v2008.06.19
이미지 소스 경로/이름 무관. 텍스트탭가능.
탭그룹id="tab1" 라면 탭메뉴는 tab1m1, tab1m2, .. 탭콘텐츠는 tab1c1, tab1c2, .. 으로 한다.
ex)tabOn("탭그룹id","활성탭id");tabOn("tab1",1);
*/
function tabOn(containerId,a){
	var tabContainer=document.getElementById(containerId);
	var tabid=(tabContainer)?tabContainer.id:"tab"+containerId;//이전코드호환
	var tabTagAll=document.getElementById(tabid).getElementsByTagName("*");
	var tabSum=0;//탭수
	for(var i=0;i<tabTagAll.length;i++){var where=tabTagAll[i].id.indexOf(containerId+"m");if(where!=-1)tabSum++;}
	for(var i=1;i<=tabSum;i++){//탭수만큼루프
		if(i<10){inn="0"+i;}else{inn=""+i;}
		tabMenu=document.getElementById(tabid+"m"+i);
		tabContent=document.getElementById(tabid+"c"+i);
		if(tabMenu){
			if(tabMenu.tagName=="IMG"){
				_imgtype=tabMenu.src.substr(tabMenu.src.length-3,tabMenu.src.length-1);
				tabMenu.src=tabMenu.src.replace("on."+_imgtype, "."+_imgtype);
			}
			if(tabMenu.tagName=="A"){tabMenu.className="";}
		}
		if(tabContent){tabContent.style.display="none";}
	}
	if(a<10){ann="0"+a;}else{ann=""+a;}
	tabMenu=document.getElementById(tabid+"m"+a);
	tabContent=document.getElementById(tabid+"c"+a);
	if(tabMenu){
		if(tabMenu.tagName=="IMG"){
			_imgtype=tabMenu.src.substr(tabMenu.src.length-3,tabMenu.src.length-1);
			tabMenu.src=tabMenu.src.replace("."+_imgtype, "on."+_imgtype);
		}
		if(tabMenu.tagName=="A"){tabMenu.className="on";}
	}
	if(tabContent){tabContent.style.display="block";}
	tabMore=document.getElementById(tabid+"more");
}


/* 메인 슬라이드메뉴 */
function jQmain_visual(select){
	var visual = $(select)//전체마스크
	var wp = visual.find('.slide_menu_wp')//전체감싸는것
	var view = wp.find('.smenu')//하나
	var menu = wp.find('>h3')
	var now = 0;
	var prev = visual.find('.prev')
	var next = visual.find('.next')
	var play = visual.find('.play')
	var stop = visual.find('.stop')
	var totalNum = wp.find('.smenu').length //이미지의갯수
	var bbg = wp.find('.slide_bbg')
	menu.on('click',function(e){
		e.preventDefault ? e.preventDefault() : e.returnValue = false;
		$(this).addClass('on').siblings().removeClass('on')
		now = parseInt($(this).attr('id').substr(4))-1
		nowFn()
	})
	menu.first().trigger('click');
	prev.on('click',function(e){
		e.preventDefault();
			var num;
			now--
			if(now<0){
				now = totalNum-1
			}
			menu.eq(now).addClass('on').siblings().removeClass('on')
			nowFn()
	})
	next.on('click',function(e){
		e.preventDefault();
			var num;
			now++
			if(now>totalNum-1){
				now = 0
			}
			menu.eq(now).addClass('on').siblings().removeClass('on')
			nowFn()
	})
	/*타이머*/


	var timer = setInterval(function(){
		var num;
		now++
		if(now>totalNum-1){
			now = 0
		}
		menu.eq(now).addClass('on').siblings().removeClass('on')
		nowFn()	
	},4000);
	

	play.on('click',function(e){
		e.preventDefault();
		if($(this).hasClass('on')==false){
			$(this).addClass('on').siblings('.stop').removeClass('on')
			timer = setInterval(function(){
				var num;
				now++
				if(now>totalNum-1){
					now = 0
				}
				menu.eq(now).addClass('on').siblings().removeClass('on')
				nowFn()	
			},4000);
		}
	})
	stop.on('click',function(e){
		e.preventDefault();
		stop.addClass('on').siblings('.play').removeClass('on')
		clearInterval(timer)
	})

	view.on('mouseover',function(e){
		e.preventDefault();
		stop.addClass('on').siblings('.play').removeClass('on')
		clearInterval(timer)
	})
	/*//타이머*/
	menu.bind('focusin',function(e){
		e.preventDefault ? e.preventDefault() : e.returnValue = false;
		$(this).addClass('on').siblings().removeClass('on')
		now = parseInt($(this).attr('id').substr(4))-1
		nowFn()
		stop.addClass('on').siblings('.play').removeClass('on')
		clearInterval(timer)
	})
	view.each(function(){
		$(this).on('focusin',function(e){
			now = parseInt($(this).attr('id').substr(6))-1
			menu.eq(now).addClass('on').siblings().removeClass('on')
			nowFn()
			stop.addClass('on').siblings('.play').removeClass('on')
			clearInterval(timer)
		})	
	})
	
	function nowFn(){
		var moveP = -(now*view.width())
		wp.animate({left:moveP}, 200, 'easeInOutExpo')
		bbg.animate({left:(moveP*-1)}, 200, 'easeInOutExpo')
		menu.eq(0).animate({left:(moveP*-1)+411}, 200, 'easeInOutExpo')
		menu.eq(1).animate({left:(moveP*-1)+484}, 200, 'easeInOutExpo')
		menu.eq(2).animate({left:(moveP*-1)+541}, 200, 'easeInOutExpo')
	}
}
