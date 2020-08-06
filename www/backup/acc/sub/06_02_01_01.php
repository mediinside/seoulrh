<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
$d1n = 6;
$d2n = 2;
$d3n = 1;
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

<br />

<!-- board -->
<div class="board">

<!-- view -->
<div class="view">

<h2 class="dpn">게시물 내용</h2>
<!-- info -->
<div class="info">
<dl class="col4">
<dt>번호</dt>
<dd style="width:45%;">1</dd>
<dt>접수현황</dt>
<dd style="width:20%;"><img src="/img/board/accept_ing.gif" width="52" height="19" alt="접수중" /></dd>
<dt>제목</dt>
<dd style="width:45%;">제10회 서울재활병원 보행훈련 세미나</dd>
<dt>접수기간</dt>
<dd style="width:20%;">2013.08.19~2013.09.20</dd>
</dl>
</div>
<!-- //info -->

<!-- substance -->
<div class="substance">

<!-- 신청하기 -->
<div class="cont_col2_02">

<div class="col202_l"><img src="/img/board/view_sm_img01.jpg" width="212" height="212" alt="강좌 사진" class="picture" /></div>

<div class="col202_r">

<div class="info" style="border-top:1px solid #ddd; width:100%;">
<dl class="col2">
<dt style="background:#fafafa;">강좌명</dt>
<dd style="width:81%;background:#fafafa;"><strong>제1회 작업치료세미나</strong></dd>
<dt>계좌번호<br />&nbsp;&nbsp;</dt>
<dd style="width:81%;">* 신한은행 <strong>100-025-475515</strong><br />
* 예금주 : 서울재활병원 이지선</dd>
<dt>문의</dt>
<dd style="width:81%;">* 문의 : 교육센터 양승신 주임  Tel.02-6020-3020</dd>
</dl>
</div>

<div class="tar" style="border-bottom:1px solid #ddd; padding:16px 0"><a href="?"><img src="/img/board/btn_request.gif" width="134" height="43" alt="신청하기" /></a></div>

</div>

<div class="clb"></div>
<!-- //신청하기 -->

<!-- 게시판 view 탭메뉴 -->
<div id="contenttabmenu4">
<h2 class="bg">본문 메뉴</h2>
<ul>
<li id="ctabm1"><a href="#cnt01">교육내용</a></li>
<li id="ctabm2"><a href="#cnt02">환불내용</a></li>
</ul>
</div>
<script type="text/javascript">initClickOn('contenttabmenu4','ctabm<?=$ctab?>');</script>
<!-- //게시판 view 탭메뉴 -->

<!-- 탭메뉴 내용 01 -->
<div id="cnt01">
<? $ctab = 1; ?>

<p>서울재활병원은 작업치료를 전공하는 학생들에게 임상에서의 작업치료에 대한 이해와 그와 관련한 학문적 소양을 높이기 위하여 '서울재활병원 작업치료 세미나'을 개최합니다.</p>

<ul class="bu3">
<li><em>* 장소 :</em>   서울재활병원 신관 7층 윤한기념관</li>
<li><em>* 참가대상 :</em>   치료사, 재활전공 학생 선착순 100명</li>
<li><em>* 교육비 :</em>   1,000원</li>
<li><em>* 세미나일정 :</em></li>
</ul>


<table class="t4 w60" summary="스웨디시 아로마 테라피 코스별 일반가, 회원가를 안내하는 표입니다.">
<caption class="dpn">스웨디시 아로마 테라피</caption>
<col width="30%" /><col />
<thead>
  <tr>
    <th scope="col">시간</th>
    <th scope="col">강의 내용</th>
  </tr>
</thead>
<tbody>
  <tr>
    <th scope="row">08:30~09:00(30)</th>
    <td>등록 및 접수</td>
  </tr>
  <tr>
    <th scope="row">09:00~09:10(10)</th>
    <td>병원장 인사말 / 강의일정 소개</td>
  </tr>
  <tr>
    <th scope="row">09:10~10:10(60)</th>
    <td>소아작업치료 소개</td>
  </tr>
  <tr>
    <th scope="row">10:10~10:20(10)</th>
    <td>Coffee Break</td>
  </tr>
  <tr>
    <th scope="row">10:20~11:20(60)</th>
    <td>소아환자의 적용사례</td>
  </tr>
  <tr>
    <th scope="row">11:20~11:30(10)</th>
    <td>Coffee Break</td>
  </tr>
  <tr>
    <th scope="row">11:30~13:00(90)</th>
    <td>청소년작업치료 소개와 적용사례</td>
  </tr>
  <tr>
    <th scope="row">13:00~14:00(60)</th>
    <td>점심식사</td>
  </tr>
  <tr>
    <th scope="row">14:00~15:30(90)</th>
    <td>성인작업치료 소개</td>
  </tr>
  <tr>
    <th scope="row">15:30~15:50(20)</th>
    <td>Coffee Break</td>
  </tr>
  <tr>
    <th scope="row">15:50~17:00(70)</th>
    <td>성인환자의 적용사례</td>
  </tr>
  <tr>
    <th scope="row">17:00~17:40(40)</th>
    <td>작업치료실 가상체험</td>
  </tr>
  <tr>
    <th scope="row">17:40~18:00(20)</th>
    <td>Q&amp;A / 설문지 작성 / 수료증 수여 </td>
  </tr>
</tbody>
</table>

</div>
<!-- //탭메뉴 내용 01 -->

<!-- 탭메뉴 내용 02 -->
<div id="cnt02">
<? $ctab = 2; ?>

<ul class="bu3">
<li><span class="fc_bb01">* 접수방법 :</span> 기간 내 입금과 홈페이지에서 신청서 접수가 되어야 접수 완료됩니다.</li>
<li><span class="fc_bb01">* 입금방법 :</span> 신청서와 동일한 이름과 학교명을 기록하여 강의일 10일전까지 입금합니다.</li>
<li><span class="fc_bb01">* 신청취소 :</span> 강의일 10일전까지 취소 및 환불이 가능하며, 이후는 불가능합니다.<br />
<span style="margin-left:70px;">환불처리는 세미나 이후 일괄처리 합니다.</span></li>
<li><span class="fc_bb01">* 수료증 :</span>   8시간 강의를 모두 들었을 경우 수료증을 수여합니다.</li>
</ul>

</div>
<!-- //탭메뉴 내용 02 -->


</div>
<!-- //substance -->

</div>
<!-- //view -->

<!-- infomenu -->
<div class="infomenu page">
<div class="left">
</div>
<div class="right">
<!-- <a href="?"><img src="/img/board/btn_list.gif" width="40" height="21" alt="목록" /></a> -->
<!-- <a href="?" class="button">이전</a>
<a href="?" class="button">다음</a> -->
<a href="?"><img src="/img/board/btn_list.gif" alt="목록" /></a>
<!-- <a href="?"><img src="/img/board/btn_write.gif" alt="목록" /> --></a>
</div>
</div>
<!-- //infomenu -->

</div>
<!-- //board -->

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