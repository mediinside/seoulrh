<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function sel_options($options, $title='옵션', $price=TRUE, $name='', $onchange='', $is_optionTag=TRUE) {
	$html = $is_optionTag ? "<select name='$name' id='$name' onchange='$onchange'><option value=''>$title</option>\n" : '';
	
	if($options) {
		foreach($options AS $opt) {
			if($is_optionTag) {
				$str_opt = $price ? str_option($opt['pdo_name'], $opt['pdo_price'], ' (', '원)') : $opt['pdo_name'];
				$html .= "<option value='". $opt['pdo_id'] ."'>". $str_opt ."</option>\n";
			}
			else {
				$str_opt = $price ? str_option($opt['pdo_name'], $opt['pdo_price'], ' <strong>', '원</strong>', FALSE) : $opt['pdo_name'];
				$html .= "<input type='radio' name='$name' id='$name' value='". $opt['pdo_id'] ."'>". $str_opt ."</br>\n";
			}
			
			$price_arr['id'. $opt['pdo_id']] = $opt['pdo_price'];
		}
	}
	
	$html .= $is_optionTag ? "</select>\n" : '';
	$html .= "<script type='text/javascript'>var option_json = '". json_encode($price_arr) ."';</script>\n";
	
	return $html;
}

function str_option($name, $price=FALSE, $prefix='', $suffix='', $is_plus=TRUE) {
	$str = $name;
	if($price !== FALSE) {
		$plus = ($is_plus && $price > 0) ? '+' : '';
		$str .= $prefix . $plus . number_format($price) . $suffix;
	}
	
	return $str;
}

function sel_date($prefix='', $sel_y='', $sel_m='', $sel_d='') {
	list($yy, $mm, $dd) = explode('-', TIME_YMD);
	
	$sel_y = setValue($yy, $sel_y);
	$sel_m = setValue($mm, $sel_m);
	$sel_d = setValue($dd, $sel_d);
	
	$year = " <select name='".$prefix."y'>\n";
	$month = " <select name='".$prefix."m'>\n";
	$day = " <select name='".$prefix."d'>\n";
	
	for($i = $yy; $i > $yy - 10; $i--) {
		$selected = $sel_y == $i ? "selected='selected'" : '';
		$year .= "<option value='$i' $selected>$i</option>\n";
	}
	for($i = 1; $i <= 12; $i++) {
		$selected = $sel_m == $i ? "selected='selected'" : '';
		$month .= "<option value='$i' $selected>$i</option>\n";
	}
	for($i = 1; $i <= 31; $i++) {
		$selected = $sel_d == $i ? "selected='selected'" : '';
		$day .= "<option value='$i' $selected>$i</option>\n";
	}
	
	$year .= '</select> ';
	$month .= '</select> ';
	$day .= '</select> ';
	
	return $year . $month . $day;
}

/* 배송비 계산
 * parameter: 상품 합계, 무료배송 조건, 기본 배송비, 무료배송 상품(여러 상품을 체크할 경우 array형태로)
 * description: 상품 합계가 무료배송 조건에 달하지 못하면 배송비 부과. 무료배송 상품은 무조건 무료 배송
 * 유료 배송 상품이 썩여 있고 무료배송 조건이 안되면 유료배송
 */
function dlvCharge($price, $free, $conf_dlvCharge, $is_dlvFreeItem=0) {
	if(!is_array($is_dlvFreeItem)) {
		$is_dlvFreeItem = array($is_dlvFreeItem);
	}
	
	if(array_search(0, $is_dlvFreeItem) === FALSE) {
		return 0;
	}
	
	return $price < $free ? $conf_dlvCharge : 0;
}

function shop_status($id='', $selected='') {
	$status_arr = array('주문'=>'입금대기', '준비'=>'발송준비', '발송'=>'발송완료', '취소'=>'주문취소', '교환'=>'교환', '반품'=>'반품');	// DB od_ststus 필드랑 동일하게
	$html = "<select name='$id'>\n";
	
	if(!$selected) {
		$html .= "<option value=''></option>\n";
	}
	
	foreach($status_arr AS $status => $text) {
		$seld = ($status == $selected) ? "selected='selected'" : '';
		$html .= "<option value='$status' $seld>$text</option>\n";
	}
	$html .= "</select>\n";
	
	return $html;
}

function edu_status($id='', $selected='') {
	$status_arr = array('주문'=>'입금대기', '준비'=>'입금완료', '취소'=>'수강취소');	// DB od_ststus 필드랑 동일하게
	$html = "<select name='$id'>\n";

	if(!$selected) {
		$html .= "<option value=''></option>\n";
	}

	foreach($status_arr AS $status => $text) {
		$seld = ($status == $selected) ? "selected='selected'" : '';
		$html .= "<option value='$status' $seld>$text</option>\n";
	}
	$html .= "</select>\n";

	return $html;
}

function shop_method($method) {
	$method_arr = array('card'=>'신용카드', 'iche' => '계좌이체', 'virtual' => '무통장입금');		// 올더게이트용
		
	return $method_arr[$method];
}

// 주문번호 생성
function create_ord_no() {
	$CI =& get_instance();
	$ssid =  $CI->session->userdata('session_id');
	
	return strtoupper(substr($ssid, -10)) . time();
}
?>
