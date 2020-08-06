<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");		
	
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>협력기관 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="COOPERATIVE_REG" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>							
							<tr>	
								<th><span>*</span>기관명</th>
								<td colspan="3"><input type="text" class="input_text" size="40" name="cp_name" id="cp_name" value="<?=$cp_name?>" /></td>
							</tr>
							<tr>	
								<th><span>*</span>소재지</th>
								<td><input type="text" class="input_text" size="40" name="cp_place" id="cp_place" value="<?=$cp_place?>" /></td>					
                            </tr>
                            <tr>	
								<th><span>*</span>대표전화</th>
								<td><input type="text" class="input_text" size="30" name="cp_phone" id="cp_phone" value="<?=$cp_phone?>" /></td>
                            </tr>	  
							<!--tr>	
								<th><span>*</span>주소</th>
								<td><input type="text" class="input_text" size="100" name="cp_addr" id="cp_addr" value="<?=$cp_addr?>" /></td>
                            </tr-->
                            <tr>	
								<th><span>*</span>썸네일</th>
								<td><input type="file" name="cp_img" id="cp_img" size="30"></td>
                            </tr>	  
                            <tr>	
								<th><span>*</span>링크</th>
								<td><input type="text" class="input_text" size="100" name="cp_link" id="cp_link" value="<?=$cp_link?>" /></td>
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
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.alphanumeric.js"></script>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.validate.js"></script>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.base64.js"></script>
<script type="text/javascript">

	$(document).ready(function(){	
														 
		// $('#cp_phone').numeric();											 
		
		$('#img_submit').click(function(){	
            if (confirm("등록 하시겠습니까??") == true){
                $('#base_form').attr('action','./proc/cooperative_proc.php');
			$('#base_form').submit();
			return false;		
         }				
				
		});		
		
		$('#img_cancel').click(function(){					
			parent.modalclose();
		});					
	});
</script>