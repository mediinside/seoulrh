<?
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");	
	include_once($GP->CLS."class.reserve.php");
	include_once($GP->CLS."class.button.php");
	
	$C_ListClass 	= new ListClass;
	$C_Button 		= new Button;	
	$C_Reserve 		= new Reserve;
	
	//error_reporting(E_ALL);
	//@ini_set("display_errors", 1);
	
	$args = array();
	$args['show_row'] = 9;
	$args['pagetype'] = "admin";
	$args['dr_idx'] = $_GET['dr_idx'];
	$args['cp_date'] = $_GET['cp_date'];	
	
	$data = "";
	$data = $C_Reserve->Day_Sch_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);	
?>
<div class="timeTable">
	<span>선택한 날짜 : <?=$_GET['cp_date']?></span>
	<div style="margin-top:5px;">
		<div>
		<form id="frm_time_reg" name="frm_time_reg" action="?" method="post">	
			<input type="button" value="시간추가" class="btnM btnGray" id="time_show" />
			<span id="time_span" style="display:none;">
				<input type="hidden" name="mode" id="mode" value="RS_TIME_ADD" />
				<input type="hidden" name="cp_date" id="cp_date" value="<?=$_GET['cp_date']?>" />
				<input type="hidden" name="dr_idx" id="dr_idx" value="<?=$_GET['dr_idx']?>" />
				<input type="hidden" name="so_idx" id="so_idx" value="<?=$data_list[0]['so_idx'];?>" />
				<input type="hidden" name="so_idx" id="so_idx" value="<?=$data_list[0]['cp_idx'];?>" />
				시작시간 : <input type="text" name="s_time" id="s_time" value="" class="input_text time" size="10" />
				종료시간 : <input type="text" name="e_time" id="e_time" value="" class="input_text time" size="10" />
				<input type="button" value="저장" class="btnM btnGray" id="time_reg" />
				<input type="button" value="취소" class="btnM btnGray" id="time_cancel" />
			</span>
		</form>
		<form id="frm_time_holiday" name="frm_time_holiday" action="?" method="post">	
			<!-- <input type="button" value="휴무일설정" class="btnM btnGray" id="holiday_btn" /> -->
			<div style="margin-top: 10px;">
				<span>휴무일 설정</span>
				<input type="checkbox" name="holiday_set" id="holiday_set" <? if($data_list[0]['cp_holiday'] == "Y"){ echo "checked";} ?> />
			</div>
			<div style="margin-top: 10px;">
				<span>* 휴무일 설정시 시간을 유지한 상태에서 설정해 주시기 바랍니다.</span>
			</div>
			<input type="hidden" name="mode" id="mode" value="RS_HOLIDAY_TIME_ADD" />
			<input type="hidden" name="cp_date_holiday" id="cp_date_holiday" value="<?=$_GET['cp_date']?>" />
			<input type="hidden" name="dr_idx_holiday" id="dr_idx_holiday" value="<?=$_GET['dr_idx']?>" />
		</form>
		</div>
	</div>
  <div id="BoardHead" class="boxBoardHead">
    <div class="boxMemberBoard">
      <table style="min-width:0;">
        <colgroup>
          <col />
          <col />
          <col />
          <col style="width:101px;" />
          <col style="width:56px;" />
        </colgroup>
        <thead>
          <tr>
            <th scope="col"><input type="checkbox" title="전체선택" name="chk_all" id="chk_all" /></th>
            <th scope="col">시작시간</th>
            <th scope="col">종료시간</th>
            <th scope="col">수정</th>
            <th scope="col">삭제</th>
          </tr>
        </thead>
        <tbody class="alignCenter">
			<?
				if($data_list_cnt > 0) {
					for($i=0; $i<$data_list_cnt; $i++) {
						$cp_s_time = $data_list[$i]['cp_s_time'];
						$cp_e_time = $data_list[$i]['cp_e_time'];
						$cp_num = $data_list[$i]['cp_num'];
						$cp_idx = $data_list[$i]['cp_idx'];
						$cp_day = $data_list[$i]['cp_day'];

						$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"capa_modi('" . $cp_idx ."')", 50,'');
						$del_btn = $C_Button -> getButtonDesign('type2','삭제',0,"capa_del('" . $cp_idx ."')", 50,'');
			?>
          <tr>
            <td><input type="checkbox" title="선택" name="chk_num[]" value="<?=$cp_idx?>" class="delCheck" /></td>
            <td><input type="text" name="cp_s_time_<?=$cp_idx?>" id="cp_s_time_<?=$cp_idx?>" value="<?=$cp_s_time?>" class="input_text time" size="10" /></td>
            <td><input type="text" name="cp_e_time_<?=$cp_idx?>" id="cp_e_time_<?=$cp_idx?>" value="<?=$cp_e_time?>" class="input_text time"  size="10" /></td>
            <td><?=$edit_btn?></td>
            <td><?=$del_btn?></td>
          </tr>
			<?
					}
				}else{
					echo "<tr><td colspan='5'>데이터가 없습니다.</td></tr>";
				}
			?>         
        </tbody>
      </table>
    </div>
  </div>
  <div class="btnWrap">
    <div class="btnLeft">
		
		<input type="button" value="선택삭제" class="btnM btnGray" id="sel_chk" />
    </div>
    <ul class="boxBoardPaging">
      <?=$page_link?>
    </ul>
  </div>
