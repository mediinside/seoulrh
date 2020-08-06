<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	ini_set('error_reporting', E_ERROR);

	include_once("../../_init.php");	
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.cooperative.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_Cooperative 	= new Cooperative;
	$C_Button 		= new Button;

	$data = "";
	$data = $C_Cooperative -> cooperative_List(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);
	
	
	$cate1_select = $C_Func -> makeSelect_Normal('cate1', $GP -> COOPERATIVE_CATE_TYPE1, $cate1, '', '::선택::');
	// $cate2_select = $C_Func -> makeSelect_Normal('cate2', $GP -> cooperative_CATE_TYPE1_1, $_GET['cate2'], ' title="카테고리2"', '::선택::');

	include_once($GP -> INC_ADM_PATH."/head.php");
?>
<body>
<div class="Wrap"><!--// 전체를 감싸는 Wrap -->
		<? include_once($GP -> INC_ADM_PATH."/header.php"); ?>
		<div class="boxContentBody">
			<div class="boxSearch">
			<!-- <? include_once($GP -> INC_ADM_PATH."/inc.mem_search.php"); ?>										 -->
			<form name="base_form" id="base_form" method="GET">
			<ul>				
				<li>
					<strong class="tit">등록일</strong>
					<span><input type="text" name="s_date" id="s_date" value="<?=$_GET['s_date']?>" class="input_text" size="13"></span>
					<span>~</span>
					<span><input type="text" name="e_date" id="e_date" value="<?=$_GET['e_date']?>" class="input_text" size="13" /></span>
				</li>				
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="cp_name" <? if($_GET['search_key'] == "cp_name"){ echo "selected";}?> >기관명</option>
						<option value="cp_place" <? if($_GET['search_key'] == "cp_place"){ echo "selected";}?> >소재지</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="16" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
					<!-- <span><input type="button" id="excel_btn" value="EXCEL" /></span>	 -->
				</li>
			</ul>
			</form>
			</div>
		</div>		
		<div class="btnWrap">
		<button onClick="layerPop('ifm_reg','./cooperative_reg.php', '100%', 600)"; class="btnSearch btnRight">협력기관 등록</button>1
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
								<th>기관</th>
								<th>기관명</th>
								<th>소재지</th>
								<th>링크</th>
								<th>대표전화</th>
								<th>등록일</th>
								<th>수정/삭제</th>
							</tr>
						</thead>
						<tbody>
							<?
								$dummy = 1;
								for ($i = 0 ; $i < $data_list_cnt ; $i++) {
									$cp_idx 			= $data_list[$i]['cp_idx'];
									$cate1 			= $data_list[$i]['cate1'];
									$cate2 			= $data_list[$i]['cate2'];
                                    $cp_place 			= $data_list[$i]['cp_place'];
                                    $cp_link 			= $data_list[$i]['cp_link'];
									$cp_name 		= $data_list[$i]['cp_name'];
									$cp_addr 		= $data_list[$i]['cp_addr'];
									$np_gubun 		= $data_list[$i]['np_gubun'];
                                    $cp_phone 		= $data_list[$i]['cp_phone'];
                                    $cp_img 			= $data_list[$i]['cp_img'];
                                    $cp_regdate 	= $C_Func->strcut_utf8($data_list[$i]['cp_regdate'], 10, true, "");	//제목 (길이, HTML TAG제한여부 처리);
                                    
                                    $img_src = "";
									if($cp_img != '') {
										$img_src = "<img src='" . $GP -> UP_cooperative_URL . $cp_img . "' alt='' width='100' />";
									}else{
										$img_src = "<img src='/images/no_image.jpg' alt='' width='100' />";
									}
									
									$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./cooperative_edit.php?cp_idx=" . $cp_idx. "', '100%', 600)", 50,'');	
									$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"cooperative_delete('" . $cp_idx. "')", 50,'');							
								?>
								<tr>
									<td><?=$data['page_info']['start_num']--?></td>
									<td>
                                        <?=$img_src?>
									</td>
									<td><?=$cp_name?></td>
									<td><?=$cp_place?></td>
									<td><?=$cp_link?></td>
									<td><?=$cp_phone?></td>				
									<td><?=$cp_regdate?></td>
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

		//엑셀 출력
		$('#excel_btn').click(function(){
				var string = $("#base_form").serialize();
				location.href = "?excel_file=cooperative_list" + "&" + string;
				return false;
		});

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

	function cooperative_delete(cp_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/cooperative_proc.php",
			data: "mode=COOPERATIVE_DEL&cp_idx=" + cp_idx,
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