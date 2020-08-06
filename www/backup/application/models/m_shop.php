<?php
class M_shop extends CI_Model {
	var $table = 'ki_shop';
	var $table_opt = 'ki_shop_opt';
	var $table_cfg = 'ki_shop_cfg';
	var $table_cate = 'ki_shop_cate';
	var $table_cart = 'ki_shop_cart';
	var $table_ord = 'ki_shop_ord';
	var $table_dlv = 'ki_shop_dlv';
	var $deliverer = array(
		1 =>	array('name' => 'CJ GLS',		'tel' => '1588-5353',	'method' => 'get',	'param' => 'slipno',		'url' => 'http://www.cjgls.co.kr/kor/service/service02_01.asp'),
		2 =>	array('name' => 'DHL',			'tel' => '1588-0001',	'method' => 'get',	'param' => 'AWB',			'url' => 'http://www.dhl.co.kr/content/kr/ko/express/tracking.shtml?brand=DHL'),
		3 =>	array('name' => 'EMS',			'tel' => '1588-1300',	'method' => 'get',	'param' => 'POST_CODE',		'url' => 'http://service.epost.go.kr/trace.RetrieveEmsTrace.postal?ems_gubun=E'),
		4 =>	array('name' => 'FedEx',		'tel' => '080-023-8000','method' => 'get',	'param' => 'tracknumbers',	'url' => 'http://www.fedex.com/Tracking?ascend_header=1&clienttype=dotcomreg&cntry_code=kr&language=korean'),
		5 =>	array('name' => 'KGB',			'tel' => '1577-4577',	'method' => 'get',	'param' => 'f_slipno',		'url' => 'http://www.kgbls.co.kr/sub5/trace.asp'),
		6 =>	array('name' => 'KG옐로우캡',	'tel' => '1588-0123',	'method' => 'get',	'param' => 'invoice_no',	'url' => 'http://www.yellowcap.co.kr/custom/inquiry_result.asp'),
		7 =>	array('name' => 'UPS',			'tel' => '1588-6886',	'method' => 'get',	'param' => 'InquiryNumber1','url' => 'http://www.ups.com/WebTracking/track?loc=ko_KR'),
		8 =>	array('name' => '대한통운',		'tel' => '1588-1255',	'method' => 'get',	'param' => 'fsp_action=PARC_ACT_002&fsp_cmd=retrieveInvNoACT&invc_no',		'url' => 'https://www.doortodoor.co.kr/parcel/doortodoor.do'),
		9 =>	array('name' => '동부익스프레스','tel' => '02-3484-2600','method' => 'get',	'param' => 'item_no',		'url' => 'http://www.dongbuexpress.co.kr/delivery/delivery_search_view.jsp'),
		10 =>	array('name' => '로젠택배',		'tel' => '1588-9988',	'method' => 'get',	'param' => 'slipno',		'url' => 'http://d2d.ilogen.com/d2d/delivery/invoice_tracesearch_quick.jsp'),
		11 =>	array('name' => '우체국택배',	'tel' => '1588-1300',	'method' => 'get',	'param' => 'sid1',			'url' => 'http://service.epost.go.kr/trace.RetrieveRegiPrclDeliv.postal'),
		12 =>	array('name' => '이노지스',		'tel' => '1566-4082',	'method' => 'get',	'param' => 'invoice',		'url' => 'http://www.innogis.co.kr/tracking_view.asp?search.x=42&search.y=28'),
		13 =>	array('name' => '일양로지스',	'tel' => '1588-0002',	'method' => 'get',	'param' => 'hawb_no',		'url' => 'http://www.ilyanglogis.com/functionality/tracking_result.asp'),
		14 =>	array('name' => '편의점택배',	'tel' => '1566-1025',	'method' => 'get',	'param' => 'invoice_no',	'url' => 'http://was.cvsnet.co.kr/src/ctod_status.jsp'),
		15 =>	array('name' => '한진택배',		'tel' => '1588-0011',	'method' => 'get',	'param' => 'wbl_num',		'url' => 'http://www.hanjin.co.kr/Delivery_html/inquiry/result_waybill.jsp'),
		16 =>	array('name' => '현대택배',		'tel' => '1588-2121',	'method' => 'get',	'param' => 'InvNo',			'url' => 'http://www.hlc.co.kr/hydex/jsp/tracking/trackingViewCus.jsp')
	);
	
	function __construct() {
		parent::__construct();
		$this->load->helper('textual');
	}
	
