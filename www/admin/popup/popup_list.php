<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP -> CLS."/class.popup.php");	
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Popup 	= new Popup;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 15;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Popup->Popup_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<!--? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?-->										
			<form name="base_form" id="base_form" method="GET">
			<ul>				
				
					<strong class="tit">등록일</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="inputSearch" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="inputSearch" size="13" /></span>
							
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="pop_title" <? if($_GET['search_key'] == "mb_name"){ echo "selected";}?> >제목</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="inputSearch" size="16" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>	

		<div style="margin-top:5px; text-align:right;">
		<button onClick="layerPop('ifm_reg','./popup_reg.php', '100%', 700)"; class="btnSearch ">팝업 등록</button>
		</div>

		<div id="BoardHead" class="boxBoardHead">				
				<div class="boxMemberBoard">
					<table>
							<col />
							<col />
							<col />
							<col />
							<col />
							<col />
							<col style="width:101px;" />
						<thead>
							<tr>
								<th>No</th>								
								<th>제목</th>								
								<th>사용유무</th>								
								<th>게시기간</th>								
								<th>크기</th>								
								<th>스크롤</th>															
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$pop_idx 		= $data_list[$i]['pop_idx'];
									$pop_width	= $data_list[$i]['pop_width'];
									$pop_height	= $data_list[$i]['pop_height'];
									$pop_x_position	= $data_list[$i]['pop_x_position'];
									$pop_y_position	= $data_list[$i]['pop_y_position'];
									$pop_title	= $data_list[$i]['pop_title'];
									$pop_use	= $data_list[$i]['pop_use'];
									$pop_start_date	= date("Y.m.d", strtotime($data_list[$i]['pop_start_date']));
									$pop_end_date	= date("Y.m.d", strtotime($data_list[$i]['pop_end_date']));
									$pop_scroll	= $data_list[$i]['pop_scroll'];
									
									$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./popup_edit.php?pop_idx=" . $pop_idx. "', 1520, 700)", 50,'');	
									$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"popup_delete('" . $pop_idx. "')", 50,'');							
								?>
										<tr>
											<td><?=$data['page_info']['start_num']--?></td>											
											<td><a href="javascript:PopupWindow('<?=$pop_idx;?>','<?=$pop_width;?>','<?=$pop_height;?>', '<?=$pop_x_position;?>', '<?=$pop_y_position;?>', '<?=$pop_scroll;?>');"><?=$pop_title?></a></td>											
											<td><?=$pop_use?></td>											
											<td><?=$pop_start_date;?> ~ <?=$pop_end_date;?></td>											
											<td><?=$pop_width;?>(W)×<?=$pop_height;?>(H)</td>											
											<td><?=$pop_scroll;?></td>											
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

	});

	function popup_delete(pop_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/popup_proc.php",
			data: "mode=POPUP_DEL&pop_idx=" + pop_idx,
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
	
	function PopupWindow(pop_no, win_width, win_height, x_pos, y_pos, scroll_bar) {
    	var le = x_pos;
			var to = y_pos;        
			var wi = parseInt(win_width);
			var he = parseInt(win_height) + 25;
			var scrollbars = "no";

			var openurl = "/popup/popup_window.php?pop_idx=" + pop_no;
			
			if( scroll_bar == "1") { 
				scrollbars = "yes";
				wi += 16;
			}
		
			window.open(openurl, '','left='+le+',top='+to+',width='+wi+',height='+he+',marginwidth=0,marginheight=0,resizable=1,scrollbars=' + scrollbars);
  }
</script>