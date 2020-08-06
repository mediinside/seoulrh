<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	
	$cate1_select = $C_Func -> makeSelect_Normal('tc_cate1', $GP -> CATE1, $tc_cate1, '', '::선택::');
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>치료법 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="TREAT_REG" />
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
								<th><span>*</span>치료법구분</th>
								<td>
									<select name="tc_cate2" id="tc_cate2">
										<option value="">:::선택:::</option>
									</select>
								</td>
							</tr> 
							<tr>
								<th><span>*</span>부위구분</th>
								<td>
									<select name="tc_cate3" id="tc_cate3">
										<option value="">:::선택:::</option>
									</select>
								</td>
							</tr>  					          
							<tr>
								<th><span>*</span>대표 이미지</th>
								<td>
									<input type="file" name="tc_img" id="tc_img" size="30" class="input_text">
								</td>
							</tr>	
							<tr>
								<th><span>*</span>제목</th>
								<td>
									<input type="text" class="input_text" size="70" name="tc_title" id="tc_title" />
								</td>
							</tr>
							<tr>
								<th><span>*</span>요약글</th>
								<td>
									<input type="text" class="input_text" size="70" name="tc_summary" id="tc_summary" />
								</td>
							</tr>
							<tr>
								<th><span>*</span>내용</th>
								<td>                          
									<textarea name="tc_content" id="tc_content" style="display:none"></textarea>
									<textarea name="ir1" id="ir1" style="width:100%; height:300px; min-width:280px; display:none;"></textarea>      
								</td>
							</tr>						
						</tbody>
					</table>
				</div>				
				<div class="btnWrap">
					<span class="btnRight">
						<button id="img_submit" class="btnSearch ">등록</button>
						<button id="img_cancel" class="btnSearch ">취소</button>
					</span>
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

	$(document).ready(function(){	
														 
		$('#img_cancel').click(function(){
				parent.modalclose();				
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