</div>
<link rel="stylesheet" type="text/css" href="/admin/css/jquery.timepicker.css" media="all" />
<script type="text/javascript" src="/admin/js/jquery.timepicker.js"></script>
<script type="text/javascript">

	function capa_modi(cp_idx) {
		
		var cp_s_time = $('#cp_s_time_' + cp_idx).val();
		var cp_e_time = $('#cp_e_time_' + cp_idx).val();
		
		$.ajax({
			type: "POST",
			url: "./proc/reserve_proc.php",
			data: "mode=CAPA_DAY_MODI&cp_s_time=" + cp_s_time + "&cp_e_time=" + cp_e_time + "&cp_idx=" + cp_idx ,
			dataType: "text",
			success: function(msg) {
				
				if($.trim(msg) == "true") {
					alert("수정 되었습니다");					
					window.location.reload();
					return false;
				}else  {
					alert("수정에 실패하였습니다.");	
					window.location.reload();
					return false;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
		
	}
	
	function capa_del(cp_idx) {
		
		$.ajax({
			type: "POST",
			url: "./proc/reserve_proc.php",
			data: "mode=CAPA_DAY_DEL&cp_idx=" + cp_idx ,
			dataType: "text",
			success: function(msg) {
				
				if($.trim(msg) == "true") {
					alert("삭제 되었습니다");		
					window.location.reload();
					return false;
				}else  {
					alert("삭제에 실패하였습니다.");
					window.location.reload();
					return false;
				}
			},
			error: function(xhr, status, error) { alert(error); }
		});
		
	}
	
	$(document).ready(function(){														 
		$('.time').timepicker({ 'timeFormat': 'H:i', 'step': 15 });

		$('#time_show').click(function(){
			$('#time_span').show();
		});

		$('#time_cancel').click(function(){
			$('#time_span').hide();
		});

		$('#time_reg').click(function(){

			if($('#cp_date').val() == '') {
				alert('해당 날짜를 선택하세요');
				return false;
			}

			if($('#s_time').val() == '') {
				alert('시작시간을 입력하세요');
				return false;
			}

			if($('#e_time').val() == '') {
				alert('종료시간을 입력하세요');
				return false;
			}

			$('#frm_time_reg').attr('action','./proc/reserve_proc.php');
			$('#frm_time_reg').submit();
			return false;
		});

		$('#holiday_btn').click(function(){

			if($('#cp_date_holiday').val() == '') {
				alert('해당 날짜를 선택하세요');
				return false;
			}
			
			$('#frm_time_holiday').attr('action','./proc/reserve_proc.php');
			$('#frm_time_holiday').submit();
			return false;
		});

		$("#holiday_set").change(function(){
			if($("#holiday_set").is(":checked")){
				holiday_set("Y");
			}else{
				holiday_set("N");
			}
		});
		
		function holiday_set(status){
			if($('#cp_date_holiday').val() == '') {
				alert('해당 날짜를 선택하세요');
				return false;
			}

			var cp_date = $('#cp_date_holiday').val();
			var dr_idx = $('#dr_idx_holiday').val();

			$.ajax({
				type: "POST",
				url: "./proc/reserve_proc.php",
				data: "mode=RS_HOLIDAY_TIME_MODI&cp_date_holiday=" + cp_date +"&dr_idx_holiday=" + dr_idx + "&status=" + status,
				dataType: "text",
				success: function(msg) {
					
					if($.trim(msg) == "true") {
						if (status == "Y") {
							alert("휴무일로 설정 되었습니다");		
						}else{
							alert("휴무일 설정이 해제 되었습니다");		
						}
						window.location.reload();
						return false;
					}else  {
						alert("휴무일 설정이 실패했습니다.");
						window.location.reload();
						return false;
					}
				},
				error: function(xhr, status, error) { alert(error); }
			});
		}
		$('#chk_all').click(function(){																 
			if($('#chk_all').is(':checked')){
				$('.delCheck').each(function(){
					$(this).attr('checked',true);
				});
			}else{
				$('.delCheck').each(function(){
					$(this).attr('checked', false);
				});
			}
    });
		
		$('#sel_chk').click(function(){																									 	
			var str_idx = "";																 
			$('.delCheck').each(function(){																						 
				if($(this).is(':checked')) {
					str_idx += $(this).val() + ",";
				}
			});	
			str_idx = str_idx.slice(0,-1);
			
			if(str_idx == '') {
				alert("삭제할 시간을 선택하세요");
				return false;
			}
			
			$.ajax({
				type: "POST",
				url: "./proc/reserve_proc.php",
				data: "mode=CAPA_DAY_DEL_ARR&cp_arr_idx=" + str_idx ,
				dataType: "text",
				success: function(msg) {
					
					if($.trim(msg) == "true") {
						alert("삭제 되었습니다");		
						window.location.reload();
						return false;
					}else  {
						alert("삭제에 실패하였습니다.");
						window.location.reload();
						return false;
					}
				},
				error: function(xhr, status, error) { alert(error); }
			});
			
		});
		
	});
</script>