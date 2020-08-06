/* 무브티커(순환,롤링)
#20111221. MoonYoungShin. 첫앵커 포커스시 맨앞으로
멈춤버튼실행여부판단.
(주의!)SF,CR만offsetHeight다른값해결: js이전에css가와야함.
mtickerEl[i].style.display="none"(mtickerEl[i].cont.offsetHeight=0)일 경우 카운터 count=0 상태로 멈춤
마우스오버시멈춤, 호출수로스피드,지연시간보정, 컨텐츠1번렌더링, 줄별너비무관(더미li불필요)
#20100105. 복수라인노출시(총개수가 노출수의 배수가 아닐때), 버튼prev 및 루프리셋문제 해결&delay인자='none'으로 루프해지
'none' 이 들어간 조건문식이 삽입됨
movemTicker()의 마지막 if문 부등호가 다름(==에서<=로)
prevmTicker()에 magicNum 이라는 변수생성 하여 경우의 수에 대응
(css)
#banner1scroll{position:absolute;left:0;top:30px;width:240px;height:15px;overflow:hidden;}
#banner1content{position:absolute;left:0;top:0;}
(ex)초기화,이전,다음,멈춤,시작
<script type="text/javascript" src="<%=sitePath%>/share/js/mticker.js"></script>
initmTicker(document.getElementById("banner1scroll"),document.getElementById("banner1content"),3000);//객체사용.
prevmTicker(1);nextmTicker(1);stopmTicker(1);playmTicker(1);//호출순번(1부터시작)사용.
prevmTicker(document.getElementById("banner1scroll"));//객체사용. initmTicker()호출후사용가능
prevmTicker(document.getElementById(mtickerEl.length);//마지막initmTicker()만사용
(task)focus하면a보이기
*/
var mtickerEl=new Array();
function initmTicker(mtickerContainer,mtickerContent,delay){
	mtickerEl[mtickerEl.length]=mtickerContainer;//컨테이너
	var speed=20;//롤링속도-작을수록빠름
	mtickerContainer.delay=delay;//로테이션속도
	mtickerContainer.n=mtickerEl.length;//순번
	mtickerContainer.moveOffset=mtickerContainer.offsetHeight;//세로움직임값=컨테이너높이
	mtickerContainer.count=0;//시간계산
	mtickerContainer.mtickerOver=false;//true=멈춤(마우스오버or키보드포커스)
	mtickerContainer.btnStop=false;//true=멈춤버튼누른상태
	mtickerContainer.cont=mtickerContent;//콘텐츠
	mtickerContainer.cont.currentTop=0;//콘텐츠현재위치
	//mtickerContainer.cont.innerHTML+=mtickerContainer.cont.innerHTML;//컨텐츠복제하여2번렌더링..롤링할때필요
	//var aa="";
	for(i=0;i<mtickerEl.length;i++){
		if(mtickerEl[i].delay !="none"){
			mtickerEl[i].delayOffset=mtickerEl[i].delay/(speed/mtickerEl.length);//로테이션속도보정
		}else{
			mtickerEl[i].delayOffset="none";
		}
		//var aa=aa+"  "+(mtickerEl[i].delayOffset*speed)/mtickerEl.length;//모든delay값확인
	}
	//alert(aa);
	mtickerContainer.move=setInterval("movemTicker()",speed);
	mtickerContainer.onmouseover=function(){this.mtickerOver=true;}
	mtickerContainer.onmouseout=function(){if(!mtickerContainer.btnStop)this.mtickerOver=false;}
	mtickerContainer.anchor=mtickerContainer.getElementsByTagName("a");
	if(mtickerContainer.anchor.length){//a가존재하면
		for(var i=0;i<mtickerContainer.anchor.length;i++){//키보드포커스제어
			mtickerContainer.anchor[i].onfocus=function(){mtickerContainer.mtickerOver=true;}
			mtickerContainer.anchor[i].onblur=function(){if(!mtickerContainer.btnStop)mtickerContainer.mtickerOver=false;}
		}
		mtickerContainer.anchor[0].onfocus=function(){//첫앵커 포커스시 맨앞으로
			mtickerContainer.mtickerOver=true;
			mtickerContainer.cont.style.top=mtickerContainer.cont.currentTop=0;
		}
		/* mtickerContainer.anchor[mtickerContainer.anchor.length-1].onfocus=function(){//끝앵커포커스. 키보드 후진 진입시 브라우저가 맨뒤로 보내줌.
			mtickerContainer.mtickerOver=true;
			mtickerContainer.cont.style.top=mtickerContainer.offsetHeight-mtickerContainer.cont.offsetHeight+"px";
		} */
	}
}
function movemTicker(){//위로이동
	for(var i=0;i<mtickerEl.length;i++){
		if(mtickerEl[i].delayOffset !="none"){
			if(mtickerEl[i].cont.currentTop%mtickerEl[i].moveOffset==0&&mtickerEl[i].count<mtickerEl[i].delayOffset){
				if(!mtickerEl[i].mtickerOver){//mtickerOver가 false 일때 카운트증가
					if(mtickerEl[i].cont.offsetHeight){mtickerEl[i].count++;}
					else{mtickerEl[i].count=0;}
				}
			}else{
				mtickerEl[i].count=0;
				//mtickerEl[i].cont.currentTop--;//한줄씩위로롤링
				mtickerEl[i].cont.currentTop-=mtickerEl[i].moveOffset;//롤링없이한줄씩순환
				if((mtickerEl[i].cont.currentTop+mtickerEl[i].cont.offsetHeight<=0)||mtickerEl[i].cont.offsetHeight==0){
					mtickerEl[i].cont.style.top=mtickerEl[i].cont.currentTop=mtickerEl[i].count=0;
				}
				mtickerEl[i].cont.style.top=mtickerEl[i].cont.currentTop+"px";
			}
		}
	}
}
//시간제어:a=순번or컨테이너객체
function prevmTicker(a){//이전
	var n=(isNaN(a))?a.n-1:a-1;
	if(!mtickerEl[n])return false;
	mtickerEl[n].count=0;
	mtickerEl[n].cont.currentTop+=mtickerEl[n].moveOffset;
	if(-mtickerEl[n].cont.currentTop<0){
		var magicNum=Math.floor((mtickerEl[n].cont.offsetHeight-1)/mtickerEl[n].moveOffset);//-1이유:개수가 맞아떨어져도 -moveOffset이 되어야 한다.
		mtickerEl[n].cont.currentTop=-(mtickerEl[n].moveOffset*magicNum);
		//mtickerEl[n].cont.currentTop=mtickerEl[n].moveOffset-mtickerEl[n].cont.offsetHeight;
	}
	mtickerEl[n].cont.style.top=mtickerEl[n].cont.currentTop+"px";
}
function nextmTicker(a){//다음
	var n=(isNaN(a))?a.n-1:a-1;
	if(!mtickerEl[n])return false;
	mtickerEl[n].count=0;
	mtickerEl[n].cont.currentTop-=mtickerEl[n].moveOffset;
	if(-mtickerEl[n].cont.currentTop>=mtickerEl[n].cont.offsetHeight){
		mtickerEl[n].cont.currentTop=0;
	}
	mtickerEl[n].cont.style.top=mtickerEl[n].cont.currentTop+"px";
}
function stopmTicker(a){//멈춤
	var n=(isNaN(a))?a.n-1:a-1;
	if(!mtickerEl[n])return false;
	mtickerEl[n].mtickerOver=true;
	mtickerEl[n].btnStop=true;
}
function playmTicker(a){//시작
	var n=(isNaN(a))?a.n-1:a-1;
	if(!mtickerEl[n])return false;
	mtickerEl[n].mtickerOver=false;
	mtickerEl[n].btnStop=false;
}