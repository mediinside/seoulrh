<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
$d1n = 4;
$d2n = 2;
$d3n = 0;
$d4n = 0;
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/html_head.php"; ?>
</head>
<body class="d1n<?=$d1n?> d2n<?=$d2n?>">

<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/head.php"; ?>
<!-- #wrap -->
<div id="wrap">
<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/sidebar.php"; ?>
<!-- #body -->
<div id="body">
<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/body_head.php"; ?>
<!-- #body_content -->
<div id="body_content">


<? include "./04_02_inc.php"; ?>
<br />

<!-- 01 -->
<div class="h2_box01 mgt_m60">
<h2>클리닉</h2>
</div>

<div class="cont_col2_03">

<div class="col203_l">
<p>뇌졸증, 척수손상, 뇌성마비, 통증, 류마티스, 골다공증, 족부, 부정렬증후군, 의지보조기 등 아동의
조기 진단과 조기 재활치료를 통해 아동의 발달을 돕고, 성장과 함께 나타나는 특별한 필요들을 채워
감으로써 보다 건강한 삶을 살 수 있도록 도와주는 클리닉</p>
</div>

<div class="col203_r"><a <?=$mAnchor[4][2][1][0]?>><img src="/img/sub/btn01.gif" alt="자세히보기" /></a></div>

</div>

<div class="clb line_btm"></div>
<!-- //01 -->


<!-- 02 -->
<div class="h2_box01 mgt_m30">
<h2>특수검사</h2>
</div>

<div class="cont_col2_03">

<div class="col203_l">
<p>뉴로피드백검사, 심경근전도검사, 요류역학검사, 뇌졸중위험인지검사, 심도전검사, 호흡기기능검사,
비디오연하조영제검사, 체온열검사, 골다공증검사 등 질환별 원인 및 정도를 진단할 수 있는 특수검사</p>
</div>

<div class="col203_r"><a <?=$mAnchor[4][2][2][0]?>><img src="/img/sub/btn01.gif" alt="자세히보기" /></a></div>

</div>

<div class="clb line_btm"></div>
<!-- //02 -->

<br />










</div>
<!-- //#body_content -->
</div>

<!-- //#body -->
</div>
</div>
<!-- //#wrap -->
<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/foot.php"; ?>


</body>
</html>