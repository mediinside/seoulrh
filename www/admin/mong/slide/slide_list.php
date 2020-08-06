<?php
	include_once("../../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.slide.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Slide 	= new Slide;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 10;
	$args['pagetype'] = "admin";
	$args['ts_lang']	  = $lang;
	$data = "";
	$data = $C_Slide->Slide_List(array_merge($_GET,$_POST,$args));
	
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
			
			<form name="base_form" id="base_form" method="GET">
			<ul>				
				<li>
					<strong class="tit">등록일</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
				</li>	      
        <li>
					<strong class="tit">노출여부</strong>
					<span>
						<label><input type="radio" name="ts_show" id="ts_show" value="Y" <? if($_GET['ts_show'] == "Y") { echo "checked"; }?> /> 노출</label>
						<label><input type="radio" name="ts_show" id="ts_show" value="N" <? if($_GET['ts_show'] == "N") { echo "checked"; }?> /> 비노출</label>
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
		<div style="margin-top:5px; text-align:right;">
		<button onClick="layerPop('ifm_reg','./slide_reg.php', '100%', 650)"; class="btnSearch ">슬라이드 등록</button>
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
							<col style="width:101px;" />
						</colgroup>
						<thead>
							<tr>
								<th>No</th>
								<th>이미지</th>
                                <!--th>테블릿 이미지</th-->
								<th>모바일 이미지</th>
								<!-- th>제목</th -->
								<th>링크</th>
								<!-- th>요약글</th -->
								<th>노출</th>
								<th>등록일</th>
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								if($data_list_cnt > 0 ) {
									for ($i = 0 ; $i < $data_list_cnt ; $i++) {
										$ts_idx 		 		= $data_list[$i]['ts_idx'];
										$ts_descrition 	= $data_list[$i]['ts_descrition'];
										$ts_title 			= $data_list[$i]['ts_title'];
										$ts_link 			= $data_list[$i]['ts_link'];
										$ts_content 		= $data_list[$i]['ts_content'];
										$ts_show 		= $data_list[$i]['ts_show'];
										$ts_img			= $data_list[$i]['ts_img'];
										$ts_m_img 		= $data_list[$i]['ts_m_img'];
										$ts_t_img 		= $data_list[$i]['ts_t_img'];
										$ts_regdate		= $data_list[$i]['ts_regdate'];																			
										
										$b_img = '';
										if($ts_img !=  '') {
											$b_img = "<img src='" . $GP -> UP_SLIDE_URL . $ts_img . "' width='100px' />";
										}else {
											$b_img = "<img src='/images/no_image.jpg' width='100px' />";
										}

										$t_img = '';
										if($ts_t_img !=  '') {
											$t_img = "<img src='" . $GP -> UP_SLIDE_URL . $ts_t_img . "' width='100px' />";
										}else {
											$t_img = "<img src='/images/no_image.jpg' width='100px' />";
										}

										$m_img = '';
										if($ts_m_img !=  '') {
											$m_img = "<img src='" . $GP -> UP_SLIDE_URL . $ts_m_img . "' width='100px' />";
										}else {
											$m_img = "<img src='/images/no_image.jpg' width='100px' />";
										}
	
										$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./slide_edit.php?ts_idx=" . $ts_idx. "', '100%', 650)", 50,'');	
										$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"slide_delete('" . $ts_idx. "')", 50,'');
								?>
									<tr>
										<td><?=$data['page_info']['start_num']--?></td>										
										<td><?=$b_img ?></td>
										<td><?=$m_img ?></td>
										<!-- td><?=$ts_title?></td -->
										<td><a herf="<?=$ts_link?>" target="_blank"><?=$ts_link?></a></td>	
										<!-- td><?=$ts_descrition?></td -->									
										<td><?=($ts_show == "Y") ? "노출" : "비노출"; ?></td>										
										<td><?=$ts_regdate?></td>										
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

	function slide_delete(ts_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/slide_proc.php",
			data: "mode=SLIDE_DEL&ts_idx=" + ts_idx,
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