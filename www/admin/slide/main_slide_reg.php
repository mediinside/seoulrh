<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	$slide_select = $C_Func -> makeSelect_Normal('ts_lang', $GP -> SLIDE_LANG, $ts_lang, '', '::선택::');	
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>메인 슬라이드 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
        <input type="hidden" name="mode" id="mode" value="SLIDE_REG" />
        <input type="hidden" name="ts_type" id="ts_type" value="C" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>	
                              <tr>
								<th><span>*</span>분류</th>
								<td>
									<?=$slide_select?>
								</td>
							</tr> 				                    												          							
							<tr>
								<th><span>*</span>제목</th>
								<td>
                                    <textarea name="ts_title" id="ts_title" style="width:98%; height:100px;  overflow:auto;" ></textarea>
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>내용</th>
								<td>
									<textarea name="ts_content" id="ts_content" style="width:98%; height:100px;  overflow:auto;" ></textarea>
								</td>
							</tr>
							<!-- <tr>
								<th><span>*</span>설명</th>
								<td>
									<input type="text" class="input_text" size="70" name="ts_descrition" id="ts_descrition"/>
								</td>
							</tr> -->
							<tr>
								<th><span>*</span>링크</th>
								<td>
									<input type="text" class="input_text" size="70" name="ts_link" id="ts_link" />
                                     <!--p class="colorIdt"></p-->
								</td>
                            </tr>
                            <tr>
								<th><span>*</span>이미지</th>
								<td>
									<input type="file" name="ts_img" id="ts_img" size="30" class="input_text"><span id="size_pc"></span> (1920 X 933)
								</td>
                            </tr>
                            <tr id="mobile_img">
								<th><span>*</span>모바일 이미지</th>
								<td>
									<input type="file" name="ts_m_img" id="ts_m_img" size="30" class="input_text"><span id="size_m"></span> (720 X 574)
								</td>
							</tr>    
                            <tr>
								<th><span>*</span>노출여부</th>
								<td>
									<label>
										<input type="radio" name="ts_show" id="ts_show" value="Y" /> 노출
									</label>
									<label>
										<input type="radio" name="ts_show" id="ts_show" value="N" checked /> 비노출
									</label>
								</td>
							</tr>
														
							<!--tr>
								<th><span>*</span>새창여부</th>
								<td>
									<label>
										<input type="checkbox" name="ts_type" id="ts_type" value="Y"/> 새창
									</label>
								</td>
                            </tr> 
                                            
                        -->
							
							
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
		
		$('#ts_type').change(function(){
			size_guide();
		});

		function size_guide(){
			var type = $("#ts_type option:selected").val();
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
			if($('#ts_descrition').val() == '') {
				alert('소제목을 입력하세요');
				$('#ts_descrition').focus();
				return false;
			}		
			
			if($('#ts_title').val() == '') {
				alert('제목을 입력하세요');
				$('#ts_title').focus();
				return false;
			}	
			
			if($('#ts_content').val() == '') {
				alert('내용을 입력하세요');
				$('#ts_content').focus();
				return false;
			}
			*/
			
			if($('#ts_img').val() == '') {
				alert('이미지를 선택하세요');
				$('#ts_img').focus();
				return false;
			}
			
			
			$('#base_form').attr('action','./proc/slide_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>