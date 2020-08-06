<?php
class M_a_board extends CI_Model {
	var $table = 'ki_board';
	
	function __construct() {
		parent::__construct();
	}

	function list_result($sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				case 'bid' :
					$this->db->like($sfl, $stx, 'after');
				break;
				case 'gr_id' :
					$this->db->where($sfl, $stx);
				break;
				default :
					$this->db->like($sfl, $stx, 'both');
				break;
			}
		}
		$this->db->stop_cache();

		$result['total_cnt'] = $this->db->count_all_results($this->table);

		$this->db->select('bid,bo_subject,bo_layout,gr_id,bo_skin,bo_use_search,bo_order_search');
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get($this->table, $limit, $offset);
		$result['qry'] = $qry->result_array();

		$this->db->flush_cache();

		return $result;
	}

	function is_group() {
		$row = $this->db->count_all_results('ki_board_group');
		return ($row < 1) ? FALSE : TRUE;
	}

	function insert() {
		$sql = $this->bo_sql_array();
		$sql['bid'] = $this->input->post('bid');
		
		$this->db->insert($this->table, $sql);
	}

	function update($is_notice='', $sql='') {
		$bid = $this->input->post('bid');
		
		if(!$sql) {
			$sql = $this->bo_sql_array();
		}

		$this->db->where(array('bid' => $bid, 'wr_is_comment' => 0));
		$sql['bo_count_write'] = $this->db->count_all_results('ki_write');

		$this->db->where(array('bid' => $bid, 'wr_is_comment' => 1));
		$sql['bo_count_comment'] = $this->db->count_all_results('ki_write');

		// 공지사항에는 등록되어 있지만 실제 존재하지 않는 글 아이디는 삭제합니다.
		if ($is_notice) {
			$notice = explode(',', $is_notice);
			$this->db->select('wr_id');
			$this->db->where('bid', $this->input->post('bid'));
			$this->db->where_in('wr_id', $notice);
			$query = $this->db->get('ki_write');
			$result = $query->result_array();

			$bo_notice = array();
			foreach($result as $row) {
				$bo_notice[] = $row['wr_id'];
			}
			
			$sql['bo_notice'] = implode(',', $bo_notice);
		}

		$this->db->update($this->table, $sql, array('bid' => $this->input->post('bid')));
	}

	function group_update() {
		$sql = array();
		$chk = $this->input->post('chk');
		foreach($chk as $key => $val) {
			$sql[$key] = $this->input->post($key);
		}

		$this->db->update($this->table, $sql, array('gr_id' => $this->input->post('gr_id')));
	}

	// 글수 조정
	function proc_count() {
		$this->db->query("update ki_write a join (select bid, wr_parent, count(wr_is_comment)-1 as cnt from ki_write where bid = '".$this->input->post('bid')."' group by wr_parent) b on a.bid = b.bid and a.wr_id = b.wr_parent set a.wr_comment = b.cnt");
	}
	
	function get_layout_cnt($ly_id='') {
		if($ly_id) {
			$this->db->where('bo_layout', $ly_id);
		}
		
		return $this->db->select('SUM("bo_layout") AS cnt')->get($this->table)->result_array();
	}
	
	function list_update($bid, $bo_subject, $gr_id, $bo_layout, $bo_skin, $bo_use_search, $bo_order_search) {
		$this->db->update($this->table, array(
			'bo_subject'		=> $bo_subject,
			'gr_id'				=> $gr_id,
			'bo_layout'			=> $bo_layout,
			'bo_skin'			=> $bo_skin,
			'bo_use_search'		=> $bo_use_search,
			'bo_order_search'	=> $bo_order_search
		), array('bid' => $bid));
	}

	function delete($bids, $ca_types) {
		$this->db->where_in('bid', $bids);
		$this->db->delete(array($this->table, 'ki_write'));
		
		$this->db->where_in('uf_table', $bids);
		$this->db->delete('ki_upload_files');
		
		$this->db->where_in('ca_type', $ca_types);
		$this->db->delete('ki_category');
	}
	
	// Insert, Update 공통 항목 데이터
	function bo_sql_array() {
		$sql = array(
			'gr_id'             => $this->input->post('gr_id'),
			'bo_db'             => $this->input->post('bo_db'),
			'bo_subject'        => $this->input->post('bo_subject'),
			'bo_admin'          => $this->input->post('bo_admin'),
			'bo_layout'         => $this->input->post('bo_layout'),
			'bo_list_level'     => $this->input->post('bo_list_level'),
			'bo_read_level'     => $this->input->post('bo_read_level'),
			'bo_write_level'    => $this->input->post('bo_write_level'),
			'bo_reply_level'    => $this->input->post('bo_reply_level'),
			'bo_comment_level'  => $this->input->post('bo_comment_level'),
			'bo_upload_level'   => $this->input->post('bo_upload_level'),
			'bo_download_level' => $this->input->post('bo_download_level'),
			'bo_count_modify'   => $this->input->post('bo_count_modify'),
			'bo_count_delete'   => $this->input->post('bo_count_delete'),
			'bo_use_captcha'    => $this->input->post('bo_use_captcha'),
			'bo_use_rss'        => $this->input->post('bo_use_rss'),
			'bo_use_sns'        => $this->input->post('bo_use_sns'),
			'bo_use_category'   => $this->input->post('bo_use_category'),
			'bo_use_comment'    => $this->input->post('bo_use_comment'),
			'bo_use_tag'        => $this->input->post('bo_use_tag'),
			'bo_use_upload'     => $this->input->post('bo_use_upload'),
			'bo_use_sideview'   => $this->input->post('bo_use_sideview'),
			'bo_use_secret'     => $this->input->post('bo_use_secret'),
			'bo_use_editor'		=> $this->input->post('bo_use_editor'),
			'bo_use_edt_img'	=> $this->input->post('bo_use_edt_img'),
			'bo_use_edt_file'	=> $this->input->post('bo_use_edt_file'),
			'bo_use_edt_ocon'	=> $this->input->post('bo_use_edt_ocon'),
			'bo_use_name'       => $this->input->post('bo_use_name'),
			'bo_use_ip_view'    => $this->input->post('bo_use_ip_view'),
			'bo_use_list_view'  => $this->input->post('bo_use_list_view'),
			'bo_use_email'      => $this->input->post('bo_use_email'),
			'bo_use_postlink'   => $this->input->post('bo_use_postlink'),
			'bo_use_board_sel'  => $this->input->post('bo_use_board_sel'),
			'bo_subject_len'    => $this->input->post('bo_subject_len'),
			'bo_page_rows'      => $this->input->post('bo_page_rows'),
			'bo_new'            => $this->input->post('bo_new'),
			'bo_hot'            => $this->input->post('bo_hot'),
			'bo_image_width'    => $this->input->post('bo_image_width'),
			'bo_skin'           => $this->input->post('bo_skin'),
			'bo_mail_skin'      => $this->input->post('bo_mail_skin'),
			'bo_reply_order'    => $this->input->post('bo_reply_order'),
			'bo_sort_field'     => $this->input->post('bo_sort_field'),
			'bo_where'          => $this->input->post('bo_where'),
			'bo_upload_ext'     => preg_replace('/ /', '', $this->input->post('bo_upload_ext')),
			'bo_upload_size'    => $this->input->post('bo_upload_size'),
			'bo_title_img'      => $this->input->post('bo_title_img'),
			'bo_show_gr'		=> $this->input->post('bo_show_gr'),
			'bo_top_html'		=> $this->input->post('bo_top_html'),
			'bo_bottom_html'	=> $this->input->post('bo_bottom_html'),
			'bo_insert_content' => $this->input->post('bo_insert_content'),
			'bo_use_search'     => $this->input->post('bo_use_search'),
			'bo_order_search'   => $this->input->post('bo_order_search'),
			'bo_use_extra'		=> $this->input->post('bo_use_extra'),
			'bo_parameter'		=> param_encode($this->input->post('bo_parameter'))
		);
		
		return $sql;
	}
}
?>
