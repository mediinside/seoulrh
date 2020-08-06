<?php if ( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class Cart extends Widget {
	function index() {
		$exec = $this->uri->segment(3, 'lists');
				
		$this->$exec();
	}
	
	function lists() {
		$this->seg = new search_seg(4);
		
        $seg	=& $this->seg;
		$qstr	= qstr_rep($seg->get_qstr(), 'id');	// 쿼리스트링
		
		$total_count = $this->M_shop->count_cart(CARTID);
		$result = $this->M_shop->list_cart(CARTID);
		
		$amount = 0;
		$dlvFree = array();
		$list = $result;
		foreach ($result as $key => $row) {
			$num = $key;
			
			$list[$key]['num'] = $num;
			$list[$key]['amount'] = ($row['pd_price'] + $row['option_data']['pdo_price']) * $row['cart_quantity'];
			$list[$key]['option'] = $row['cart_opt_id'] ? '<p><img src="'. RT_PATH .'/img/shop/i_option.gif" align="middle"/> '. str_option($row['option_data']['pdo_name'], $row['option_data']['pdo_price'],' (','원)') ."</p>\n" : '';
			$list[$key]['href'] = RT_PATH.'/'.$this->type.'/view/id/'.$row['pd_id'].'/cid/'.$row['pd_cate'].$qstr;
			
			$list[$key]['regdate'] = preg_replace('/\-/', '.', substr($row['pd_regdate'], 0, 10));
			
			$amount = $amount + $list[$key]['amount'];
			$dlvFree[] = $list[$key]['pd_dlvFree'];
			
			// 대표 이미지
			$list[$key]['imgNo'] = '';
			for($i = 1; $i < 9; $i++) {
				if(isset($row['pd_image'.$i]) && $row['pd_image'.$i]) {
					$list[$key]['imgNo'] = $row['pd_image'.$i];
					break;
				}
			}
		}
		
		$dlv_price = dlvCharge($amount, $this->shop_conf['dlv_free'], $this->shop_conf['dlv_price'], $dlvFree);
		
		$vars = array(
			'_TITLE_'		=> '장바구니',
			'_BODY_'		=> 'shop/'.CA_SKIN.'/cart',
			
			'qstr'			=> $qstr,
			
			'total_count'	=> $total_count,
			'amount'		=> $amount,
			'dlv_price'		=> $dlv_price,
			'shop_conf'		=> $this->shop_conf,
			
			'list'			=> $list
		);
		
		$this->load->view(null, $vars);
	}
	
	function add() {
		$idx			= $this->input->post('idx');
		$option			= $this->input->post('option');
		$quantity		= $this->input->post('quantity');
		
		if(!$this->M_shop->add_cart($idx, $option, $quantity)) {
			die('100');
		}
		
		die('000');
	}
	
	function delete() {
		$nos =			$this->input->post('chk');
		$cart_ssid =	$this->input->post('cart_ssid');
		
		if(!is_array($nos) || !is_array($cart_ssid)) {
			exit;
		}
		
		$where = array();
		foreach($nos AS $key => $no) {
			$where[] = "(cart_ssid = '". $cart_ssid[$key] ."' AND cart_no = '$no')";
		}
		
		if(!$this->M_shop->del_cart($where)) {
			alert('오류가 발생하였습니다.\\n\\n잠시후 다시 시도해주세요.');
		}
		goto_url(RT_PATH .'/shop/cart');
	}

	function update() {
		$nos =			$this->input->post('chk');
		$cart_ssid =	$this->input->post('cart_ssid');
		$quantity =		$this->input->post('quantity');
		
		if(!is_array($nos) || !is_array($cart_ssid) || !is_array($quantity)) {
			exit;
		}
		
		foreach($nos AS $key => $no) {
			$data = "cart_quantity = '". $quantity[$key] ."'";
			$where = "cart_ssid = '". $cart_ssid[$key] ."' AND cart_no = '$no'";
			
			if(!$this->M_shop->mod_cart($data, $where)) {
				alert('오류가 발생하였습니다.\\n\\n잠시후 다시 시도해주세요.');
			}
		}
		goto_url(RT_PATH .'/shop/cart');
	}
	
	function order() {
		if(!IS_MEMBER) {
			goto_url('/member/login?url=/shop/cart/order');
		}
		
		$mb_id = setValue('', $this->session->userdata('ss_mb_id'));
		$order_no = create_ord_no();	// 주문번호
		
		$join = $this->M_shop->table_opt.' b ON (cart_opt_id = b.pdo_id) LEFT JOIN ' . $this->M_shop->table.' c ON (cart_pd_id = c.pd_id)';
		$data = "cart_mb_id = '$mb_id', cart_od_no = '$order_no', cart_opt_nm = b.pdo_name, cart_opt_pr = b.pdo_price, cart_pPrice = c.pd_price + IFNULL(b.pdo_price, 0), cart_pAmount = cart_quantity * (c.pd_price + IFNULL(b.pdo_price, 0))";
		
		$this->M_shop->mod_cart($data, FALSE, $join);
		
		$result = $this->M_shop->list_cart(CARTID);
		$total_count = count($result);
		
		if(!$total_count) {
			alert('장바구니 목록이 비었습니다.');
		}
		
		$amount = 0;
		$dlvFree = array();
		$list = $result;
		foreach ($result as $key => $row) {
			$opt_plus = $row['cart_opt_pr'] > 0 ? '+' : '';
			
			$list[$key]['amount'] = ($row['pd_price'] + $row['option_data']['pdo_price']) * $row['cart_quantity'];
			$list[$key]['option'] = $row['cart_opt_id'] ? '<p><img src="'. RT_PATH .'/img/shop/i_option.gif" align="middle"/> '. str_option($row['option_data']['pdo_name'], $row['option_data']['pdo_price'],' (','원)') ."</p>\n" : '';
			
			$amount = $amount + $list[$key]['amount'];
			$dlvFree[] = $list[$key]['pd_dlvFree'];
			
			// 대표 이미지
			$list[$key]['imgNo'] = '';
			for($i = 1; $i < 9; $i++) {
				if(isset($row['pd_image'.$i]) && $row['pd_image'.$i]) {
					$list[$key]['imgNo'] = $row['pd_image'.$i];
					break;
				}
			}
		}

		$dlv_price = dlvCharge($amount, $this->shop_conf['dlv_free'], $this->shop_conf['dlv_price'], $dlvFree);
		
		$vars = array(
			'_TITLE_'		=> '장바구니',
			'_BODY_'		=> 'shop/'.CA_SKIN.'/order',
			'_CSS_'			=> array('jquery-ui'),
			'_JS_'			=> array('jquery-ui.min', 'jtimepicker'),
			
			'total_count'	=> $total_count,
			'amount'		=> $amount,
			'dlv_price'		=> $dlv_price,
			'order_no'		=> $order_no,
			'duedate'		=> $this->input->post('duedate'),
			
			'shop_conf'		=> $this->shop_conf,
				
			'list'			=> $list
		);
		
		$this->load->view(null, $vars);
	}
	
	function setup_plugin() {
		// 결제 플러그인 설치 js 파일
		switch($this->shop_conf['pg_id']) {
			case 'kcp' :
				$pg_js['utf-8'][] =	'';
				$pg_js['euc-kr'][] =	'';
				$pg_js['utf-8'][] = $pg_js['euc-kr'][] = '';										// utf-8, euc-kr 공통
				break;
			case 'agspay' :
				$pg_js['utf-8'][] =		'http://www.allthegate.com/plugin/AGSWallet_utf8';
				$pg_js['euc-kr'][] =	'http://www.allthegate.com/plugin/AGSWallet';
				$pg_js['utf-8'][] = $pg_js['euc-kr'][] = PG_PATH .'/lib/AGS_script';			// utf-8, euc-kr 공통
				break;
			default :
				return FALSE;
				break;
		}
		
		$js = setValue(array(), $pg_js[$this->config->item('charset')]);
		
		$vars = array(
			'_TITLE_'		=> '',
			'_BODY_'		=> 'index',
			
			'_JS_'			=> $js,
		);
		
		$this->load->setLayout('layout/layout_blank');
		$this->load->view(null, $vars);
	}
	
	function request() {
		$ordr_no =				$this->input->post('order_no');

		$order['password'] = 	md5($this->input->post('od_password'));
		
		$order['name'] =		$this->input->post('od_name');
		$order['email'] =		$this->input->post('od_email');
		$order['phone1'] =		$this->input->post('od_phone1');
		$order['phone2'] =		$this->input->post('od_phone2');
		$order['phone3'] =		$this->input->post('od_phone3');
		$order['mobile1'] =		$this->input->post('od_mobile1');
		$order['mobile2'] =		$this->input->post('od_mobile2');
		$order['mobile3'] =		$this->input->post('od_mobile3');
		$order['zipcode1'] =	$this->input->post('od_zipcode1');
		$order['zipcode2'] =	$this->input->post('od_zipcode2');
		$order['address1'] =	$this->input->post('od_address1');
		$order['address2'] =	$this->input->post('od_address2');
		
		$delivery['name'] =		$this->input->post('dlv_name');
		$delivery['email'] =	$this->input->post('dlv_email');
		$delivery['phone1'] =	$this->input->post('dlv_phone1');
		$delivery['phone2'] =	$this->input->post('dlv_phone2');
		$delivery['phone3'] =	$this->input->post('dlv_phone3');
		$delivery['mobile1'] =	$this->input->post('dlv_mobile1');
		$delivery['mobile2'] =	$this->input->post('dlv_mobile2');
		$delivery['mobile3'] =	$this->input->post('dlv_mobile3');
		$delivery['zipcode1'] =	$this->input->post('dlv_zipcode1');
		$delivery['zipcode2'] =	$this->input->post('dlv_zipcode2');
		$delivery['address1'] =	$this->input->post('dlv_address1');
		$delivery['address2'] =	$this->input->post('dlv_address2');
		$delivery['memo'] =		$this->input->post('dlv_memo');
		
		$result = $this->M_shop->list_cart(CARTID);
		$total_count = count($result);
		
		$amount = 0;
		$dlvFree = array();
		foreach ($result as $key => $row) {
			$pd_name = $row['pd_name'];
			$amount = $amount + (($row['pd_price'] + $row['cart_opt_pr']) * $row['cart_quantity']);
			$dlvFree[] = $row['pd_dlvFree'];
		}

		$delivery['dlv_price'] = dlvCharge($amount, $this->shop_conf['dlv_free'], $this->shop_conf['dlv_price'], $dlvFree);
		
		$amount = $amount + $delivery['dlv_price'];							// 결제금액
		$item_name = $total_count > 1 ? $pd_name .' 외 '. ($total_count - 1) .'건' : $pd_name;	// 상품명
		
		if(!IS_MEMBER) {
			if($order['password']) {
				$CI =& get_instance();
				$CI->load->library('encrypt');
				$order['password'] = $CI->encrypt->encode($order['password']);
			}
			else {
				alert('비회원 조회 비밀번호가 입력되지 않았습니다.');
			}
		}
		
		$vars = array(
			'_TITLE_'		=> '',
			'_BODY_'		=> 'shop/order_'. $this->shop_conf['pg_id'],
			
			'ordr_no'		=> $ordr_no,
			'amount'		=> $amount,
			'item_name'		=> $item_name,
			'pay_method'	=> $this->input->post('pay_method'),
			'shop_logo'		=> '',
			
			'order'			=> $order,
			'delivery'		=> $delivery,
			
			'shop_conf'		=> $this->shop_conf
		);
		
		$this->load->setLayout('layout/layout_blank');
		$this->load->view(null, $vars);
	}
	
	function result() {
		$vars = array(
			'_TITLE_'		=> '지불 결과',
			'_BODY_'		=> 'shop/result_'. $this->shop_conf['pg_id']
		);
		
		$this->load->view(null, $vars);
	}
}
?>
