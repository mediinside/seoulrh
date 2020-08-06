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

<div class="title fs20 ls-1" style="padding:25px 0 25px 50px;background:#f1f1f1; border-top:1px solid #e4e4e4;"><strong>세미나 신청하기</strong></div>

<!-- 쓰기폼 -->
<form action="?">
<!-- fieldset -->
<fieldset><legend class="dpn">쓰기폼</legend>

<table border="1" class="write2" summary="멀티게시판 쓰기 폼">
<col style="width: 20%;" /><col  />
<tbody>
<tr class="first">
<th scope="row" class="item"><label for="tname">이&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;름</label></th>
<td class="text"><label for="kpassword">국문</label> <input type="text" name="kname" id="kname" value="" class="text" style="width:100px;" /> &nbsp;&nbsp;<label for="tpassword">영문</label> <input type="password" name="tpassword" id="tpassword" value="" class="text" style="width:100px;" /></td>
</tr>
<tr>
<th scope="row" class="item">성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;별</th>
<td class="text">
<input type="radio" name="radio1" id="radio1_1" value="" class="radio" /><label for="radio1_1">  여자</label> &nbsp;&nbsp; 
<input type="radio" name="radio1" id="radio1_2" value="" class="radio" /><label for="radio1_2">  남자</label>
</td>
</tr>

<tr>
<th scope="row" class="item">생&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;일</th>
<td class="text">
<select name="year" OnChange="if(this.value) frm.hp2.focus();" class="put_write" style="width:80px;">
<option value="">선택</option>
<option value="010">2013</option>
<option value="011">2012</option>
<option value="016">2011</option>
<option value="017">2010</option>
<option value="018">2009</option>
<option value="019">2008</option>
</select> -
<input type="text" name="month" id="month" value="" class="text" style="width:80px;" /> - 
<input type="text" name="day" id="day" value="" class="text" style="width:80px;" />
</td>
</tr>

<tr>
<th scope="row" class="item">연&nbsp;락&nbsp;처</th>
<td class="text">
<select name="hp1" OnChange="if(this.value) frm.hp2.focus();" class="put_write" style="width:80px;">
<option value="">선택</option>
<option value="010">010</option>
<option value="011">011</option>
<option value="016">016</option>
<option value="017">017</option>
<option value="018">018</option>
<option value="019">019</option>
</select> -
<input type="text" name="number1" id="number1" value="" class="text" style="width:80px;" /> - 
<input type="text" name="number2" id="number2" value="" class="text" style="width:80px;" />
</td>
</tr>

<tr>
<th scope="row" class="item"><label for="tmail">이&nbsp;메&nbsp;일</label></th>
<td><input type="text" name="tmail" id="tmail" value="" class="text" style="width:150px;" /> <!-- <label for="checkbox1"><input type="checkbox" name="checkbox1" id="checkbox1" value="" checked="checked" class="checkbox" />응답메일 수신</label> --></td>
</tr>

<tr>
<th scope="row" class="item"><label for="request01">신&nbsp;청&nbsp;자</label></th>
<td><label for="checkbox1"><input type="checkbox" name="checkbox1" id="checkbox1" value="" checked="checked" class="checkbox" /> 학생</label> &nbsp;
<label for="checkbox2"><input type="checkbox" name="checkbox2" id="checkbox2" value="" class="checkbox" /> 치료사</label> &nbsp;
<label for="checkbox2"><input type="checkbox" name="checkbox2" id="checkbox2" value="" class="checkbox" /> 의사</label> &nbsp;
<label for="checkbox2"><input type="checkbox" name="checkbox2" id="checkbox2" value="" class="checkbox" /> 기타</label><br />
학교 <input type="text" name="item01" id="item01" value="" class="text" style="width:150px;" />  &nbsp;
근무처 <input type="text" name="item02" id="item02" value="" class="text" style="width:150px;" /><br />
<div style="padding-top:3px;">학년 <input type="text" name="item03" id="item03" value="" class="text" style="width:150px;" /></div></td>
</tr> 

<tr>
<th scope="row" class="item"><label for="request02">신청경로</label></th>
<td><label for="checkbox1"><input type="checkbox" name="checkbox1" id="checkbox1" value="" checked="checked" class="checkbox" /> 홈페이지 공지</label> &nbsp;
<label for="checkbox2"><input type="checkbox" name="checkbox2" id="checkbox2" value="" class="checkbox" /> 학교방문</label> &nbsp;
<label for="checkbox2"><input type="checkbox" name="checkbox2" id="checkbox2" value="" class="checkbox" /> 지인소개</label> &nbsp;
<label for="checkbox2"><input type="checkbox" name="checkbox2" id="checkbox2" value="" class="checkbox" /> 커뮤니티 공지</label> &nbsp;
<label for="checkbox2"><input type="checkbox" name="checkbox2" id="checkbox2" value="" class="checkbox" /> 기타</label></td>
</tr>

<tr>
<th scope="row" class="item"><label for="tcontent">신청동기</label></th>
<td class="text">
<textarea name="tcontent" id="tcontent" rows="5" cols="80"></textarea></td>
</tr>
</tbody>
</table>

</fieldset>
<!-- //fieldset -->

<!-- infomenu -->
<div class="center">
<a href="?"><img src="/img/board/btn_request2.gif" width="77" height="26" alt="신청" /></a> &nbsp; &nbsp;<a href="?"><img src="/img/board/btn_cancel.gif" width="77" height="26" alt="취소" /></a>
</div>

<!-- //infomenu -->

</form>
<!-- //쓰기폼 -->

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