<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	include_once($GP->CLS."class.cooperative.php");
	$C_Cooperative 	= new Cooperative;
	
	$args = "";	
	$args['cp_idx'] = $_GET['cp_idx'];
	$rst = $C_Cooperative->cooperative_info($args);
	extract($rst);
	/*if($rst) {
		extract($rst);
		$cate1_select = $C_Func -> makeSelect_Normal('cate1', $GP -> COOPERATIVE_CATE_TYPE1, $cate1, '', '::선택::');		
	}else{
		$C_Func->put_msg_and_modalclose("정보가 올바르지 않습니다.");	
	}	*/	
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>협력기관 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="COOPERATIVE_MODI" />
		<input type="hidden" name="cp_idx" id="cp_idx" value="<?=$_GET['cp_idx']?>" />
        <input type="hidden" name="before_image_main" id="before_image_main" value="<?=$cp_img?>" />
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
								<td><input type="text" class="input_text" size="25" name="cp_phone" id="cp_phone" value="<?=$cp_phone?>" /></td>
							</tr>
                            <tr>	
								<th><span>*</span>썸네일</th>
								<td>
                                <input type="file" name="cp_img" id="cp_img" size="30"  >
                                <?
									if($cp_img != "") {
										echo  "<br>" . $cp_img;
								?>
									<a href="#" onClick="img_del('<?=$cp_img;?>','<?=$_GET['cp_idx']?>')">(X)</a>
								<?}?>
                                </td>
                            </tr>	  
                            <tr>	
								<th><span>*</span>링크</th>
								<td><input type="text" class="input_text" size="100" name="cp_link" id="cp_link" value="<?=$cp_link?>" /></td>
							</tr>									
						</tbody>
					</table>
				</div>				
				<div class="btnWrap">
				<button id="img_submit" class="btnSearch ">수정</button>
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
            if (confirm("수정 하시겠습니까??") == true){
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
