if (typeof(ADMIN_JS) == 'undefined') {
    if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');
	
	var ADMIN_JS = true;
	
	function add_data(cnt) {
		switch($('#dbv_type'+ cnt).val()) {
			case 'board' :
				add_boardLatest(cnt);
				break;
			case 'post' :
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
	
	function add_param(name, value) {
		name = name != undefined ? name : '';
		value = value != undefined ? value : '';
		
		var _html =	'<dl><dt style="width:110px;">변수명</dt>'+
			'<dd class="inline" style="width:150px;"><input type="text" class="ed imeDis" size="20" name="ct_parameter[name][]" value="'+ name +'"/></dd>'+
			'<dt style="width:110px;">값</dt>'+
			'<dd><input type="text" class="ed imeDis" size="45" name="ct_parameter[value][]" value="'+ value +'"/> '+
			'<input type="button" class="btn_simp" onclick="remove_child($(this).parent());" value=" - 제거 "/>'+
			'</dd></dl>';
		
		$('#param_list').append(_html);
	}
	
	function add_data_form(id, variable, type, ref_table, ref_id, ext, subject) {
		id = id != undefined ? id : '';
		variable = variable != undefined ? variable : '';
		type = type != undefined ? type : '';
		ref_table = ref_table != undefined ? ref_table : '';
		ref_id = ref_id != undefined ? ref_id : '';
		subject = subject != undefined ? subject : '';
		ext = ext != undefined ? ext : '';
		
		order = id ? cnt : --mcnt;
		
		var _html =	'<dl><dt class="data'+ cnt +'" style="width:60px;">이름</dt>'+
					'<dd class="data'+ cnt +' inline" style="width:120px;"><input type="text" class="ed" size="15" name="dbVar['+ cnt +'][dbv_var]" value="'+ variable +'"/></dd>'+
					'<dt class="data'+ cnt +'" style="width:60px;">리소스</dt>'+
					'<dd class="data'+ cnt +' inline li_comt" style="width:260px;"><span id="dbv_subject'+ cnt +'"></span></dd>'+
	    			'<dt class="data'+ cnt +'" style="width:80px;">설정</dt>'+
					'<dd class="data'+ cnt +'">'+
					'<select id="dbv_type'+ cnt +'" name="dbVar['+ cnt +'][dbv_type]" onchange="chg_type($(this),'+ cnt +');">'+
					'<option value="">선택</option>'+
					'<option value="board">게시판</option>'+
					'<option value="post">게시물</option>'+
					'<option value="product">상품</option>'+
					'<option value="banner">배너</option>'+
					'</select> '+
					'<input type="hidden" name="dbVar['+ cnt +'][dbv_id]" value="'+ id +'"/>'+
					'<input type="hidden" name="dbVar['+ cnt +'][dbv_ref_table]" value="'+ ref_table +'"/>'+
					'<input type="hidden" name="dbVar['+ cnt +'][dbv_ref_id]" value="'+ ref_id +'"/>'+
					'<input type="hidden" name="dbVar['+ cnt +'][dbv_order]" value="'+ order +'"/>'+
					'<input type="button" id="btn_indata'+ cnt +'" class="btn_simp hide" value=" 입력 " onclick="add_data(\''+ cnt +'\');"/> '+
					'<input type="button" class="btn_simp" value=" 삭제 " onclick="remove_child($(this).parent());"/>'+
					'</dd></dl>';
		$('#data_list').append(_html);
		$('#dbv_type'+ cnt).val(type);
		
		switch(type) {
			case 'board' :
				ext = $.parseJSON(ext);
				
				$('#btn_indata'+ cnt).hide();
				add_boardLatest(cnt);
				$('#board'+ cnt).val(ref_table);
				$('input[name="dbVar['+ cnt +'][dbv_ext][qty]"]').val(ext.qty);
				$('input[name="dbVar['+ cnt +'][dbv_ext][len]"]').val(ext.len);
				$('input[name="dbVar['+ cnt +'][dbv_ext][size]"]').val(ext.size);
				
				if(ext.qty) {
					$('#dbv_qty_txt'+ cnt).hide();
				}
				if(ext.len) {
					$('#dbv_len_txt'+ cnt).hide();
				}
				if(ext.size) {
					$('#dbv_size_txt'+ cnt).hide();
				}
				break;
			case 'product' :
				ext = $.parseJSON(ext);
				
				$('#btn_indata'+ cnt).hide();
				add_shopLatest(cnt);
				$('#board'+ cnt).val(ref_table);
				$('input[name="dbVar['+ cnt +'][dbv_ext][qty]"]').val(ext.qty);
				$('input[name="dbVar['+ cnt +'][dbv_ext][len]"]').val(ext.len);
				$('input[name="dbVar['+ cnt +'][dbv_ext][size]"]').val(ext.size);
				
				if(ext.qty) {
					$('#dbv_qty_txt'+ cnt).hide();
				}
				if(ext.len) {
					$('#dbv_len_txt'+ cnt).hide();
				}
				if(ext.size) {
					$('#dbv_size_txt'+ cnt).hide();
				}
				break;
			case 'post' :
				$('#btn_indata'+ cnt).show();
				if(subject) {
					print_data(cnt, subject);
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
	/* remove_child로 대체
	function del_data(del_cnt) {
		$('.data'+ del_cnt).remove();
		$('#dbv_subject'+ del_cnt).remove();
	}
	*/
	function add_boardLatest(cnt) {
		$('#dbv_subject'+ cnt).html('<div class="fL"><select id="board'+ cnt +'" style="width:120px;">'+ board_option +'</select>&nbsp;</div>');
		$('#dbv_subject'+ cnt).append(' <input type="text" class="ed imeDis" id="ext_qty'+ cnt +'" name="dbVar['+ cnt +'][dbv_ext][qty]" size="3" maxlength="2" onfocus="$(\'#dbv_qty_txt'+cnt+'\').hide();" onkeypress="return only_number();" value=""/><div id="dbv_qty_txt'+cnt+'" class="posR fL"><div class="posA dgray" onclick="$(\'#ext_qty'+ cnt +'\').focus();$(this).hide();" style="width:100px;left:3px">개수</div>');
		$('#dbv_subject'+ cnt).append(' <input type="text" class="ed imeDis" id="ext_len'+ cnt +'" name="dbVar['+ cnt +'][dbv_ext][len]" size="3" maxlength="3" onfocus="$(\'#dbv_len_txt'+cnt+'\').hide();" onkeypress="return only_number();" value=""/><div id="dbv_len_txt'+cnt+'" class="posR fL"><div class="posA dgray" onclick="$(\'#ext_len'+ cnt +'\').focus();$(this).hide();" style="width:100px;left:37px">제목</div>');
		$('#dbv_subject'+ cnt).append(' <input type="text" class="ed imeDis" id="ext_size'+ cnt +'" name="dbVar['+ cnt +'][dbv_ext][size]" size="7" maxlength="7" onfocus="$(\'#dbv_size_txt'+cnt+'\').hide();" value=""/><div id="dbv_size_txt'+cnt+'" class="posR fL"><div class="posA dgray" onclick="$(\'#ext_size'+ cnt +'\').focus();$(this).hide();" style="width:100px;left:76px">썸네일</div>');
		$('#dbv_subject'+ cnt).show();
		$('#board'+ cnt).change(function(){
			$('input[name="dbVar['+ cnt +'][dbv_ref_table]"]').val($('#board'+ cnt).val());
		});
	}

	function add_shopLatest(cnt) {
		$('#dbv_subject'+ cnt).html('<div class="fL"><select id="board'+ cnt +'" style="width:120px;">'+ shop_option +'</select>&nbsp;</div>');
		$('#dbv_subject'+ cnt).append(' <input type="text" class="ed imeDis" id="ext_qty'+ cnt +'" name="dbVar['+ cnt +'][dbv_ext][qty]" size="3" maxlength="2" onfocus="$(\'#dbv_qty_txt'+cnt+'\').hide();" onkeypress="return only_number();" value=""/><div id="dbv_qty_txt'+cnt+'" class="posR fL"><div class="posA dgray" onclick="$(\'#ext_qty'+ cnt +'\').focus();$(this).hide();" style="width:100px;left:3px">개수</div>');
		$('#dbv_subject'+ cnt).append(' <input type="text" class="ed imeDis" id="ext_len'+ cnt +'" name="dbVar['+ cnt +'][dbv_ext][len]" size="3" maxlength="3" onfocus="$(\'#dbv_len_txt'+cnt+'\').hide();" onkeypress="return only_number();" value=""/><div id="dbv_len_txt'+cnt+'" class="posR fL"><div class="posA dgray" onclick="$(\'#ext_len'+ cnt +'\').focus();$(this).hide();" style="width:100px;left:37px">이름</div>');
		$('#dbv_subject'+ cnt).append(' <input type="text" class="ed imeDis" id="ext_size'+ cnt +'" name="dbVar['+ cnt +'][dbv_ext][size]" size="7" maxlength="7" onfocus="$(\'#dbv_size_txt'+cnt+'\').hide();" value=""/><div id="dbv_size_txt'+cnt+'" class="posR fL"><div class="posA dgray" onclick="$(\'#ext_size'+ cnt +'\').focus();$(this).hide();" style="width:100px;left:76px">썸네일</div>');
		$('#dbv_subject'+ cnt).show();
		$('#board'+ cnt).change(function(){
			$('input[name="dbVar['+ cnt +'][dbv_ref_table]"]').val($('#board'+ cnt).val());
		});
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
		
		print_data(select_cnt, '<strong>['+ bo_subject +']</strong> '+ subject);

		// 있으면 실행
		try{ postlink_complete(select_cnt); } catch(e) {}
	}
	
	function print_data(cnt, subject) {
		$('#dbv_subject'+ cnt).html(subject);
		$('#dbv_subject'+ cnt).show();
	}
	
	function chg_type(obj,cnt) {
		$('input[name="dbVar['+ cnt +'][dbv_ref_table]"]').val('');
		$('input[name="dbVar['+ cnt +'][dbv_ref_id]"]').val('');

		switch(obj.val()) {
			case 'board' :
				$('#btn_indata'+ cnt).hide();
				add_boardLatest(cnt);
				break;
			case 'post' :
				$('#dbv_subject'+ cnt).html('');
				$('#btn_indata'+ cnt).show();
				$('#banner'+ cnt).hide();
				break;
			case 'banner' :
				$('#btn_indata'+ cnt).hide();
				add_banner(cnt)
				break;
			case 'product' :
				$('#btn_indata'+ cnt).hide();
				add_shopLatest(cnt);
				break;
			default :
				break;
		}
	}
}
