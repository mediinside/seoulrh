<script type="text/javascript">//<![CDATA[
function loadJS(){var el=document.body;if(el)el.className+=" js";}
loadJS();
//]]></script><noscript><p>일부 기능은 자바스크립트가 지원되어야 사용하실 수 있습니다.</p></noscript>
<!-- #container_wp -->
<div id="container_wp">
<!-- #container -->
<div id="container">

<div id="skipnavigation">
<ul>
<li><a href="#body">본문 바로가기</a></li>
<li><a href="#topmenu">주 메뉴 바로가기</a></li>
<?if($d1n!=0){?>
<li><a href="#sidemenu">부 메뉴 바로가기</a></li>
<?}?>
</ul>
</div><hr />

<!-- #head -->
<div id="head">

<div id="logo"><a <?=$mAnchor[0][0][0][0]?> title="홈으로 이동"><img src="/img/inc/logo.gif" width="144" height="49" alt="<?=$siteName?>" /></a></div>

<!-- gm1 -->
<div id="gm1">
<h3 class="blind">Global Navigation</h3>
<ul>
<!-- <li><a <?=$mAnchor[0][1][2][0]?>><img src="/img/inc/gm01.gif" alt="<?=$mTitle[0][1][2][0]?>" /></a></li>
<li><a <?=$mAnchor[0][1][3][0]?>><img src="/img/inc/gm02.gif" alt="<?=$mTitle[0][1][3][0]?>" /></a></li> -->
<li><a <?=$mAnchor[0][1][4][0]?>><img src="/img/inc/gm03.gif" alt="<?=$mTitle[0][1][4][0]?>" /></a></li>
</ul>
</div>
<!-- //gm1 -->

<!-- langmenu -->
<div id="language">
<div id="lmselect" class="lmselect">
<h3><img src="/img/inc/languageh3.gif" width="80" height="18" alt="Language" /></h3>
<div id="lmoption" class="lmoption">
<ul>
<li><a <?=$mAnchor[0][1][5][0]?>><?=$mTitle[0][1][5][0]?></a></li>
<li><a <?=$mAnchor[0][1][6][0]?>><?=$mTitle[0][1][6][0]?></a></li>
<li><a <?=$mAnchor[0][1][7][0]?>><?=$mTitle[0][1][7][0]?></a></li>
<li><a <?=$mAnchor[0][1][8][0]?>><?=$mTitle[0][1][8][0]?></a></li>
</ul>
</div>
</div>
</div>
<script type="text/javascript">initmSelect("lmselect","lmoption");</script>
<!-- //langmenu -->

<!-- #topmenu -->
<div id="topmenu">
<h3>주 메뉴</h3><a href="#visual" onfocus="top2menuHideAll();return false;" class="close" title="주 메뉴 모두 닫기"></a>
<ul id="top1menu">
<li><a <?=$mAnchor[1][0][0][0]?> id="top1m1"><?=$mTitle[1][0][0][0]?></a>
	<ul id="top2m1">
	<li id="top2m1m1"><?=$mMenu[1][1][0][0]?></li>
	<li id="top2m1m2"><?=$mMenu[1][2][0][0]?></li>
	<li id="top2m1m3"><?=$mMenu[1][3][0][0]?></li>
	<li id="top2m1m4"><?=$mMenu[1][4][0][0]?></li>
	<li id="top2m1m5"><?=$mMenu[1][5][0][0]?></li>
    </ul>
</li>
<li><a <?=$mAnchor[2][0][0][0]?> id="top1m2"><?=$mTitle[2][0][0][0]?></a>
	<ul id="top2m2">
	<li id="top2m2m1"><?=$mMenu[2][1][0][0]?></li>
	<li id="top2m2m2"><?=$mMenu[2][2][0][0]?></li>
	</ul>
</li>
<li><a <?=$mAnchor[3][0][0][0]?> id="top1m3"><?=$mTitle[3][0][0][0]?></a>
	<ul id="top2m3" style="margin-left:-25px;">
	<li id="top2m3m1"><?=$mMenu[3][1][0][0]?></li>
	<li id="top2m3m2"><?=$mMenu[3][2][0][0]?></li>
	<li id="top2m3m3"><?=$mMenu[3][3][0][0]?></li>
	<li id="top2m3m4"><?=$mMenu[3][4][0][0]?></li>
	</ul>
