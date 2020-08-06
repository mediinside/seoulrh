<?php
class M_a_shop extends CI_Model {
	var $table = 'ki_shop';
	var $table_opt = 'ki_shop_opt';
	var $table_cfg = 'ki_shop_cfg';
	var $table_cate = 'ki_shop_cate';
	var $table_cart = 'ki_shop_cart';
	var $table_ord = 'ki_shop_ord';
	var $table_dlv = 'ki_shop_dlv';
	//var $pg_co = array('agspay' => '올더게이트', 'ini' => '이니시스', 'kcp' => 'KCP');
	var $pg_co = array('agspay' => '올더게이트');
	
	function __construct() {
		parent::__construct();
	}
	
	function setConfig($sql) {
		if(!$sql)
			return false;
		
		foreach($sql AS $scf_id => $scf_value) {
			$scf_value = is_array($scf_value) ? $scf_value : array($scf_value,'');
			$query = "
				INSERT INTO ". $this->table_cfg ." (scf_id, scf_value, scf_extend, scf_mdydate)
				VALUES
					('$scf_id', '" . $scf_value[0] ."', '" . addslashes($scf_value[1]) ."', '". TIME_YMDHIS ."')
				ON DUPLICATE KEY UPDATE
					scf_value =			'". $scf_value[0] ."',
					scf_extend =		'". addslashes($scf_value[1]) ."',
					scf_mdydate =		'". TIME_YMDHIS ."'
			";
			
			if(!$this->db->query($query)) {
				return FALSE;
			}
		}
		
		return true;
	}
	
	function list_result($sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		
		if ($stx) {
			$this->db->start_cache();
			$this->db->like($sfl, $stx, 'center');
		}
		
		$this->db->stop_cache();
		
		$this->db->select('*');
		$this->db->order_by($sst, $sod);

		$qry = $this->db->get($this->table, $limit, $offset);
		$result['qry'] = $qry->result_array();
		$result['total_cnt'] = $this->db->count_all_results($this->table);
		
		$this->db->flush_cache();

		return $result;
	}
	
	function record($w, $sql) {
		if(!$sql)
			return false;
		
		if ($w == '') {
			$sql['pd_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table, $sql, array('pd_id' => $this->input->post('pd_id')));
			return $this->input->post('pd_id');
		}
	}
	
	function set_options($id, $options) {		// $options = array('id'=>'', 'name'=>'', 'calculate'=>'', 'price'=>'');
		if(!$id)
			return false;
		
		$primary = $this->db->primary($this->table_opt);
		
		// 넘어오지 않은 기존 id는 삭제
		$this->db->where('pd_id', $id);
		if($options['id']) {
			$this->db->where_not_in($primary, $options['id']);
		}
		$this->db->delete($this->table_opt);
		
		$sql = "INSERT INTO ". $this->table_opt.
			"(pd_id, pdo_name, pdo_price, pdo_regdate) VALUES ";
		
		$opt_sql = $batch = array();
		if($options) {
			foreach($options['name'] AS $key => $name) {
				$price = (int)($options['calculate'][$key] . $options['price'][$key]);
				
				if(trim($name) && $options['id'][$key]) {
					$batch[$key][$primary]			= $options['id'][$key];
					$batch[$key]['pdo_name']		= $name;
					$batch[$key]['pdo_price']		= $price;
				}
				else if(trim($name)) {
					$opt_sql[] =  "('$id', '$name', '". $price ."', '". TIME_YMDHIS ."')";					
				}
			}
		}
		
		// Insert
		if($opt_sql) {
			$this->db->query($sql . implode(',', $opt_sql));
		}
		
		// Update
		if($batch) {
			$this->db->update_batch($this->table_opt, $batch, $primary);
		}
	}
	
	function list_update($ids, $data) {
		$primary = $this->db->primary($this->table);
		
		$batch = array();		
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			$batch[$key]['pd_mdydate']	= TIME_YMDHIS;
			foreach ($data as $fld => $val)
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
		}
		
		$this->db->update_batch($this->table, $batch, $primary);
	}
	
