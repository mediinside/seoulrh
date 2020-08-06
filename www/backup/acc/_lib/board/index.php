<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?
$d1n = 2;
$d2n = 1;
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



<?
$onMenuId="ctabm".$ctabm;
?>
<? include "./board_head.html"; ?>


<!-- boardtemplate -->
<div id="boardtemplate" class="board">

<div class="boardtemplate" id="boardtemplate1">
<h2>목록</h2>
<? include "./list_head.html"; ?>
<? include "./list.html"; ?>
<? include "./list_foot.html"; ?>
<? include "./page.html"; ?>
</div>
<div class="boardtemplate" id="boardtemplate2">
<h2>목록_갤러리</h2>
<? include "./list_head.html"; ?>
<? include "./list_gallery.html"; ?>
<? include "./list_foot.html"; ?>
<? include "./page.html"; ?>
<br /><br />
<h2>목록_갤러리1(웹진형)</h2>
<? include "./list_head.html"; ?>
<? include "./list_gallery1.html"; ?>
<? include "./list_foot.html"; ?>
<? include "./page.html"; ?>
<br /><br />
<h2>목록_갤러리2</h2>
<? include "./list_head.html"; ?>
<? include "./list_gallery2.html"; ?>
<? include "./list_foot.html"; ?>
<? include "./page.html"; ?>
<br /><br />
<h2>목록_갤러리_혼합1</h2>
<? include "./list_head.html"; ?>
<? include "./list_mix1.html"; ?>
<? include "./list_foot.html"; ?>
<? include "./page.html"; ?>
</div>
<div class="boardtemplate" id="boardtemplate3">
<h2>내용 + 댓글</h2>
<? include "./view.html"; ?>
<? include "./comment.html"; ?>
<? include "./page.html"; ?>
</div>
<div class="boardtemplate" id="boardtemplate4">
<h2>쓰기 + 우편번호 검색</h2>
<h3>table형 <em>- 유동폭에서 장점(px과 %를 함께 사용가능).. 여러 사이트(폭) 적용시 편리함.</em></h3>
<? include "./write_table.html"; ?>
<h3>div형 <em>- table형보다 웹표준 우수함. 유동폭 적용. CSS수정이 복잡. (2단배치시Sf3오차). </em></h3>
<? include "./write.html"; ?>
</div>
<div class="boardtemplate" id="boardtemplate5">
<h2>로그인</h2>
<? include "./login.html"; ?>
<? include "./find.html"; ?>
</div>
<div class="boardtemplate" id="boardtemplate6">
<h2>달력</h2>
<? include "./calendar.html"; ?>
</div>
<div class="boardtemplate" id="boardtemplate7">
</div>
<div class="boardtemplate" id="boardtemplate8">
</div>

</div>
<!-- //boardtemplate -->

<script type="text/javascript">initClickOn("contenttabmenu","<?=onMenuId?>","boardtemplate");</script>




</div>
<!-- //#body_content -->
</div>

<!-- //#body -->
</div>


<!-- //#wrap -->
<? include "$_SERVER[DOCUMENT_ROOT]/share/inc/foot.php"; ?>


</body>
</html>