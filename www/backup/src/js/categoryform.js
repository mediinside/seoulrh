if (typeof(CATEGORYFORM_JS) == 'undefined') {
    if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');

	var CATEGORYFORM_JS = true;
	
	$(document).ready(function() {
	    var type = document.getElementById('type').value;
	    var img_path = rt_path + '/src/imgs/adm/category';
		var update_url = rt_path + '/check/category/update';

		$('#loading').ajaxStart(function() { $(this).show(); })
					 .ajaxStop (function() { $(this).hide(); });

		// 트리 + , -
		$('#category button').css('cursor','pointer').click(function() {
			var par = $(this).parent();
			switch (par.attr('class')) {
				case 'k_tree_off' :
					par.attr('class', 'k_tree_on');
				break;
				case 'k_tree_last k_tree_off' :
					par.attr('class', 'k_tree_last k_tree_on');
				break;
				case 'k_tree_on' :
					par.attr('class', 'k_tree_off');
				break;
				case 'k_tree_last k_tree_on' :
					par.attr('class', 'k_tree_last k_tree_off');
				break;
			}
		});

		// 분류 클릭
		$('#category span').css('cursor','pointer').click(function() {
			$('#category span').removeAttr('style');
			$(this).css({'font-weight':'bold','color':'red'});

			$('#codeVal').val($(this).parent().attr('id'));
			$('#nameVal').text($(this).text());
			$('#status').css('display', 'block');
			$('#add').css('cursor','pointer').click();
			return false;
		});

		// 상태창 닫기
		$('#sClose').css('cursor','pointer').click(function() {
			$('#category span').removeAttr('style');
			$('#status').css('display', 'none');
		});

		// 상태창 아이콘
		$('#add').css('cursor','pointer').click(function() {
			$('#w').val('');

			$(this).attr('src', img_path + '/btn_add_o.gif');
			$('#mod').attr('src', img_path + '/btn_init.gif');
			$('#del').attr('src', img_path + '/btn_del.gif');

			$('#subVal').val('').focus();
		});
		$('#mod').css('cursor','pointer').click(function() {
			$('#w').val('u');

			$(this).attr('src', img_path + '/btn_init_o.gif');
			$('#add').attr('src', img_path + '/btn_add.gif');
			$('#del').attr('src', img_path + '/btn_del.gif');

			$('#subVal').val($.trim($('#nameVal').text())).focus();
		});
		$('#del').css('cursor','pointer').click(function() {
			$('#w').val('d');

			$(this).attr('src', img_path + '/btn_del_o.gif');
			$('#add').attr('src', img_path + '/btn_add.gif');
			$('#mod').attr('src', img_path + '/btn_init.gif');

			if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
				var codeVal = $('#codeVal').val();
				code = codeVal.split('_');
				code = code[1];
				$.post(update_url,
					{ type:type, w:'d', ca_code:code },
					function(data) {
						if (data == 'TRUE') {
							if ($('#'+codeVal).attr('class').indexOf('k_tree_last') != -1) {
								if ($('#'+codeVal).parent('ul').children('li').length == 1) {
									$('#'+codeVal).parent().parent().children('button').remove();
									$('#'+codeVal).parent().remove();
								}
								else { 
									$('#'+codeVal).prev().addClass('k_tree_last');
									$('#'+codeVal).remove();
								}
							}
							else {
								$('#'+codeVal).remove();
							}
							$('#status').css('display', 'none');
						}
					}
				);
			}
		});

		// 최상위 분류 추가
		$('#topSubmit').css('cursor','pointer').click(function() {
			var topVal = $('#topVal').val();
			if (!topVal) {
				alert('분류 이름을 입력하십시오.');
				$('#topVal').focus();
				return false;
			}

			if (!$('#category > ul').is('ul'))
				$('#category').append("<ul></ul>");

			var lastId = $('#category li:last').attr('id');
			var code = (typeof lastId == 'undefined') ? 'C_0' :lastId;

			code = code.split('_');
			code = parseInt(code[1]) + parseInt(1);
			
			$.post(update_url,
				{ type:type, w:'', ca_code:code, ca_name:topVal },
				function(data) {
					if (data == 'TRUE') {
						$('#category li:last').attr('class', 'k_tree_off');
						$('#category ul:first').append("<li id='C_"+code+"' class='k_tree_last k_tree_off'><span class='k_tree_label'>"+topVal+"</span></li>");
					}
					else
						alert('Error : ' + data);

					$('#topVal').val('').focus();
				}
			);
		});

		// 분류 추가 / 수정
		$('#valSubmit').css('cursor','pointer').click(function() {
			var subVal = $('#subVal').val();
			if (!subVal) {
				alert('분류 이름을 입력하십시오.');
				$('#subVal').focus();
				return false;
			}

			var w = $('#w').val();
			var codeVal = $('#codeVal').val();
			var code;

			switch (w) {
				case '' :
					var lastCode;
					if ($('#'+codeVal+' > ul').is('ul'))
						lastCode = $('#'+codeVal+' > ul > li:last-child').attr('id');
					else
						lastCode = false;

					if (lastCode == false) {
						code = codeVal.split('-');
						if (typeof code[1] == 'undefined')
							code[1] = '';

						code = code[0] + '-' + code[1] + '001';
						if (code.length >= 257) { // varchar(255)
							alert('적당히 추가 해라잉?');
							return false;
						}
					}
					else {
						code = lastCode.split('-');
						
						var codeLen = code[1].length;
						var codePar = code[1].substring(0, codeLen - 3);
						var codeNum = code[1].substring(codeLen, codeLen - 3);
						
						// parseInt('08',10);
						codeNum = parseInt(codeNum, 10) + parseInt(1);
						codeLen = String(codeNum).length;

						if (codeLen < 4 && codeNum != 999) {
							for (i=3-codeLen; i--;) codeNum = '0' + codeNum;
							code = code[0] + '-' + codePar + codeNum;
						}
						else {
							alert('분류 허용개수를 초과하였습니다. (최대 998개)\n\n중간 분류 삭제시 현재 총개수는 변하지 않습니다.');
							return false;
						}
					}
				break;
				case 'u' :
					code = codeVal;
				break;
				default :
					alert("옵션선택이 올바르지 않습니다.");
					return false;
				break;
			}

			code = code.split('_');
			code = code[1];
			$.post(update_url,
				{ type:type, w:w, ca_code:code, ca_name:subVal },
				function(data) {
					if (data == 'TRUE') {
						switch (w) {
							case '' :
								var cLen = code.length;
								if (code.substring(cLen, cLen - 3) == '001') {
									var liCls = $('#'+codeVal).attr('class').replace('off', 'on');
									$('#'+codeVal).attr('class', liCls).prepend("<button type='button'>+</button>");
									$('#'+codeVal).append("<ul><li id='C_"+code+"' class='k_tree_last k_tree_off'><span class='k_tree_label'>"+subVal+"</span></li></ul>");
								}
								else {
									$('#'+codeVal+' > ul > li:last-child').removeClass('k_tree_last');
									$('#'+codeVal+' > ul').append("<li id='C_"+code+"' class='k_tree_last k_tree_off'><span class='k_tree_label'>"+subVal+"</span></li>");
								}
							break;
							case 'u' :
								$('#'+codeVal+' > span').text(subVal);
								$('#nameVal').text(subVal);
							break;
						}
					}
					else
						alert('Error : ' + data);

					$('#subVal').val('').focus();
				}
			);
		});
	});
}