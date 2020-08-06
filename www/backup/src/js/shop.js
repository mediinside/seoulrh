if (typeof(SHOP_JS) == 'undefined') {
    if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');
	
	var SHOP_JS = true;
	var IS_BUY_MODE = false;
	var SHOP_FORM = null;

	// 검색 리다이렉트
	function dateSearch(f) {
		location.href =	f.action.replace(/\/$/g, '')+'/'+
						f.miny.value+'/'+
						f.minm.value+'/'+
						f.mind.value+'/'+
						f.maxy.value+'/'+
						f.maxm.value+'/'+
						f.maxd.value;
		return false;
	}
		
	// 장바구니담기, 바로구매 폼셋
	function set_fshop(is_buy, is_soldout, soldout_str) {
		if(is_soldout) {
			alert(soldout_str +'된 상품입니다.');
			return false;
		}

		IS_BUY_MODE = is_buy;
		
		SHOP_FORM.submit();
	}
	
	// 장바구니 담기
	function addCart() {
		$.post(SHOP_FORM.attr('action'), SHOP_FORM.serialize(), function(resCode){
			if(resCode == '000') {
				if(IS_BUY_MODE || confirm('상품을 장바구니에 담았습니다.\n\n지금 확인 하시겠습니까?')) {
					location.href = SHOP_FORM.attr('action').replace('\/add','');
				}
			}
			else {
				alert('ERROR!\n\n잠시후 다시 시도해주세요.')
			}
		});
	}
	
	// 옵션 변경
	function set_option() {
		set_quantity();
	}

	// 리스트 수량 변경
	function set_listQty(suffix, price, opt_price, sum, min, max) {
		$('#price').val(price);
		
		set_quantity(sum, min, max, opt_price, suffix);
	}
	
	// 수량 조절
	function set_quantity(sum, min, max, etc_price, suffix) {
		if(typeof suffix == 'undefined')	suffix = '';

		var price =			$('#price'+ suffix);		// 가격
		var amount =		$('#amount'+ suffix);		// 단품 합계
		var qty =			$('#quantity'+ suffix);		// 수량
		var option =		$('#option'+ suffix);		// 옵션
		var total_price =	$('#total_price');			// 총 합계
		var dlv_price =		$('#dlv_price');			// 배송비 Text
		var dlvPri =		$('#dlvPri');				// 배송비
		var dlvFre =		$('#dlvFre');				// 무료배송 조건
		var dlvFreItem =	$('#dlvFreItem');			// 무료배송 상품

		if(typeof sum == 'undefined') 		sum = 0;
		if(typeof min == 'undefined') 		min = 1;
		if(typeof max == 'undefined') 		max = 9999;
		if(typeof etc_price == 'undefined')	etc_price = 0;
		
		if(parseInt(qty.val()) + sum < min) {
			alert('최소 '+ min +'개 이상만 주문하실 수 있습니다.');
			return;
		}
		
		if(parseInt(qty.val()) + sum > max) {
			alert('최대 '+ max +'개 이하만 주문하실 수 있습니다.');
			return;
		}

		quantity =		parseInt(qty.val()) + sum;
		one_price =		parseInt(price.val()) + etc_price;
		opt_price =		option_price(option.val());
		amnt =			quantity * (one_price + opt_price);
		total =			suffix === '' ? amnt : amount_total(suffix, amnt);
		dlv =			!dlvFreItem && total < dlvFre.val() ? parseInt(dlvPri.val()) : 0;

		// 수량
		qty.val(quantity);
		
		// 배송비
		dlv_price.html( number_format(dlv) +' 원' );
		
		// 단품 합계
		amount.html( number_format(amnt+dlv) +' 원' );
		
		// 총 합계
		total_price.html( number_format(total+dlv) +' 원' );
	}
	
	// 옵션 가격
	function option_price(optionId) {
		if(typeof optionId == 'undefined' || optionId == '') {
			return 0;
		}
		
		var option_obj =  $.parseJSON(option_json);
		return parseInt(eval('option_obj.id'+ optionId));
	}
	
	// 총 합계 가격
	function amount_total(suffix, newAmount) {
		var amntPrice = $('input[name="amntPrice[]"]');
		var total = 0;
		
		$('#amntPrice'+ suffix).val(newAmount);
		
		for(var i = 0; i < amntPrice.length; i++) {
			total = total + parseInt(amntPrice[i].value);
		}
		
		return total;
	}
	
	// 상세페이지 대표 이미지
	function shop_viewImg(id, no, size) {
		var id = id;
		var no = no;
		var size = size;

		document.getElementById('bigImg').src = '/useful/thumbnail/'+ size +'/ki_shop/'+ id +'?no='+ no;
		document.getElementById('btn_showImg').style.cursor = 'pointer';
		document.getElementById('btn_showImg').onclick = function(){
			layerWin('/useful/gallery/shop/id/'+ id +'/no/'+ no, 'shopImgs', 700, 550);
		}
	}
	
	// 선택 수정/삭제
	function chk_cart(f, mode) {
		var str = '';
		var ctrl = $('#ctrl').val();
		
		if(ctrl != 'shop' && ctrl != 'edu') {
			return fasle;
		}
		
		if(mode == 'mod') {
			str = '수정';
			act = ctrl +'/cart/update';
		}
		else if(mode == 'del') {
			str = '삭제';
			act = ctrl +'/cart/delete';
		}
		else if(mode == 'ord') {
			str = '주문';
			act = ctrl +'/cart/order';
		}

		if (f.find(".check:checked").length < 1) {
	    	alert(str + "할 상품를 하나 이상 선택하세요.");
			return;
	    }
	
	    if (str == '삭제' && !confirm('선택한 상품 정말 삭제 하시겠습니까?'))
			return;

		f.attr('action', '/'+ act);
		f.submit();
	}
	
	// 카트 개별 수정/삭제
	function exec_cart(key, isDel) {
		var ctrl = $('#ctrl').val();
		
		if(ctrl != 'shop' && ctrl != 'edu') {
			return fasle;
		}
		
		var f = document.fshop;
		var no = $("input[name='chk["+ key +"]']", f).val();
		var ssid = $("input[name='cart_ssid[]']", f).eq(key).val();
		var quantity = $("input[name='quantity[]']", f).eq(key).val();
		
		var url = isDel ? ctrl +'/cart/delete' : ctrl +'/cart/update';
		
		post_s(url, {'chk[]':no,'cart_ssid[]':ssid,'quantity[]':quantity}, false);
	}
	
	// 배송조회
	function dlv_trace(no, is_sent) {
		if(is_sent) {
			win_open('/shop/bought/trace?no='+ no, 'dlv_trace', 'width=700, height=550, scrollbars=yes');
		}
		else {
			alert('배송 정보가 없습니다.')
		}
	}
	
	// 영수증 출력
	function receipt(pg_id, pg_code, method, auth, tid, tdate) {
		if(method != 'card') {
			return false;
		}
		
		date = new Date();
		yy = date.getFullYear();
		mm = date.getMonth() + 1;
		dd = date.getDate();
		if((mm+"").length < 2) {
			mm = "0"+ mm;
		}
		if((dd+"").length < 2) {
			dd = "0"+ dd;
		}
		today = ""+ yy + mm + dd;
		
		if(pg_id == 'agspay') {
			if(today == tdate) {
				url="http://www.allthegate.com/customer/receiptLast3.jsp"
				url=url+"?sRetailer_id="+ pg_code;
				url=url+"&approve="+ auth;
				url=url+"&send_no="+ tid;
				url=url+"&send_dt="+ tdate;
				
				window.open(url, "window","toolbar=no,location=no,directories=no,status=,menubar=no,scrollbars=no,resizable=no,width=420,height=700,top=0,left=150");
			}
			else {
				url = 'http://www.allthegate.com/support/card_search.html';
				window.open(url, "window","toolbar=no,location=no,directories=no,status=,menubar=no,scrollbars=yes,resizable=no,width=630,height=510,top=0,left=150");
			}
		}
	}
}

$(document).ready(function(){
	if (typeof(SHOP_CHECK_JS) == 'undefined') {
	    if (typeof rt_path == 'undefined')
	        alert('rt_path 변수가 선언되지 않았습니다.');
		
		var SHOP_CHECK_JS = true;
		
		$('#allcheck').click(function() {
			var chk = $(".check", document.fboardlist);
			if (this.checked)
				chk.attr('checked', true);
			else
				chk.attr('checked', false);
		});
	}
});
