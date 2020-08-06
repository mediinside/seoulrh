<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	
	include_once($GP->CLS."class.reserve.php");
	include_once($GP->CLS."class.button.php");
	include_once($GP -> CLS."/class.doctor.php");	
	$C_Doctor 	= new Doctor;

	$C_ListClass 	= new ListClass;
	$C_Reserve 	= new Reserve;
	$C_Button 		= new Button;	
	
	$args = '';
	$rst = $C_Doctor -> Doctor_List_All($args);

	$args['dr_idx'] = $_GET['dr_idx'];
	$doctor_info = $C_Doctor -> Doctor_Info($args);
	
	// echo 'doctor: '.$doctor_info['dr_name'];

	if ($doctor_info['dr_name'] != '') {
		$doctor_text = '선택한 의료진 : ['.$GP -> CLINIC_TYPE[dr_clinic].']'.$doctor_info['dr_name'];
	}

	if(!$rst) {
		$C_Func->put_msg_and_modalclose("의료진 정보를 먼저 등록하세요.");
	}	


	$cn_select = $C_Func -> makeSelect_Normal('dr_center', $GP -> CENTER_TYPE, $dr_center, ' style="width:200px;" ', '::선택::');	
	$cn_select1 = $C_Func -> makeSelect_Normal('dr_clinic', $GP -> CLINIC_TYPE, $dr_clinic, ' style="width:200px;" ', '::선택::');	
	$sel_doctor = $C_Func ->makeSelect_Normal("dr_idx", $rst, $_GET['dr_idx'], "class='select_type1' style='width:250px;'", "::선택::");	
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<form name="base_form" id="base_form" method="GET">
			<strong class="tit">의료진선택</strong>
			<span>
				<?=$sel_doctor?>
			</span>
	<span><button id="search_submit" class="btnSearch ">검색</button></span>
			</form>
			</div>
		</div>
		<div class="calSection">
			<? include_once "./d_sch_cal.php";?>
      <iframe src="d_time_list.php" id="d_time"></iframe>			
		</div>
		
		<? include_once($GP -> INC_ADM_PATH."/footer.php"); ?>
	</div>
</div><!-- 전체를 감싸는 Wrap //-->
</body>
</html>
<script type="text/javascript">

	$(document).ready(function(){														 
	
		callDatePick('s_date');
		callDatePick('e_date');

		$('#search_submit').click(function(){																			 
			
			if($('#dr_idx').val() == '') {
				alert('의료진을 선택하세요');
				return false;
			}

			$('#base_form').submit();
			return false;
		});

	});

	function adm_delete(mb_code)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/main_proc.php",
			data: "mode=ADMINDEL&mb_code=" + mb_code,
			dataType: "text",
			success: function(msg) {

				if($.trim(msg) == "true") {
					alert("삭제되었습니다");
					window.location.reload();
					return false;
				}else{
					alert('삭제에 실패하였습니다.');
					return;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});

	}
</script>