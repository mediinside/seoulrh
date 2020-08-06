if (typeof(BOARD_JS) == 'undefined') {
    if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');
	
	var BOARD_JS = true;

	// 검색 리다이렉트
	function doSearch(f) {
		var stx = f.stx.value.replace(/(^\s*)|(\s*$)/g,'');
		if (stx.length < 2) {
			alert('2글자 이상으로 검색하십시오.');
			f.stx.focus();
			return false;
		}
		
		var sca = (f.sca.value) ? '/sca/' + f.sca.value : '';
		location.href = rt_path +'/board/'+ f.bid.value +'/lists'+ sca + f.qstr.value +'/sfl/'+ f.sfl.value +'/stx/'+ sEncode(stx);
		return false;
	}
	
	function resize(obj, w, h) {
		var imgsrc = obj.src;
		imgsrc = imgsrc.replace(/thumb\/[0-9]+px_/g, '');
		
		var size = "이미지 사이즈 : "+w+" x "+h;
		var popup = window.open('', 'image_window', 'width='+w+', height='+h+', top=20,left=20,scrollbars=yes,status=no,resizable=no');
		popup.document.open(); 
		popup.document.write("<html><head><meta http-equiv='content-type' content='text/html; charset="+rt_charset+"'>")
		popup.document.write("<style>*{margin:0;padding:0;} body{width:"+w+"px;height:"+h+"px;overflow:auto;}</style>");
		popup.document.write("<title>"+size+"</title></head><body oncontextmenu='return false'>")
		popup.document.write("<img src=\""+imgsrc+"\" onclick='self.close()' style='cursor:pointer;'>")
		popup.document.write("</body></html>");
		popup.document.close();
	}
	
	function ret_postlink(bo_subject, subject, json_data) {
		var data = $.parseJSON(json_data);
		if(data.table == $("#fwrite input[name='bo_table']").val() && data.idx == $("#fwrite input[name='wr_id']").val()) {
			alert('이 글을 관련글로 입력하실 수 없습니다.');
		}
		else {
			var _HTML_ = '<li><strong>['+ bo_subject +']</strong> '+ subject +
			'<input type="hidden" name="wr_postlink[]" value=\''+ json_data +'\'/>' +	
			'<input type="button" class="btn_simp" onclick="child_del($(this));" value=" - 제거 "/></li>';
		
			$('#postlink_list').append(_HTML_);	
		}
	}
	
	function boExec(url, mode, current_lev, permission_lev, is_admin) {
		var mode_txt = '';
		
		switch(mode) {
			case 'list' :
				mode_txt = '목록보기';
				break;
			case 'write' :
				mode_txt = '글쓰기';
				break;
			case 'reply' :
				mode_txt = '답변';
				break;
			case 'modify' :
				mode_txt = '수정';
				break;
			case 'delete' :
				mode_txt = '삭제';
				break;
			case 'comment' :
				mode_txt = '댓글';
				break;
			default :
				mode_txt = '읽기';
				break;
		}
		
		if(!is_admin && current_lev < permission_lev) {
			alert(mode_txt +' 권한이 없습니다.');
		}
		else {
			location.href = url;
		}
	}
}
