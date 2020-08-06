<?php
class Shop extends CI_Controller {
	public $seg;			// uri 정보
	public $cate;			// 카테고리 정보
	public $shop_conf;		// 쇼핑몰 설정
	public $type = 'shop';	// 제품 유형 (product or shop)
	
	function __construct() {
		parent::__construct();
		
		$this->load->helper(array('shop', 'search'));
		$this->load->model(array('M_shop', 'M_staff', 'M_latest', 'M_layout'));
	}
	
	function _remap() {
		$this->seg =		new search_seg(3);
		
		$mode =				$this->uri->segment(2, 'lists');
		$cid =				$this->seg->get_seg('cid');
		
		$this->shop_conf =	$this->M_shop->getConfig();
		
		define('CARTID', $this->session->userdata('ss_cart_id'));
		
		// 상품 페이지
		if($cid) {
			$this->cate = $this->M_shop->row_cate($cid, 'a.*', TRUE);	
			
			if($this->cate && !$this->cate['ca_hidden']) {
				define('CID', $this->cate['ca_id']);
				define('CA_SKIN', $this->cate['ca_skin']);
				define('RESIZE_WIDTH', '734');
				
				$param = $this->cate['ca_parameter'];
				$layout = $this->M_layout->row($this->cate['ca_layout']);
			}
		}
		// 쇼핑몰 관련 페이지(장바구니, 주문, 배송 등)
		else if(isset($this->shop_conf[$mode .'_skin'])) {
			define('CID', $mode);
			define('CA_SKIN',  $this->shop_conf[$mode .'_skin']);
			define('PG_PATH', PG_DIR .'/'. $this->shop_conf['pg_id']);
			
			$param = $this->shop_conf[$mode .'_parameter'];
			$layout = $this->M_layout->row($this->shop_conf[$mode .'_layout']);
		}
		
		if(!defined('CID') && $mode != 'images') {
			show_404();
		}
		
		$this->load->vars(array_merge(array(
			'mainTitle'		=> $this->cate['ca_pname'],		// 한국아이국악협회 커스터마이징
			'subTitle'		=> $this->cate['ca_name'],		// 한국아이국악협회 커스터마이징
			'_JS_'			=> array('shop', 'jvalidate', 'layerDlg'),
			'_CSS_'			=> array('shop')
		), param_decode($param)));
		
		// 레이아웃
		$this->load->setLayout(LAYOUT_PATH.'/'.$layout['ly_file']);
		
		// 위젯 실행
		widget::run("_shop/$mode");
	}
}
