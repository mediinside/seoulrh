if (typeof(COMMON_JS) == 'undefined') {
	if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');
	
    var COMMON_JS = true;

    function win_open(url, name, option) {
    	if(url.match(/^\/+/g, '') == null) {
    		url = rt_path + '/' + url
    	}
    	
        var popup = window.open(url, name, option);
        popup.focus();
    }

    // 쪽지 창
    function win_memo(url) {
		if (!url)
			url = "member/memo/lists";
        win_open(url, "winMemo", "left=50,top=50,width=616,height=460,scrollbars=1");
    }
    
	// 자기소개 창
    function win_profile(mb_id) {
        win_open("member/profile/qry/"+mb_id, 'winProfile', 'left=50,top=50,width=400,height=500,scrollbars=1');
    }

    // 우편번호 창
    function win_zip(frm_name, frm_zip1, frm_zip2, frm_addr1, frm_addr2) {
        url = rt_path+"/useful/zip/qry/"+frm_name+"/"+frm_zip1+"/"+frm_zip2+"/"+frm_addr1+"/"+frm_addr2;
        win_open(url, "winZip", "left=50,top=50,width=463,height=400,scrollbars=1");
    }

	// POST 전송, 결과값 리턴
    function post_s(href, parm, del) {
        if (!del || confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) { 
			$.post(rt_path + '/' + href, parm, function(req) {
                document.write(req);
			});
		}
    }
    
    // POST 이동
    function post_goto(url, parm, target, confirm_msg) {
        var f = document.createElement('form');
        
        var basePath, objs, value;
        for (var key in parm) {
            value = parm[key];
            objs = document.createElement('input');
            objs.setAttribute('type', 'hidden');
            objs.setAttribute('name', key);
            objs.setAttribute('value', value);
            f.appendChild(objs);
        }
        
        if (target)
            f.setAttribute('target', target);

		if (url.match(/^http:\/\//) || url.match(/^https:\/\//))
			basePath = '';
		else
			basePath = rt_path + '/'
		
		if(confirm_msg == undefined || confirm(confirm_msg)) {
			f.setAttribute('method', 'post');
	        f.setAttribute('action', basePath + url);
	        document.body.appendChild(f);
	        f.submit();
		}
    }
	
    // POST 창
    function post_win(name, url, parm, opt) {
        var temp_win = window.open('', name, opt);
            post_goto(url, parm, name);
    }

	// 일반 삭제 검사 확인
    function del(href) {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) 
            document.location.href = rt_path + '/' + href;
    }

	// 플래시에 변수 추가 fh
	function flash(src, ids, width, height, wmode, fh) { 
        var wh = ""; 
        if (parseInt(width) && parseInt(height)) 
            wh = " width='"+width+"' height='"+height+"' "; 
        return document.write("<object classid='clsid:d27cdb6e-ae6d-11cf-96b8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0' "+wh+" id="+ids+"><param name=wmode value="+wmode+"><param name=movie value="+src+"><param name=quality value=high><param name=flashvars value="+fh+"><embed src="+src+" quality=high wmode="+wmode+" flashvars="+fh+" type='application/x-shockwave-flash' pluginspage='http://www.macromedia.com/shockwave/download/index.cgi?p1_prod_version=shockwaveflash' "+wh+"></embed></object>"); 
    }

	// 동영상 파일 (html 임베드)
    function embed(src, ids, width, height, autostart) {
        var wh = "";
        if (parseInt(width) && parseInt(height)) 
            wh = " width='"+width+"' height='"+height+"' ";
        if (!autostart) autostart = false;
        return document.write("<embed src='"+src+"' "+wh+" autostart='"+autostart+"'></embed>");
    }
    
	// 아이프레임 높이 자동조절
	function reSize(obj) {
		try {
			var objBody = frames[obj].document.body;
			var objFrame = document.getElementById(obj);
			ifrmHeight = objBody.scrollHeight + (objBody.offsetHeight - objBody.clientHeight);
			objFrame.style.height = ifrmHeight;
		}
		catch(e) {}
	}

	function sEncode(val) {
		return encodeURIComponent(val).replace(/%/g, '.');
	}
    
    // script 에서 js 파일 로드
    function importScript(FILES) {
        var _importScript = function(filename) { 
        	if (filename) {
        		document.write('<script type="text/javascript" src="'+rt_path+'/js/'+filename+'.js"></s'+'cript>');
            }
        };
        
        for (var i=0; i<FILES.length; i++) {
        	_importScript(FILES[i]);
        }
    }
    
    // jQuery textarea
    function txresize(tx, type, size) {
        var tx = $('#'+tx);
        
        if (type == 1 && tx.height() > size)
            tx.animate({'height':'-='+size+'px'}, 'fast');
        else if (type == 2)
            tx.animate({'height':size}, 'fast');
        else if (type == 3)
            tx.animate({'height':'+='+size+'px'}, 'fast');
    }

	// 팝업 닫기
	function popup_close(id, onday) {
		if (onday) {
			var today = new Date();
			today.setTime(today.getTime() + (60*60*1000*24));
			document.cookie = id + "=" + escape( true ) + "; path=/; expires=" + today.toGMTString() + ";";
		}

		if (window.parent.name.substring(0, 5) == 'popup')
			window.close();
		else
			document.getElementById(id).style.display = 'none';
	}
    // 쿠키 입력
    function set_cookie(name, value, expirehours, domain) 
    {
        var today = new Date();
        today.setTime(today.getTime() + (60*60*1000*expirehours));
        document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + today.toGMTString() + ";";
        if (domain) {
            document.cookie += "domain=" + domain + ";";
        }
    }

    // 쿠키 얻음
    function get_cookie(name) 
    {
        var find_sw = false;
        var start, end;
        var i = 0;

        for (i=0; i<= document.cookie.length; i++)
        {
            start = i;
            end = start + name.length;

            if(document.cookie.substring(start, end) == name) 
            {
                find_sw = true
                break
            }
        }

        if (find_sw == true) 
        {
            start = end + 1;
            end = document.cookie.indexOf(";", start);

            if(end < start)
                end = document.cookie.length;

            return document.cookie.substring(start, end);
        }
        return "";
    }

    // 쿠키 지움
    function del_cookie(name) 
    {
        var today = new Date();

        today.setTime(today.getTime() - 1);
        var value = get_cookie(name);
        if(value != "")
            document.cookie = name + "=" + value + "; path=/; expires=" + today.toGMTString();
    }
	
	// 숫자만 입력받기 사용: style="ime-mode:disabled" onkeypress="return only_number();" (style은 css의 imeDis 클래스로 대체)
	function only_number(event) {
		ev = window.event || event;
		 
		if((ev.keyCode>=48 && ev.keyCode<=57) || ev.keyCode == "8" ) return true;
		else return false;
	}
	
	function number_format(num) {
		var num_str = num.toString();
		var result = "";
		
		for(var i=0; i<num_str.length; i++){
			var tmp = num_str.length - (i+1);
			
			if(((i%3)==0) && (i!=0)) {
				result = ',' + result;
			}
			
			result = num_str.charAt(tmp) + result;
		}
		
		return result;
	}

	// 이미지 변경
	function swapImg(obj, img) {
		var oldImg = obj.src;
		obj.onmouseout = function(){ obj.src = oldImg; }
		obj.src = img;
	}

	// 자식 노드를 파라메터로 넘겨 삭제
	function child_del(obj) {
		obj.parent().remove();
	}
	
	function mSearch(f) {
		var stx = f.stx.value.replace(/(^\s*)|(\s*$)/g,'');
		
		var bid = '';
		if(f.board_select != undefined) {
			bid = f.board_select.value ? '/bid/'+ f.board_select.value : '';
		}
		/*
		if (stx.length < 2) {
			alert('2글자 이상으로 검색해 주세요.');
			f.stx.focus();
			return false;
		}
		*/

		location.href = rt_path + '/search/index' + bid + '/stx/' + sEncode(stx);
		return false;
	}
	
	// 폰트 크기 변경
	function resize_font(obj, size) {
		var oldsize = parseInt(obj.css('font-size'));
		if((oldsize+size) > 5 && (oldsize+size) < 50) {
			obj.css('font-size', (oldsize+size) + 'px');
		}
	}
	
	// 영역 인쇄
	function pagePrint(obj) {
		var bodyHtml = '';
		if(window.onbeforeprint != undefined) {
			window.onbeforeprint = function() {
				bodyHtml = $('body').html();
				$('body').html(obj.html());
			}
		}
		if(window.onafterprint != undefined) {
			window.onafterprint = function() {
				$('body').html(bodyHtml);
			}
		}
		window.print();
	}
	
	function add_postlink() {
		win_open(rt_path +'search/postlink', 'postlink', 'width=600,height=600');
	}
	
	function sel_postlink(bo_subject, subject, table, idx) {
		opener.ret_postlink(bo_subject, subject, '{"table":"'+ table +'", "idx":"'+ idx +'"}');
		window.close();
	}
}
