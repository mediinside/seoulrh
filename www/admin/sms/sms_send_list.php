<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP -> CLS."/class.sms.php");	
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Sms 	= new Sms;
	$C_Button 		= new Button;
	
	if($_GET['year'] == "") {
		$year = date('Y');
	}else{
		$year = $_GET['year'];
	}
	
	$table = "tblSmsSendList_". $year;	 //생성테이블명		
	$ck_table = $C_Func->TableExists($table, $GP->DB_TABLE);	//테이블 존재여부		
	
	if(!$ck_table) {
		$C_Func->put_msg_and_back("해당 년도에 데이터가 없습니다");
		exit();
	}
	
	$args = array();
	$args['show_row'] = 15;
	$args['year'] = $year;
	$args['pagetype'] = "admin";
	$data = "";	
	$data = $C_Sms->Sms_Send_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
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
					<strong class="tit">등록일</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
				</li>			
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="toq_qa" <? if($_GET['search_key'] == "toq_qa"){ echo "selected"; } ?>>질의</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="16" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>			
		<div id="BoardHead" class="boxBoardHead" style="padding-top:10px;">				
				<div class="boxMemberBoard">
					<table>
						<colgroup>
							<col>
							<col>
							<col>
							<col>
							<col>
							<col style="width:50px;">
						</colgroup>
						<thead>
							<tr>
								<th>No</th>
								<th>발송내용</th>
								<th>보낸수</th>
								<th>전송결과</th>
								<th>등록일</th>
								<th>삭제</th>
							</tr>
						</thead>
						<tbody>
							<?				
								if($data_list_cnt > 0) {
									for ($i = 0 ; $i < $data_list_cnt ; $i++) {
										$ssl_idx 		= $data_list[$i]['ssl_idx'];
										$ssl_content 	= $data_list[$i]['ssl_content'];
										$ssl_sendnum	= $data_list[$i]['ssl_sendnum'];
										$ssl_result 	= $data_list[$i]['ssl_result'];
										$ssl_senddate 	= $data_list[$i]['ssl_senddate'];								
			
										$edit_btn = $C_Button -> getButtonDesign('type2','삭제',0,"sms_send_delete('" . $ssl_idx. "', '" . $year. "')", 50,'');
								?>
								<tr>
									<td><?=$data['page_info']['start_num']--?></td>
									<td><?=$ssl_content?></td>
									<td><?=$ssl_sendnum?></td>
									<td><?=$ssl_result?></td>
									<td><?=$ssl_senddate?></td>
									<td><?=$edit_btn?></td>
								</tr>
								<?
									}
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
</body>
</html>
<script type="text/javascript">

	$(document).ready(function(){														 
	
		callDatePick('s_date');
		callDatePick('e_date');

		$('#search_submit').click(function(){
			$('#base_form').submit();
			return false;
		});

	});
	
	function sms_send_delete(ssl_idx, year)
	{		
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/sms_proc.php",
			data: "mode=SMS_SEND_DEL&ssl_idx=" + ssl_idx + "&year=" + year,
			dataType: "text",
			success: function(msg) {

				if($.trim(msg) == "true") {
					alert("삭제되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('삭제에 실패하였습니다.');
					return;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});

	}
</script>