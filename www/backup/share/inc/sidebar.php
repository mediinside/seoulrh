<!-- #sidebar -->
<div id="sidebar">

<?if($d2n!=0){?><!-- 서브메인 제외 -->

<h2 id="side_title"><a <?=$mAnchor[$d1n][0][0][0]?>><img src="/img/inc/side1h<?=$d1nn?>.gif" width="200" height="172" alt="<?=$mTitle[$d1n][0][0][0]?>" /></a></h2>

<!-- #sidemenu -->
<div id="sidemenu">
<h3>부 메뉴</h3>
<?if($d1n==0){?>
<?}else if($d1n==1){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[1][0][0][0]?>
	<ul id="side3m1">
	<li id="side3m1m1"><?=$mMenu[1][1][0][0]?></li>
	<li id="side3m1m2"><?=$mMenu[1][2][0][0]?></li>
	<li id="side3m1m3"><?=$mMenu[1][3][0][0]?></li>
	<li id="side3m1m4"><?=$mMenu[1][4][0][0]?></li>
	<li id="side3m1m4"><?=$mMenu[1][5][0][0]?></li>
	</ul>
</li>
<li id="side2m2"><?=$mMenu[2][0][0][0]?>
	<ul id="side3m2">
	<li id="side3m2m1"><?=$mMenu[2][1][0][0]?></li>
	<li id="side3m2m2"><?=$mMenu[2][2][0][0]?></li>
	</ul>
</li>
<li id="side2m3"><?=$mMenu[3][0][0][0]?>
	<ul id="side3m3">
	<li id="side3m3m1"><?=$mMenu[3][1][0][0]?></li>
	<li id="side3m3m2"><?=$mMenu[3][2][0][0]?></li>
	<li id="side3m3m3"><?=$mMenu[3][3][0][0]?></li>
	<li id="side3m3m4"><?=$mMenu[3][4][0][0]?></li>
	</ul>
</li>
<li id="side2m4"><?=$mMenu[4][0][0][0]?>
	<ul id="side3m4">
	<li id="side3m4m1"><?=$mMenu[4][1][0][0]?></li>
	<li id="side3m4m2"><?=$mMenu[4][2][0][0]?></li>
	<li id="side3m4m3"><?=$mMenu[4][3][0][0]?></li>
	<li id="side3m4m4"><?=$mMenu[4][4][0][0]?></li>
	</ul>
</li>
<li id="side2m5"><?=$mMenu[1][5][0][0]?></li>
</ul>

<?}else if($d1n==2){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[2][1][0][0]?></li>
<li id="side2m2"><?=$mMenu[2][2][0][0]?></li>
</ul>

<?}else if($d1n==3){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[3][1][0][0]?>
	<ul id="side3m1">
	<li id="side3m1m1"><?=$mMenu[3][1][1][0]?></li>
	<li id="side3m1m2"><?=$mMenu[3][1][2][0]?></li>
	<li id="side3m1m3"><?=$mMenu[3][1][3][0]?></li>
	</ul>
</li>
<li id="side2m2"><?=$mMenu[3][2][0][0]?>
	<ul id="side3m2">
	<li id="side3m2m1"><?=$mMenu[3][2][1][0]?></li>
	<li id="side3m2m2"><?=$mMenu[3][2][2][0]?></li>
	</ul>
</li>
<li id="side2m3"><?=$mMenu[3][3][0][0]?></li>
<li id="side2m4"><?=$mMenu[3][4][0][0]?></li>
</ul>

<?}else if($d1n==4){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[4][1][0][0]?>
	<ul id="side3m1">
	<li id="side3m1m1"><?=$mMenu[4][1][1][0]?></li>
	<li id="side3m1m2"><?=$mMenu[4][1][2][0]?></li>
	<li id="side3m1m3"><?=$mMenu[4][1][3][0]?></li>
	<li id="side3m1m4"><?=$mMenu[4][1][4][0]?></li>
	</ul>
</li>
<li id="side2m2"><?=$mMenu[4][2][0][0]?>
	<ul id="side3m2">
	<li id="side3m2m1"><?=$mMenu[4][2][1][0]?></li>
	<li id="side3m2m2"><?=$mMenu[4][2][2][0]?></li>
	</ul>
</li>
<li id="side2m3"><?=$mMenu[4][3][0][0]?></li>
<li id="side2m4"><?=$mMenu[4][4][0][0]?></li>
</ul>

<?}else if($d1n==5){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[5][1][0][0]?></li>
<li id="side2m2"><?=$mMenu[5][2][0][0]?>
	<ul id="side3m2">
	<li id="side3m2m1"><?=$mMenu[5][2][1][0]?></li>
	<li id="side3m2m2"><?=$mMenu[5][2][2][0]?></li>
	</ul>
</li>
<li id="side2m3"><?=$mMenu[5][3][0][0]?>
	<ul id="side3m3">
	<li id="side3m3m1"><?=$mMenu[5][3][1][0]?></li>
	<li id="side3m3m2"><?=$mMenu[5][3][2][0]?></li>
	<li id="side3m3m3"><?=$mMenu[5][3][3][0]?></li>
	<li id="side3m3m4"><?=$mMenu[5][3][4][0]?></li>
	<li id="side3m3m5"><?=$mMenu[5][3][5][0]?></li>
	<li id="side3m3m6"><?=$mMenu[5][3][6][0]?></li>
	</ul>
</li>
<li id="side2m4"><?=$mMenu[5][4][0][0]?></li>
<li id="side2m5"><?=$mMenu[5][5][0][0]?></li>
<li id="side2m6"><?=$mMenu[5][6][0][0]?></li>
</ul>

<?}else if($d1n==6){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[6][1][0][0]?></li>
<li id="side2m2"><?=$mMenu[6][2][0][0]?>
	<ul id="side3m2">
	<li id="side3m2m1"><?=$mMenu[6][2][1][0]?></li>
	<li id="side3m2m2"><?=$mMenu[6][2][2][0]?></li>
	<li id="side3m2m3"><?=$mMenu[6][2][3][0]?></li>
	<li id="side3m2m4"><?=$mMenu[6][2][4][0]?></li>
	<li id="side3m2m5"><?=$mMenu[6][2][5][0]?></li>
	<li id="side3m2m6"><?=$mMenu[6][2][6][0]?></li>
	</ul>
</li>
<li id="side2m3"><?=$mMenu[6][3][0][0]?>
	<ul id="side3m3">
	<li id="side3m3m1"><?=$mMenu[6][3][1][0]?></li>
	<li id="side3m3m2"><?=$mMenu[6][3][2][0]?></li>
	</ul>
</li>
<li id="side2m4"><?=$mMenu[6][4][0][0]?></li>
</ul>

<?}else if($d1n==7){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[7][1][0][0]?></li>
<li id="side2m2"><?=$mMenu[7][2][0][0]?>
	<ul id="side3m2">
	<li id="side3m2m1"><?=$mMenu[7][2][1][0]?></li>
	<li id="side3m2m2"><?=$mMenu[7][2][2][0]?></li>
	</ul>
</li>
<li id="side2m3"><?=$mMenu[7][3][0][0]?>
	<ul id="side3m3">
	<li id="side3m3m1"><?=$mMenu[7][3][1][0]?></li>
	<li id="side3m3m2"><?=$mMenu[7][3][2][0]?></li>
	</ul>
</li>
<li id="side2m4"><?=$mMenu[7][4][0][0]?></li>
</ul>

<?}else if($d1n==8){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[8][1][0][0]?></li>
<li id="side2m2"><?=$mMenu[8][2][0][0]?></li>
</ul>

<?}else if($d1n==9){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[9][1][0][0]?></li>
</ul>

<?}else if($d1n==10){?>
<ul id="side2m">
<li id="side2m1"><?=$mMenu[10][1][0][0]?></li>
<li id="side2m2"><?=$mMenu[10][2][0][0]?></li>
<li id="side2m3"><?=$mMenu[10][3][0][0]?></li>
<li id="side2m4"><?=$mMenu[10][4][0][0]?></li>
</ul>

<?}else{?>
<?}?>
</div>
<script type="text/javascript">initSideMenu(<?=$d2n?>,<?=$d3n?>);</script>
<!-- /#sidemenu -->

<!-- sm1 -->
<div id="sm1">
<ul>
<li><a <?=$mAnchor[0][3][1][0]?>><img src="/img/inc/sb1.gif" width="56" height="55" alt="<?=$mTitle[0][3][1][0]?>" /></a></li>
<li><a <?=$mAnchor[0][3][2][0]?>><img src="/img/inc/sb2.gif" width="56" height="55" alt="<?=$mTitle[0][3][2][0]?>" /></a></li>
<li><a <?=$mAnchor[0][3][3][0]?>><img src="/img/inc/sb3.gif" width="56" height="55" alt="<?=$mTitle[0][3][3][0]?>" /></a></li>
<li><a <?=$mAnchor[0][3][4][0]?>><img src="/img/inc/sb4.gif" width="56" height="55" alt="<?=$mTitle[0][3][4][0]?>" /></a></li>
</ul>
</div>
<!-- /sm1 -->


<?}/* 서브메인제외 */?>

</div>
<hr />
<!-- /#sidebar -->