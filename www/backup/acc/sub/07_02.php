<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
$d1n = 7;
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


<? include "./07_inc.php"; ?>
<br />

<!-- 01 -->
<div class="h2_box01 mgt_m60">
<h2>후원안내</h2>
</div>

<div class="cont_col2_03">

<div class="col203_l mgt_p14">
<p>후원금 사용처, 소득공제 안내 및 후원 계좌번호를 알려드립니다.</p>
</div>

<div class="col203_r"><a <?=$mAnchor[7][2][1][0]?>><img src="/img/sub/btn01.gif" alt="자세히보기" /></a></div>

</div>

<div class="clb line_btm"></div>
<!-- //01 -->


<!-- 02 -->
<div class="h2_box01 mgt_m30">
<h2>후원내역</h2>
</div>

<div class="cont_col2_03">

<div class="col203_l mgt_p14">
<p>후원 내역과 후원금 내역을 알려드립니다.</p>
</div>

<div class="col203_r"><a <?=$mAnchor[7][2][2][0]?>><img src="/img/sub/btn01.gif" alt="자세히보기" /></a></div>

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