	function getConfig($scf_id='') {
		if($scf_id) {
			$this->db->where('scf_id', $scf_id);
		}
		
		// 초기값
		$config = array(
			'pg_id'				=> 'kcp',		// pg사 id (M_a_shop.php의 $pg_co 키값을 사용)
			'pg_code'			=> 'T0001',		// 제휴사 코드
			'pg_store'			=> 'New Store',	// 상점 이름
			'pg_is_real'		=> '0',			// 테스트모드 리얼모드 구분
			'cart_layout'		=> '2',			// 장바구니 레이아웃
			'cart_skin'			=> 'shop',		// 장바구니 스킨
			'cart_parameter'	=> '',			// 장바구니 파라메터
			'dlv_price'			=> '3000',		// 기본 배송비
			'dlv_additional'	=> '3000',		// 제주/도서/산간 추가 배송비
			'dlv_free'			=> '30000',		// 무료배송 조건 결제금액
			'dlv_deliverer'		=> '0'			// 배송사
		);
		
		$result = $this->db->get($this->table_cfg)->result_array();
		foreach($result AS $conf) {
			$config[$conf['scf_id']] = $conf['scf_value'] ? $conf['scf_value'] : $conf['scf_extend'];
		}
		
		return $config;
	}
	
	function list_result($cate, $limit, $offset, $wr_field='a.*', $ids='') {
		if($cate) {
			$this->db->where('pd_cate', $cate);
		}
		if($ids) {
			$this->db->where_in('pd_id', $ids);
		}
		$this->db->where('pd_hidden', '0');
		
		$this->db->select($wr_field.', b.pdo_id AS is_option');
		$this->db->join($this->table_opt.' b', 'a.pd_id = b.pd_id', 'left');
		$this->db->group_by('a.pd_id');
		$this->db->order_by('pd_id', 'desc');
		$qry = $this->db->get($this->table.' a', $limit, $offset);
		$result = $qry->result_array();
		
		foreach($result AS $key => $row) {
			$result[$key]['pd_options'] = $result[$key]['is_option'] ? $this->get_options($row['pd_id']) : array();
		}
		
		return $result;
	}
	
	function list_count($cate, $ids='') {
		if($cate) {
			$this->db->where('pd_cate', $cate);
		}
		if($ids) {
			$this->db->where_in('pd_id', $ids);
		}
		$this->db->where('pd_hidden', 0);
				
		$this->db->select(' COUNT(pd_id) as '.$this->db->protect_identifiers('cnt'));
		$qry = $this->db->get($this->table);
		$row = $qry->row();
		return $row->cnt;
	}
	
	// 최근 상품
	function latest($min_cate, $max_cate, $limit, $contLength, $thumb='75x100') {
		if($min_cate) $this->db->where('pd_cate >= '.$min_cate);
		if($max_cate) $this->db->where('pd_cate <= '.$max_cate);
		$this->db->where('pd_hidden', '0');
		
		$this->db->select('pd_id, pd_cate, pd_name');
		$this->db->order_by('pd_id', 'desc');
		$qry = $this->db->get($this->table, $limit);
		$result = $qry->result_array();
		
		for($i = count($result); $i < $limit; $i++) {
			$result[] = array('href' => '#none', 'pd_id' => '', 'pd_cate' => '', 'pd_name' => '등록된 상품이 없습니다.');
		}
		
		foreach($result as $key => $val) {
			$result[$key]['pd_name'] = cut_str($val['pd_name'], $contLength);
			$result[$key]['href'] = RT_PATH .'/shop/view/id/'. $val['pd_id'] .'/cid/'. $val['pd_cate'];
			$result[$key]['thumb'] = '/useful/thumbnail/'. $thumb .'/shop/'.$val['pd_id'];
		}
		
		return $result;
	}
	
	function row($id, $fields='*') {
		if (!$id) return FALSE;
	
		if($row = $this->db->get_row($id, $this->table, $fields)) {
			$row['pd_options'] = $this->get_options($row['pd_id']);
		}
		
		return $row;
	}
	
	function get_options($id) {
		if(!$id) {
			return FALSE;
		}
		
		$this->db->select('*');
		$this->db->where('pd_id', $id);
		$this->db->order_by('pdo_id', 'asc');
		$qry = $this->db->get($this->table_opt);
		return $qry->result_array();
	}
	
	function filepath() {
		return DATA_PATH.'/product';
	}
	
	// 조회수 증가
	function hit_update($id) {
		$this->db->set('pd_hit', 'pd_hit + 1', FALSE);
		$this->db->update($this->table, null, array('pd_id' => $id));
	}
	
	// 인기 상품
	function get_best($limit=5, $fields='*') {
		$this->db->select($fields);
		$this->db->where('pd_show', '1');
		$this->db->order_by('pd_id', 'desc');
		$qry = $this->db->get($this->table, $limit);
		$rows = $qry->result_array();
		
		for($i = count($rows); $i < $limit; $i++) {
			$rows[] = array('pd_id'=>'');
		}
		
		return $rows;
	}
	
