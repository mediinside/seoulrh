<?php
	include_once("../../_init.php");
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.recruit.php");	
	include_once($GP->CLS."class.button.php");
	include_once($GP -> CLS."class.jhboard.php");
	$C_JHBoard = new JHBoard();
	$C_ListClass 	= new ListClass;
	$C_Recruit 	= new Recruit;
	$C_Button 		= new Button;
	
	ini_set("memory_limit" , -1);
	
	$excelHanArr = array(
		"이름" => "rc_name",
		"이메일" => "rc_email",
		"성별" => "rc_sex",
		"생년월일" => "rc_birth",
		"연락처" => "rc_mobile",
		"우편번호" => "rc_post",
		"주소" => "rc_addr1",
		"상세주소" => "rc_addr2",
		"합격여부" => "rc_pass",
		"등록일자" => "rc_regdate"
	);
	
	$args = array();
	$args['show_row'] = 20;
	$args['pagetype'] = "admin";	
	$args['excel_file']		= $_GET['excel_file'];
	$args['excel']			= $excelHanArr;

	$data = "";
	$data = $C_Recruit->Recruit_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);


	$args = '';
	$args['jb_code'] = "20";
	$args['to_go'] = "N";
	//$args['limit']  = " limit 0, 10 ";
	$rc_data = $C_JHBoard->Board_Main_Data($args);
	
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
            <strong class="tit">등록일자</strong>
            <span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
            <span>~</span>
            <span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
          </li>			          	
          <li>
            <strong class="tit">채용안내</strong>
            <span>
            	<select name="jb_idx" id="jb_idx">
              	<option value="">:::선택::::</option>
              	<?
                	for($i=0; $i<count($rc_data); $i++) {
						$jb_idx = $rc_data[$i]['jb_idx'];
						$jb_title = $rc_data[$i]['jb_title'];
						if($jb_idx == $_GET['jb_idx']) {
							echo "<option value='" . $jb_idx ."' selected>" . $jb_title . "</option>";
						}else{
							echo "<option value='" . $jb_idx ."'>" . $jb_title . "</option>";
						}
					}
				?>
              </select>
            </span>
          </li>
			<li>
				<strong class="tit">합격여부</strong>
				<span>
					<input type="radio" name="rc_pass" value="" checked />전부
					<input type="radio" name="rc_pass" value="Y" <? if($_GET['rc_pass'] == "Y"){ echo "checked";}?>/>합격
					<input type="radio" name="rc_pass" value="N" <? if($_GET['rc_pass'] == "N"){ echo "checked";}?>/>불합격
					<input type="radio" name="rc_pass" value="G" <? if($_GET['rc_pass'] == "G"){ echo "checked";}?>/>심사중
				</span>
			</li>
          <li>
            <strong class="tit">검색조건</strong>
            <span>
            <select name="search_key" id="search_key">
              <option value="">:: 선택 ::</option>
              <option value="rc_name" <? if($_GET['search_key'] == "rc_name"){ echo "selected";}?> >성명</option>
              <option value="rc_mobile" <? if($_GET['search_key'] == "rc_mobile"){ echo "selected";}?>>연락처</option>
              <option value="rc_email" <? if($_GET['search_key'] == "rc_email"){ echo "selected";}?>>이메일</option>
            </select>
            </span>
            <span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="17" /></span>
            <span><button id="search_submit" class="btnSearch ">검색</button></span>
            <span><input type="button" id="excel_btn" value="EXCEL" /></span>
			<!-- span><input type="button" id="sms_btn" value="문자" /></span>
			<span><input type="button" id="email_btn" value="메일" /></span>
			<span><input type="button" id="pass_btn" value="합격" /></span>
			<span><input type="button" id="no_pass_btn" value="불합격" /></span -->
			<span><input type="button" id="del_all_btn" value="일괄삭제" /></span>
          </li>
        </ul>
        </form>
			</div>
		</div>
    
			<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					<table>
						<colgroup>
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
							<col style="width:150px;"/>
						</colgroup>
						<thead>
							<tr>
								<th><input type="checkbox" name="ck_all" id="ck_all" /></th>
								<th>No</th>
								<th>사진</th>
								<th>성명</th>
								<th>연락처</th>
								<th>이메일</th>								
								<th>지원서</th>
								<th>합격여부</th>
								<th>등록일자</th>
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								if($data_list_cnt > 0) {
									for ($i = 0 ; $i < $data_list_cnt ; $i++) {
										$rc_idx 			= $data_list[$i]['rc_idx'];
										$rc_name 			= $data_list[$i]['rc_name'];
										$rc_sex 			= $data_list[$i]['rc_sex'];
										$rc_birth			= $data_list[$i]['rc_birth'];
										$rc_age				= $data_list[$i]['rc_age'];
										$rc_mobile		= $data_list[$i]['rc_mobile'];
										$rc_email			= $data_list[$i]['rc_email'];
										$rc_post			= $data_list[$i]['rc_post'];
										$rc_addr1			= $data_list[$i]['rc_addr1'];
										$rc_addr2			= $data_list[$i]['rc_addr2'];
										$rc_pic				= $data_list[$i]['rc_pic'];
										$rc_doc				= $data_list[$i]['rc_doc'];	
										$rc_sms				= $data_list[$i]['rc_sms'];
										$rc_pass				= $data_list[$i]['rc_pass'];
										$rc_regdate		= date("Y.m.d", strtotime($data_list[$i]['rc_regdate']));
																			
										$us_img = '';
										if($rc_pic !=  '') {
											$us_img = "<img src='" . $GP -> UP_RECRUIT_URL . $rc_pic . "' width='100px' />";
										}
										
										$send_btn = "";
										if($rc_sms != 'Y') {
											$send_btn = $C_Button -> getButtonDesign('type2','문자',0,"send_sms('" . $rc_idx ."', '" . $rc_name. "')", 100, "title='접수문자 발송' ");	 
										}
										
										if($rc_sms == 'Y') {
											$send_btn = $C_Button -> getButtonDesign('type2','완료',0,"", 90,"title='접수문자 발송완료' ");	 
										}

										$msg = "";
										if($rc_pass == "Y") {
											$msg = "합격";
										}else if($rc_pass == "N") {
											$msg = "불합격";
										}else {
											$msg = "심사중";
										}
										
										
										$code_file = $GP -> HOME. "common/recruit/" . $rc_doc;							
										$file_dn =  "<a href=\"/bbs/download.php?downview=1&file=" . $code_file . "&name=" . $rc_name . "_".$rc_doc ." \" class=\"btnL btnLs\">지원서</a>";		
									
										
										$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./recruit_edit.php?rc_idx=" . $rc_idx. "','100%', 580)", 50,'');	
										$edit_btn .= "" . $C_Button -> getButtonDesign('type2','삭제',0,"recruit_delete('" . $rc_idx. "')", 50,'');							
								?>
										<tr>
											<td><input type="checkbox" name="ck_num" value="<?=$rc_idx?>" /></td>
											<td><?=$data['page_info']['start_num']--?></td>
											<td><?=$us_img;?></td>
											<td><?=$rc_name?></td>
											<td><?=$rc_mobile?></td>
											<td><?=$rc_email?></td>											
											<td><?=$file_dn?></td>
											<td><?=$msg?></td>
											<td><?=$rc_regdate;?></td>
											<td class="alignCenter"><?=$send_btn?> <?=$edit_btn?></td>
										</tr>
										<?
										$dummy++;
									}
								}else{
									echo "<tr><td colspan='8' align='center'>채용안내를 먼저 선택후 검색해주세요</td></tr>";
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


		//전체선택 체크박스 클릭
		$("#ck_all").click(function(){
			//만약 전체 선택 체크박스가 체크된상태일경우
			if($("#ck_all").prop("checked")) {
				//해당화면에 전체 checkbox들을 체크해준다
				$("input:checkbox[name='ck_num']").prop("checked",true);
			// 전체선택 체크박스가 해제된 경우
			} else {
				//해당화면에 모든 checkbox들의 체크를해제시킨다.
				$("input:checkbox[name='ck_num']").prop("checked",false);
			}
		});
	
		callDatePick('s_date');
		callDatePick('e_date');
		
		//엑셀 출력
		$('#excel_btn').click(function(){
				var string = $("#base_form").serialize();
				location.href = "?excel_file=recruit" + "&" + string;
				return false;
		});

		$('#search_submit').click(function(){																			 

			if($.trim($('#search_content').val()) != '')
			{
				if($('#search_key option:selected').val() == '')
				{
					alert('검색조건을 선택하세요');
					return false;
				}
			}

			if($('#search_key option:selected').val() != '')
			{
				if($.trim($('#search_content').val()) == '')
				{
					alert('검색내용을 입력하세요');
					$('#search_content').focus();
					return false;
				}
			}


			$('#base_form').submit();
			return false;
		});

		$('#pass_btn').click(function(){			
			var num = "";
			$("input:checkbox[name=ck_num]").each(function(){
				if($(this).prop('checked') == true) {
					num += $(this).val()  + ",";
				}
			});
			num = num.slice(0,-1);

			if(num == "") {
				alert("합격할 사람을 체크해주세요");
				return false;
			}

			$.ajax({
				type: "POST",
				url: "./proc/recruit_proc.php",
				data: "mode=RECRUIT_PASS_Y&arr_idx=" + num,
				dataType: "text",
				success: function(msg) {
					
					if($.trim(msg) == "true") {
						alert("처리 되었습니다");
						window.location.reload();
						return false;
					}else{
						alert('처리에 실패하였습니다.');
						return;
					}
				},
				error: function(xhr, status, error) { alert(error); }
			});
		});


		$('#no_pass_btn').click(function(){			
			var num = "";
			$("input:checkbox[name=ck_num]").each(function(){
				if($(this).prop('checked') == true) {
					num += $(this).val()  + ",";
				}
			});
			num = num.slice(0,-1);

			if(num == "") {
				alert("불합격할 사람을 체크해주세요");
				return false;
			}

			$.ajax({
				type: "POST",
				url: "./proc/recruit_proc.php",
				data: "mode=RECRUIT_PASS_N&arr_idx=" + num,
				dataType: "text",
				success: function(msg) {
					
					if($.trim(msg) == "true") {
						alert("처리 되었습니다");
						window.location.reload();
						return false;
					}else{
						alert('처리에 실패하였습니다.');
						return;
					}
				},
				error: function(xhr, status, error) { alert(error); }
			});
		});

		$('#del_all_btn').click(function(){			
			var num = "";
			$("input:checkbox[name=ck_num]").each(function(){
				if($(this).prop('checked') == true) {
					num += $(this).val()  + ",";
				}
			});
			num = num.slice(0,-1);

			if(num == "") {
				alert("삭제할 사람을 체크해주세요");
				return false;
			}

			$.ajax({
				type: "POST",
				url: "./proc/recruit_proc.php",
				data: "mode=RECRUIT_DEL_ALL&arr_idx=" + num,
				dataType: "text",
				success: function(msg) {
					
					if($.trim(msg) == "true") {
						alert("처리 되었습니다");
						window.location.reload();
						return false;
					}else{
						alert('처리에 실패하였습니다.');
						return;
					}
				},
				error: function(xhr, status, error) { alert(error); }
			});
		});


		$('#sms_btn').click(function(){			
			var num = "";
			$("input:checkbox[name=ck_num]").each(function(){
				if($(this).prop('checked') == true) {
					num += $(this).val()  + ",";
				}
			});
			num = num.slice(0,-1);

			if(num == "") {
				alert("문자를 보낼 사람을 체크해주세요");
				return false;
			}

			layerPop('ifm_addr','recruit_sms.php?arr_idx=' + num, '100%', 700);
			return false;

		});

		
		$('#email_btn').click(function(){			
			//alert('도메인을 설정한 후 발송가능 합니다');
			//return false;

			var num = "";
			$("input:checkbox[name=ck_num]").each(function(){
				if($(this).prop('checked') == true) {
					num += $(this).val()  + ",";
				}
			});
			num = num.slice(0,-1);

			if(num == "") {
				alert("메일을 보낼 사람을 체크해주세요");
				return false;
			}

			layerPop('ifm_addr','recruit_email.php?arr_idx=' + num, '100%', 700);
			return false;

		});

		


	});

	
	function send_sms(rc_idx, name) {
		if(!confirm( name + " 고객님에게 접수완료 문자를 보내시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/recruit_proc.php",
			data: "mode=RECRUIT_SMS&rc_idx=" + rc_idx,
			dataType: "text",
			success: function(msg) {
				
				if($.trim(msg) == "true") {
					alert("발송 되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('발송에 실패하였습니다.');
					return;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
	}


	function recruit_delete(rc_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/recruit_proc.php",
			data: "mode=RECRUIT_DEL&rc_idx=" + rc_idx,
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