<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> CLS."/class.reserve.php");
	include_once($GP -> CLS."/class.doctor.php");
	$C_Reserve 	= new Reserve;
	$C_Doctor 	= new Doctor;
	
	$args = "";
	$args['rd_idx'] 	= $_GET['rd_idx'];
	$rst = $C_Reserve ->Reserve_Detail_Adm($args);
	
	if($rst) {
		extract($rst);		
		
		
		$cn_select = $C_Func -> makeSelect_Normal('dr_center', $GP -> CENTER_TYPE, $dr_center, ' title="진료센터 선택" ', '::선택::');	
		$rd_select = $C_Func -> makeSelect_Normal('rd_status', $GP -> RESERVE_RESULT, $rd_status, ' title="예약상태 선택" ', '::선택::');	
	}
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>예약 정보</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="RS_MODI" />
		<input type="hidden" name="rd_idx" id="rd_idx" value="<?=$_GET['rd_idx']?>" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">
      	<div class="layerTable">				
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th width="15%"><span>*</span>성명</th>
                <td width="85%">
                  <?=$mb_name?>(<?=$mb_id?>) 
                </td>
              </tr>                    
              <tr>
                <th><span>*</span>이메일</th>
                <td>
                  <?=$mb_email?>
                </td>
              </tr>	            
              <tr>
                <th><span>*</span>핸드폰</th>
                <td>
                  <?=$mb_mobile?>
                </td>
              </tr>
			  <tr>
                <th><span>*</span>생년월일</th>
                <td>
                  <?=$mb_birth?>
                </td>
              </tr>
			  <tr>
                <th><span>*</span>성별</th>
                <td>
                  <?=$GP -> MEM_SEX[$mb_sex]?>
                </td>
              </tr>
			   <tr>
                <th><span>*</span>타입</th>
                <td>
                  <?=$GP -> RESERVE_CL_TYPE[$mb_type]?>
                </td>
              </tr>
			   <tr>
                <th><span>*</span>주소</th>
                <td>
                  <?=$mb_post?> <?=$mb_address1?> <?=$mb_address2?>
                </td>
              </tr>

			  <tr>
                <th><span>*</span>증상</th>
                <td>
                  <?=$C_Func->dec_contents($mb_con)?>
                </td>
              </tr>

              <tr>
               <tr>
                <th><span>*</span>진료센터</th>
                <td>
                  <?=$cn_select?>
                </td>
              </tr>
              <tr>
                <th><span>*</span>의료진</th>
                <td>							
                  <select id="dr_idx"  name="dr_idx":>
                    <option value=''>::: 선택 :::</option>											
                  </select>
                </td>
              </tr>	            						
              <tr>
                <th><span>*</span>예약일자</th>
                <td>
                  <input type="text" class="input_text" size="15" name="rd_date" id="rd_date" value="<?=$rd_date?>" />
                </td>
              </tr>	
              <tr>
                <th><span>*</span>예약시간</th>
                <td>
                  <select name="cp_idx" id="cp_idx">
                    <option value="">::: 선택 :::</option>
                  </select>
                </td>
              </tr>		
              <tr>
                <th><span>*</span>예약상태</th>
                <td>
                  <?=$rd_select?>
                </td>
              </tr>					
            </tbody>
          </table>		
        </div>		
				<div class="btnWrap">
				<!-- <button id="img_submit" class="btnSearch ">수정</button> -->
				<button id="img_cancel" class="btnSearch " onClick="javascript:parent.modalclose();">취소</button>
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
			yearSuffix: '년',
			onSelect: function (dateText, inst) {      
				time_sel(dateText);
			} 
		});
	}
	
	
	//시간 가져오기
	function time_sel(date) {
		
		var dr_idx = $('#dr_idx option:selected').val();
		
		$.ajax({
			type: "POST",
			url: "doctor_time.php",
			data: "date=" + date + "&dr_idx=" + dr_idx,
			dataType: "text",
			success: function(data) {
				$('#cp_idx').empty();
				$('#cp_idx').append(data);
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		
	}
	
	$(document).ready(function(){	
														 
		callDatePick('rd_date');	
		
		$.ajax({
			type: "POST",
			url: "doctor_time.php",
			data: "date=<?=$rd_date?>&dr_idx=<?=$dr_idx?>&rd_s_time=<?=$rd_s_time?>",
			dataType: "text",
			success: function(data) {
				$('#cp_idx').empty();
				$('#cp_idx').append('<option value="">::: 선택 :::</option>');
				$('#cp_idx').append(data);
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		
		
		$.ajax({
			type: "POST",
			url: "doctor_sel.php",
			data: "dr_center=<?=$dr_center?>&dr_idx=<?=$dr_idx?>",
			dataType: "text",
			success: function(data) {
				$('#dr_idx').empty();
				$('#dr_idx').append('<option value="">::: 선택 :::</option>');
				$('#dr_idx').append(data);					
			},
			error: function(xhr, status, error) { alert(error); }
		});		
		
		
		$('#dr_idx').change(function(){
																 
			$('#rd_date').val('');			
			
			$('#cp_idx').empty();
			$('#cp_idx').prev('label').text("진료시간을 선택하세요.");
			$('#cp_idx').append('<option value="">진료시간을 선택하세요.</option>');																 
		});
		
		
		
		$('#dr_center').change(function(){
			 var val = $(this).val();

			 if(val == '') {
				 return false;
			 }

			$('#dr_idx').empty();
			$('#dr_idx').append('<option value="">::: 선택 :::</option>');
			
			$('#rd_date').val('');		

			$('#cp_idx').empty();
			$('#cp_idx').prev('label').text("진료시간을 선택하세요.");
			$('#cp_idx').append('<option value="">진료시간을 선택하세요.</option>');

			$.ajax({
				type: "POST",
				url: "doctor_sel.php",
				data: "dr_center=" + val,
				dataType: "text",
				success: function(data) {
					$('#dr_idx').empty();
					$('#dr_idx').append('<option value="">::: 선택 :::</option>');
					$('#dr_idx').append(data);					
				},
				error: function(xhr, status, error) { alert(error); }
			});

		});										
		
		
		$('#img_submit').click(function(){
				
				if($('#dr_center option:selected').val() == '') {
					alert('진료센터를 선택하세요');
					return false;
				}
				
				if($('#dt_idx option:selected').val() == '') {
					alert('의료진을 선택하세요');
					return false;
				}	
				
				if($('#rd_date').val() == '') {
					alert('예약일자를 선택하세요');
					$('#rd_date').focus();
					return false;
				}
				
				
				if($('#cp_idx option:selected').val() == '') {
					alert('예약시간을 선택하세요');
					return false;
				}	
				
				
				$('#base_form').attr('action','./proc/reserve_proc.php');
				$('#base_form').submit();
				return false;							
		});
	});
</script>
