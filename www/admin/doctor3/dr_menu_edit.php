<?php
	include_once("../../_init.php");
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
			
		$cn_select = $C_Func -> makeSelect_Normal('dr_clinic', $GP -> CLINIC_TYPE, $dr_clinic, '', '::선택::');		
		$cn_select1 = $C_Func -> makeSelect_Normal('dr_thesis', $GP -> DOCTOR_THESIS, $dr_thesis, '', '::선택::');	
		$cn_select2 = $C_Func -> makeSelect_Normal('dr_center', $GP -> CENTER_TYPE, $dr_center, '', '::선택::');	
		$cn_chk1 = $C_Func -> makeCheckbox_Normal($GP -> DOCTOR_POSITION, 'dr_position[]', $dr_ps, '', '130');
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
		<input type="hidden" name="before_image_m_list" id="before_image_m_list" value="<?=$dr_m_list_img?>" />
		<input type="hidden" name="before_m_profile_img" id="before_m_profile_img" value="<?=$image_profile_list?>" />
		<input type="hidden" name="before_vod_thum_list" id="before_vod_thum_list" value="<?=$dr_vod_thum_img?>" />
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
							<th><span>*</span>대표이미지</th>
							<td>
								<input type="file" name="dr_face_img" id="dr_face_img" size="30">(165 X 165)
								<?
									if($dr_face_img != "") {
										echo  "<br>" . $dr_face_img;
								?>
									<a href="#" onClick="img_del('<?=$dr_face_img;?>','<?=$_GET['dr_idx']?>','F')">(X)</a>
								<? } ?>
							</td>
						</tr>	
						<!--tr>
							<th><span>*</span>연출 이미지</th>
							<td>
								<input type="file" name="dr_list_img" id="dr_list_img" size="30">
								<?
									if($dr_list_img != "") {
										echo  "<br>" . $dr_list_img;
								?>
									<a href="#" onClick="img_del('<?=$dr_list_img;?>','<?=$_GET['dr_idx']?>','L')">(X)</a>
								<? } ?>
							</td>
						</tr>		
						<tr>
							<th><span>*</span>모바일 대표 이미지</th>
							<td>
								<input type="file" name="dr_m_list_img" id="dr_m_list_img" size="30">
								<?
									if($dr_m_list_img != "") {
										echo  "<br>" . $dr_m_list_img;
								?>
									<a href="#" onClick="img_del('<?=$dr_m_list_img;?>','<?=$_GET['dr_idx']?>','ML')">(X)</a>
								<? } ?>
							</td>
						</tr>
						<tr>
							<th><span>*</span>모바일 프로필 이미지</th>
							<td>
								<input type="file" name="image_profile_list" id="image_profile_list" size="30">(130 X 130)
								<?
									if($image_profile_list != "") {
										echo  "<br>" . $image_profile_list;
								?>
									<a href="#" onClick="img_del('<?=$image_profile_list;?>','<?=$_GET['dr_idx']?>','ML')">(X)</a>
								<? } ?>
							</td>
						</tr>
						<tr>
							<th><span>*</span>영상 썸네일 이미지</th>
							<td>
								<input type="file" name="dr_vod_thum_img" id="dr_vod_thum_img" size="30">
								<?
									if($dr_vod_thum_img != "") {
										echo  "<br>" . $dr_vod_thum_img;
								?>
									<a href="#" onClick="img_del('<?=$dr_vod_thum_img;?>','<?=$_GET['dr_idx']?>','VOD')">(X)</a>
								<? } ?>
							</td>
						</tr>
						<tr>
							<th><span>*</span>영상주소</th>
							<td>
								<input type="text" class="input_text" size="70" name="dr_vod_url" id="dr_vod_url" value="<?=$dr_vod_url?>" />
							</td>
						</tr>
            			<tr>
							<th><span>*</span>진료이력내용</th>
							<td>
								<textarea name="dr_history1" id="dr_history1" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history1?></textarea>
							</td>
						</tr>		
            			<tr>
							<th><span>*</span>전문 분야</th>
							<td>
								<textarea name="dr_history2" id="dr_history2" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history2?></textarea>
							</td>
						</tr>		
						<tr>
							<th><span>*</span>학술 활동</th>
							<td>
								<textarea name="dr_history3" id="dr_history3" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history3?></textarea>
							</td>
                        </tr-->	
                        <tr>
							<th><span>*</span>진료 분야</th>
							<td>
								<textarea name="dr_history4" id="dr_history4" style="width:98%; height:100px; overflow:auto;" ><?=$dr_history4?></textarea>
							</td>
						</tr>	
        
						<tr>
							<th><span>*</span>메인노출여부</th>
							<td>
								<input type="radio" name="dr_main_view" value="Y" <? if($dr_main_view == "Y") { echo "checked"; }?> />노출
								<input type="radio" name="dr_main_view" value="N" <? if($dr_main_view == "N") { echo "checked"; }?> />비노출
							</td>
						</tr>	
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

				/*oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
				
				var con	= $('#ir1').val();
				$('#dr_history2').val(con);		*/
		
				if($('#dr_history2').val() == '') {
					alert('의료진 한마디를 입력하세요');
					return false;
				}

				if($('#dr_history3').val() == '') {
					alert('의료진 설명을 입력하세요');
					return false;
				}
				
				if($('#dr_clinic option:selected').val() == '') {
					alert('진료과목을 선택하세요');
					return false;
				}
							
				if($('#dr_history').val() == '') {
					alert('주요경력을 입력하세요');
					$('#dr_history').focus();
					return false;
				}	
				
				$('#base_form').attr('action','./proc/dt_proc.php');
				$('#base_form').submit();
				return false;							
		});
	});
</script>
