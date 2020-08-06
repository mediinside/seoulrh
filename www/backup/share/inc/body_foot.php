</div>
<!-- //#forPrint -->
<?
//일반 HTML 페이지에서 사용될 경우에 필요하여 한번 삽입
include_once "$_SERVER[DOCUMENT_ROOT]/include/connect_mysql.php";
include_once "$_SERVER[DOCUMENT_ROOT]/include/DB_Class_mysql.php";
include_once "$_SERVER[DOCUMENT_ROOT]/include/func.php";
?>
<!-- #body_foot -->
<div id="body_foot">
<?
$cPageCode = substr("0".$d1n,0,2).substr("0".$d2n,0,2).substr("0".$d3n,0,2).substr("0".$d4n,0,2);
if($d5n) {	$cPageCode = $cPageCode.substr("0".$d5n,0,2);	}

$pSiteCode = "1";
?>
<? include_once "$_SERVER[DOCUMENT_ROOT]/include/page_code.php"; ?>
<div id="charge">
<h3>본문 콘텐츠 담당자, 최종수정일</h3>
<dl>
<dt class="manager"><img src="/img/inc/manager.gif" width="62" height="21" alt="담당자" /></dt>
<dd class="manager"><?=$mCharge?> <?=$mPhone?></dd>
<dt class="update"><strong>최종수정일 :</strong></dt>
<dd class="update"><?=$mUpdate?></dd>
</dl>
</div>

<div id="bodyutil">
<h3>본문 유틸리티</h3>
<ul>
<li><a href="/share/ui/printpage.html" onclick="window.open(this.href,'','resizable=yes,menubar=yes,scrollbars=yes,width=737,height=600,left=10,top=10'); return false;" onkeypress="" title="새 창에서 본문 인쇄 미리보기"><img src="/img/inc/btn_print.gif" width="58" height="21" alt="인쇄" title="본문 인쇄" /></a></li>
<li><a href="?" onclick="history.back();return false;"><img src="/img/inc/btn_back.gif" width="58" height="21" alt="뒤로" /></a></li>
<li><a href="#container" title="페이지로 상단으로 이동"><img src="/img/inc/btn_top.gif" width="58" height="21" alt="위로" /></a></li>
</ul>
</div>
<!-- satisfaction -->
<?
$que = mysql_query("select today, totalday from total_count");
$row = mysql_fetch_array($que);
$today = $row[0];
$totalday = $row[1];
mysql_free_result($que);

if($d5n <> ""){
	$strSQL = "select count(*) from page_survey where page_domain='".str_replace("www.","",$_SERVER[HTTP_HOST])."' and page_url='".$d1n."o".$d2n."o".$d3n."o".$d4n."o".$d5n."'";

}else{
	$strSQL = "select count(*) from page_survey where page_domain='".str_replace("www.","",$_SERVER[HTTP_HOST])."' and  page_url='".$d1n."o".$d2n."o".$d3n."o".$d4n."'";
}
$strQue = mysql_query($strSQL);
$row = mysql_fetch_array($strQue);
$survey_t = $row[0];
mysql_free_result($strQue);
mysql_close($connect);
?>
<? include $_SERVER[DOCUMENT_ROOT]."/include/page_survey.php"; ?>
<!-- //satisfaction -->

</div>
<!-- //#body_foot -->