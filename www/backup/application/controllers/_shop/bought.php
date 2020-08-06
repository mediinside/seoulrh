<?php if ( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class Bought extends Widget {
	function index() {
		$exec = $this->uri->segment(3, 'lists');
				
		$this->$exec();
	}
	
	function lists() {
		$seg	=& $this->seg;
		$od_no  = $this->uri->segment(4);				// 주문번호
		
		if(!IS_MEMBER && (!$this->session->userdata('ss_od_no') || $this->session->userdata('ss_od_no') != $od_no)) {
			goto_url('/member/login?url=/shop/bought');
		}
		
		$per_page = 15;								// 페이지당 출력 갯수
		$page  = $seg->get_seg('page');				// 페이지
		
		$min_y   = setValue(date('Y', strtotime('-1 week')), $this->uri->segment(4));
		$min_m   = setValue(date('m', strtotime('-1 week')), $this->uri->segment(5));
		$min_d   = setValue(date('d', strtotime('-1 week')), $this->uri->segment(6));
		$max_y   = setValue(date('Y', strtotime(TIME_YMD)), $this->uri->segment(7));
		$max_m   = setValue(date('m', strtotime(TIME_YMD)), $this->uri->segment(8));
		$max_d   = setValue(date('d', strtotime(TIME_YMD)), $this->uri->segment(9));
		
		$total_count = $this->M_shop->count_order();
		
		// 페이지
		if($page < 1) $page = 1;
		
		// 페이징
		$config['base_url']    = RT_PATH.'/'.$this->type.'/bought/page/';
		$config['per_page']    = $per_page;
		$config['total_rows']  = $total_count;
		$config['uri_segment'] = $seg->get_order('page');
		$config['suffix']      = $seg->get_qstr();
		
		$CI =& get_instance();
		$CI->load->library('pagination');
		$CI->pagination->initialize($config);
		$paging = $CI->pagination->create_links();
		
		$offset = ($page - 1) * $per_page;
		
		$sch_date = array('min'=>strtotime($min_y.'-'.$min_m.'-'.$min_d), 'max'=>strtotime($max_y.'-'.$max_m.'-'.$max_d));
		$result = $this->M_shop->list_order($per_page, $offset, $sch_date);
		
		$list = $result;
		foreach ($result as $key => $row) {
			
		}
		
		$vars = array(
			'_TITLE_'		=> '주문/배송조회',
			'_BODY_'		=> 'shop/'.CA_SKIN.'/bought',
			
			'total_count'	=> $total_count,
			
			'shop_conf'		=> $this->shop_conf,
			
			'sch_min'		=> sel_date('min', $min_y, $min_m, $min_d),
			'sch_max'		=> sel_date('max', $max_y, $max_m, $max_d),
			'sch_week'		=> RT_PATH.'/shop/bought/lists/'. date('Y/m/d', strtotime('-1 week')) .'/'. date('Y/m/d', strtotime(TIME_YMD)),
			'sch_month1'	=> RT_PATH.'/shop/bought/lists/'. date('Y/m/d', strtotime('-1 month')) .'/'. date('Y/m/d', strtotime(TIME_YMD)),
			'sch_month3'	=> RT_PATH.'/shop/bought/lists/'. date('Y/m/d', strtotime('-3 month')) .'/'. date('Y/m/d', strtotime(TIME_YMD)),
			'sch_month6'	=> RT_PATH.'/shop/bought/lists/'. date('Y/m/d', strtotime('-6 month')) .'/'. date('Y/m/d', strtotime(TIME_YMD)),
			
			'list'			=> $list,
			'paging'		=> $paging
		);
		
		$this->load->view(null, $vars);
	}
	
	function view() {
		$seg	=& $this->seg;
		$od_no  = $this->uri->segment(4);				// 주문번호
		
		if(!IS_MEMBER && (!$this->session->userdata('ss_od_no') || $this->session->userdata('ss_od_no') != $od_no)) {
			goto_url('/member/login?url=/shop/bought');
		}
		
		$total_count = $this->M_shop->count_cart(CARTID, $od_no);
		$result = $this->M_shop->list_cart(CARTID, $od_no);
		$order = $this->M_shop->get_order($od_no);
		
		$list = $result;
		foreach ($result as $key => $row) {
			$opt_plus = $row['cart_opt_pr'] > 0 ? '+' : '';
			
			$list[$key]['amount'] = ($row['pd_price'] + $row['option_data']['pdo_price']) * $row['cart_quantity'];
			$list[$key]['option'] = $row['cart_opt_id'] ? '<p><img src="'. RT_PATH .'/img/shop/i_option.gif" align="middle"/> '. str_option($row['option_data']['pdo_name'], $row['option_data']['pdo_price'],' (','원)') ."</p>\n" : '';
			
			// 대표 이미지
			$list[$key]['imgNo'] = '';
			for($i = 1; $i < 9; $i++) {
				if(isset($row['pd_image'.$i]) && $row['pd_image'.$i]) {
					$list[$key]['imgNo'] = $row['pd_image'.$i];
					break;
				}
			}
		}
		
		$vars = array(
			'_TITLE_'		=> '주문내역',
			'_BODY_'		=> 'shop/'.CA_SKIN.'/order',
			
			'total_count'	=> $total_count,
			
			'shop_conf'		=> $this->shop_conf,
			
			'list'			=> $list,
			'amount'		=> $order['od_amount'],
			'dlv_price'		=> $order['dlv_price'],
			'order_no'		=> $od_no,
			'is_view'		=> TRUE,
		);
	
		$this->load->view(null, $vars);
	}
	
	function trace() {
		$od_no = $this->input->get('no');
		$order = $this->M_shop->get_order($od_no, 'dlv_deliverer, dlv_no');
		
		if($order) {
			if($order['dlv_deliverer'] && $order['dlv_no']) {
				$dlver = $this->M_shop->deliverer[$order['dlv_deliverer']];
				
				if($dlver['method'] == 'get') {
					goto_url($dlver['url'] .'?'. $dlver['param'] .'='. $order['dlv_no']);
				}
				else {
					post_s($dlver['url'], array($dlver['param'] => $order['dlv_no']));
				}
			}
			else {
				die('배송 정보가 없습니다.');
			}
		}
		else {
			die('주문 정보가 없습니다.');
		}
	}
}
?>
