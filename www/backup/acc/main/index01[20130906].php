<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
$d1n = 0;
$d2n = 0;
$d3n = 0;
$d4n = 0;
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/html_head.php"; ?>
</head>
<body class="d1n<?=$d1n?> d2n<?=$d2n?> main">
<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/head.php"; ?>
<!-- #wrap -->
<div id="wrap">
<!-- #body -->
<div id="body">

<style type="text/css">
#picpr{position:absolute;width:980px; height:375px;left:0; top:177px;}
#picpr h3{display:none;}
#picpr #prcon{position:absolute;left:0;top:277;width:980px;height:375px;overflow:hidden;}
#picpr ul{list-style:none;position:absolute;left:0;top:0; }
#picpr ul li{}
#picpr ul li img{vertical-align:top;left:0;top:0;}
#picpr #prnum{position:absolute;left:470px;top:400px;z-index:10;}
#picpr #prnum img{margin:0 0px 0;cursor:pointer;}

</style>

<div style="margin:0 auto;width:980px;text-align:center;">

<!-- picpr -->
<div id="picpr">
<h3>메인이미지 홍보</h3>
<!-- prcon -->
<div id="prcon">
<ul style="margin:0; padding:0;">
<li><a class="n1" href="/acc/sub/01_01.php"><img src="http://seoulrh.whoiserp.com/images/pr01.png" alt="메인 비주얼이미지01" width="980" height="375" border="0" usemap="#Map" />
    <map name="Map" id="Map">
      <area shape="rect" coords="5,5,197,184" href="/acc/sub/01_01.php" />
      <area shape="poly" coords="48,193,202,193,203,3,975,5,976,371,4,372,3,191" href="/acc/sub/01_01.php" />
    </map>
</a></li>
<li><a class="n2" href="/acc/sub/01_01.php"><img src="http://seoulrh.whoiserp.com/images/pr02.png" alt="메인 비주얼이미지02" width="980" height="375" border="0" usemap="#Map2" />
    <map name="Map2" id="Map2">
      <area shape="rect" coords="1,2,977,371" href="/acc/sub/01_01.php" />
    </map>
</a></li>
</ul>
</div>
<!-- //prcon -->
<!-- prnum -->
<div id="prnum">
<img src="/images/01.png" width="15" height="15" alt="1번째 보기" class="n1" />
<img src="/images/02.png" width="15" height="15" alt="2번째 보기" class="n2" />
</div>
<!-- //prnum -->
<!-- prcontrol -->
<!-- <div id="prcontrol">
<a href="#picpr" onclick="playPicpr();return false;" class="play" title="이미지홍보 순환 시작"><img src="/img/main/picpr/b_play.gif" width="15" height="14" alt="시작" class="play" /></a>
<a href="#picpr" onclick="stopPicpr();return false;" class="stop" title="이미지홍보 순환 멈춤"><img src="/img/main/picpr/b_stop.gif" width="15" height="14" alt="멈춤" class="stop" /></a>
</div> -->
<!-- //prcontrol -->
</div>
<!-- //picpr -->

</div>

<script language="javascript" type="text/javascript">
	
