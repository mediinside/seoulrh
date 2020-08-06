<?php
	// error_reporting(E_ALL^E_NOTICE);
	// @ini_set("display_errors", 1);
	include_once("../../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once $GP -> CLS . 'class.grievance.php';
	$C_Grievance = new Grievance();
	
	$args = "";
	$args['cs_idx'] 	= $_GET['cs_idx'];
	$rst = $C_Grievance ->Cs_Counsel_Detail($args);
	
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
		<script type="text/javascript" src="/bbs/smarteditor/js/HuskyEZCreator.js" charset="utf-8"></script>
		<script type="text/javascript" src="/js/admin/jquery.base64.js"></script>
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
                  <?=$cs_name1?>
                </td>
              </tr>                    
              <tr>
                <th><span>*</span>사번</th>
                <td>
                  <?=$cs_no?>
                </td>
              </tr>	            
              <tr>
                <th><span>*</span>핸드폰</th>
                <td>
                  <?=$cs_phone?>
                </td>
              </tr>
			  <tr>
                <th><span>*</span>제목</th>
                <td>
                  <?=$cs_title?>
                </td>
              </tr>
			  <tr>
                <th><span>*</span>발생일시</th>
                <td>
                  <?=$cs_date?>
                </td>
              </tr>
			   <tr>
                <th><span>*</span>발생장소</th>
                <td>
                  <?=$cs_location?>
                </td>
              </tr>
			   <tr>
                <th><span>*</span>피해자</th>
                <td>
                  <?=$cs_victim?>
                </td>
              </tr>
			  <tr>
                <th><span>*</span>가해자</th>
                <td>
                  <?=$cs_perpetrator?>
                </td>
              </tr>
              <tr>
               <tr>
                <th><span>*</span>폭력의 종류</th>
                <td>
                  <?=$cs_type?>
                </td>
              </tr>
              <tr>
                <th><span>*</span>상해 여부</th>
                <td>
                  <?=$cs_condition?>
                </td>
              </tr>	            						
              <tr>
                <th><span>*</span>상해의 정도</th>
                <td>
                  <?=$cs_degree?>
                </td>
              </tr>	
              <tr>
                <th><span>*</span>목격자</th>
                <td>
                  <?=$cs_witness?>
                </td>
              </tr>					
              <tr>
                <th><span>*</span>사전의 자세한 서술</th>
                 <td>
		            <textarea name="cs_content" id="cs_content" style="display:none"></textarea>
		            <textarea name="ir1" id="ir1" class="inputTxt" placeholder="6하 원칙에 의거 구체적으로  작성해 주세요." style="width:100%; height:300px; min-width:280px;"><?=$cs_content?></textarea>
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
	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "ir1",
		sSkinURI: "/bbs/smarteditor/SmartEditor2Skin1.html",
		htParams : {
			bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
			//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
			fOnBeforeUnload : function(){
				//alert("완료!");
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
		},
		fCreator: "createSEditor2"
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
			yearSuffix: '년',
			onSelect: function (dateText, inst) {      
				time_sel(dateText);
			} 
		});
	}
	
	
	// //시간 가져오기
	// function time_sel(date) {
		
	// 	var dr_idx = $('#dr_idx option:selected').val();
		
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "doctor_time.php",
	// 		data: "date=" + date + "&dr_idx=" + dr_idx,
	// 		dataType: "text",
	// 		success: function(data) {
	// 			$('#cp_idx').empty();
	// 			$('#cp_idx').append(data);
	// 		},
	// 		error: function(xhr, status, error) { alert(error); }
	// 	});	
		
	// }
	
	$(document).ready(function(){	
														 
/*		callDatePick('rd_date');	
		
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
		*/
		
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
