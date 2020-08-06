<?php
	include_once("../../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> CLS."/class.doctor.php");
	$C_Doctor 	= new Doctor;
	
	$args = "";
	$args['dr_idx'] 	= $_GET['dr_idx'];
	$rst = $C_Doctor ->Doctor_Info($args);
	
	if($rst) {
		extract($rst);
		$dr_treat  = $C_Func->dec_contents_edit($dr_treat);		
		$dr_history  = $C_Func->dec_contents_edit($dr_history);		
		$dr_history1  = $C_Func->dec_contents_edit($dr_history1);		
		$dr_history2  = $C_Func->dec_contents_edit($dr_history2);		
		$dr_history3  = $C_Func->dec_contents_edit($dr_history3);		
		$dr_history4  = $C_Func->dec_contents_edit($dr_history4);		
		$dr_history5  = $C_Func->dec_contents_edit($dr_history5);		
		$dr_history6  = $C_Func->dec_contents_edit($dr_history6);		
		$dr_history7  = $C_Func->dec_contents_edit($dr_history7);		
		
		$dr_m_arr = explode('|', $dr_m_sd);
		$dr_a_arr = explode('|', $dr_a_sd);
		$dr_ps = explode(',', $dr_position);
			
		$cn_select = $C_Func -> makeSelect_Normal('dr_clinic', $GP -> ENG_CLINIC_TYPE, $dr_clinic, '', '::선택::');		
		$cn_select1 = $C_Func -> makeSelect_Normal('dr_thesis', $GP -> ENG_DOCTOR_THESIS, $dr_thesis, '', '::선택::');	
		$cn_select2 = $C_Func -> makeSelect_Normal('dr_center', $GP -> ENG_CENTER_TYPE, $dr_center, '', '::선택::');	
		$cn_chk1 = $C_Func -> makeCheckbox_Normal($GP -> ENG_DOCTOR_POSITION, 'dr_position[]', $dr_ps, '', '300');
	}
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>의료진 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="DOCTOR_MODI" />
		<input type="hidden" name="dr_idx" id="dr_idx" value="<?=$_GET['dr_idx']?>" />
		<input type="hidden" name="before_image_main" id="before_image_main" value="<?=$dr_face_img?>" />
		<input type="hidden" name="before_image_list" id="before_image_list" value="<?=$dr_list_img?>" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
			<div class="layerTable">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<th width="15%"><span>*</span>성명</th>
							<td width="85%">
								<input type="text" class="input_text" size="25" name="dr_name" id="dr_name" value="<?=$dr_name?>" />
							</td>
						</tr>
						<tr>
							<th><span>*</span>직책</th>
							<td>
								<?=$cn_chk1?>
							</td>
						</tr>
            <tr>
							<th><span>*</span>진료센터</th>
							<td>
								<?=$cn_select2?>
							</td>
						</tr>	
						<tr>
							<th><span>*</span>진료과목</th>
							<td>
								<?=$cn_select?>
							</td>
						</tr>	
            	<tr>
							<th><span>*</span>학위</th>
							<td>
								<?=$cn_select1?>
							</td>
						</tr>
						<!--tr>
							<th><span>*</span>선택진료여부</th>
							<td>
								<input type="radio" name="dr_choice" value="Y" <? if($dr_choice == "Y") { echo "checked"; }?>  />선택
								<input type="radio" name="dr_choice" value="N" <? if($dr_choice == "N") { echo "checked"; }?> />미선택
							</td>
						</tr-->	
						<tr>
							<th><span>*</span>전문의여부</th>
							<td>
								<input type="radio" name="dr_special" value="Y" <? if($dr_special == "Y") { echo "checked"; }?> />전문의
								<input type="radio" name="dr_special" value="N" <? if($dr_special == "N") { echo "checked"; }?> />비전문의
							</td>
						</tr>	
						<!--tr>
							<th><span>*</span>진료일정</th>
							<td>
								<table>
									<tr>
										<td></td>
										<td>월</td>
										<td>화</td>
										<td>수</td>
										<td>목</td>
										<td>금</td>
										<td>토</td>
										<td>일</td>
									</tr>
									<tr>
										<td>오전</td>
										<td><?=$C_Func -> makeSelect_Normal('dr_m_sd1', $GP -> DOCTOR_SCH, $dr_m_arr[0], '', '::선택::');?></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_m_sd2', $GP -> DOCTOR_SCH, $dr_m_arr[1], '', '::선택::');?></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_m_sd3', $GP -> DOCTOR_SCH, $dr_m_arr[2], '', '::선택::');?></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_m_sd4', $GP -> DOCTOR_SCH, $dr_m_arr[3], '', '::선택::');?></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_m_sd5', $GP -> DOCTOR_SCH, $dr_m_arr[4], '', '::선택::');?></td>
										<td><input type="text" class="input_text" size="10" name="dr_m_sd6" id="dr_m_sd6" value="<?=$dr_m_arr[5]?>" /></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_m_sd7', $GP -> DOCTOR_SCH, $dr_m_arr[6], '', '::선택::');?></td>
									</tr>
									<tr>
										<td>오후</td>
										<td><?=$C_Func -> makeSelect_Normal('dr_a_sd1', $GP -> DOCTOR_SCH, $dr_a_arr[0], '', '::선택::');?></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_a_sd2', $GP -> DOCTOR_SCH, $dr_a_arr[1], '', '::선택::');?></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_a_sd3', $GP -> DOCTOR_SCH, $dr_a_arr[2], '', '::선택::');?></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_a_sd4', $GP -> DOCTOR_SCH, $dr_a_arr[3], '', '::선택::');?></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_a_sd5', $GP -> DOCTOR_SCH, $dr_a_arr[4], '', '::선택::');?></td>
										<td><input type="text" class="input_text" size="10" name="dr_a_sd6" id="dr_a_sd6" value="<?=$dr_a_arr[5]?>" /></td>
										<td><?=$C_Func -> makeSelect_Normal('dr_a_sd7', $GP -> DOCTOR_SCH, $dr_a_arr[6], '', '::선택::');?></td>
									</tr>
								</table>
							</td>
						</tr-->
						<!-- tr>
							<th><span>*</span>전공분야</th>
							<td>
								<input type="text" class="input_text" size="25" name="dr_treat" id="dr_treat" value="<?=$dr_treat?>" />
							</td>
						</tr -->																
						<tr>
							<th><span>*</span>대표이미지</th>
							<td>
								<input type="file" name="dr_face_img" id="dr_face_img" size="30">(560 X 746)
								<?
									if($dr_face_img != "") {
										echo  "<br>" . $dr_face_img;
								?>
									<a href="#" onClick="img_del('<?=$dr_face_img;?>','<?=$_GET['dr_idx']?>','M')">(X)</a>
								<? } ?>
							</td>
						</tr>	
						<!--tr>
							<th><span>*</span>진료시간표 이미지</th>
							<td>
								<input type="file" name="dr_list_img" id="dr_list_img" size="30">
								<?
									if($dr_list_img != "") {
										echo  "<br>" . $dr_list_img;
								?>
									<a href="#" onClick="img_del('<?=$dr_list_img;?>','<?=$_GET['dr_idx']?>','L')">(X)</a>
								<? } ?>
							</td>
						</tr-->		
						<tr>
							<th><span>*</span>대표약력</th>
							<td>
								<textarea name="dr_history" id="dr_history" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history?></textarea>
							</td>
						</tr>	
            <tr>
							<th><span>*</span>학력,교수경력</th>
							<td>
								<textarea name="dr_history1" id="dr_history1" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history1?></textarea>
							</td>
						</tr>		
            <tr>
							<th><span>*</span>학회활동</th>
							<td>
								<textarea name="dr_history2" id="dr_history2" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history2?></textarea>
							</td>
						</tr>		
            <!--tr>
							<th><span>*</span>경력</th>
							<td>
								<textarea name="dr_history3" id="dr_history3" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history3?></textarea>
							</td>
						</tr>		
            <tr>
							<th><span>*</span>상훈</th>
							<td>
								<textarea name="dr_history4" id="dr_history4" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history4?></textarea>
							</td>
						</tr-->		
            <tr>
							<th><span>*</span>저서</th>
							<td>
								<textarea name="dr_history5" id="dr_history5" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history5?></textarea>
							</td>
						</tr>		
            <tr>
							<th><span>*</span>학술활동</th>
							<td>
								<textarea name="dr_history6" id="dr_history6" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history6?></textarea>
							</td>
						</tr>
            <!--tr>
							<th><span>*</span>언론보도</th>
							<td>
								<textarea name="dr_history7" id="dr_history7" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history7?></textarea>
							</td>
						</tr>
						<tr>
							<th><span>*</span>메인노출여부</th>
							<td>
								<input type="radio" name="dr_main_view" value="Y" <? if($dr_main_view == "Y") { echo "checked"; }?> />노출
								<input type="radio" name="dr_main_view" value="N" <? if($dr_main_view == "N") { echo "checked"; }?> />비노출
							</td>
						</tr-->	
					</tbody>
				</table>
			</div>				
				<div style="margin-top:5px; text-align:center;">
				<button id="img_submit" class="btnSearch ">수정</button>
				<button id="img_cancel" class="btnSearch " onClick="javascript:parent.modalclose();">취소</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.alphanumeric.js"></script>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.validate.js"></script>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.base64.js"></script>
<script type="text/javascript">
	
	function img_del(image, idx, type)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/dt_proc.php",
			data: "mode=DOCTOR_IMGDEL&dr_idx=" + idx + "&file=" + image + "&type=" + type,
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
	
	$(document).ready(function(){	
		
		$('#img_submit').click(function(){
				
				if($('#dr_name').val() == '') {
					alert('성명을 입력하세요');
					$('#dr_name').focus();
					return false;
				}
				
				var chk = $("input[name='dr_position[]']:checkbox:checked").length;
				
				if(chk == 0) {
					alert("직책을  선택하세요");
					return false;
				}
				
				if($('#dr_clinic option:selected').val() == '') {
					alert('진료과목을 선택하세요');
					return false;
				}

				
				$('#base_form').attr('action','./proc/dt_proc.php');
				$('#base_form').submit();
				return false;							
		});
	});
</script>
