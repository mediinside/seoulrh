<?php	
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	$cate1_select = $C_Func -> makeSelect_Normal('tc_cate1', $GP -> CATE1, $tc_cate1, '', '::선택::');
?>
<script type="text/javascript">
	
	$(document).ready(function(){
														 
		//엔터키 막기
		$("*").keypress(function(e){
			if(e.keyCode==13) 
			{
				$('#img_search').click();
				return false;
			}
			else
			{
				return true;	
			}
		});	
		
		$('#tc_cate1').change(function(){
				var val = $(this).val();
				
				$('#tc_cate2').empty();	
				$('#tc_cate2').append("<option value=''>:::선택:::</option>");
				$('#tc_cate3').empty();
				$('#tc_cate3').append("<option value=''>:::선택:::</option>");
				
				if(val != '') {
						$.ajax({
							type: "POST",
							url: "cate1.php",
							data: "tc_cate1=" + val,
							dataType: "text",
							success: function(data) {
								$('#tc_cate2').empty();											
								$('#tc_cate2').append(data);
							},
							error: function(xhr, status, error) { alert(error); }
						});	
				}
		});
		
		$('#tc_cate2').change(function(){
				var val = $(this).val();
				
				$('#tc_cate3').empty();
				$('#tc_cate3').append("<option value=''>:::선택:::</option>");
				
				if(val != '') {
						$.ajax({
							type: "POST",
							url: "cate2.php",
							data: "tc_cate2=" + val,
							dataType: "text",
							success: function(data) {
								$('#tc_cate3').empty();
								$('#tc_cate3').append(data);
							},
							error: function(xhr, status, error) { alert(error); }
						});	
				}
		});
		
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});			


		var par = "";
		go_FindTreatList(1, par);		
		
		$('#img_search').click(function(){														
			go_FindTreatList(1, '');
			return false;
		});
		
		$('#img_submit').click(function(){
			
			var tmp_lng = $("input[name=sel_treat]:checkbox:checked").length;
			
			if(tmp_lng == 0) {
				alert('하나 이상 선택하셔야 합니다');
				return false;
			}
			var tc_idx = "";  
			var tc_title = "";
			var arr_idx = new Array();
			var page = $('#n_page').val();
	
			var arr_title = new Array();
	    $("input[name=sel_treat]:checkbox:checked").each(function (i) {  
  	      var arr_tmp = $(this).val().split('|');
					tc_idx += arr_tmp[0] + ",";
					tc_title += arr_tmp[1] + ",";	
					arr_idx[i] = arr_tmp[0]; 
					arr_title[i] = arr_tmp[1]; 
    	});  
			tc_idx = tc_idx.slice(0,-1);
			tc_title = tc_title.slice(0,-1);			
			
			<?
				if($_GET['type'] == "A") {
			?>
			var num = "1";			
			<? }else{?>
			var num = "2";			
			<? } ?>			
			
			var old_idx = $(parent.document).find('#tc_idx_' + num).val();
			var old_title = $(parent.document).find('#tc_title_' + num).val();		
			var uniq_idx = "";
			var uniq_title = "";
			
			if(old_idx != '' && page > 1) {					
				var arr_idx_old = old_idx.split(',');	
				var arr_title_old = old_title.split(',');	
				
				var rs_idx = $.merge(arr_idx_old, arr_idx);  
				var rs_title = $.merge(arr_title_old, arr_title); 
				
				uniq_idx = unique(rs_idx);
				uniq_title = unique(rs_title);
			}else{
				uniq_idx = tc_idx;
				uniq_title = tc_title;
			}
			
			$(parent.document).find('#tc_idx_' + num).val(uniq_idx);
			$(parent.document).find('#tc_title_' + num).val(uniq_title);
			$(parent.document).find('#tc_msg_' + num).text(uniq_title);
		
		
			parent.modalclose();					
		});
	});	
	
	function unique(array) {
		
		var result = [];
		$.each(array, function(index, element) {             //배열의 원소수만큼 반복
			if ($.inArray(element, result) == -1) {  //result 에서 값을 찾는다.  //값이 없을경우(-1)
				result.push(element);                       //result 배열에 값을 넣는다.
			}
		});
		return result;
	}
	
	
	var go_FindTreatList = function(page, par) {				
		var string = $("#frm_find").serialize();				
		
		if(page > 1) {		
			var data  = "page=" + page + "&" + par;
		}else{
			var data  = par + "&" + string;
		}	
		
		var scrollpage = $(window).scrollTop();		
		var logingImg = "<div style='width:100%; text-align:center; padding-top:50px;'><img src='/admin/images/loading.gif'><div>";	
		$.ajax({
			url: './treat_search_list.php',
			type: 'POST',
			data: data,
			timeout: 1000 * 10 , //10초동안 응답이 없으면 error처리
			contentTypeString : "text/xml; charset=utf-8",
			beforeSend : function(){ $("#find_result").html(logingImg); },
			error: function(){ return;  },
			success: function(data){				
				$("#find_result").html(data);
			}
		});		
		//return false;
	}
</script>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer boxSearchMember">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>치료법 찾기</strong></span>
		</div>
		<form id="frm_find" name="frm_find" method="post">
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">
				<ul class="mem_search_pop">
					<li><?=$cate1_select?></li>
          <li><select name="tc_cate2" id="tc_cate2"><option value="">:::선택:::</option></select></li>
          <li><select name="tc_cate3" id="tc_cate3"><option value="">:::선택:::</option></select></li>
					<li><button id="img_search" class="btnSearch ">검색</button></li>					
				</ul>	
				
				<div id="find_result"></div>
											
				<div class="btnWrap">
				<button id="img_submit" class="btnSearch ">확인</button>
				<button id="img_cancel" class="btnSearch ">취소</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>