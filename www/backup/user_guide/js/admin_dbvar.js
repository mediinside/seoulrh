if (typeof(ADMIN_JS) == 'undefined') {
    if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');
	
	var ADMIN_JS = true;
	
	function add_data(cnt) {
		switch($('#dbv_type'+ cnt).val()) {
			case 'board' :
				add_postlink(cnt);
				break;
			case 'banner' :
				add_banner(cnt);
				break;
			default :
				alert('관련 정보가 없습니다.');
				break;
		}
		select_cnt = cnt;
	}
	
	function add_data_form(id, variable, type, ref_table, ref_id, type_name, subject) {
		name = name != undefined ? name : '';
		variable = variable != undefined ? variable : '';
		ref_table = ref_table != undefined ? ref_table : '';
		ref_id = ref_id != undefined ? ref_id : '';
		order = id ? cnt : --mcnt;
		
		var _html =	'<dt class="data'+ cnt +'" style="width:60px;">이름</dt>'+
					'<dd class="data'+ cnt +' hor" style="width:120px;"><input type="text" class="ed" size="15" name="dbVar['+ cnt +'][dbv_var]" value="'+ variable +'"/></dd>'+
					'<dt class="data'+ cnt +'" style="width:60px;">리소스</dt>'+
					'<dd class="data'+ cnt +' hor li_comt" style="width:230px;"><span id="dbv_subject'+ cnt +'"></span></dd>'+
	    			'<dt class="data'+ cnt +'" style="width:80px;">설정</dt>'+
					'<dd class="data'+ cnt +'">'+
					'<select id="dbv_type'+ cnt +'" name="dbVar['+ cnt +'][dbv_type]" onchange="chg_type($(this),'+ cnt +');">'+
					'<option value="">선택</option>'+
					'<option value="board">게시판</option>'+
					'<option value="product">상품</option>'+
					'<option value="banner">배너</option>'+
					'</select> '+
					'<input type="hidden" name="dbVar['+ cnt +'][dbv_id]" value="'+ id +'"/>'+
					'<input type="hidden" name="dbVar['+ cnt +'][dbv_ref_table]" value="'+ ref_table +'"/>'+
					'<input type="hidden" name="dbVar['+ cnt +'][dbv_ref_id]" value="'+ ref_id +'"/>'+
					'<input type="hidden" name="dbVar['+ cnt +'][dbv_order]" value="'+ order +'"/>'+
					'<input type="button" id="btn_indata'+ cnt +'" class="btn_simp hide" value=" 입력 " onclick="add_data(\''+ cnt +'\');"/> '+
					'<input type="button" class="btn_simp" value=" 삭제 " onclick="del_data(\''+ cnt +'\');"/>'+
					'</dd>';
		$('#data_list').append(_html);
		$('#dbv_type'+ cnt).val(type);
		
		switch(type) {
			case 'board' :
				$('#btn_indata'+ cnt).show();
				if(type_name && subject) {
					print_data(cnt, type_name, subject);
				}
				break;
			case 'banner' :
				add_banner(cnt);
				$('#banner'+ cnt).val(ref_id);
				break;
			default :
				break;
		}
		cnt++;
	}
	
	function del_data(del_cnt) {
		$('.data'+ del_cnt).remove();
		$('#dbv_subject'+ del_cnt).remove();
	}
	
	function add_banner(cnt) {
		$('#dbv_subject'+ cnt).html('<select id="banner'+ cnt +'">'+ banner_option +'</select>');
		$('#dbv_subject'+ cnt).show();
		$('#banner'+ cnt).change(function(){
			$('input[name="dbVar['+ cnt +'][dbv_ref_id]"]').val($('#banner'+ cnt).val());
		});
	}
	
	function ret_postlink(bo_subject, subject, json_data) {
		var data = $.parseJSON(json_data);
	
		$('input[name="dbVar['+ select_cnt +'][dbv_ref_table]"]').val(data.table);
		$('input[name="dbVar['+ select_cnt +'][dbv_ref_id]"]').val(data.idx);
		
		print_data(select_cnt, bo_subject, subject);

		// 있으면 실행
		try{ postlink_complete(select_cnt); } catch(e) {}
	}
	
	function print_data(cnt, name, subject) {
		$('#dbv_subject'+ cnt).html('<strong>['+ name +']</strong> '+ subject +'');
		$('#dbv_subject'+ cnt).show();
	}
	
	function chg_type(obj,cnt) {
		$('input[name="dbVar['+ cnt +'][dbv_ref_table]"]').val('');
		$('input[name="dbVar['+ cnt +'][dbv_ref_id]"]').val('');

		switch(obj.val()) {
			case 'board' :
				$('#btn_indata'+ cnt).show();
				$('#banner'+ cnt).hide();
				break;
			case 'banner' :
				$('#btn_indata'+ cnt).hide();
				add_banner(cnt)
				break;
			default :
				$('#btn_indata'+ cnt).hide();
				$('#banner'+ cnt).hide();
				$('#dbv_subject'+ cnt).hide();
				break;
		}
	}
}