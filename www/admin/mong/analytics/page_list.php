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
		"접속페이지" => "s_StatusURL",
		"접속수" => "cnt"
	);
		
	
	$args = array();
	$args['s_date'] = $s_date;
	$args['e_date'] = $e_date;
	$args['excel_file']		= $_GET['excel_file'];
	$args['excel']				= $excelHanArr;
	$args['show_row'] = 15;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Analytics->Analytics_Page_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
	
	
	$arr_tmp = $C_Analytics->Analytics_Total($args);
	
	$totalCount = 0;
	for ($i = 0 ; $i < $data_list_cnt ; $i++) {	
		$totalCount = $totalCount + $data_list[$i]['cnt'];
	}
	

	
	include_once($GP -> INC_ADM_PATH."/head.php");
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?>										
			<form name="base_form" id="base_form" method="GET">
			<ul>		
      	<li>
        	<strong class="tit">페이지타입</strong>
          <span>       
          	<select name="tps_type" id="tps_type">
            	<option value="">:::선택:::</option>
            	<?
              	foreach($GP->PAGE_TYPE as $key => $val) {																	
									if(trim($val[0]) == $_GET['tps_type']) {
										echo "<option value='" . trim($val[0]) . "' selected>" . trim($val[1]) . "</option>";										
									}else{
										echo "<option value='" . trim($val[0]) . "'>" . trim($val[1]) . "</option>";									
									}
								}
							?>
						</select>
          </span>
        </li>			
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
              <li>페이지 접속수 : <span><?=$totalcount;?></span></li>
            </ul>			 
						<table>
            	<colgroup>
              	<col width="8%"></col>
                <col width="10%"></col>
                <col width="10%"></col>
                <col width="10%"></col>
                <col width="25%"></col>
                <col width="25%"></col>
                <col width="12%"></col>
              </colgroup>
							<thead>
								<tr>
									<th scope="col">No</th>
                  <th scope="col">페이지</th>
                  <th scope="col">접속일자</th>
									<th scope="col">접속시간</th>
                  <th scope="col">접속페이지</th>
									<th scope="col">접속전페이지</th>                
									<th scope="col">아이피</th>
								</tr>
							</thead>
							<tbody>
								<?
										$dummy = 1;
										for ($i = 0 ; $i < $data_list_cnt ; $i++) {
											$tps_dateitme 		= $data_list[$i]['tps_dateitme'];
											$tps_refer	= $data_list[$i]['tps_refer'];
											$tps_ip 	= $data_list[$i]['tps_ip'];
											$tps_type	 	= $data_list[$i]['tps_type'];
											$tps_url 	= $data_list[$i]['tps_url'];	
											
											$ex_time=explode(" ", $tps_dateitme);							
											$ex_refer = "";
											if ($tps_refer != "-") {
												$ex_referer=explode("?", $tps_refer);		
												$replace_ex_referer=ereg_replace ( "http://", "", $ex_referer[0]);
												$ex_refer = "<a href=\"${tps_refer}\" target=\"new\">${replace_ex_referer}</a> ";			
											}else{
												$ex_refer = "URL 입력 또는 즐겨찾기를 통해 접속"; 
											}
											
											$arr_tmp = explode('?', $tps_refer);
											parse_str($arr_tmp[1], $arr_str);											
											
										?>
								<tr>
									<td><?=$data['page_info']['start_num']--?></td>
                  <td>
                  	<?	
											foreach($GP->PAGE_TYPE as $key => $val) {												
												if(trim($val[0]) == trim($tps_type)) {
													echo $val[1];
												}
											}
										?>
                  </td>
                  <td><?=$ex_time[0]?></td>
									<td><?=$ex_time[1]?></td>
                  <td><?=$tps_url?></td>
									<td><?=$ex_refer?></td>                  
									<td><?=$tps_ip?></td>
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
				location.href = "?excel_file=페이지 통계" + "&" + string;
				return false;
		});

	});
</script>
</body>
</html>