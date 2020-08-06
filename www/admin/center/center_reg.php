<?php
	include_once("../../_init.php");
    include_once($GP -> INC_ADM_PATH."/head.php");	
    
    $seoulrh_select = $C_Func -> makeSelect_Normal('s_select', $GP -> seoulrh_TYPE, $s_select, '', '::선택::');
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>조직운영안내 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="seoulrh_REG" />
        <input type="hidden" name="s_type" id="s_type" value="C" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>                         												          														                       
							<tr>
								<th><span>*</span>부서명</th>
								<td>
									<input type="text" class="input_text" size="100" name="s_content1" id="s_content1"/>
								</td>
                            </tr>	
                            <tr>
								<th><span>*</span>이름</th>
								<td>
									<input type="text" class="input_text" size="100" name="s_content2" id="s_content2"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>직종</th>
								<td>
									<input type="text" class="input_text" size="100" name="s_content3" id="s_content3"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>담당업무</th>
								<td>
									<input type="text" class="input_text" size="100" name="s_content4" id="s_content4"/>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>부서전화번호</th>
								<td>
									<input type="text" class="input_text" size="100" name="s_content5" id="s_content5"/>
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

			

			$('#base_form').attr('action','./proc/center_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>