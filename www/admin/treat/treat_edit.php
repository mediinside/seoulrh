<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	include_once($GP -> CLS."/class.treat.php");
	$C_Treat 	= new Treat;
	
	$args = "";
	$args['tc_idx'] 	= $_GET['tc_idx'];
	$rst = $C_Treat ->TREAT_Info($args);
	
	if($rst) {
		extract($rst);
		$tc_content1  = $C_Func->dec_contents_edit($tc_content);		
		
		$cate1_select = $C_Func -> makeSelect_Normal('tc_cate1', $GP -> CATE1, $tc_cate1, '', '::선택::');
	}
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>치료법 수정</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="TREAT_MODI" />
		<input type="hidden" name="tc_idx" id="tc_idx" value="<?=$_GET['tc_idx']?>" />
		<input type="hidden" name="before_image_main" id="before_image_main" value="<?=$tc_img?>" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">
				<div class="layerTable">
					
				<table class="table table-bordered">
					<tbody>
						<tr>
							<th width="15%"><span>*</span>센터구분</th>
							<td width="85%">
								<?=$cate1_select?>
							</td>
						</tr>
						<tr>
							<th width="15%"><span>*</span>치료법구분</th>
							<td width="85%">
								<select name="tc_cate2" id="tc_cate2">
									<option value="">:::선택:::</option>
								</select>
							</td>
						</tr> 
            <tr>
							<th width="15%"><span>*</span>부위구분</th>
							<td width="85%">
								<select name="tc_cate3" id="tc_cate3">
									<option value="">:::선택:::</option>
								</select>
							</td>
						</tr>      
						<tr>
							<th><span>*</span>대표 이미지</th>
							<td>
								<input type="file" name="tc_img" id="tc_img" size="30" class="input_text">
								<?
									if($tc_img != "") {
										echo  "<br>" . $tc_img;
								?>
									<a href="#" onClick="img_del('<?=$tc_img;?>','<?=$_GET['tc_idx']?>')">(X)</a>
								<?}?>
							</td>
						</tr>	
						<tr>
							<th><span>*</span>제목</th>
							<td>
								<input type="text" class="input_text" size="70" name="tc_title" id="tc_title" value="<?=$tc_title?>" />
						  </td>
						</tr>
						<tr>
							<th><span>*</span>요약글</th>
							<td>								
								<textarea name="tc_summary" id="tc_summary" style="width:100%; height:80px; "><?=$tc_summary?></textarea>
							</td>
						</tr>
						<tr>
								<th><span>*</span>내용</th>
								<td>    									
									<textarea name="tc_content" id="tc_content" style="display:none"></textarea>
									<textarea name="ir1" id="ir1" style="width:100%; height:300px; min-width:280px; display:none;"><?=$tc_content1?></textarea>      
								</td>
						</tr>	
					</tbody>
				</table>
				</div>				
				<div class="btnWrap">
				<button id="img_submit" class="btnSearch ">수정</button>
				<button id="img_cancel" class="btnSearch ">취소</button>
				</div>
			</div>
		</div>  
    <input type="hidden" name="img_full_name" id="img_full_name" value="<?=$tc_editor_img?>" />  
  	<input type="hidden" name="upfolder" id="upfolder" value="../../common/treat" />
		</form>
	</div>
</div>
</body>
</html>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.alphanumeric.js"></script>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.validate.js"></script>
<script type="text/javascript" src="<?=$GP -> JS_SMART_PATH?>/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.base64.js"></script>
<script type="text/javascript">

	var oEditors = [];
	nhn.husky.EZCreator.createInIFrame({
		oAppRef: oEditors,
		elPlaceHolder: "ir1",
		sSkinURI: "/bbs/smarteditor/SmartEditor2Skin.html",
		htParams : {
			bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseVerticalResizer : true,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
			bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
			//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
			fOnBeforeUnload : function(){
				//alert("완료!");
			}
		}, //boolean
		fOnAppLoad : function(){
			//예제 코드
			//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);

			//oEditors.getById["ir1"].exec("EVENT_EDITING_AREA_CLICK");
		},
		fCreator: "createSEditor2"
	});
	
	function insertIMG(filename){
		var tname = document.getElementById('img_full_name').value;

		if(tname != "")
		{
			document.getElementById('img_full_name').value = tname + "," + filename;
		}
		else
		{
			document.getElementById('img_full_name').value = filename;
		}
	}
	
	
	
	function img_del(image, idx)
	{
		if(!confirm("삭제하시겠습니까?")) return;

		$.ajax({
			type: "POST",
			url: "./proc/treat_proc.php",
			data: "mode=TREAT_IMGDEL&tc_idx=" + idx + "&tc_img=" + image,
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
		
		$.ajax({
			type: "POST",
			url: "cate1.php",
			data: "tc_cate1=<?=$tc_cate1?>&tc_cate2=<?=$tc_cate2?>",
			dataType: "text",
			success: function(data) {
				$('#tc_cate2').empty();
				$('#tc_cate2').append(data);
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		
		$.ajax({
			type: "POST",
			url: "cate2.php",
			data: "tc_cate2=<?=$tc_cate2?>&tc_cate3=<?=$tc_cate3?>",
			dataType: "text",
			success: function(data) {
				$('#tc_cate3').empty();				
				$('#tc_cate3').append(data);
			},
			error: function(xhr, status, error) { alert(error); }
		});	
		
		$('#tc_cate1').change(function(){
				var val = $(this).val();
				
				$('#tc_cate2').empty();	
				$('#tc_cate2').append("<option value=''>:::선택:::</option>");
				$('#tc_cate3').empty();
				$('#tc_cate3').append("<option value=''>:::선택:::</option>");
				
				if(val != '') {
						$.ajax({
							type: "POST",
							url: "cate1.php",
							data: "tc_cate1=" + val,
							dataType: "text",
							success: function(data) {
								$('#tc_cate2').empty();
								$('#tc_cate2').append(data);
							},
							error: function(xhr, status, error) { alert(error); }
						});	
				}
		});
		
		$('#tc_cate2').change(function(){
				var val = $(this).val();				
			
				$('#tc_cate3').empty();
				$('#tc_cate3').append("<option value=''>:::선택:::</option>");
				
				if(val != '') {
						$.ajax({
							type: "POST",
							url: "cate2.php",
							data: "tc_cate2=" + val,
							dataType: "text",
							success: function(data) {
								$('#tc_cate3').empty();								
								$('#tc_cate3').append(data);
							},
							error: function(xhr, status, error) { alert(error); }
						});	
				}
		});
		
		
		
		$('#img_submit').click(function(){			
			
			if($('#tc_cate option:selected').val() == '') {
				alert("카테고리를 선택하세요");
				return false;
			}
			
			oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
			
			var con	= $('#ir1').val();
			$('#tc_content').val(con);		
	
			if($('#tc_content').val() == '') {
				alert('내용을 입력하세요');
				return false;
			}		
			
			
			$('#base_form').attr('action','./proc/treat_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>