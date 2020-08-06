<script type="text/javascript" src="<?=$GP -> JS_PATH?>/admin/jquery.base64.js"></script>
<form name="frm_comment" id="frm_comment" action="<?=$get_c_par;?>" method="post">
<?php
  //회원일 경우 비밀번호를 입력할 필요가 없다.
  if(empty($_SESSION['suserid']))
  {
  ?>
<div class="replyText guest">
  <p class="guestInput">
		<label>이름 : <input type="text" name="jbc_name" id="jbc_name" size="18" maxlength="30" value="<?=$check_name;?>" class="txt"></label>  		
		<label>비밀번호 : <input type="password" name="jbc_password" id="jbc_password" size="18" maxlength="50" class="txt"></label>
  </p>
  <?php
  }
  else
  {
echo "<div class=\"replyText\">";
		echo ("<input type=\"hidden\" name=\"jbc_name\" id=\"jbc_name\"  value=\"" . $_SESSION['susername'] . "\">");		
    $password_key=md5($_SESSION['suserid']);
    $tm=explode(" ",microtime());
    $jbc_password=$password_key . $tm[1];
    echo ("<input type=\"hidden\" name=\"jbc_password\" id=\"jbc_password\" value=\"${jbc_password}\">");
  }
  ?>
  <textarea rows="10" cols="20" name="jbc_content" id="jbc_content"></textarea>
</div>
<div class="btnWrap">
  <span class="btnLeft">
    <a href="#;" class="btnM btnConfirm" id="comment_submit">남기기</a>
  </span>
</div>
</form>
<script type="text/javascript">
	$(document).ready(function() {		
		$('#comment_submit').click(function() {
			
			var t = $.base64Encode($('#jbc_content').val()); 
			$('#jbc_content').val(t);	
			
			
			if ( $('#jbc_content').val() == "") {
				alert('내용을 입력하세요.');
				$('#jbc_content').focus();
				return false;
			}
			
			$('#frm_comment').submit();
			return false;
		});		
	});
</script>
