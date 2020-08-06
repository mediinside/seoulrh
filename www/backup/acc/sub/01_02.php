<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
$d1n = 1;
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

<? include "./01_inc.php"; ?>
<br />

<!-- 01 -->
<div class="h2_box01" style="margin-top:80px;">
<h2>입원안내</h2>
</div>

<div class="cont_col2_03">

<div class="col203_l">
<ul class="bu">
<li>내용 : 입원신청안내, 입원상담, 입원예정일 통보, 입원안내, 퇴원안내 등</li>
<li>병동위치 : 성인 - 신관 8층 , 소아/청소년 - 본관 3,4층</li>
<li>입원상담 : 02-6020-3010(성인), 02-6020-3011(영유아/소아/청소년)</li>
</ul>
</div>

<div class="col203_r"><a <?=$mAnchor[1][1][1][0]?>><img src="/img/sub/btn01.gif" alt="자세히보기" /></a></div>

</div>

<div class="clb line_btm"></div>
<!-- //01 -->


<!-- 02 -->
<div class="h2_box01" style="margin-top:28px;">
<h2>병동소개</h2>
</div>

<div class="cont_col2_03">

<div class="col203_l">
<ul class="bu">
<li>외래에 내원한 환자 중 집중적인 재활치료가 필요한 경우 재활병동에 입원하게 됩니다.</li>
<li>입원대상 :  아급성기 환자, 집중치료 및 합병증 관리가 필요한 만성기 환자, 집중 재활치료가 필요한 아동</li>
<li>입원치료 : 전반적으로 평가하고 세밀한 치료 계획을 세울 수 있는 장점</li>
</ul>
</div>

<div class="col203_r"><a <?=$mAnchor[1][1][2][0]?>><img src="/img/sub/btn01.gif" alt="자세히보기" /></a></div>

</div>

<div class="clb line_btm"></div>
<!-- //02 -->







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