<?php
	include_once("../../_init.php");
	
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.analytics.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Analytics 	= new Analytics;
	$C_Button 		= new Button;	
	
	
	//년월이 관련 변수 설정

	$Year	= date("Y");	
	$Month	= date("m");	
	$Day	= date("d");		
	
	
	$s_date = ($_GET['s_date'] != '') ?$_GET['s_date']:date('Y-m-d');
	
	$e_date = ($_GET['e_date'] != '')?$_GET['e_date']:date('Y-m-d');

		
	$excelHanArr = array("관리자"		=> "nAdminId",
						 "접속일자"		=> "dtRegDate",
						 "접속시간"		=> "dtRegTime",
						 "쿼리"			=> "vcQuery",
						 "아이피"		=> "vcIP");
		
	
	$args = array();
	//$args['Year'] = $Year;
	//$args['Month'] = $Month;
	//$args['Day'] = $Day;
	$args['s_date']			= $s_date;
	$args['e_date']			= $e_date;
	$args['excel_file']		= $_GET['excel_file'];
	$args['excel']			= $excelHanArr;
	$args['show_row']		= 30;
	$args['pagetype']		= "admin";
	$data = "";
	$data = $C_Analytics->Analytics_admin_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);

	/**
	$args = '';
	$args['Year'] = $Year;
	$args['Month'] = $Month;
	$args['Day'] = $Day;
	$arr_tmp = $C_Analytics->Analytics_Total($args);
*/
	include_once($GP -> INC_ADM_PATH."/head.php");
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<!--? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?-->										
			<form name="base_form" id="base_form" method="GET">
			<ul>	
				<li>
					<strong class="tit">검색일자</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>	
					<span><input type="button" id="excel_btn" value="EXCEL" /></span>	
				</li>	          						
			</ul>
			</form>
			</div>
		</div>	
	
		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					 <ul class="analysis_tot">
					 	<li>전체접속수 : <span><?=number_format($totalcount)?></span></li>

					 </ul>
						<table>
            	<colgroup>
              	<col width="8%"></col>
                <col width="12%"></col>
                <col width="10%"></col>
                <col width="10%"></col>
				<col width="10%"></col>
				<col width="*"></col>
                <col width="12%"></col>
              </colgroup>
							<thead>
								<tr>
									<th scope="col">No</th>
									<th scope="col">실행자</th>
									<th scope="col">접속일자</th>
									<th scope="col">접속시간</th>
									<th scope="col">액션종류</th>
									<th scope="col">쿼리</th>									
									<th scope="col">아이피</th>
								</tr>
							</thead>
							<tbody>
								<?
										$dummy = 1;
										for ($i = 0 ; $i < $data_list_cnt ; $i++) {

											switch($data_list[$i]['emActType'])
											{
												case "S1" : $actType[$i] = "Count"; break;
												case "SS" : $actType[$i] = "One Row"; break;
												case "S" : $actType[$i] = "All Row"; break;
												case "I" : $actType[$i] = "Insert"; break;
												case "U" : $actType[$i] = "Update"; break;
												
											}
								?>
								<tr>
									<td style="text-align:center"><?=number_format($data['page_info']['start_num']--)?></td>
									<td style="text-align:center"><?=$data_list[$i]['nAdminId'];?></td>
									<td style="text-align:center"><?=$data_list[$i]['dtRegDate'];?></td>
									<td style="text-align:center"><?=$data_list[$i]['dtRegTime'];?></td>
									<td style="text-align:center"><?=$actType[$i];?></td>
									<td style="text-align:left;"><?=$data_list[$i]['vcQuery'];?></td>
									<td style="text-align:center"><?=$data_list[$i]['vcIP']?></td>
								</tr>
								<?
								$dummy++;
							}
							?>
							</tbody>
						</table>
				</div>			
			</div>
			<ul class="boxBoardPaging">
				<?=$page_link?>
			</ul>
		</div>
		<? include_once($GP -> INC_ADM_PATH."/footer.php"); ?>
	</div>
</div><!-- 전체를 감싸는 Wrap //-->
<script type="text/javascript">

	$(document).ready(function(){	
		
		//엑셀 출력
		$('#excel_btn').click(function(){
				var string = $("#base_form").serialize();
				location.href = "?excel_file=관리자 로그" + "&" + string;
				return false;
		});
		
		callDatePick('s_date');
		callDatePick('e_date');

		$('#search_submit').click(function(){		

			$('#base_form').submit();
			return false;
		});

	});
</script>
</body>
</html>