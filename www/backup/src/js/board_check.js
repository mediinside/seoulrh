function check_confirm(str) {
    if ($("input[name='wr_id[]']:checked", document.fboardlist).length < 1) {
    	alert(str + '할 자료를 하나 이상 선택하세요.');
		return false;
    }
    return true;
}

// 선택한 게시물 삭제
function select_delete() {
    var f = document.fboardlist;

    str = '삭제';
    if (!check_confirm(str))
        return;

    if (!confirm('선택한 게시물을 정말 '+str+' 하시겠습니까?\n\n한번 '+str+'한 자료는 복구할 수 없습니다'))
        return;

    f.action = rt_path + '/_board/record_write/delete';
    f.submit();
}

// 선택한 게시물 복사 및 이동
function select_copy(sw) {
    var f = document.fboardlist;
    var str = (sw == 'copy') ? '복사' : '이동';
                       
    if (!check_confirm(str))
        return;

    var sub_win = window.open('', 'mvcp', 'left=50, top=50, width=500, height=550, scrollbars=1');

    f.sw.value = sw;
    f.action = rt_path + '/_board/movecopy';
    f.target = 'mvcp';
	f.submit();
}


$(document).ready(function(){
	if (typeof(BOARD_CHECK_JS) == 'undefined') {
	    if (typeof rt_path == 'undefined')
	        alert('rt_path 변수가 선언되지 않았습니다.');
		
		var BOARD_CHECK_JS = true;
		
		$('#allcheck').click(function() {
			var chk = $("input[name='wr_id[]']", document.fboardlist);
			if (this.checked)
				chk.attr('checked', true);
			else
				chk.attr('checked', false);
		});
	}
});
