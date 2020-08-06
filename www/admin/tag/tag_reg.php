<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	$cate1_select = $C_Func -> makeSelect_Normal('tt_cate', $GP -> CATE1, $tt_cate, '', '::선택::');
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>태그 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="TAG_REG" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th width="15%"><span>*</span>센터구분</th>
								<td width="85%">
									<?=$cate1_select?>
								</td>
							</tr> 							          							
							<tr>
								<th><span>*</span>태그명</th>
								<td>
									<input type="text" class="input_text" size="70" name="tt_tag_name" id="tt_tag_name" />
								</td>
							</tr>
							<tr>
								<th><span>*</span>이동 URL</th>
								<td>
									<input type="text" class="input_text" size="70" name="tt_url" id="tt_url" />
								</td>
							</tr>
              <tr>
								<th><span>*</span>노출여부</th>
								<td>
									<label>
										<input type="radio" name="tt_show" id="tt_show" value="Y" /> 노출
									</label>
									<label>
										<input type="radio" name="tt_show" id="tt_show" value="N" checked /> 비노출
									</label>
								</td>
							</tr>								
						</tbody>
					</table>
				</div>				
				<div class="btnWrap">
					<button id="img_submit" class="btnSearch ">등록</button>
					<button id="img_cancel" class="btnSearch ">취소</button>
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
		
		
		$('#img_submit').click(function(){
			
			if($('#tt_cate option:selected').val() == '') {
				alert("센터구분을 선택하세요");
				return false;
			}			
			
	
			if($('#tt_tag_name').val() == '') {
				alert('태그명을 입력하세요');
				$('#tt_tag_name').focus();
				return false;
			}		
			
			if($('#tt_url').val() == '') {
				alert('태그주소를 입력하세요');
				$('#tt_url').focus();
				return false;
			}		
			
			
			$('#base_form').attr('action','./proc/tag_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>