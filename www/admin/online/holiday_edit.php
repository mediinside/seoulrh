<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> CLS."/class.reserve.php");	
	$C_Reserve 	= new Reserve;
	
	$args = "";
	$args['th_idx'] 	= $_GET['th_idx'];
	$rst = $C_Reserve ->HOliday_Detail($args);
	
	if($rst) {
		extract($rst);		
	}
		
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>휴무 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="HOLIDAY_MODI" />
    <input type="hidden" name="th_idx" id="th_idx" value="<?=$_GET['th_idx']?>" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<th width="15%"><span>*</span>휴무일명</th>
							<td width="85%">
								<input type="text" class="input_text" size="25" name="th_con" id="th_con" value="<?=$th_con?>" />
							</td>
						</tr>						
						<tr>
							<th><span>*</span>휴무일자</th>
							<td>
								<input type="text" class="input_text" size="15" name="th_date" id="th_date" value="<?=$th_date?>" />								
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
<script type="text/javascript">

	
	$(document).ready(function(){	
														 
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});				
		
		$('#img_submit').click(function(){
			
			if($('#th_con').val() == '') {
				alert("휴무명을 입력하세요");
				$('#th_con').focus();
				return false;
			}
			
			if($('#th_date').val() == '') {
				alert("일자를 입력하세요");
				$('#th_date').focus();
				return false;
			}			
		
			$('#base_form').attr('action','./proc/reserve_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>
<script type="text/javascript">
	$(function() {
		callDatePick('th_date');
	});

	function callDatePick (id) {	
		var dates = $( "#" + id ).datepicker({
			prevText: '이전 달',
			nextText: '다음 달',
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'],
			dayNamesShort: ['일','월','화','수','목','금','토'],
			dayNamesMin: ['일','월','화','수','목','금','토'],
			dateFormat: 'yy-mm-dd',
			showMonthAfterYear: true,
			yearSuffix: '년'	  
		});
	}
</script>