	// 빈 옵션
	function opt_false() {
		$column = $this->db->query("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name='".$this->opt_table."'")->result_array();
		foreach($column as $key => $val)
			if($val['COLUMN_NAME'] != 'pd_id') $col[$val['COLUMN_NAME']] = 0;
		return $col;
	}
	
	// 다음, 이전 상품 링크
	function prev_next_link($cate, $id, $sca='', $sfl='', $stx='') {
		$this->db->start_cache(); // S
		$this->db->select('pd_id, pd_name');
		$this->db->where('pd_soldout', '0');
		$this->db->stop_cache(); // E
		
		// 이전 상품
		$this->db->where('pd_id <', $id);
		//$this->_get_search_cache($sca, $sfl, $stx);
		$this->db->order_by('pd_id desc');
		$qry = $this->db->get($this->table, 1);
		$prev = $qry->row_array();
		
		// 다음 상품
		$this->db->where('pd_id >', $id);
		//$this->_get_search_cache($sca, $sfl, $stx);
		$this->db->order_by('pd_id asc');
		$qry = $this->db->get($this->table, 1);
		$next = $qry->row_array();
		
		$this->db->flush_cache();
		
		$result['prev'] = ($prev) ? $prev : FALSE;
		$result['next'] = ($next) ? $next : FALSE;

		return $result;
	}
	
	/*--- 장바구니 관련 ---*/
	function add_cart($idx, $option, $quantity) {
		if(!$idx) {
			return FALSE;
		}
		
		$this->db->select_max('cart_no');
		$this->db->where('cart_ssid', CARTID);
		$cart = $this->db->get($this->table_cart)->row_array();
		
		$mb_id = setValue('', $this->session->userdata('ss_mb_id'));
		
		$sql = array(
			'cart_ssid'		=> CARTID,
			'cart_no'		=> $cart['cart_no']+1,
			'cart_mb_id'	=> $mb_id,
			'cart_pd_id'	=> $idx,
			'cart_opt_id'	=> $option,
			'cart_quantity'	=> $quantity,
			'cart_ip'		=> $this->input->server('REMOTE_ADDR'),
			'cart_regdate'	=> TIME_YMDHIS
		);
		
		if(!$this->db->insert($this->table_cart, $sql)) {
			return FALSE;
		}
		
		return TRUE;
	}
	
	function del_cart($where) {
		if(!CARTID || !$where) {
			return FALSE;
		}
		
		$mb_id = setValue('', $this->session->userdata('ss_mb_id'));
		
		$this->db->where('((cart_ssid = "'. CARTID .'" OR (cart_mb_id <> "" AND cart_mb_id = "'. $mb_id .'")) AND cart_paydate = 0)');
		$this->db->where(implode(' OR ', $where));
		
		if(!$this->db->delete($this->table_cart)) {
			return FALSE;
		}
		
		return TRUE;
	}
	
	function mod_cart($data, $where='', $join='') {
		if(!CARTID || !$data) {
			return FALSE;
		}
		
		$mb_id = setValue('', $this->session->userdata('ss_mb_id'));
		
		$join = $join ? ' LEFT JOIN '. $join : '';
		$where = $where ? ' AND ('. $where .')' : '';
		$where = "(cart_ssid = '". CARTID ."' OR (cart_mb_id <> '' AND cart_mb_id = '". $mb_id ."')) AND cart_paydate = 0". $where;
		
		if(!$this->db->query('UPDATE '. $this->table_cart . $join .' SET '. $data .' WHERE '. $where)) {
			return FALSE;
		}
		
		return TRUE;
	}
	
	function list_cart($ssid='', $od_no='', $is_admin=FALSE) {
		$mb_id = setValue('', $this->session->userdata('ss_mb_id'));

		if($od_no) {
			$this->db->where('a.cart_od_no', $od_no);
		}
		else {
			$this->db->where('a.cart_paydate', '0');
		}
		
		$where_ssid = $ssid ? 'a.cart_ssid = "'. $ssid .'" OR' : '';
		
		if(!$is_admin) {
			$this->db->where('('. $where_ssid .' (a.cart_mb_id <> "" AND a.cart_mb_id = "'. $mb_id .'"))');
		}
		
		if(!$od_no) {
			$this->db->where('b.pd_hidden', '0');
			$this->db->where('b.pd_soldout', '0');
		}
		
		$this->db->join($this->table.' b', 'a.cart_pd_id = b.pd_id');
		
		$this->db->select('a.*, b.*');
		
		$this->db->order_by('a.cart_regdate', 'desc');
		
		$qry = $this->db->get($this->table_cart .' a');
		$result = $qry->result_array();
		
		foreach($result AS $key => $row) {
			if(!$result[$key]['option_data'] = $row['cart_opt_id'] ? $this->db->get_row($row['cart_opt_id'], $this->table_opt, 'pdo_name,pdo_price') : FALSE) {
				$result[$key]['option_data'] = array('pdo_name' => '', 'pdo_price' => 0);
				$row['cart_opt_id'] = '';
			}
		}
		
		return $result;
	}
	
