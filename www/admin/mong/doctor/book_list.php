<?php	
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	include_once($GP -> CLS .'/class.list.php');		
	include_once($GP -> CLS."/class.doctor.php");
	$C_Doctor 	= new Doctor;
	$C_ListClass 	= new ListClass;

	
	$args = array();
	$args['show_row'] = 5;	
	$args['pagetype'] = "admin";
	$data = "";
	$data = $C_Doctor->BookList(array_merge($_GET,$_POST,$args));
	
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
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer boxSearchMember">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>방송출연, 자문, 저서 및 논문 리스트</strong></span>
		</div>
		<form id="frm_find" name="frm_find" method="post">
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">
				<ul class="mem_search_pop">					
					<li><button id="img_reg" class="btnSearch">방송출연, 자문, 저서 및 논문 등록</button></li>					
				</ul>	
				
				<div id="BoardHead" class="boxBoardHead">				
						<div class="boxMemberBoard_layer">
							<table>						
								<thead>
									<tr>
										<th>No</th>
										<th>타입</th>
										<th>첨부파일</th>
										<th>제목</th>
										<th>등록일</th>
										<th>수정/삭제</th>
									</tr>
								</thead>
								<tbody>
									<?
										$dummy = 1;
										for($i=0; $i<$data_list_cnt; $i++) {
											$tb_idx 			= $data_list[$i]['tb_idx'];
											$tb_type 			= $data_list[$i]['tb_type'];
											$tb_title 		= $C_Func->dec_contents_edit($data_list[$i]['tb_title']);
											$tb_file_code = $data_list[$i]['tb_file_code'];
											$tb_content 	= $C_Func->dec_contents_edit($data_list[$i]['tb_content']);			
											$tb_regdate 	= $data_list[$i]['tb_regdate'];		
												 
											$file_ext = substr( strrchr($tb_file_code, "."),1); 
											$file_ext = strtolower($file_ext);	//확장자를 소문자로...											

											$str = "";
											if ($file_ext=="gif" || $file_ext=="jpg" || $file_ext=="png") {	
												$str = "<img src='" . $GP -> UP_BOOK_URL . $tb_file_code . "' height='150' />";
											}else{
												$str = $tb_file_code;
											}
											
											$edit_btn = $C_Button -> getButtonDesign('type2','수정',0,"layerPop('ifm_reg','./book_edit.php?tb_idx=" . $tb_idx. "', 600, 300)", 50,'');	
											$edit_btn .= $C_Button -> getButtonDesign('type2','삭제',0,"book_delete('" . $tb_idx. "')", 50,'');
										?>
										<tr>
											<td><?=$data['page_info']['start_num']--?></td>
											<td><?=$GP -> DOCTOR_PUBLISH[$tb_type]?></td>
											<td><?=$str?></td>
											<td><?=$tb_title?></td>
											<td><?=$tb_regdate?></td>
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
			</div>
		</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	
	$(document).ready(function(){
														 
		$('#img_reg').click(function(){		
				layerPop('ifm_reg','./book_reg.php?dr_idx=<?=$_GET['dr_idx']?>', 600, 300)																 	
				return false;
		});
		
	});
	
	function book_delete(tb_idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/dt_proc.php",
			data: "mode=BOOK_DEL&tb_idx=" + tb_idx,
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
</body>
</html>
