<?php
	include_once("../../_init.php");
	
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.analytics.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Analytics 	= new Analytics;
	$C_Button 		= new Button;	
	
	
	//년월이 관련 변수 설정

	$Year = date("Y");	
	$Month = date("m");	
	$Day = date("d");		
	
	
	if($_GET['s_date'] != '') {
		$s_date = $_GET['s_date'];
	}else{
		$s_date = date('Y-m-d');
	}
	
	if($_GET['e_date'] != '') {
		$e_date = $_GET['e_date'];
	}else{
		$e_date = date('Y-m-d');
	}

		
	$excelHanArr = array(
		"접속아이디" => "s_ID",
		"접속시간" => "s_register_date",
		"접속전페이지" => "s_StatusReferer",
		"아이피" => "s_StatusIP"
	);
		
	
	$args = array();
	//$args['Year'] = $Year;
	//$args['Month'] = $Month;
	//$args['Day'] = $Day;
	$args['s_date'] = $s_date;
	$args['e_date'] = $e_date;
	$args['excel_file']		= $_GET['excel_file'];
	$args['excel']				= $excelHanArr;
	$args['show_row'] = 15;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Analytics->AdminLog_Day_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);

	$args = '';
	$args['Year'] = $Year;
	$args['Month'] = $Month;
	$args['Day'] = $Day;
	$arr_tmp = $C_Analytics->AdminLog_Total($args);

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
				
					<strong class="tit">검색일자</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>	
					<span><input type="button" id="excel_btn" value="EXCEL" /></span>	
					
        
       	
        
				<!-- li>
					<span style="padding-right:42px; line-height:24px;">날짜</span>
					<span>
						<select name="Year" class="input" onChange="document.base_form.submit();">
						<?php
						$s_year = date('Y') - 5;
						$e_year = date('Y');												
						for ($i=$s_year;$i<=$e_year;$i++) {
							echo "<option value=".$i;
							if(!$Year) {
								if ($i == date("Y")) echo " selected";
							} else {
								if ($i == "$Year") echo " selected";
							}
							echo ">".$i."</option>";								
						}							
						?>
						</select>년
						<select name="Month" class="input" onChange="document.base_form.submit();">
						<?php
						for ($i=1;$i<=12;$i++) {
							if($i < 10) $num_i = "0".$i;
							else		$num_i = $i;
							echo "<option value=".$num_i;
							if(!$Month) {
								if ($i == date("m")) echo " selected";
							} else {
								if ($i == "$Month") echo " selected";
							}
							echo ">".$i."</option>";								
						}							
						?>
						</select>월							
						<select name="Day" class="input" onChange="document.base_form.submit();">
						<?php
						for ($i=1;$i<=31;$i++) {
							if($i < 10) $num_i = "0".$i;
							else		$num_i = $i;
							echo "<option value=".$num_i;
							if(!$Day) {
								if ($i == date("d")) echo " selected";
							} else {
								if ($i == "$Day") echo " selected";
							}
							echo ">".$i."</option>";								
						}							
						?>
						</select>일
					</span -->
          
				</li>							
			</ul>
			</form>
			</div>
		</div>	
	
		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					 <ul class="analysis_tot">
					 	<li>전체접속수 : <span><?=$arr_tmp[0];?></span></li>
						<li>월간접속수 : <span><?=$arr_tmp[1];?></span></li>
						<li>일간접속수 : <span><?=$arr_tmp[2];?></span></li>
					 </ul>
						<table>
                        <colgroup>
                        <col width="8%"></col>
                        <col width="12%"></col>
                        <col width="*"></col>
                        <col width="12%"></col>
                      </colgroup>
							<thead>
								<tr>
									<th scope="col">No</th>
                  					<th scope="col">접속아이디</th>
									<th scope="col">접속시간</th>
									<th scope="col">접속일자</th>
									<th scope="col">아이피</th>
								</tr>
							</thead>
							<tbody>
								<?
										$dummy = 1;
										for ($i = 0 ; $i < $data_list_cnt ; $i++) {
											$s_register_date 		= $data_list[$i]['s_register_date'];
											$s_ID					= $data_list[$i]['s_ID'];
											$s_StatusIP 			= $data_list[$i]['s_StatusIP'];
											$s_StatusURL 			= $data_list[$i]['s_StatusURL'];	
											
											$ex_time=explode(" ", $s_register_date);							
											$ex_refer = "";
											if ($s_StatusReferer != "-") {
												$ex_referer=explode("?", $s_StatusReferer);		
												$replace_ex_referer=ereg_replace ( "http://", "", $ex_referer[0]);
												$ex_refer = "<a href=\"${s_StatusReferer}\" target=\"new\">${replace_ex_referer}</a> ";			
											}else{
												$ex_refer = "URL 입력 또는 즐겨찾기를 통해 접속"; 
											}
											
											$arr_tmp = explode('?', $s_StatusReferer);
											parse_str($arr_tmp[1], $arr_str);
											
											
										?>
								<tr>
									<td><?=$data['page_info']['start_num']--?></td>
                                    <td><?=$s_ID?></td>
                 					<td><?=$ex_time[0]?></td>
									<td><?=$ex_time[1]?></td>
									<td><?=$s_StatusIP?></td>
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
				location.href = "?excel_file=일별통계" + "&" + string;
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