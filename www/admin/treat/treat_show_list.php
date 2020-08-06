<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP -> CLS."/class.treat.php");	
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Treat 	= new Treat;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 15;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Treat->Treat_Show_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
	
	$cate1_select = $C_Func -> makeSelect_Normal('tcs_cate1', $GP -> CATE1, $_GET['tcs_cate1'], '', '::선택::');
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?>										
			<form name="base_form" id="base_form" method="GET">
			<ul>				
				<li>
					<strong class="tit">등록일</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
				</li>	
        <li>
					<strong class="tit">구분</strong>
					<span>
          	<?=$cate1_select?>
            <select name="tcs_cate2" id="tcs_cate2">
              <option value="">:::선택:::</option>
            </select>
            <select name="tcs_cate3" id="tcs_cate3">
              <option value="">:::선택:::</option>
            </select>
          </span>					
				</li>			
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="tc_title_1" <? if($_GET['search_key'] == "tc_title_1"){ echo "selected";}?> >비수술</option>
            <option value="tc_title_2" <? if($_GET['search_key'] == "tc_title_2"){ echo "selected";}?> >수술</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="16" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>	

		<div class="btnWrap">
		<button onClick="layerPop('ifm_reg','./treat_show_reg.php', '100%', 700)"; class="btnSearch btnRight">치료법 노출 등록</button>
		</div>

		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					<table>
						<colgroup>
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
							<col style="width:101px;"/>
						</colgroup>
						<thead>
							<tr>
								<th>No</th>
								<th>CATE1</th>
								<th>CATE2</th>
								<th>CATE3</th>
                <th>비수술</th>
								<th>수술</th>
								<th>등록일</th>
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$tcs_idx 		= $data_list[$i]['tcs_idx'];
									$tcs_cate1	= $data_list[$i]['tcs_cate1'];
									$tcs_cate2	= $data_list[$i]['tcs_cate2'];
									$tcs_cate3	= $data_list[$i]['tcs_cate3'];
									$tc_title_1	= $data_list[$i]['tc_title_1'];									
									$tc_title_2	= $data_list[$i]['tc_title_2'];									
									$tcs_regdate	= date("Y.m.d", strtotime($data_list[$i]['tcs_regdate']));
									
									$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./treat_show_edit.php?tcs_idx=" . $tcs_idx. "', '100%', 700)", 50,'');
									$edit_btn .= "" . $C_Button -> getButtonDesign('type2','삭제',0,"treat_show_delete('" . $tcs_idx. "')", 50,'');							
								?>
										<tr>
											<td><?=$data['page_info']['start_num']--?></td>											
											<td><?=$GP->CATE1[$tcs_cate1]?></td>
											<td><?=$GP->CATE4[$tcs_cate1][$tcs_cate2]?></td>
											<td><?=$GP->CATE5[$tcs_cate2][$tcs_cate3]?></td>
											<td><?=$tc_title_1;?></td>
                      <td><?=$tc_title_2;?></td>
											<td><?=$tcs_regdate;?></td>
											<td><?=$edit_btn?></td>
										</tr>
										<?
										$dummy++;
									}
							?>						
						</tbody>
					</table>
				</div>			
			</div>
			<ul class="boxBoardPaging">
				<?=$page_link?>
			</ul>
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

			if($.trim($('#search_content').val()) != '')
			{
				if($('#search_key option:selected').val() == '')
				{
					alert('검색조건을 선택하세요');
					return false;
				}
			}

			if($('#search_key option:selected').val() != '')
			{
				if($.trim($('#search_content').val()) == '')
				{
					alert('검색내용을 입력하세요');
					$('#search_content').focus();
					return false;
				}
			}


			$('#base_form').submit();
			return false;
		});
		
		
		<?
			if($_GET['tcs_cate1'] != '' && $_GET['tcs_cate2'] != '') {
		?>
		$.ajax({
			type: "POST",
			url: "cate_show1.php",
			data: "tcs_cate1=<?=$_GET['tcs_cate1']?>&tcs_cate2=<?=$_GET['tcs_cate2']?>",
			dataType: "text",
			success: function(data) {
				$('#tcs_cate2').empty();
				$('#tcs_cate2').append(data);
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		<?
			}
		?>
		
		<?
			if($_GET['tcs_cate2'] != '' && $_GET['tcs_cate3'] != '') {
		?>
		$.ajax({
			type: "POST",
			url: "cate_show2.php",
			data: "tcs_cate2=<?=$_GET['tcs_cate2']?>&tcs_cate3=<?=$_GET['tcs_cate3']?>",
			dataType: "text",
			success: function(data) {
				$('#tcs_cate3').empty();				
				$('#tcs_cate3').append(data);
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		<?
			}
		?>
		
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

	});

	function treat_show_delete(tcs_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/treat_proc.php",
			data: "mode=TREAT_SHOW_DEL&tcs_idx=" + tcs_idx,
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