function rotatePicpr(){//순환
	if(_picPR.onnum)_picPR.order=_picPR.onnum;
	if(stopState)return false;
	if(_picPR.order<_picPR.prcon.a.length)_picPR.order++;
	else _picPR.order=1;
	picprOnNum(_picPR.order);
	_picPR.onnum=0;
}
function picprOnNum(a){//n번째보기=순환용으로 사용
	var onnum=a;
	var etype=null;
	movePicpr(onnum);
}
function picprOn(e){//n번째보기할당=홍보이미지포커스,순번이미지클릭시
	var e=e?e:window.event;
	var etype=e.type;
	if(etype=="focus")stopPicpr();
	var onnum=this.className.replace("n","");
	_picPR.order=onnum;//2010.06.04추가
	movePicpr(onnum,etype);
	restartInterval();
}
function movePicpr(onnum,etype){//이동+순번이미지활성
	var onnum=parseInt(onnum)-1;
	var brName=navigator.appName.charAt(0);
	if(etype=="focus"&&brName=="M"&&onnum!=0){//IE에서 focus일때
	//_picPR.prcon.inner.style.top=0;
	}else{
		_picPR.prcon.inner.style.top=onnum*(-_picPR.prcon.offsetHeight)+"px";//순환높이=콘텐츠블록높이
	}
	for(var i=0;i<_picPR.prnum.img.length;i++)	{
		var where=_picPR.prnum.img[i].src.indexOf("on.png",0)
		if(where!=-1)_picPR.prnum.img[i].src=_picPR.prnum.img[i].src.replace("on.png",".png");
	}
	_picPR.prnum.img[onnum].src=_picPR.prnum.img[onnum].src.replace(".png","on.png");
	_picPR.onnum=parseInt(onnum)+1;
}
function playPicpr(){//시작
	stopState=false;
	if(!_picPR.prcon.inner.move){//오류방지
		_picPR.prcon.inner.move=setInterval("rotatePicpr()",_picPR.interval);
	}
	if(!_picPR.prcontrol)return false;
	for(var i=0;i<_picPR.prcontrol.img.length;i++){
		_picPR.prcontrol.img[i].src=_picPR.prcontrol.img[i].src.replace("on.png",".png");
		if(_picPR.prcontrol.img[i].className=="play")_picPR.prcontrol.img[i].src=_picPR.prcontrol.img[i].src.replace(".png","on.png");
	}
}
function stopPicpr(){//멈춤
	stopState=true;
	if(_picPR.prcon.inner.move){
		clearInterval(_picPR.prcon.inner.move);
		_picPR.prcon.inner.move=0;//clearInterval실행후남은값(브라우저별다른값)초기화
	}
	var prcontrol=document.getElementById("prcontrol");
	if(!_picPR.prcontrol)return false;
	for(var i=0;i<_picPR.prcontrol.img.length;i++){
		_picPR.prcontrol.img[i].src=_picPR.prcontrol.img[i].src.replace("on.png",".png");
		if(_picPR.prcontrol.img[i].className=="stop")_picPR.prcontrol.img[i].src=_picPR.prcontrol.img[i].src.replace(".png","on.png");
	}
}
function prevPicpr(){//이전
	if(_picPR.order>1)_picPR.order--;
	else _picPR.order=_picPR.prcon.a.length;
	picprOnNum(_picPR.order);
	restartInterval();
}
function nextPicpr(){//다음
	if(_picPR.order<_picPR.prcon.a.length)_picPR.order++;
	else _picPR.order=1;
	picprOnNum(_picPR.order);
	restartInterval();
}
function restartInterval(){//다시시작
	if(_picPR.prcon.inner.move){
		clearInterval(_picPR.prcon.inner.move);
		_picPR.prcon.inner.move=setInterval("rotatePicpr()",_picPR.interval);
	}
}
function initPicpr(interval){//이미지홍보초기화,이벤트할당
	_picPR=document.getElementById("picpr");
	_picPR.interval=(interval)?interval:5000;//순환시간기본값
	_picPR.order=_picPR.onnum=0;
  _picPR.prcon=document.getElementById("prcon");//콘텐츠블록
	_picPR.prcon.inner=_picPR.prcon.getElementsByTagName("ul")[0];
	_picPR.prcon.img0=_picPR.prcon.inner.getElementsByTagName("img")[0];
  _picPR.prcon.a=_picPR.prcon.getElementsByTagName("a");
  _picPR.prnum=document.getElementById("prnum");//순번블록
  _picPR.prnum.img=_picPR.prnum.getElementsByTagName("img");
	_picPR.prcontrol=document.getElementById("prcontrol");
	for(var i=0;i<_picPR.prcon.a.length;i++){
		_picPR.prcon.a[i].onfocus=picprOn;
		_picPR.prcon.a[i].onblur=playPicpr;
		_picPR.prnum.img[i].onclick=picprOn;
	}
	if(!_picPR.prcontrol){//제어블록없으면
		_picPR.prcon.onmouseover=_picPR.prnum.onmouseover=stopPicpr;
		_picPR.prcon.onmouseout=_picPR.prnum.onmouseout=playPicpr;
	}else{
		_picPR.prcontrol.a=_picPR.prcontrol.getElementsByTagName("a");
		_picPR.prcontrol.img=_picPR.prcontrol.getElementsByTagName("img");
		var stopis=false;
		for(var i=0;i<_picPR.prcontrol.a.length;i++){if(_picPR.prcontrol.a[i].className=="stop")stopis=true;}
		if(!stopis){//stop기능이없으면
			_picPR.prcon.onmouseover=_picPR.prnum.onmouseover=stopPicpr;
			_picPR.prcon.onmouseout=_picPR.prnum.onmouseout=playPicpr;
			for(var i=0;i<_picPR.prcontrol.a.length;i++){
				if((_picPR.prcontrol.a[i].className=="prev")||(_picPR.prcontrol.a[i].className=="next")){
					_picPR.prcontrol.a[i].onfocus=_picPR.prcontrol.a[i].onmouseover=stopPicpr;
					_picPR.prcontrol.a[i].onblur=_picPR.prcontrol.a[i].onmouseout=playPicpr;
				}
			}
		}
	}
	playPicpr();//시작(버튼)으로초기화
	rotatePicpr();//순환
}
initPicpr(3000);//순환시간1/1000초

</script>









</div>
<!-- //body -->
</div>
<!-- //#wrap -->
<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/foot.php"; ?>
 <script type="text/javascript" src="/popup/popup.js"></script>
 
 
</body>
</html>