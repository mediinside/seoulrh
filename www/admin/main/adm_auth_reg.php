<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> INC_ADM_PATH."inc.adm_auth.php");
	include_once $GP -> INC_ADM_PATH.'menu.php';
	include_once($GP->CLS."class.admmain.php");
	$C_AdminMain 	= new AdminMain;


	$args = '';
	$board_data = $C_AdminMain ->Board_List_All($args);
	
	$sel_level = $C_Func ->makeSelect("tl_level", $GP -> AUTH_LEVEL, $tl_level, "", "::: 선택 :::");		
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>권한 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?">
		<input type="hidden" name="mode" id="mode" value="ADMINAUTHREG" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<th width="15%"><span>*</span>권한</th>
								<td width="85%">
									<?=$sel_level?>
								</td>
							</tr>
							<tr>
								<th><span>*</span>접근폴더</th>
								<td>
									<?
									$mq = '';
									foreach($GP -> MENU_ADMIN as $key => $val) {
										$menuQuery = $val['folder'];
										foreach ($GP -> MENU_SUB_ADMIN[$menuQuery] as $key1 => $val1) {
											foreach ($val1 as $key2 => $val2) {
									?>
									<!-- div style="width:150px; display:inline-block; margin:7px;"><input type="checkbox" name="tl_folder[]" value="<?=$val['folder']?>" /><?=$val['name']?></div -->	
									<div style="width:200px; display:inline-block; margin:7px;"><input type="checkbox" name="tl_folder[]" value="<?=$menuQuery?>|<?=$val2['title']?>" /><?=$val2['name']?></div>	
									<?
											}
										}
									}
									?>
								</td>
							</tr>
							<tr>
								<th><span>*</span>접근게시판</th>
								<td>
									<?
										for($i=0; $i<count($board_data); $i++) {
											$jba_title = $board_data[$i]['jba_title'];
											$jb_code = $board_data[$i]['jb_code'];
									?>
									<div style="width:150px; display:inline-block; margin:7px;"><input type="checkbox" name="tl_bbs[]" value="<?=$jb_code?>" /><?=$jba_title?></div>
									<?
									}
									?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="btnWrap">
				<button id="img_submit" class="btnSearch ">등록</button>
				<button id="img_cancel" class="btnSearch ">취소</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>
<?
include_once($GP -> INC_ADM_PATH."/footer.php");
?>
<script src="/admin/js/jquery.alphanumeric.js" type="text/javascript"></script>
<script type="text/javascript" src="/admin/js/jquery.validate.js"></script>
<script type="text/javascript">

	$(document).ready(function(){	
		
		$('#img_submit').click(function(){					
																		
				if($('#tl_level option:selected').val() == '') {
					alert('권한을 선택하세요');
					return false;
				}
				
				if($('input[name="tl_folder[]"]:checkbox:checked').length == 0) {
					alert('접근 폴더를 선택하세요');
					return false;
				}

				if($('input[name="tl_bbs[]"]:checkbox:checked').length == 0) {
					alert('접근 가능 게시판을 선택하세요');
					return false;
				}
		
				$('#base_form').attr('action','./proc/main_proc.php');
				$('#base_form').submit();
				return false;
		});
		
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});		
		
		
		
	});
</script>
