if (typeof co_guest != 'undefined') {
	if (co_guest) {
		var btn_submit = document.getElementById('btn_submit');
		btn_submit.style.display = 'none';
		btn_submit.disabled = true;

		$('#comment_box').on('keyup', '#wr_key', function() {
			var btn_submit = document.getElementById('btn_submit');
			var kcaptcha = document.getElementById('kcaptcha');
			
			if (hex_md5(this.value)	== md5_norobot_key) {
				kcaptcha.style.display = 'none';
				btn_submit.style.display = 'block';
				btn_submit.disabled = false;
			}
			else {
				kcaptcha.style.display = 'block';
				btn_submit.style.display = 'none';
				btn_submit.disabled = true;
			}
		});
	}
	
	var	save_before	= '';
	var	save_html =	document.getElementById('comment_box').innerHTML;
	function comment_box(comment_id, work) {
		var	el_id;
		// 코멘트 아이디가 넘어오면 답변, 수정
		if (comment_id)
			el_id =	(work == 'c') ? 'reply_' + comment_id : 'edit_'	+ comment_id;
		else
			el_id =	'comment_box';
	
		if (save_before	!= el_id) {
			if (save_before) {
				document.getElementById(save_before).style.display = 'none';
                document.getElementById(save_before).innerHTML = '';
			}

			document.getElementById(el_id).style.display = 'block';
			document.getElementById(el_id).innerHTML = save_html;
			// 코멘트 수정
			if (work ==	'cu') {
				document.getElementById('wr_content').value	= document.getElementById('save_comment_' +	comment_id).value;
                
				if (document.getElementById('secret_comment_'+comment_id).value)
					document.getElementById('wr_secret').checked = true;
				else
					document.getElementById('wr_secret').checked = false;
			}
			
			document.fviewcomment.comment_id.value	= comment_id;
			document.fviewcomment.w.value = work;
			
			save_before	= el_id;
			
			document.getElementById('cw_place').style.display = (comment_id) ? 'block' : 'none';
			document.getElementById('comment_reply').style.display = (comment_id && work == 'c') ? 'block' : 'none';
		}
		
		if (co_guest && work == 'c')
			$('#kcaptcha').click();
		
		var wr_parent = $('#wr_content').parent();
			wr_parent.width(wr_parent.parent().width()-90);
	}
	
	comment_box('',	'c'); // 코멘트폼 출력
}