	function delete($ids) {
		if(is_array($ids))
			$this->db->where_in('pd_id', $ids);
		else
			$this->db->where('pd_id', $ids);
		
		return $this->db->delete($this->table);
	}
	
	
	/*--- 카테고리 관련 ---*/
	function record_cate($w, $sql) {
		if(!$sql)
			return false;
	
		if ($w == '') {
			$sql['ca_regdate'] = TIME_YMDHIS;
			$this->db->insert($this->table_cate, $sql);
			return $this->db->insert_id();
		}
		else {
			$this->db->update($this->table_cate, $sql, array('ca_id' => $this->input->post('ca_id')));
			return $this->input->post('ca_id');
		}
	}
	
	function delete_cate($id) {
		if($this->db->delete($this->table_cate, array('ca_pid' => $id)))
			return $this->db->delete($this->table_cate, array('ca_id' => $id));
	}
	

	function list_order($sst, $sod, $sfl, $stx, $limit, $offset, $sch_date='') {
		$this->db->start_cache();
		
		if(isset($sch_date['min']) && isset($sch_date['max'])) {
			$this->db->where("DATE_FORMAT(od_regdate,'%Y-%m-%d') BETWEEN '".date('Y-m-d',$sch_date['min'])."' AND '".date('Y-m-d',$sch_date['max'])."'");
		}

		if ($stx) {
			$this->db->like($sfl, $stx, 'center');
		}
		
		$this->db->select('*');
		$this->db->order_by($sst, $sod);
	
		$qry = $this->db->get($this->table_ord, $limit, $offset);
		$result['qry'] = $qry->result_array();
		$result['total_cnt'] = $this->db->count_all_results($this->table_ord);
		
		$this->db->flush_cache();
		
		return $result;
	}
	
	function update_order($sql) {
		$primary = $this->db->primary($this->table_ord);
		$id = $this->input->post($primary);
		$this->db->update($this->table_ord, $sql, array($primary => $id));
		
		return $id;
	}
	
	/* 배송 관련 */
	function list_delivery($sst, $sod, $sfl, $stx, $limit, $offset, $status='') {
		$this->db->start_cache();
		
		if($stx) {
			$this->db->start_cache();
			$this->db->like($sfl, $stx, 'center');
		}
		
		if($status) {
			$this->db->where_in('od_status', $status);
		}
		
		$this->db->select('a.*, b.*');
		$this->db->order_by($sst, $sod);
		$this->db->stop_cache();
		$this->db->join($this->table_dlv.' a', 'a.od_no = b.od_no', 'left');
		
		$qry = $this->db->get($this->table_ord .' b', $limit, $offset);

		
		$result['qry'] = $qry->result_array();
		$result['total_cnt'] = $this->db->count_all_results($this->table_ord);

		$this->db->flush_cache();
		
		return $result;
	}
	
	function update_delivery($sql) {
		$query = "
				INSERT INTO ". $this->table_dlv ." (od_no, dlv_no, dlv_deliverer, dlv_payPrice, dlv_mdydate, dlv_regdate)
				VALUES
					('". $sql['od_no'] ."', '". $sql['dlv_no'] ."', '". $sql['dlv_deliverer'] ."', '". $sql['dlv_payPrice'] ."', '" . TIME_YMDHIS ."', '". TIME_YMDHIS ."')
				ON DUPLICATE KEY UPDATE
					od_no =				'". $sql['od_no'] ."',
					dlv_no =			'". $sql['dlv_no'] ."',
					dlv_deliverer =		'". $sql['dlv_deliverer'] ."',
					dlv_payPrice =		'". $sql['dlv_payPrice'] ."',
					dlv_mdydate =		'". TIME_YMDHIS ."',
					dlv_regdate =		'". TIME_YMDHIS ."'
			";
			
		if(!$id = $this->db->query($query)) {
			return FALSE;
		}
		
		return $id;
	}
	
	function list_update_dlv($ids, $data) {
		$primary = $this->db->primary($this->table_ord);
		
		$batch = array();
		foreach ($ids as $key => $id) {
			$batch[$key][$primary] = $id;
			foreach ($data as $fld => $val)
				$batch[$key][$fld] = isset($val[$id]) ? $val[$id] : '';
		}
	
		$this->db->update_batch($this->table_ord, $batch, $primary);
	}
	
	/* 오래된 장바구니 정리 */
	function cart_clear($day=30) {
		$this->db->where('cart_paydate', 0);
		$this->db->where('cart_regdate <', date('Y-m-d H:i:s', strtotime("-$day day")));
		$this->db->delete($this->table_cart);
		
		$data = array('scf_value' => TIME_YMD);
		return $this->M_a_shop->setConfig($data);
	}
}
?>
