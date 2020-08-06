<?php
//error_reporting(E_ALL);

//ini_set("display_errors", 1);

	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.seoulrh.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_seoulrh 	= new seoulrh;
	$C_Button 		= new Button;
	
	$args = array();
    $args['show_row'] = 10;
    $args['s_type'] = "D";
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_seoulrh->seoulrh_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);


    $seoulrh_select = $C_Func -> makeSelect_Normal('s_select', $GP -> seoulrh_TYPE, $s_select, '', '::선택::');
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody" style="display: none;">
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
					<strong class="tit">노출여부</strong>
					<span>
						<label><input type="radio" name="s_show" id="s_show" value="Y" <? if($_GET['s_show'] == "Y") { echo "checked"; }?> /> 노출</label>
						<label><input type="radio" name="s_show" id="s_show" value="N" <? if($_GET['s_show'] == "N") { echo "checked"; }?> /> 비노출</label>
					</span>
				</li>				
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="searcs_key" id="searcs_key">
						<option value="">:: 선택 ::</option>
						<option value="tt_tag_name" <? if($_GET['searcs_key'] == "tt_tag_name"){ echo "selected";}?> >태그명</option>
					</select>
					</span>
					<span><input type="text" name="searcs_content" id="searcs_content" value="<?=$_GET['searcs_content']?>" class="input_text" size="17" /></span>
					<span><button id="searcs_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>
		<div style="margin-top:5px; text-align:right;">
		<button onClick="layerPop('ifm_reg','./service_reg.php', '100%', 650)"; class="btnSearch ">유튜브 영상 등록</button>
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
                                <th>구분</th>
                                <th>제목</th>
                                <th>URL</th>	                                
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
										$s_idx        = $data_list[$i]['s_idx'];
                                        $s_year      = $data_list[$i]['s_year'];
                                        $s_content1      = $data_list[$i]['s_content1'];
                                        $s_content2      = $data_list[$i]['s_content2'];
                                        $s_content3      = $data_list[$i]['s_content3'];
                                        $s_content4      = $data_list[$i]['s_content4'];
                                        $s_content5      = $data_list[$i]['s_content5'];
                                        $s_content6      = $data_list[$i]['s_content6'];
                                        $s_content7      = $data_list[$i]['s_content7'];
										$s_type       = $data_list[$i]['s_type'];										
										$s_show       = $data_list[$i]['s_show'];										
										$s_regdate    = $data_list[$i]['s_regdate'];																			
																				
										$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./service_edit.php?s_idx=" . $s_idx. "', '100%', 650)", 50,'');	
										$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"seoulrh_delete('" . $s_idx. "')", 50,'');
							?>
									<tr>
                                        <td><?=$data['page_info']['start_num']--?></td>									
										<td><?=$GP -> SERVICE_TYPE[$s_year]?></td>										
                                        <td><?=$s_content1?></td>	
                                        <td><?=$s_content2?></td>	                                                                            							
										<td><?=($s_show == "Y") ? "노출" : "비노출"; ?></td>										
										<td><?=$s_regdate?></td>										
										<td><?=$edit_btn?></td>
									</tr>
									<?
										$dummy++;
									}
								}else{
									echo "<tr><td colspan='11' align='center'>데이터가 없습니다.</td></tr>";
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

		$('#searcs_submit').click(function(){																			 

			if($.trim($('#searcs_content').val()) != '')
			{
				if($('#searcs_key option:selected').val() == '')
				{
					alert('검색조건을 선택하세요');
					return false;
				}
			}

			if($('#searcs_key option:selected').val() != '')
			{
				if($.trim($('#searcs_content').val()) == '')
				{
					alert('검색내용을 입력하세요');
					$('#searcs_content').focus();
					return false;
				}
			}


			$('#base_form').submit();
			return false;
		});

	});

	function seoulrh_delete(s_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/service_proc.php",
			data: "mode=seoulrh_DEL&s_idx=" + s_idx,
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