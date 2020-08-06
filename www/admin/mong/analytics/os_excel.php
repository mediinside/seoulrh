<?php
	$excelFile="기타 통계";	
	$excelFile = iconv("UTF-8","EUC-KR",$excelFile);	
	header("Cache-Control:no-cache");
	header("Pragma:no-cache");
	header( "Content-type: application/vnd.ms-excel" ); 
	header( "Content-Disposition: attachment; filename=$excelFile.xls" );
	header( "Content-Description: PHP4 Generated Data" ); 
	header("Cache-control:max-age=0");		
	
	include_once("../../_init.php");
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.analytics.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Analytics 	= new Analytics;
	$C_Button 		= new Button;	
	
	
	//년월이 관련 변수 설정
	if (!$_GET['s_date']) {
		$s_date = date("Y-m-d");		
	}else{
		$s_date = $_GET['s_date'];		
	}
	if (!$_GET['e_date']) {
		$e_date = date("Y-m-d");		
	}else{
		$e_date = $_GET['e_date'];		
	}
		
	
	$args = array();
	$args['s_date'] = $s_date;
	$args['e_date'] = $e_date;
	$args['show_row'] = 15;
	$args['pagetype'] = "admin";
	$data = "";	
	$data = $C_Analytics->Analytics_OS_List(array_merge($_GET,$_POST,$args));
	$data1 = $C_Analytics->Analytics_BW_List(array_merge($_GET,$_POST,$args));
	$data3 = $C_Analytics->Analytics_Bot_List(array_merge($_GET,$_POST,$args));
	$data4 = $C_Analytics->Analytics_Engine_List(array_merge($_GET,$_POST,$args));	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<style>
/* base */
* {
	-webkit-text-size-adjust:none;
}
textarea, select, input, body {
	font:12px Dotum, "돋움", Gulim, "굴림", sans-serif;
	color:#333;
	line-height:18px;
}
body, div, h1, h2, h3, h4, h5, h6, ul, ol, li, dl, dt, dd, p, form, fieldset, input, table, tr, th, td {
	margin:0;
	padding:0;
}
h1, h2, h3, h4, h5, h6 {
	font-weight:normal;
	font-size:100%;
}
ul, ol, li {
	list-style:none;
}
fieldset, img {
	border:none;
}
hr, button img, caption, legend {
	display:none;
}
/* cross */
* html input {
	margin:-1px 0;
}
*:first-child+html input {
	margin:-1px 0
}
input.checkbox {
	width:13px;
	height:13px;
	vertical-align:top;
}
/* a-style */
a {
	color:#333;
	text-decoration:none;
}
a:hover, a:active {
	color:auto;
	text-decoration:none;
}
.wrap {
	width:1150px;
	margin:0 auto;
	padding:21px 0 0 0;
}
.wrap .header {
	width:100%;
	margin:0 auto;
}
. th, td {
	border:1px solid #999;	
}
</style>

</head>
<body>
<div class="wrap">
  <div class="header">
    <div id="list_base">
      <div class="boxMemberBoard">					 
						<table style="width:200px;" border="1">            	
							<thead>
								<tr>
									<th scope="col" bgcolor='CCCCCC'>운영체제</th>
									<th scope="col" bgcolor='CCCCCC'>수량</th>
								</tr>
							</thead>
							<tbody>
								<?
								$dummy = 1;
								for ($i = 0 ; $i < count($data); $i++) {
									$s_OS 		= $data[$i]['s_OS'];
									$cnt			= $data[$i]['cnt'];											
								?>
								<tr>
									<td><?=$s_OS?></td>
									<td><?=$cnt?></td>
								</tr>
								<?							
										}
								?>
							</tbody>
						</table>
				</div>			
        
        <div class="boxMemberBoard">					 
						<table style="width:200px;" border="1">            	
							<thead>
								<tr>
									<th scope="col" bgcolor='CCCCCC'>브라우저</th>
									<th scope="col" bgcolor='CCCCCC'>수량</th>
								</tr>
							</thead>
							<tbody>
								<?
								$dummy = 1;
								for ($i = 0 ; $i < count($data1); $i++) {
									$s_Browser 		= $data1[$i]['s_Browser'];
									$cnt			= $data1[$i]['cnt'];											
								?>
								<tr>
									<td><?=$s_Browser?></td>
									<td><?=$cnt?></td>
								</tr>
								<?							
										}
								?>
							</tbody>
						</table>
				</div>			
        
        <div class="boxMemberBoard">					 
						<table style="width:200px;" border="1">            	
							<thead>
								<tr>
									<th scope="col" bgcolor='CCCCCC'>검색로봇</th>
									<th scope="col" bgcolor='CCCCCC'>수량</th>
								</tr>
							</thead>
							<tbody>
								<?
								$dummy = 1;
								for ($i = 0 ; $i < count($data3); $i++) {
									$s_SearchBot 		= $data3[$i]['s_SearchBot'];
									$cnt			= $data3[$i]['cnt'];											
								?>
								<tr>
									<td><?=$s_SearchBot?></td>
									<td><?=$cnt?></td>
								</tr>
								<?							
										}
								?>
							</tbody>
						</table>
				</div>			
        
        <div class="boxMemberBoard">					 
						<table style="width:200px;" border="1">            	
							<thead>
								<tr>
									<th scope="col" bgcolor='CCCCCC'>검색엔진</th>
									<th scope="col" bgcolor='CCCCCC'>수량</th>
								</tr>
							</thead>
							<tbody>
								<?
								$dummy = 1;
								for ($i = 0 ; $i < count($data4); $i++) {
									$s_SearchEngine 		= $data4[$i]['s_SearchEngine'];
									$cnt								= $data4[$i]['cnt'];											
								?>
								<tr>
									<td><?=$s_SearchEngine?></td>
									<td><?=$cnt?></td>
								</tr>
								<?							
										}
								?>
							</tbody>
						</table>
				</div>	
      
    </div>
    <!--내용 -->
  </div>
  <!-- content -->
</div>
<!-- container -->
<!-- wrap -->
</body>
</html>