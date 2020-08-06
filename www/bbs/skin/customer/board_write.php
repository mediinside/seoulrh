<script type="text/javascript" src="<?=$GP -> JS_SMART_PATH?>/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="/resource/js/jquery.base64.js"></script>
<!-- main section 01 start -->
			<section class="contents">
				<div class="sub-visual-wrap">
					<img src="/resource/images/notice-bnnr1.png" alt="">
				</div>
				<!-- //end .sub-visual-wrap -->
			</section>
			<!-- main section 01 end -->
			<!-- main section 02 start -->
			<section class="contents sub-contents">
				<div class="board-list wd-1200" style="padding-top:0;">
					<h3 class="sub-tit">
						고객의 소리
						<span>※ 필수 입력 정보는 빠짐없이 입력해 주세요.</span>
					</h3>
					<form name="frm_Board" id="frm_Board" action="<?=$get_par;?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="jb_title" id="jb_title" value="고객의소리" />
						<table class="form_table">
							<colgroup>
								<col style="width:160px">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th scope="row" class="label important">분류</th>
									<td>
										<div class="cate_box">
											<select class="input_text" name="jb_type" id="jb_type">
												<option value="A">칭찬</option>
												<option value="B">민원</option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row" class="label important">이름</th>
									<td>
										<input type="text" class="input_text" id="jb_name" name="jb_name">
									</td>
								</tr>
								<tr>
									<th scope="row" class="label important">이메일</th>
									<td>
										<div class="email_box">
											<input type="text" class="input_text" id="jb_email1" name="jb_email1">
											<p class="unit">@</p>
											<input type="text" class="input_text" id="jb_email2" name="jb_email2" >
											<select class="input_text" id="s_jb_email2" name="s_jb_email2">
												<option value="">직접 입력</option>
												<option value="naver.com">naver.com</option>
												<option value="daum.net">daum.net</option>
												<option value="nate.com">nate.com</option>
												<option value="hanmail.net">hanmail.net</option>
												<option value="hotmail.com">hotmail.com</option>
												<option value="yahoo.co.kr">yahoo.co.kr</option>
												<option value="gmail.com">gmail.com</option>
											</select>
										</div>
									</td>
								</tr>
								<tr>
									<th scope="row" class="label important vt-top">문의사항</th>
									<td>
                                        <textarea name="jb_content" id="jb_content" style="display:none" rows="20"></textarea>
                                        <textarea name="ir1" id="ir1" style="width:100%; height:300px; min-width:280px; display:none;"></textarea>
									</td>
								</tr>
							</tbody>
						</table>
					</form>
					<div class="btn-box right flex m-top">
						<label class="check_box">
							<input type="checkbox" class="check" id="" name="agree">
							<a href="/agree/agree1.html" target="_blank" class="link">개인정보정책</a>에 동의합니다.
						</label>
						<label class="check_box">
							<input type="checkbox" class="check" id="" name="conditions">
							<a href="/agree/agree2.html" target="_blank" class="link">이용약관</a>에 동의합니다.
						</label>
						<a class="btn-green" href="#" id="img_submit" >등록</a>
					</div>
				</div>
			</section>

  <!-- <div class="btnWrap">
    <a href="#;" id="img_submit" class="btnM btnConfirm">글쓰기</a>
    <a href="javascript:history.go(-1);" class="btnM btnCancel">취소</a>
  </div> -->
  <input type="hidden" name="jb_bruse_check" value="Y" checked>
  <input type="hidden" name="img_full_name" id="img_full_name" />
  <input type="hidden" name="upfolder" id="upfolder" value="jb_<?=$jb_code?>" />
</form>
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

    $(document).ready(function () {
        //이메일 domain선택 selectBox
        $('#s_jb_email2').change(function(){
            $("#s_jb_email2 option:selected").each(function () {
                //직접입력일 경우
                if($(this).val()== 'self'){
                            $("#jb_email2").val('');                             //textBox값 초기화
                            $("#jb_email2").attr("disabled",false);    //textBox 활성화
                }
                else if($(this).val()== 'select'){
                            //선택(초기값)일 경우
                            $("#jb_email2 ").val('');                          //textBox값 초기화
                       //     $("#jb_email2 ").attr("disabled",false); //textBox 활성화
                }
                else{
                        $("#jb_email2").val($(this).text());      //selectBox에서 선택한 값을 textBox에 입력
                       // $("#jb_email2").attr("disabled",true); //textBox 비활성화
                }
            });
         });
    });


	$('#img_submit').click(function(){

        	//	alert("aa");
		if($('#jb_name').val() == '')	{
			alert('이름을 입력하세요');
			$('#jb_name').focus();
			return false;
		}
		/*
		if($('#jb_password').val() == '')	{
			alert('비밀번호를 입력하세요');
			$('#jb_password').focus();
			return false;
		}*/

		if($('#jb_email1').val() == '' || $('#jb_email2').val() == '')	{
			alert('이메일을 정확히 입력하세요');
			$('#jb_email1').focus();
			return false;
		}

		oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);

		var con	= $('#ir1').val();


		$('#jb_content').val(con);


		if($('#jb_content').val() == '' || $('#jb_content').val() == '<br> ')
		{
			alert('내용을 입력하세요');
			return false;
		}

		var t = $.base64Encode($('#ir1').val());
		$('#jb_content').val(t);


		if($('#zsfCode').val() == '')	{
			alert('자동방지 입력키를 입력하세요');
			$('#zsfCode').focus();
			return false;
		}

        if($('input:checkbox[name="agree"]:checked').val() == undefined ) {
			alert("개인정보 취급 방침에 동의해 주세요.");
			return false;
		}

        if($('input:checkbox[name="conditions"]:checked').val() == undefined ) {
			alert("이용약관에 동의해 주세요.");
			return false;
		}

		$('#frm_Board').submit();
		return false;

	});


	function CheckEmail(str)
	{
		 var filter=/^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i
		 if (filter.test(str)) { return true; }
		 else { return false; }
	}

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
</script>
