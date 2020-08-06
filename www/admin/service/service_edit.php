<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	include_once($GP -> CLS."/class.seoulrh.php");
	$C_seoulrh 	= new seoulrh;
	
	$args = "";
	$args['s_idx'] 	= $_GET['s_idx'];
	$rst = $C_seoulrh ->seoulrh_Info($args);
	
	if($rst) {
		extract($rst);		
    }	

    $service_select = $C_Func -> makeSelect_Normal('s_year', $GP -> SERVICE_TYPE, $s_year, '', '::선택::');
    
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>유튜브 영상 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="seoulrh_MODI" />
		<input type="hidden" name="s_idx" id="s_idx" value="<?=$_GET['s_idx']?>" />
        <input type="hidden" name="s_type" id="s_type" value="D" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
					<table class="table table-bordered">
						<tbody>                       
                        <tr>
								<th><span>*</span>위치</th>
								<td>
									<?=$service_select?>
								</td>
							</tr> 		                       												          							                     
							<tr>
								<th><span>*</span>제목</th>
								<td>
									<input type="text" class="input_text" size="100" name="s_content1" id="s_content1" value="<?=$s_content1?>"/>
								</td>
                            </tr>	
                            <tr>
								<th><span>*</span>영상 URL</th>
								<td>
                                    <input type="text" class="input_text" size="100" name="s_content2" id="s_content2" value="<?=$s_content2?>"/>
                                    ex) https://www.youtube.com/embed/fa-URCbJm4A 
								</td>
                            </tr>
                            <tr>
							<th><span>*</span>내용</th>
							<td>
								<textarea name="s_content3" id="s_content3" style="width:98%; height:100px; overflow:auto;" ><?=$s_content3?>"</textarea>
							</td>
						    </tr>                               
						    </tr>   	
							<tr>
								<th><span>*</span>노출여부</th>
								<td>
									<input type="radio" name="s_show" id="s_show" value="Y" <? if($s_show == "Y"){ echo "checked";}?> />노출
									<input type="radio" name="s_show" id="s_show" value="N" <? if($s_show == "N"){ echo "checked";}?> />비노출
								</td>
							</tr>								
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
			url: "./proc/seoulrh_proc.php",
			data: "mode=seoulrh2_IMGDEL&s_idx=" + idx + "&file=" + image + "&type=" + type,
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
		size_guide();
		$('#img_cancel').click(function(){
				parent.modalclose();				
		});	
		
		$('#s_type').change(function(){
			size_guide();
		});
		
		function size_guide(){
			var type = $("#s_type option:selected").val();
			if (type == 'main') {
				$('#size_pc').text('(1398*600)');
				$('#size_m').text('(720*420)');
				$('#mobile_img').show();
			}else if (type == 'left') {
				$('#size_pc').text('(200*360)');
				$('#size_m').text('(720*180)');
				$('#mobile_img').show();
			}else{
				$('#size_pc').text('(360*200)');
				$('#mobile_img').hide();
			}
		}
		
		$('#img_submit').click(function(){
			/*
			if($('#s_descrition').val() == '') {
				alert('소제목을 입력하세요');
				$('#s_descrition').focus();
				return false;
			}		
			
			if($('#s_title').val() == '') {
				alert('제목을 입력하세요');
				$('#s_title').focus();
				return false;
			}	
			
			if($('#s_content').val() == '') {
				alert('내용을 입력하세요');
				$('#s_content').focus();
				return false;
			}
			*/
			
			$('#base_form').attr('action','./proc/service_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>