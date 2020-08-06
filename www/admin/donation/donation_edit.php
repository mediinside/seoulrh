<?php
error_reporting(E_ALL);

ini_set("display_errors", 1);

	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	include_once($GP -> CLS."/class.donation.php");
    $C_donation 	= new donation;
    
    
	$args = "";
	$args['do_idx'] 	= $_GET['do_idx'];
	$rst = $C_donation ->donation_Info($args);
	
	if($rst) {
		extract($rst);	
    }
    $donation_select = $C_Func -> makeSelect_Normal('do_type', $GP -> donation_TYPE2, $do_type, '', '::선택::');	
	
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>메인 슬라이드 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="donation_MODI" />
		<input type="hidden" name="do_idx" id="do_idx" value="<?=$_GET['do_idx']?>" />
		<input type="hidden" name="before_image_main" id="before_image_main" value="<?=$do_img?>" />
		<input type="hidden" name="before_image_main2" id="before_image_main2" value="<?=$do_img2?>" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>		
                        <tr>
								<th><span>*</span>분류</th>
								<td>
									<?=$donation_select?>
								</td>
							</tr> 													          							
							<tr>
								<th><span>*</span>제목</th>
								<td>
									<input type="text" class="input_text" size="70" name="do_title" id="do_title" value="<?=$do_title?>" />
								</td>
							</tr>							
							<tr>
								<th><span>*</span>링크</th>
								<td>
                                    <input type="text" class="input_text" size="70" name="do_link" id="do_link" value="<?=$do_link?>" />
                                    ex) https://www.youtube.com/embed/fa-URCbJm4A 
                                      <!--p class="colorIdt"></p-->
								</td>
							</tr>
							<!-- tr>
								<th><span>*</span>내용</th>
								<td>
									<textarea name="do_content" id="do_content" style="width:98%; height:100px;  overflow:auto;" ><?=$do_content?></textarea>
								</td>
							</tr -->
							<tr>
								<th><span>*</span>노출여부</th>
								<td>
									<input type="radio" name="do_show" id="do_show" value="Y" <? if($do_show == "Y"){ echo "checked";}?> />노출
									<input type="radio" name="do_show" id="do_show" value="N" <? if($do_show == "N"){ echo "checked";}?> />비노출
								</td>
							</tr>
							<!-- <tr>
								<th><span>*</span>새창여부</th>
								<td>
									<label>
										<input type="checkbox" name="do_type" id="do_type" value="Y" <? if($do_type == "Y"){ echo "checked";}?> /> 새창
									</label>
								</td>
							</tr> -->
						<tr>
							<th><span>*</span>이미지</th>
							<td>
								<input type="file" name="do_img" id="do_img" size="30">(578 X 163)
								<?
									if($do_img != "") {
										echo  "<br>" . $do_img;
								?>
									<a href="#" onClick="img_del('<?=$do_img;?>','<?=$_GET['do_idx']?>','W')">(X)</a>
								<? } ?>
							</td>
						</tr>
						<!--tr>
							<th><span>*</span>오른쪽 배너</th>
							<td>
								<input type="file" name="do_img2" id="do_img2" size="30"><span id="size_m"></span>
								<?
									if($do_img2 != "") {
										echo  "<br>" . $do_img2;
								?>
									<a href="#" onClick="img_del('<?=$do_img2;?>','<?=$_GET['do_idx']?>','M')">(X)</a>
								<? } ?>
							</td>
						</tr-->
						</tbody>
					</table>
				</div>				
				<div class="btnWrap">
					<span class="btnRight">
						<button id="img_submit" class="btnSearch ">수정</button>
						<button id="img_cancel" class="btnSearch ">취소</button>
					</span>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>
<script type="text/javascript">


	function img_del(image, idx, type)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/donation_proc.php",
			data: "mode=donation_IMGDEL&do_idx=" + idx + "&file=" + image + "&type=" + type,
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

	$(document).ready(function(){	
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});	
		
				
		$('#img_submit').click(function(){			
			
			$('#base_form').attr('action','./proc/donation_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>