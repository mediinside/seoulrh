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

<!-- visual -->
<? include "./inc_test01.php"; ?><!-- 메인 이미지 임시 테스트용 -->
<!-- visual -->

<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/head.php"; ?>
<!-- #wrap -->
<div id="wrap">
<!-- #body -->
<div id="body">

<!-- m1 -->
<div id="m1">
<img src="/img/main/m1.png" width="525" height="38" alt="재활은 당신의 삶을 새롭게 디자인합니다." />
</div>
<!-- m1 -->

<!-- m12 -->
<div id="m12">
<h3><img src="/img/main/m12h.gif" width="107" height="10" alt="Notice &amp; news" /></h3>
<span class="new"><img src="/img/main/new.gif" width="32" height="10" alt="new" /></span>
<!-- m12s -->

<div id="m12s">
<ul id="m12c">

<li><a href="?">서울재활병원 하나고등학교공연</a></li>

<li><a href="?">제4회 서울재활병원 임상작업치료세미나</a></li>

<li><a href="?">2013년 6월 천사카페에 초대합니다.</a></li>

<li><a href="?">서울재활병원 영유아낮병동 오픈</a></li>

<li><a href="?">2013 가족에게 쓰는 편지 행사 공모</a></li>

</ul>
</div>
<!-- //m12s -->
<script type="text/javascript">initmTicker(document.getElementById('m12s'),document.getElementById('m12c'),4000);</script>
<div class="control">
<a href="#m12s" onclick="prevmTicker(document.getElementById('m12s'));return false;" title="Notice &amp; news 이전 보기"><img src="/img/main/m12prev.gif" alt="이전" /></a>
<a href="#m12s" onclick="nextmTicker(document.getElementById('m12s'));return false;" title="Notice &amp; news 다음 보기"><img src="/img/main/m12next.gif" alt="다음" /></a>
<!-- <a href="#m12s" onclick="stopmTicker(document.getElementById('m12s'));return false;" title="Notice &amp; news 순환 멈춤"><img src="/img/main/b1stop.gif" alt="멈춤" /></a> -->
</div>
</div>
<!-- //m12 -->


<!-- m13 -->
<div id="m13">
<h3><img src="/img/main/m13h.gif" width="57" height="10" alt="Recruit" /></h3>
<span class="new"><img src="/img/main/new.gif" width="32" height="10" alt="new" /></span>
<!-- m13s -->

<div id="m13s">
<ul id="m13c">

<li><a href="?">서울재활병원 성인물리치료사(운동치료) 모집공고</a></li>

<li><a href="?">성인물리치료(운동) 합격자 발표</a></li>

<li><a href="?">서울재활병원 지원시 함께 제출하세요</a></li>

<li><a href="?">언어치료사 및 성인물리치료사(통증) 합격자</a></li>

<li><a href="?">간호조무사 모집공고</a></li>

</ul>
</div>
<!-- //m13s -->
<script type="text/javascript">initmTicker(document.getElementById('m13s'),document.getElementById('m13c'),4000);</script>
<div class="control">
<a href="#m13s" onclick="prevmTicker(document.getElementById('m13s'));return false;" title="Recruit 이전 보기"><img src="/img/main/m12prev.gif" alt="이전" /></a>
<a href="#m13s" onclick="nextmTicker(document.getElementById('m13s'));return false;" title="Recruit 다음 보기"><img src="/img/main/m12next.gif" alt="다음" /></a>
<!-- <a href="#m13s" onclick="stopmTicker(document.getElementById('m13s'));return false;" title="Recruit 순환 멈춤"><img src="/img/main/b1stop.gif" alt="멈춤" /></a> -->
</div>
</div>
<!-- //m13 -->











</div>
<!-- //body -->
</div>
<!-- //#wrap -->
<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/foot.php"; ?>
 
 
</body>
</html>