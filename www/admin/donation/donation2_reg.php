<?php
	include_once("../../_init.php");
    include_once($GP -> INC_ADM_PATH."/head.php");	
    
    $donation_select = $C_Func -> makeSelect_Normal('do_select', $GP -> donation_TYPE, $do_select, '', '::선택::');
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>후원금 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="donation2_REG" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>                            												          							
							<tr>
								<th><span>*</span>년도</th>
								<td>
                                    <input type="text" class="input_text" size="30" name="do_year" id="do_year" onKeyup="this.value=this.value.replace(/[^0-9]/g,'');"/>년      &nbsp;※숫자만 입력해 주세요.
                                    
								</td>
                            </tr>
                            <tr>
							<th><span>*</span>타입</th>
							<td>
								<?=$donation_select?>
							</td>
						    </tr>
							<tr>
								<th><span>*</span>구분</th>
								<td>
									<input type="text" class="input_text" size="30" name="do_gubun" id="do_gubun"/>
								</td>
                            </tr>	
                            <tr>
								<th><span>*</span>금액</th>
								<td>
									<input type="text" class="input_text" size="30" name="do_pay" id="do_pay"/>
								</td>
							</tr>											
						
                            <tr>
								<th><span>*</span>노출여부</th>
								<td>
									<label>
										<input type="radio" name="do_show" id="do_show" value="Y" checked   /> 노출
									</label>
									<label>
										<input type="radio" name="do_show" id="do_show" value="N"  /> 비노출
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
		
		$('#do_type').change(function(){
			size_guide();
		});

		function size_guide(){
			var type = $("#do_type option:selected").val();
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

			if($('#do_year').val() == '') {
				alert('년도를 입력하세요');
				$('#do_year').focus();
				return false;
			}		

			if($('#do_gubun').val() == '') {
				alert('구분내용을 입력하세요');
				$('#do_content').focus();
				return false;
			}
			

			$('#base_form').attr('action','./proc/donation_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>