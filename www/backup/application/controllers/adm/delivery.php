<?php
class Delivery extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_shop', 'M_shop'));
		$this->load->helper(array('sideview', 'search', 'shop'));
		$this->load->library('pagination');
		
		// 오래된 장바구니 정리
		$shop_conf = $this->M_shop->getConfig();
		if($shop_conf['cart_last_clear'] < TIME_YMD) {
			$this->M_a_shop->cart_clear();
		}
	}
	
	function index() {
		$this->lists();
	}
	
	function order() {
		include "init.php";

		$list = $this->get_data('order', '주문');
		
		$vars = array(
			'_TITLE_'		=> '주문 리스트',
			'_BODY_'		=> ADM_F.'/delivery/dlv_order',
			
			'list'			=> $list
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}

	function ready() {
		include "init.php";
		
		$list = $this->get_data('ready', '준비');
		
		$vars = array(
			'_TITLE_'		=> '발송 준비 리스트',
			'_BODY_'		=> ADM_F.'/delivery/dlv_ready',
			
			'list'			=> $list
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}

	function send() {
		include "init.php";
		
		$shop_conf	=	$this->M_shop->getConfig();
		$list =			$this->get_data('send', '준비');

		foreach($list AS $key => $row) {
			$list[$key]['dlv_payPrice'] = isset($list[$key]['dlv_payPrice']) ? $list[$key]['dlv_payPrice'] : $list[$key]['dlv_price'];
		}
		
		$vars = array(
			'_TITLE_'		=> '주문 일괄배송',
			'_BODY_'		=> ADM_F.'/delivery/dlv_send',
			
			'list'			=> $list,
			'shop_conf'		=> $shop_conf
		);
	
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function sent() {
		include "init.php";
		
		$list = $this->get_data('sent', '발송');
		
		$vars = array(
			'_TITLE_'		=> '배송중 리스트',
			'_BODY_'		=> ADM_F.'/delivery/dlv_sent',

			'list'			=> $list
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}

	function cancel() {
		include "init.php";
		
		$list = $this->get_data('cancel', '취소');
		
		$vars = array(
			'_TITLE_'		=> '주문취소 리스트',
			'_BODY_'		=> ADM_F.'/delivery/dlv_cancel',
			
			'list'			=> $list
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}

	function sendback() {
		include "init.php";
		
		$list = $this->get_data('sendback', array('교환','반품'));
		
		$vars = array(
			'_TITLE_'		=> '교환/반품 리스트',
			'_BODY_'		=> ADM_F.'/delivery/dlv_sendback',
			
			'list'			=> $list
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
		
	function lists() {
		include "init.php";

		$list = $this->get_data();

		$vars = array(
			'_TITLE_'		=> '배송관리 전체 리스트',
			'_BODY_'		=> ADM_F.'/delivery/dlv_list',
			
			'list'			=> $list
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($no='') {
		include "init.php";
		
		if(!$no) {
			show_404();
		}

		$this->load->library('form_validation');

		$config = array(
				array('field'=>'od_no', 'label'=>'주문번호', 'rules'=>'trim|required|max_length[20]|xss_clean'),
				array('field'=>'od_name', 'label'=>'주문자명', 'rules'=>'trim|required|max_length[20]|xss_clean'),
				array('field'=>'dlv_name', 'label'=>'수화인명', 'rules'=>'trim|required|max_length[20]|xss_clean'),
				array('field'=>'dlv_zipcode1', 'label'=>'수화인 우편번호', 'rules'=>'trim|required|max_length[3]|xss_clean'),
				array('field'=>'dlv_zipcode2', 'label'=>'수화인 우편번호', 'rules'=>'trim|required|max_length[3]|xss_clean'),
				array('field'=>'dlv_address1', 'label'=>'수화인 주소', 'rules'=>'trim|required|max_length[200]|xss_clean'),
				array('field'=>'dlv_address2', 'label'=>'수화인 주소', 'rules'=>'trim|required|max_length[200]|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if($this->form_validation->run() == FALSE) {
			$shop_conf	= $this->M_shop->getConfig();
			
			$order = $this->M_shop->get_order($no);
			$order['method'] = shop_method($order['pay_method']);
			$order['dlv_payPrice'] = isset($order['dlv_payPrice']) ? $order['dlv_payPrice'] : $order['dlv_price'];
			
			$vars = array(
				'_TITLE_'	=> '주문정보',
				'_BODY_'	=> ADM_F.'/delivery/dlv_form',
				'_CSS_'		=> array('shop'),
				
				'token'		=> get_token(),
				'shop_conf'	=> $shop_conf,
				'order'		=> $order
			);
			
			$this->load->view('layout/layout_blank', $vars);
		}
		else {
			check_token();
			
			$data = array(
				'od_status'		=> $this->input->post('od_status'),
				'od_name'		=> $this->input->post('od_name'),
				'od_email'		=> $this->input->post('od_email'),
				'od_phone1'		=> $this->input->post('od_phone1'),
				'od_phone2'		=> $this->input->post('od_phone2'),
				'od_phone3'		=> $this->input->post('od_phone3'),
				'od_mobile1'	=> $this->input->post('od_mobile1'),
				'od_mobile2'	=> $this->input->post('od_mobile2'),
				'od_mobile3'	=> $this->input->post('od_mobile3'),
				'od_zipcode1'	=> $this->input->post('od_zipcode1'),
				'od_zipcode2'	=> $this->input->post('od_zipcode2'),
				'od_address1'	=> $this->input->post('od_address1'),
				'od_address2'	=> $this->input->post('od_address2'),
				'dlv_name'		=> $this->input->post('dlv_name'),
				'dlv_email'		=> $this->input->post('dlv_email'),
				'dlv_phone1'	=> $this->input->post('dlv_phone1'),
				'dlv_phone2'	=> $this->input->post('dlv_phone2'),
				'dlv_phone3'	=> $this->input->post('dlv_phone3'),
				'dlv_mobile1'	=> $this->input->post('dlv_mobile1'),
				'dlv_mobile2'	=> $this->input->post('dlv_mobile2'),
				'dlv_mobile3'	=> $this->input->post('dlv_mobile3'),
				'dlv_zipcode1'	=> $this->input->post('dlv_zipcode1'),
				'dlv_zipcode2'	=> $this->input->post('dlv_zipcode2'),
				'dlv_address1'	=> $this->input->post('dlv_address1'),
				'dlv_address2'	=> $this->input->post('dlv_address2'),
				'dlv_memo'		=> $this->input->post('dlv_memo'),
				'od_mdydate'	=> TIME_YMDHIS
			);
			
			$data_dlv = array(
					'od_no'			=> $this->input->post('od_no'),
					'dlv_no'		=> $this->input->post('dlv_no'),
					'dlv_deliverer'	=> $this->input->post('dlv_deliverer'),
					'dlv_payPrice'	=> $this->input->post('dlv_payPrice'),
					'dlv_mdydate'	=> TIME_YMDHIS
			);
			
			$od_id = $this->M_a_shop->update_order($data);
			$dlv_id = $this->M_a_shop->update_delivery($data_dlv);
			
			alert('수정 되었습니다.', ADM_F.'/delivery/form/'.$od_id);
		}
	}
	
	function item($no='') {
		$total_count = $this->M_shop->count_cart(FALSE, $no, TRUE);
		$result = $this->M_shop->list_cart(FALSE, $no, TRUE);
		$order = $this->M_shop->get_order($no);
		
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
			'_TITLE_'	=> '주문정보',
			'_BODY_'	=> ADM_F.'/delivery/dlv_item',
			'_CSS_'		=> array('shop'),
			
			'list'			=> $list,
			'amount'		=> $order['od_amount'],
			'dlv_price'		=> $order['dlv_price'],
		);

		$this->load->view('layout/layout_blank', $vars);
	}
	
	function update() {
		check_token(URL);
		
		$ids =				$this->input->post('chk');
		$od_status =		$this->input->post('od_status');
		$keys		=		$this->input->post('keys');
		$dlv_no		=		$this->input->post('dlv_no');
		$dlv_deliverer	=	$this->input->post('dlv_deliverer');
		$dlv_payPrice	=	$this->input->post('dlv_payPrice');
		
		if(!$ids)
			return false;
		
		$data = array(
			'od_status' => $od_status
		);
		
		$this->M_a_shop->list_update_dlv($ids, $data);
		
		if($dlv_no && $dlv_deliverer && $dlv_payPrice) {
			foreach($ids AS $od_no) {
				$key = array_search($od_no, $keys);
				if($key !== FALSE) {
					$data_dlv = array(
						'od_no'			=> $od_no,
						'dlv_no'		=> $dlv_no[$key],
						'dlv_deliverer'	=> $dlv_deliverer[$key],
						'dlv_payPrice'	=> $dlv_payPrice[$key],
						'dlv_mdydate'	=> TIME_YMDHIS
					);
					$dlv_id = $this->M_a_shop->update_delivery($data_dlv);
				}
			}
		}
		
		goto_url(URL);
	}
	
	private function get_data($mode='lists', $status='') {
		// 서치
		$seg =	new search_seg;
		$page =	$seg->get_seg('page');
		$sst =	$seg->get_seg('sst');
		$sod =	$seg->get_seg('sod');
		$sfl =	$seg->get_seg('sfl');
		$stx =	$seg->get_seg('stx');
		$qstr =	$seg->get_qstr();
		
		// 정렬
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'od_regdate';
		if (!$sod) $sod = 'desc';
		
		// 페이징
		$pageConf['suffix'] =	$qstr;
		$pageConf['base_url'] =	RT_PATH.'/'.ADM_F.'/delivery/'.$mode.'/page/';
		$pageConf['per_page'] =	15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_a_shop->list_delivery($sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset, $status);
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		// 추가 데이터
		$list = array();
		foreach ($result['qry'] as $i => $row) {
			$list[$i] =						$row;
			$list[$i]['no'] =				$pageConf['total_rows'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] =				$i % 2;
			$list[$i]['s_view'] =			icon('보기', "javascript:show_order_item('". $row['od_no'] ."');");
			$list[$i]['s_mod'] =			icon('수정', "javascript:show_order_info('". $row['od_no'] ."');");
			$list[$i]['status'] =			shop_status('od_status['. $row['od_no'] .']', $row['od_status']);
			$list[$i]['remain'] =			$row['od_amount'] - $row['pay_amount'];
			$list[$i]['method'] =			shop_method($list[$i]['pay_method']);
			list($list[$i]['regdate']) =	explode(' ', $list[$i]['od_regdate']);
		}
		
		$sort_link['od_mb_id'] =			sort_link('od_mb_id') ."?qstr=$qstr";
		$sort_link['od_name'] =				sort_link('od_name') ."?qstr=$qstr";
		$sort_link['remain'] =				sort_link('remain') ."?qstr=$qstr";
		$sort_link['od_status'] =			sort_link('od_status') ."?qstr=$qstr";
		$sort_link['od_regdate'] =			sort_link('od_regdate') ."?qstr=$qstr";
		
		$vars = array(
			'token'			=> get_token(),
			'qstr'			=> $qstr,
			
			'sfl'			=> $sfl,
			'stx'			=> $stx,
			
			'total_cnt'		=> $pageConf['total_rows'],
			'paging'		=> $this->pagination->create_links(),
			'sort_link'		=> $sort_link
		);
		
		$this->load->vars($vars);
		
		return $list;
	}
}
?>