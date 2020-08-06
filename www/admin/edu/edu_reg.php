<?php
	include_once("../../_init.php");
    include_once($GP -> INC_ADM_PATH."/head.php");	
    
    $seoulrh_select = $C_Func -> makeSelect_Normal('s_select', $GP -> seoulrh_TYPE, $s_select, '', '::선택::');
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>국제협력사업 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="seoulrh_REG" />
        <input type="hidden" name="s_type" id="s_type" value="A" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>                            												          							
							<tr>
								<th><span>*</span>년도</th>
								<td>
                                    <input type="text" class="input_text" size="30" name="s_year" id="s_year" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"/>년      &nbsp;※숫자만 입력해 주세요.
                                    
								</td>
                            </tr>                            
							<!--tr>
								<th><span>*</span>사업명</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content1" id="s_content1"/>
								</td>
                            </tr-->	
                            <tr>
								<th><span>*</span>사업개요</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content2" id="s_content2"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>사업기간</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content3" id="s_content3"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>사업실적</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content4" id="s_content4"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>협력 상대국(기관)</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content5" id="s_content5"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>책임자</th>
								<td>
									<input type="text" class="input_text" size="30" name="s_content6" id="s_content6"/>
								</td>
                            </tr>
                          
                            <tr>
								<th><span>*</span>노출여부</th>
								<td>
									<label>
										<input type="radio" name="s_show" id="s_show" value="Y" checked   /> 노출
									</label>
									<label>
										<input type="radio" name="s_show" id="s_show" value="N"  /> 비노출
									</label>
								</td>
							</tr>
						</tbody>
					</table>
				</div>				
				<div class="btnWrap">
					<span class="btnRight">
						<button id="img_submit" class="btnSearch ">등록</button>
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

	$(document).ready(function(){	
														 
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

			if($('#s_year').val() == '') {
				alert('년도를 입력하세요');
				$('#s_year').focus();
				return false;
			}		

            if($('#s_year').val().length != 4) {
				alert('년도형식을 알맞게 작성해주세요');
				$('#s_year').focus();
				return false;
			}		

			if($('#s_gubun').val() == '') {
				alert('구분내용을 입력하세요');
				$('#s_content').focus();
				return false;
			}
			

			$('#base_form').attr('action','./proc/edu_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>