</li>
<li><a <?=$mAnchor[4][0][0][0]?> id="top1m4"><?=$mTitle[4][0][0][0]?></a>
	<ul id="top2m4">
	<li id="top2m4m1"><?=$mMenu[4][1][0][0]?></li>
	<li id="top2m4m2"><?=$mMenu[4][2][0][0]?></li>
	<li id="top2m4m3"><?=$mMenu[4][3][0][0]?></li>
	<li id="top2m4m4"><?=$mMenu[4][4][0][0]?></li>
	</ul>
</li>
<li><a <?=$mAnchor[5][0][0][0]?> id="top1m5"><?=$mTitle[5][0][0][0]?></a>
	<ul id="top2m5">
	<li id="top2m5m1"><?=$mMenu[5][1][0][0]?></li>
	<li id="top2m5m2"><?=$mMenu[5][2][0][0]?></li>
	<li id="top2m5m3"><?=$mMenu[5][3][0][0]?></li>
	<li id="top2m5m4"><?=$mMenu[5][4][0][0]?></li>
	<li id="top2m5m5"><?=$mMenu[5][5][0][0]?></li>
	<li id="top2m5m6"><?=$mMenu[5][6][0][0]?></li>
	</ul>
</li>
<li><a <?=$mAnchor[6][0][0][0]?> id="top1m6"><?=$mTitle[6][0][0][0]?></a>
	<ul id="top2m6" class="w01">
	<li id="top2m6m1"><?=$mMenu[6][1][0][0]?></li>
	<li id="top2m6m2"><?=$mMenu[6][2][0][0]?></li>
	<li id="top2m6m3"><?=$mMenu[6][3][0][0]?></li>
	<li id="top2m6m4"><?=$mMenu[6][4][0][0]?></li>
	</ul>
</li>
<li><a <?=$mAnchor[7][0][0][0]?> id="top1m7"><?=$mTitle[7][0][0][0]?></a>
	<ul id="top2m7" style="margin-left:20px;">
	<li id="top2m7m1"><?=$mMenu[7][1][0][0]?></li>
	<li id="top2m7m2"><?=$mMenu[7][2][0][0]?></li>
	<li id="top2m7m3"><?=$mMenu[7][3][0][0]?></li>
	<li id="top2m7m4"><?=$mMenu[7][4][0][0]?></li>
	</ul>
</li>
<li><a <?=$mAnchor[8][0][0][0]?> id="top1m8"><?=$mTitle[8][0][0][0]?></a>
	<ul id="top2m8">
	<li id="top2m8m1"><?=$mMenu[8][1][0][0]?></li>
	<li id="top2m8m2"><?=$mMenu[8][2][0][0]?></li>
	</ul>
</li>
</ul>
<a href="#visual" onfocus="top2menuHideAll();return false;" class="close" title="주 메뉴 모두 닫기"></a>
</div>
<script type="text/javascript">initTopMenu(<?=$d1n?>,<?=$d2n?>);</script>
<!-- //#topmenu -->

</div><hr />
<!-- //#head -->

<div id="top2bg"></div>

<?
//$visualAlt[1] = $mTitle[1][0][0][0].". 상단이미지 대체텍스트를 넣어주세요.";
//$visualAlt[2] = $mTitle[2][0][0][0].". 상단이미지 대체텍스트를 넣어주세요.";
//$visualAlt[3] = $mTitle[3][0][0][0].". 상단이미지 대체텍스트를 넣어주세요.";
//$visualAlt[4] = $mTitle[4][0][0][0].". 상단이미지 대체텍스트를 넣어주세요.";
//$visualAlt[5] = $mTitle[5][0][0][0].". 상단이미지 대체텍스트를 넣어주세요.";
//$visualAlt[6] = $mTitle[6][0][0][0].". 상단이미지 대체텍스트를 넣어주세요.";
//$visual_num="";
//if($d1n<10) $visual_num="0".$d1n; else $visual_num="";
?>
<!--?if($d1n!=0){?><!-- 메인제외 -->
<!-- <div id="visual"><img src="/img/inc/visual<?=$d1nn?>.jpg" width="727" height="118" alt="<?=$visualAlt[$d1n]?>" /></div>
<hr /> -->
<!-- //메인제외 --><!--?}?>