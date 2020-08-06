<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");
	
	include_once($GP->CLS."class.list.php");
	include_once($GP->CLS."class.admmain.php");
	include_once($GP->CLS."class.button.php");
	$C_ListClass 	= new ListClass;
	$C_AdminMain 	= new AdminMain;
	$C_Button 		= new Button;
	
	$args = array();
	$args['show_row'] = 10;
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_AdminMain->getAdminList(array_merge($_GET,$_POST,$args));
	
	$data_list 		= $data['data'];
	$page_link 		= $data['page_info']['link'];
	$page_search 	= $data['page_info']['search'];
	$totalcount 	= $data['page_info']['total'];
	
	$totalpages 	= $data['page_info']['totalpages'];
	$nowPage 		= $data['page_info']['page'];
	$totalcount_l 	= number_format($totalcount,0);
	
	$data_list_cnt 	= count($data_list);


	$get_par = "page=" . $nowPage . "&s_date=" . $_GET['s_date'] . "&e_date=" . $_GET['e_date'] . "&search_key=" . $_GET['search_key']. "&search_content=" . $_GET['search_content'];
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
					<strong class="tit">의료진선택</strong>
					<span>
						<select style="width:200px;">
							<option>선택하기</option>
						</select>
					</span>
				</li>			
				<li>
					<strong class="tit">진료과목선택</strong>
					<span>
						<select style="width:200px;">
							<option>선택하기</option>
						</select>
					</span>
				</li>			
				<li>
					<strong class="tit">검색조건</strong>
					<span>
					<select name="search_key" id="search_key">
						<option value="">:: 선택 ::</option>
						<option value="mb_name" <? if($_GET['search_key'] == "mb_name"){ echo "selected";}?> >성명</option>
						<option value="mb_email" <? if($_GET['search_key'] == "mb_email"){ echo "selected";}?>>이메일</option>
					</select>
					</span>
					<span><input type="text" name="search_content" id="search_content" value="<?=$_GET['search_content']?>" class="input_text" size="17" /></span>
					<span><button id="search_submit" class="btnSearch ">검색</button></span>
				</li>
			</ul>
			</form>
			</div>
		</div>
		<div class="calSection">
			<div class="calWrap">
				<div class="calHead">
					<button type="button" class="prev"><span>이전달</span></button>
					<strong>2015년 03월</strong>
					<button type="button" class="next"><span>다음달</span></button>
				</div>
				<div class="calBody">
					<table>
						<caption>진료가능 날짜 선택</caption>
						<colgroup>
							<col style="width:14%" />
							<col style="width:14%" />
							<col style="width:14%" />
							<col style="width:14%" />
							<col style="width:14%" />
							<col style="width:14%" />
							<col style="width:14%" />
						</colgroup>
						<thead>
							<tr>
								<th scope="col" class="sun">일</th>
								<th scope="col">월</th>
								<th scope="col">화</th>
								<th scope="col">수</th>
								<th scope="col">목</th>
								<th scope="col">금</th>
								<th scope="col">토</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="sun"></td>
								<td><button type="button">2</button></td>
								<td><button type="button">3</button></td>
								<td><button type="button">4</button></td>
								<td><button type="button">5</button></td>
								<td><button type="button">6</button></td>
								<td><button type="button">7</button></td>
							</tr>
							<tr>
								<td class="sun"><button type="button">1</button></td>
								<td><button type="button">2</button></td>
								<td><button type="button">3</button></td>
								<td><button type="button">4</button></td>
								<td><button type="button">5</button></td>
								<td><button type="button">6</button></td>
								<td><button type="button">7</button></td>
							</tr>
							<tr>
								<td class="sun"><button type="button">1</button></td>
								<td><button type="button">2</button></td>
								<td class="cho"><button type="button">3</button></td>
								<td class="on"><button type="button">4</button></td>
								<td class="today"><button type="button">5</button></td>
								<td><button type="button">6</button></td>
								<td><button type="button">7</button></td>
							</tr>
							<tr>
								<td class="sun"><button type="button">1</button></td>
								<td><button type="button">2</button></td>
								<td><button type="button">3</button></td>
								<td><button type="button">4</button></td>
								<td><button type="button">5</button></td>
								<td><button type="button">6</button></td>
								<td></td>
							</tr>
						</tbody>
					</table>
				</div>
				<p class="calInfo">
					<span><img src="/admin/images/icon_cho.gif" alt="" /> 선택</span>
					<span><img src="/admin/images/icon_on.gif" alt="" /> 진료가능날짜</span>
				</p>
				<p>* 진료가능 날짜를 선택하시려면 해당 날짜를 클릭하세요.</p>
			</div>
			<div class="timeTable">
				<div id="BoardHead" class="boxBoardHead">
					<div class="boxMemberBoard">
						<table>
							<colgroup>
								<col />
								<col />
								<col />
								<col style="width:101px;" />
								<col style="width:56px;" />
							</colgroup>
							<thead>
								<tr>
									<th scope="col"><input type="checkbox" title="전체선택" /></th>
									<th scope="col">시작시간</th>
									<th scope="col">종료시간</th>
									<th scope="col">수정</th>
									<th scope="col">삭제</th>
								</tr>
							</thead>
							<tbody class="alignCenter">
								<tr>
									<td><input type="checkbox" title="선택" /></td>
									<td>오전 09:00</td>
									<td>오전 09:30</td>
									<td>
										<span id="webForm"><input type="button" value="수정"></span>
									</td>
									<td>
										<span id="webForm"><input type="button" value="삭제"></span>
									</td>
								</tr>
								<tr>
									<td><input type="checkbox" title="선택" /></td>
									<td>
										<input type="text" />
									</td>
									<td>
										<input type="text" />
									</td>
									<td class="editBtn">
										<span><input type="button" value="취소"></span><span><input type="button" value="저장"></span>
									</td>
									<td>
										<span><input type="button" value="삭제"></span>
									</td>
								</tr>
								<tr>
									<td><input type="checkbox" title="선택" /></td>
									<td>오전 09:00</td>
									<td>오전 09:30</td>
									<td>
										<span id="webForm"><input type="button" value="수정"></span>
									</td>
									<td>
										<span id="webForm"><input type="button" value="삭제"></span>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
				<div class="btnWrap">
					<div class="btnLeft">
						<input type="button" value="선택삭제" class="btnM btnGray" />
					</div>
					<ul class="boxBoardPaging">
						<li class="boxPagingPrev"><span><a href="?page=1&amp;s_date=&amp;e_date="><strong>&lt;</strong></a></span></li><li		class="boxPagingNum"><strong>1</strong> <a href="?page=2&amp;s_date=&amp;e_date=">2</a> <a href="?page=3&amp;s_date=&amp;e_date=">3</a> <a href="?page=4&amp;s_date=&amp;e_date=">4</a> <a href="?page=5&amp;s_date=&amp;e_date=">5</a></li><li class="boxPagingNext"><span><a href="?page=6&amp;s_date=&amp;e_date="><strong>&gt;</strong></a></span></li>
					</ul>
				</div>
			</div>
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