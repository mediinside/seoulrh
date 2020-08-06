if (typeof(ADMIN_JS) == 'undefined') {
    if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');
	
	var ADMIN_JS = true;
	
	function allcheck(elm) {
		var chk = $("input[name='chk[]']", document.form);
		
		if (elm.checked)
			chk.prop({ checked: true });
		else
			chk.prop({ checked: false });
	}

	function slt_check(f, act) {
		var str = '';
		if (act.indexOf('update') != -1)
			str = '수정'; 
		else if (act.indexOf('delete') != -1) 
			str = '삭제';
		else
			return;

		if ($("input[name='chk[]']:checked", f).length < 1) {
	    	alert(str + "할 자료를 하나 이상 선택하세요.");
			return;
	    }
	
	    if (str == '삭제' && !confirm('선택한 자료를 정말 삭제 하시겠습니까?'))
			return;

		f.action = act;
		f.submit();
	}
	
	// 검색 리다이렉트
	function doSearch(f, url, get) {
		var stx = f.stx.value.replace(/(^\s*)|(\s*$)/g,'');
		if (f.stx.type == "text" && stx.length < 2) {
			alert('2글자 이상으로 검색하십시오.');
			f.stx.focus();
			return false;
		}
		if(get == undefined) get = '';
		location.href = rt_path + '/' + rt_admin + '/' + url + '/page/1/sfl/' + f.sfl.value + '/stx/' + sEncode(stx) + get;
		return false;
	}
	
	// 탭 버튼 객체 활성
	function show_table(obj, id) {
		id = id != undefined ? id : '';
		
		$('#tab_content .tab_layer').hide();
		$('#tab_content #'+ id).show();
		$('#tab_buttons [type="button"]').removeClass('active');
		$(obj).addClass('active');
	}
	
    // 컨텐츠 복사
	function content_copy(id) {
		layerWin('/adm/content/copy/'+ id, 'contentCopy', 380, 230);
	}
	
    // 회원 아이디 검색 창
    function win_id(frm_name, frm_input, return_fld) {
    	CtrlWinDlg.setDlgWidth(250).setDlgHeight(400).setScrolling('yes').setCloseBtn(true).setLoad("/useful/schMember/qry/"+ frm_name +"/"+ frm_input +"/"+ return_fld).drawWinDlg("ACCESS_WIN");
    }
	
    // 신청 정보 보기
	function apply_view(cid, id) {
		layerWin('/adm/apply/view/cid/'+ cid +'/id/'+ id, 'applyView', 800, 700, 'auto');
	}
}
