<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.tag.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Tag 	= new Tag;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 10;
	$args['pagetype'] = "admin";

	$tt_cate = 'A';
	if($_GET['tt_cate'] != '') {
		$tt_cate = $_GET['tt_cate'];
	}
	
	$args['tt_cate'] = $tt_cate;
	$data = "";
	$data = $C_Tag->Tag_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);


	$cate1_select = $C_Func -> makeSelect_Normal('tt_cate', $GP -> CATE1, $tt_cate, '', '::선택::');
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
          </span>					
				</li>	
        <li>
					<strong class="tit">노출여부</strong>
					<span>
						<label><input type="radio" name="tt_show" id="tt_show" value="Y" <? if($_GET['tt_show'] == "Y") { echo "checked"; }?> /> 노출</label>
						<label><input type="radio" name="tt_show" id="tt_show" value="N" <? if($_GET['tt_show'] == "N") { echo "checked"; }?> /> 비노출</label>
					</span>
				</li>				
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="tt_tag_name" <? if($_GET['search_key'] == "tt_tag_name"){ echo "selected";}?> >태그명</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="17" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>
		<div class="btnWrap">
		<p class="txtLeft">순번 변경시 해당 행을 드래그하여 원하시는 위치에 놓으시면 됩니다.</p>
		<button onClick="layerPop('ifm_reg','./tag_reg.php', '100%', 400)"; class="btnSearch btnRight ">태그 등록</button>
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
							<col style="width:101px;" />
						</colgroup>
						<thead>
							<tr>
								<th>No</th>								
								<th>CATE</th>								
								<th>태그명</th>								
								<th>URL</th>		
                <th>노출여부</th>														
								<th>등록일</th>								
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<input type="hidden" name="max_desc" id="max_desc" value="<?=$data_list[0]['tt_desc']?>" />
							<?
								$dummy = 1;
								if($data_list_cnt > 0 ) {
									for ($i = 0 ; $i < $data_list_cnt ; $i++) {
										$tt_idx 		 = $data_list[$i]['tt_idx'];
										$tt_cate 		 = $data_list[$i]['tt_cate'];
										$tt_tag_name = $data_list[$i]['tt_tag_name'];
										$tt_url 		 = $data_list[$i]['tt_url'];
										$tt_show 	   = $data_list[$i]['tt_show'];
										$tt_desc 	   = $data_list[$i]['tt_desc'];
										$tt_regdate  = $data_list[$i]['tt_regdate'];																			
									
	
										$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./tag_edit.php?tt_idx=" . $tt_idx. "', '100%', 400)", 50,'');	
										$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"tag_delete('" . $tt_idx. "')", 50,'');
								?>
									<tr id="<?=$tt_idx?>">
										<td><?=$data['page_info']['start_num']--?></td>										
										<td><?=$GP -> CATE1[$tt_cate]?></td>										
										<td><?=$tt_tag_name?></td>										
										<td><a href="<?=$tt_url?>" target="_blank"><?=$tt_url?></a></td>									
										<td><?=($tt_show == "Y") ? "노출" : "비노출"; ?></td>										
										<td><?=$tt_regdate?></td>										
										<td><?=$edit_btn?></td>
									</tr>
									<?
										$dummy++;
									}
								}else{
									echo "<tr><td colspan='7' align='center'>데이터가 없습니다.</td></tr>";
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
		
		var fixHelper = function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		};

		var cl_id = "";
		var ch_id = "";
		$(".boxMemberBoard tbody").sortable({
			helper: fixHelper,
			start: function( event, ui ) {
				cl_id = ui.item.attr('id');
			},	
			stop: function( event, ui ) {
				/*
				var tot_num = ui.item.parent().find('tr').length - 1;
				var now_num = ui.item.index();				
				
				if(now_num == tot_num) {
					fd_num = now_num - 1;					
					ch_id = ui.item.parent().find("tr:eq(" + fd_num + ")").attr('id');					
				}else {
					fd_num = now_num + 1;					
					ch_id = ui.item.parent().find("tr:eq(" + fd_num + ")").attr('id');					
				}
				*/
				
				var tot_num = ui.item.parent().find('tr').length;
				var tmp_id = "";
				for(i=0;  i< tot_num; i++){
					var val = ui.item.parent().find("tr:eq(" + i + ")").attr('id');
					tmp_id += val + ",";
				 }
				 tmp_id = tmp_id.slice(0,-1);

				 var max_desc = $('#max_desc').val();
				 console.log(tmp_id);
				 console.log(max_desc);
				
				
				$.ajax({
					type: "POST",
					url: "./proc/tag_proc.php",
					data: "mode=TT_AUTO_CH&tmp_id=" + tmp_id + "&max_desc=" + max_desc,
					dataType: "text",
					success: function(msg) {
		
						if($.trim(msg) == "true") {
							alert("변경되었습니다.");
							window.location.reload();
							return false;
						}else{
							alert('변경에 실패하였습니다.');
							return;
						}
					},
					error: function(xhr, status, error) { alert(error); }
				});
				
			},	
			
			
		}).disableSelection();
		
	
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

	});

	function tag_delete(tt_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/tag_proc.php",
			data: "mode=TAG_DEL&tt_idx=" + tt_idx,
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