	function count_cart($ssid='', $od_no='', $is_admin=FALSE) {
		$mb_id = setValue('', $this->session->userdata('ss_mb_id'));
		
		if($od_no) {
			$this->db->where('a.cart_od_no', $od_no);
		}

		$where_ssid = $ssid ? 'a.cart_ssid = "'. $ssid .'" OR' : '';
		
		if(!$is_admin) {
			$this->db->where('('. $where_ssid .' (a.cart_mb_id <> "" AND a.cart_mb_id = "'. $mb_id .'"))');
		}
		
		$this->db->where('b.pd_hidden', 0);
		$this->db->join($this->table.' b', 'a.cart_pd_id = b.pd_id');
		
		$this->db->select(' COUNT(a.cart_pd_id) as '.$this->db->protect_identifiers('cnt'));
		$qry = $this->db->get($this->table_cart .' a');
		$row = $qry->row();
		
		return $row->cnt;
	}
	
	
	/*--- 카테고리 관련 ---*/
	function list_result_cate() {
		$this->db->select('*');
		$this->db->order_by('ca_sort', 'ASC');
		$this->db->order_by('ca_pid', 'ASC');
				
		$qry = $this->db->get($this->table_cate);
		$result = $qry->result_array();

		return $result;
	}
	
	function row_cate($id, $fields='*', $is_pjoin=FALSE) {
		if (!$id) return FALSE;
		
		if($is_pjoin) {
			$this->db->join($this->table_cate .' b', 'a.ca_pid = b.ca_id', 'left');
			$fields = $fields .', b.ca_name AS ca_pname';
		}
		
		$this->db->select($fields);
		
		$this->db->where('a.ca_id = "'.$id.'"');
		$qry = $this->db->get($this->table_cate .' a');

		return $qry->row_array();
	}

	/*--- 주문 관련 ---*/
	function list_order($limit, $offset, $sch_date='') {
		$mb_id = setValue('', $this->session->userdata('ss_mb_id'));
		$od_no = setValue('', $this->session->userdata('ss_od_no'));
		
		if($mb_id) {
			$this->db->where('(b.od_mb_id <> "" AND b.od_mb_id = "'. $mb_id .'")');
		}
		else if($od_no) {
			$this->db->where('b.od_no', $od_no);
		}
		else {
			return FALSE;
		}
		
		if(isset($sch_date['min']) && isset($sch_date['max'])) {
			$this->db->where("DATE_FORMAT(b.od_regdate,'%Y-%m-%d') BETWEEN '".date('Y-m-d',$sch_date['min'])."' AND '".date('Y-m-d',$sch_date['max'])."'");
		}

		$this->db->join($this->table_dlv.' a', 'a.od_no = b.od_no', 'left');
		
		$this->db->select('a.dlv_no, a.dlv_deliverer, b.*');
		$this->db->order_by('b.od_regdate', 'desc');
		
		$qry = $this->db->get($this->table_ord .' b', $limit, $offset);
		$result = $qry->result_array();
		
		return $result;
	}
	
	function count_order() {
		$mb_id = setValue('', $this->session->userdata('ss_mb_id'));
		$od_no = setValue('', $this->session->userdata('ss_od_no'));
		
		if($mb_id) {
			$this->db->where('(od_mb_id <> "" AND od_mb_id = "'. $mb_id .'")');
		}
		else if($od_no) {
			$this->db->where('od_no', $od_no);
		}
		else {
			return FALSE;
		}
		
		$this->db->select('COUNT(od_no) AS '.$this->db->protect_identifiers('cnt'));
		$qry = $this->db->get_where($this->table_ord);
		$row = $qry->row();
		
		return $row->cnt;
	}
	
	function get_order($od_no, $fields='a.*, b.*') {
		if (!$od_no) return FALSE;

		$this->db->select($fields);
		$this->db->join($this->table_dlv.' a', 'a.od_no = b.od_no', 'left');
		$this->db->where('b.od_no', strtoupper($od_no));
		$row = $this->db->get($this->table_ord .' b')->row_array();
		
		return $row;
	}
}
?>
