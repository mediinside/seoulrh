<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	include_once($GP -> CLS."/class.seoulrh.php");
	$C_seoulrh 	= new seoulrh;
	
	$args = "";
	$args['s_idx'] 	= $_GET['s_idx'];
	$rst = $C_seoulrh ->seoulrh_Info($args);
	
	if($rst) {
		extract($rst);		
    }	

    $seoulrh_select = $C_Func -> makeSelect_Normal('s_select', $GP -> seoulrh_TYPE, $s_select, '', '::선택::');
    
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>국제협력사업 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="seoulrh_MODI" />
		<input type="hidden" name="s_idx" id="s_idx" value="<?=$_GET['s_idx']?>" />
        <input type="hidden" name="s_type" id="s_type" value="A" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>                       
                        <tr>
								<th><span>*</span>년도</th>
								<td>
                                    <input type="text" class="input_text" size="30" name="s_year" id="s_year" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');" value="<?=$s_year?>"/>년      &nbsp;※숫자만 입력해 주세요.
                                    
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>사업명</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content1" id="s_content1" value="<?=$s_content1?>"/>
								</td>
                            </tr>	
                            <tr>
								<th><span>*</span>사업개요</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content2" id="s_content2" value="<?=$s_content2?>"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>사업기간</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content3" id="s_content3" value="<?=$s_content3?>"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>사업실적</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content4" id="s_content4" value="<?=$s_content4?>"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>협력 상대국(기관)</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content5" id="s_content5" value="<?=$s_content5?>"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>책임자</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content6" id="s_content6" value="<?=$s_content6?>"/>
								</td>
                            </tr>	
							<tr>
								<th><span>*</span>노출여부</th>
								<td>
									<input type="radio" name="s_show" id="s_show" value="Y" <? if($s_show == "Y"){ echo "checked";}?> />노출
									<input type="radio" name="s_show" id="s_show" value="N" <? if($s_show == "N"){ echo "checked";}?> />비노출
								</td>
							</tr>								
						</tbody>
					</table>
				</div>				
				<div class="btnWrap">
					<span class="btnRight">
						<button id="img_submit" class="btnSearch ">수정</button>
						<button id="img_cancel" class="btnSearch ">취소</button>
					</span>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>
<script type="text/javascript">


	function img_del(image, idx, type)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/seoulrh_proc.php",
			data: "mode=seoulrh2_IMGDEL&s_idx=" + idx + "&file=" + image + "&type=" + type,
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
		size_guide();
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});	
		
		$('#s_type').change(function(){
			size_guide();
		});
		
		function size_guide(){
			var type = $("#s_type option:selected").val();
			if (type == 'main') {
				$('#size_pc').text('(1398*600)');
				$('#size_m').text('(720*420)');
				$('#mobile_img').show();
			}else if (type == 'left') {
				$('#size_pc').text('(200*360)');
				$('#size_m').text('(720*180)');
				$('#mobile_img').show();
			}else{
				$('#size_pc').text('(360*200)');
				$('#mobile_img').hide();
			}
		}
		
		$('#img_submit').click(function(){
			/*
			if($('#s_descrition').val() == '') {
				alert('소제목을 입력하세요');
				$('#s_descrition').focus();
				return false;
			}		
			
			if($('#s_title').val() == '') {
				alert('제목을 입력하세요');
				$('#s_title').focus();
				return false;
			}	
			
			if($('#s_content').val() == '') {
				alert('내용을 입력하세요');
				$('#s_content').focus();
				return false;
			}
			*/
			
			$('#base_form').attr('action','./proc/edu_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>