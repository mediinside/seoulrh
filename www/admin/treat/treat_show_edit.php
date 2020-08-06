<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	include_once($GP -> CLS."/class.treat.php");
	$C_Treat 	= new Treat;
	
	$args = "";
	$args['tcs_idx'] 	= $_GET['tcs_idx'];
	$rst = $C_Treat ->TREAT_Show_Info($args);
	
	if($rst) {
		extract($rst);
		
		$cate1_select = $C_Func -> makeSelect_Normal('tcs_cate1', $GP -> CATE1, $tcs_cate1, '', '::선택::');
	}
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>치료법 노출 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="TREAT_SHOW_MODI" />
    <input type="hidden" name="tcs_idx" id="tcs_idx" value="<?=$_GET['tcs_idx']?>" />
    <input type="hidden" name="tc_idx_1" id="tc_idx_1" value="<?=$tc_idx_1?>" />
    <input type="hidden" name="tc_title_1" id="tc_title_1" value="<?=$tc_title_1?>" />
    <input type="hidden" name="tc_idx_2" id="tc_idx_2" value="<?=$tc_idx_2?>" />
    <input type="hidden" name="tc_title_2" id="tc_title_2" value="<?=$tc_title_2?>" />
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
								<th width="15%"><span>*</span>질환구분</th>
								<td width="85%">
									<select name="tcs_cate2" id="tcs_cate2">
										<option value="">:::선택:::</option>
									</select>
								</td>
							</tr> 
				<tr>
								<th width="15%"><span>*</span>질환명구분</th>
								<td width="85%">
									<select name="tcs_cate3" id="tcs_cate3">
										<option value="">:::선택:::</option>
									</select>
								</td>
							</tr>            		          					
							<tr>
								<th><span>*</span>비수술항목</th>
								<td>
					<input type="button" value="노출항목선택" onClick="layerPop_new('iframset','./treat_search.php?type=A', '100%', 650)" />
									<strong id="tc_msg_1"><?=$tc_title_1?></strong>                
								</td>
							</tr>	
				<tr>
								<th><span>*</span>수술항목</th>
								<td>
					<input type="button" value="노출항목선택" onClick="layerPop_new('iframset','./treat_search.php?type=B', '100%', 650)" />
									<strong id="tc_msg_2"><?=$tc_title_2?></strong>                
								</td>
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
														 
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});	
		
		$.ajax({
			type: "POST",
			url: "cate_show1.php",
			data: "tcs_cate1=<?=$tcs_cate1?>&tcs_cate2=<?=$tcs_cate2?>",
			dataType: "text",
			success: function(data) {
				$('#tcs_cate2').empty();
				$('#tcs_cate2').append(data);
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		
		$.ajax({
			type: "POST",
			url: "cate_show2.php",
			data: "tcs_cate2=<?=$tcs_cate2?>&tcs_cate3=<?=$tcs_cate3?>",
			dataType: "text",
			success: function(data) {
				$('#tcs_cate3').empty();				
				$('#tcs_cate3').append(data);
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		
		$('#tcs_cate1').change(function(){
				var val = $(this).val();
				
				$('#tcs_cate2').empty();	
				$('#tcs_cate2').append("<option value=''>:::선택:::</option>");
				$('#tcs_cate3').empty();
				$('#tcs_cate3').append("<option value=''>:::선택:::</option>");
				
				if(val != '') {
						$.ajax({
							type: "POST",
							url: "cate_show1.php",
							data: "tcs_cate1=" + val,
							dataType: "text",
							success: function(data) {
								$('#tcs_cate2').empty();	
								$('#tcs_cate2').append(data);
							},
							error: function(xhr, status, error) { alert(error); }
						});	
				}
		});
		
		$('#tcs_cate2').change(function(){
				var val = $(this).val();
				
				$('#tcs_cate3').empty();
				$('#tcs_cate3').append("<option value=''>:::선택:::</option>");
				
				
				if(val != '') {
						$.ajax({
							type: "POST",
							url: "cate_show2.php",
							data: "tcs_cate2=" + val,
							dataType: "text",
							success: function(data) {
								$('#tcs_cate3').empty();								
								$('#tcs_cate3').append(data);
							},
							error: function(xhr, status, error) { alert(error); }
						});	
				}
		});
		
		$('#img_submit').click(function(){
			
			if($('#tc_cate option:selected').val() == '') {
				alert("카테고리를 선택하세요");
				return false;
			}			

	
			if($('#tc_idx').val() == '') {
				alert('노출할 항목을 선택하세요');
				return false;
			}		
			
			
			$('#base_form').attr('action','./proc/treat_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>