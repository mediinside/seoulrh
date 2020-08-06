<?php
	include_once("../../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>팝업 등록</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="POPUP_REG" />
        <input type="hidden" name="pop_lang" value="<?=$lang?>" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">				
				<div class="layerTable">
				<table class="table table-bordered">
					<tbody>
						<tr>
							<th width="15%"><span>*</span>타이틀</th>
							<td width="85%">
								<input type="text" class="input_text" size="25" name="pop_title" id="pop_title" value="<?=$pop_title?>" />
							</td>
						</tr>
						<tr>
							<th><span>*</span>형식</th>
							<td>
								<input type="radio" name="pop_type" value="T" checked="checked"  />TEXT
                <input type="radio" name="pop_type" value="I"  />Image
							</td>
						</tr>
						<tr>
							<th><span>*</span>사용여부</th>
							<td>
								<input type="radio" name="pop_use" value="Y" checked="checked"  />사용
								<input type="radio" name="pop_use" value="N" />미사용
							</td>
						</tr>
						<tr>
							<th><span>*</span>게시기간</th>
							<td>
								<input type="text" class="input_text" size="15" name="pop_start_date" id="pop_start_date" />
								 ~ 
								<input type="text" class="input_text" size="15" name="pop_end_date" id="pop_end_date" />
							</td>
						</tr>
						<tr>
								<th><span>*</span>크기</th>
								<td>
										가로 <input type="text" class="input_text" size="15" name="pop_width" id="pop_width" />px X 
										세로 <input type="text" class="input_text" size="15" name="pop_height" id="pop_height" />px
								</td>
						</tr>
						<tr>
								<th><span>*</span>위치</th>
								<td>
										LEFT <input type="text" class="input_text" size="15" name="pop_x_position" id="pop_x_position" />px X 
										TOP <input type="text" class="input_text" size="15" name="pop_y_position" id="pop_y_position" />px
								</td>
						</tr>
						<tr>
								<th><span>*</span>스크롤사용여부</th>
								<td>
										<input type="radio" name="pop_scroll" value="Y" class="input_text" />사용
										<input type="radio" name="pop_scroll" value="N" class="input_text" checked="checked" />미사용
								</td>
						</tr>
						<tr id="TR1">
								<th><span>*</span>내용</th>
								<td>                          
									<textarea name="pop_contents" id="pop_contents" style="display:none"></textarea>
									<textarea name="ir1" id="ir1" style="width:100%; height:300px; min-width:280px; display:none;"></textarea>      
								</td>
						</tr>
						<tr id="TR2" style="display:none;">
								<th><span>*</span>이미지</th>
								<td>
									<input type="file" name="pop_file" id="pop_file" size="30" class="input_text">
								</td>
						</tr>
						<tr id="TR3" style="display:none;">
								<th><span>*</span>클릭시 이동할 페이지</th>
								<td>
									<input name="pop_link_url" id="pop_link_url" type="text" class="input_text" value="http://" size="50" maxlength="200">
								</td>
						</tr>  
						<tr>
								<th><span>*</span>닫기설정</th>
								<td>
									팝업창을
									<select name="pop_cookie" id="pop_cookie">
										<option value="1">하루동안</option>
										<option value="7">일주일동안</option>
										<option value="30">한달동안</option>
										<option value="90">영원히</option>
									</select>나타나지 않도록 설정합니다.
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
		<input type="hidden" name="img_full_name" id="img_full_name" value="<?=$editor_img_code?>" />  
  		<input type="hidden" name="upfolder" id="upfolder" value="../../popup/upfile" />
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
														 
		$('#pop_width').numeric();	
		$('#pop_height').numeric();	
		$('#pop_x_position').numeric();	
		$('#pop_y_position').numeric();
		
		$(":input:radio[name=pop_type]").click(function(){
				var val = $(this).val();
				
				if(val == "T") {
					$('#TR1').show();
					$('#TR2').hide();
					$('#TR3').hide();
				}else{
					$('#TR1').hide();
					$('#TR2').show();
					$('#TR3').show();
				}				
		});
		
		$('#img_submit').click(function(){
			
			if($('#pop_title').val() == '') {
				alert("팝업타이틀을 입력하세요");
				$('#pop_title').focus();
				return false;
			}
			
			var t = $(":input:radio[name=pop_type]:checked").val();
			
			if(t == "T"){
				oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);
				
				var con	= $('#ir1').val();
				$('#pop_contents').val(con);		
		
				if($('#pop_contents').val() == '') {
					alert('내용을 입력하세요');
					return false;
				}					
			}
			
			
			if($('#pop_start_date').val() == '') {
				alert("게시기간을 입력하세요");
				$('#pop_start_date').focus();
				return false;
			}
			
			if($('#pop_end_date').val() == '') {
				alert("게시기간을 입력하세요");
				$('#pop_end_date').focus();
				return false;
			}
			
			if(t == "I" && $('#pop_link_url').val() == '') {
				alert("클릭시 이동할 페이지를 입력하세요");
				$('#pop_link_url').focus();
				return false;
			}
			
			if(t == "I" && $('#pop_file').val() == '') {
				alert("노출 이미지를 선택하세요");
				$('#pop_file').focus();
				return false;
			}
			
			$('#base_form').attr('action','./proc/popup_proc.php');
			$('#base_form').submit();
			return false;
		});					
	
	});
</script>
<script type="text/javascript">
	$(function() {
		callDatePick('pop_start_date');
		callDatePick('pop_end_date');
	});

	function callDatePick (id) {	
		var dates = $( "#" + id ).datepicker({
			prevText: '이전 달',
			nextText: '다음 달',
			monthNames: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			monthNamesShort: ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'],
			dayNamesShort: ['일','월','화','수','목','금','토'],
			dayNamesMin: ['일','월','화','수','목','금','토'],
			dateFormat: 'yy-mm-dd',
			showMonthAfterYear: true,
			yearSuffix: '년'	  
		});
	}
</script>