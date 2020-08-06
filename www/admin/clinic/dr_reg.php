<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	

	$cn_select = $C_Func -> makeSelect_Normal('dr_clinic', $GP -> CLINIC_TYPE, $dr_clinic, '', '::선택::');		
	$cn_select1 = $C_Func -> makeSelect_Normal('dr_thesis', $GP -> DOCTOR_THESIS, $dr_thesis, '', '::선택::');		
	$cn_select2 = $C_Func -> makeSelect_Normal('dr_center', $GP -> CENTER_TYPE, $dr_center, '', '::선택::');		
	$cn_chk1 = $C_Func -> makeCheckbox_Normal($GP -> DOCTOR_POSITION, 'dr_position[]', $dr_position, '', '130');
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>진료과 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="DOCTOR_REG" />
		<!-- <input type="hidden" name="dr_branch" id="dr_branch" value="suwon" /> -->
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>
							<!-- <tr>
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
							</tr> -->
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
							<!-- <tr>
								<th><span>*</span>학위</th>
								<td>
									<?=$cn_select1?>
								</td>
							</tr>
							<tr>
								<th><span>*</span>선택진료여부</th>
								<td>
									<input type="radio" name="dr_choice" value="Y"  />선택
									<input type="radio" name="dr_choice" value="N" checked />미선택
								</td>
							</tr>	
							<tr>
								<th><span>*</span>전문의여부</th>
								<td>
									<input type="radio" name="dr_special" value="Y"  />전문의
									<input type="radio" name="dr_special" value="N" checked />비전문의
								</td>
							</tr>	 -->
							<!-- <tr>
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
																		<td><?=$C_Func -> makeSelect_Normal('dr_m_sd5', $GP -> DOCTOR_SCH, $dr_m_arr[5], '', '::선택::');?></td>
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
																		<td><?=$C_Func -> makeSelect_Normal('dr_a_sd6', $GP -> DOCTOR_SCH, $dr_a_arr[5], '', '::선택::');?></td>
																		<td><input type="text" class="input_text" size="10" name="dr_a_sd6" id="dr_a_sd6" value="<?=$dr_a_arr[5]?>" /></td>
																		<td><?=$C_Func -> makeSelect_Normal('dr_a_sd7', $GP -> DOCTOR_SCH, $dr_a_arr[6], '', '::선택::');?></td>
																	</tr>
																	<tr>
																		<td>비고</td>
																		<td colspan="6"><input type="text" class="input_text" size="70" name="dr_history4" id="dr_history4" value="" /></td>
																	</tr>
																</table>
															</td>
														</tr>			 -->							
							<!-- tr>
								<th><span>*</span>전공분야</th>
								<td>
									<input type="text" class="input_text" size="25" name="dr_treat" id="dr_treat" value="<?=$dr_treat?>" />							
								</td>
							</tr -->													
							<!-- <tr>
								<th><span>*</span>대표이미지</th>
								<td>
									<input type="file" name="dr_face_img" id="dr_face_img" size="30">(465 X 552)
								</td>
							</tr> -->	
							<tr>
								<th><span>*</span>진료과 대표 이미지</th>
								<td>
									<input type="file" name="dr_list_img" id="dr_list_img" size="30">(1040 X 585)
								</td>
							</tr>
							<!-- <tr>
								<th><span>*</span>모바일 대표 이미지</th>
								<td>
									<input type="file" name="dr_m_list_img" id="dr_m_list_img" size="30">(560 X 746)
								</td>
							</tr> -->	
							<tr>
								<th><span>*</span>영상 썸네일 이미지</th>
								<td>
									<input type="file" name="dr_vod_thum_img" id="dr_vod_thum_img" size="30">(1040 X 585)
								</td>
							</tr>
							<tr>
								<th><span>*</span>영상주소</th>
								<td>
									<input type="text" class="input_text" size="70" name="dr_vod_url" id="dr_vod_url" value="" />
								</td>
							</tr>
							<!-- <tr>
										<th><span>*</span>진료내용</th>
										<td>
											<textarea name="dr_history1" id="dr_history1" style="width:98%; height:100px; overflow:auto;" ></textarea>
										</td>
									</tr>		
									<tr>
									<th><span>*</span>진료과 한마디</th>
									<td>
										<textarea name="dr_history2" id="dr_history2" style="width:98%; height:100px; overflow:auto;" ></textarea>
									</td>
															</tr> -->		
						<tr>
							<th><span>*</span>진료과 설명</th>
							<td>
								<textarea name="dr_history3" id="dr_history3" style="width:98%; height:100px; overflow:auto;" ></textarea>
							</td>
						</tr>		
           <!--  <tr>
										<th><span>*</span>경력</th>
										<td>
											<textarea name="dr_history3" id="dr_history3" style="width:98%; height:100px; overflow:auto;" ></textarea>
										</td>
									</tr>		
						<tr>
										<th><span>*</span>상훈</th>
										<td>
											<textarea name="dr_history4" id="dr_history4" style="width:98%; height:100px; overflow:auto;" ></textarea>
										</td>
									</tr>		
						<tr>
										<th><span>*</span>저서</th>
										<td>
											<textarea name="dr_history5" id="dr_history5" style="width:98%; height:100px; overflow:auto;" ></textarea>
										</td>
									</tr> -->
								<!-- <tr>
								<th><span>*</span>대표약력</th>
								<td>
									<textarea name="dr_history" id="dr_history" style="width:98%; height:100px;  overflow:auto;" ></textarea>
								</td>
							</tr>							
							<tr>
								<th><span>*</span>학술활동</th>
								<td>
									<textarea name="dr_history6" id="dr_history6" style="width:98%; height:100px; overflow:auto;" ></textarea>
								</td>
							</tr> -->
							<!-- <tr>
								<th><span>*</span>언론보도</th>
								<td>
									<textarea name="dr_history7" id="dr_history7" style="width:98%; height:100px; overflow:auto;" ></textarea>
								</td>
							</tr> -->
							<tr>
								<th><span>*</span>메인노출여부</th>
								<td>
									<input type="radio" name="dr_main_view" value="Y"  />노출
									<input type="radio" name="dr_main_view" value="N" checked />비노출
								</td>
							</tr>	
						</tbody>
					</table>
				</div>				
				<div class="btnWrap">
				<button id="img_submit" class="btnSearch ">등록</button>
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
<script type="text/javascript" src="<?=$GP -> JS_SMART_PATH?>/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.base64.js"></script>
<script type="text/javascript">
	/*var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "ir1",
		sSkinURI: "/bbs/smarteditor/SmartEditor2Skin.html",
		htParams : {
			bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
			//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
			fOnBeforeUnload : function(){
				//alert("완료!");
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
	});*/
	
	$(document).ready(function(){	
		
		$('#img_submit').click(function(){
				
				// if($('#dr_name').val() == '') {
				// 	alert('성명을 입력하세요');
				// 	$('#dr_name').focus();
				// 	return false;
				// }
				
				// var chk = $("input[name='dr_position[]']:checkbox:checked").length;
				
				// if(chk == 0) {
				// 	alert("직책을  선택하세요");
				// 	return false;
				// }

				/*oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
				
				var con	= $('#ir1').val();
				$('#dr_history2').val(con);		
		*/
				// if($('#dr_history2').val() == '') {
				// 	alert('진료과 한마디를 입력하세요');
				// 	return false;
				// }

				if($('#dr_history3').val() == '') {
					alert('진료과 설명을 입력하세요');
					return false;
				}
				
				if($('#dr_clinic option:selected').val() == '') {
					alert('진료과목을 선택하세요');
					return false;
				}				
				
				// if($('#dr_history').val() == '') {
				// 	alert('대표약력을 입력하세요');
				// 	$('#dr_history').focus();
				// 	return false;
				// }
				
				$('#base_form').attr('action','./proc/dt_proc.php');
				$('#base_form').submit();
				return false;							
		});
	});
</script>
