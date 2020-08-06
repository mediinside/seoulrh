if (typeof(SHOP_JS) == 'undefined') {
    if (typeof rt_path == 'undefined')
        alert('rt_path 변수가 선언되지 않았습니다.');
	
	var SHOP_JS = true;

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
	function set_fshop(mode, item_id, action) {
		if(mode) {
			$('#fshop #mode').val(mode);
		}
		if(item_id && item_id !='undefined') {
			$('#fshop #idx').val(item_id);
		}
		if(action && action !='undefined') {
			$('#fshop').attr('action', action);
		}
		
		$('#fshop').submit();
	}
	
	// 장바구니 담기
	function addCart(item_id, go_cart) {
		$.post(rt_path +"/shop/cart/add", $('#fshop').serialize(), function(resCode){
			if(resCode == '000') {
				if(go_cart == true || confirm('상품을 장바구니에 담았습니다.\n\n지금 확인 하시겠습니까?')) {
					location.href = rt_path +'/shop/cart';
				}
			}
			else {
				alert('시스템 오류로 저장하지 못하였습니다.\n\n잠시 후 다시 시도해주세요.')
			}
		});
	}
	
	// 수량 조절
	function set_quantity(suffix, opt_price, val, min, max) {
		var idx =			$('#idx'+ suffix);
		var qty =			$('#quantity'+ suffix);
		var price =			$('#price'+ suffix);
		var total_price =	$('#total_price'+ suffix);
		var option =		$('#option'+ suffix);
		var old =			parseInt(qty.val());
		
		if(!old || old ==0) {
			val = val ? val : 1;
		}
		if(min != 'unfined') {
			if(old == min && val < 0) {
				alert('최소 '+ min +'개 이상만 주문하실 수 있습니다.');
				return;
			}
			else if(old + val < min) {
				val = min - old;
			}
		}
		if(max != 'unfined') {
			if(old == max && val > 0) {
				alert('최대 '+ max +'개 이하만 주문하실 수 있습니다.');
				return;
			}
			else if(old + val > max) {
				val = max - old;
			}
		}
		
		qty.val(parseInt(qty.val()) + val);
		
		if(total_price) {
			var opt_price = opt_price ? parseInt(opt_price) : 0;
			var priceInt = parseInt(price.html().replace(',','')) + opt_price;
			//var id = suffix ? idx.val() : '';
			var id = idx.val();
			
			set_price(total_price, qty, option.val(), priceInt, id);
		}
	}
	
	// 가격 계산
	function set_price(total, qty, optionId, priceInt, id) {
		var opt_price = 0;
		var dlv_priceInt = parseInt($('#dlv_price').html().replace(',',''));
		
		if(typeof id !== 'undefined' && id) {
			if(typeof eval('price_json'+id) !== 'undefined' && optionId) {
				var price_obj =  $.parseJSON(eval('price_json'+id));
				opt_price = parseInt(eval('price_obj.id'+optionId));
			}
		}
		
		total.html(number_format(qty.val() * (priceInt + opt_price) + dlv_priceInt));
	}
}

