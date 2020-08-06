<?php
class M_upload_files extends CI_Model {
	var $table = 'ki_upload_files';
	
	function __construct() {
		parent::__construct();
	}

	// 파일 Insert
	function file_insert($uf_table, $uf_id, $values) {
		if (!$values) return FALSE;
		
		$this->db->query('INSERT INTO '.$this->table.' ( uf_table, uf_id, uf_no, uf_editor, uf_source, uf_file, uf_download, uf_filesize, uf_width, uf_height, uf_type, uf_datetime ) VALUES '.$values);

		$cnt = $this->db->affected_rows();
		return $cnt;
	}
	
	// 파일 삭제 ($uf_nos가 없을 경우 해당 id 전체 삭제)
	function file_delete($uf_table, $uf_id, $uf_nos='', $except=false) {
		if (!$uf_nos && $except) return FALSE;
		if (!$uf_table || !$uf_id) return FALSE;
		
		$mime = array_fill(0, 4, 0);
		$dirs = $this->get_dir($uf_table);
		
		$where['uf_table'] = $uf_table;
		
		$this->db->start_cache();
		
		if(is_array($uf_nos)) {
			if($except)
				$this->db->where_not_in('uf_no', $uf_nos);
			else
				$this->db->where_in('uf_no', $uf_nos);
		}
		else if($uf_nos) {
			if($except)
				$this->db->where("uf_no != '$uf_nos'");
			else
				$this->db->where('uf_no', $uf_nos);
		}
		
		$where['uf_id'] = $uf_id;
		
		$this->db->where($where);
		$this->db->stop_cache();
		
		$qry = $this->db->get($this->table);
		if($rows = $qry->result_array()) {
			foreach($rows as $key => $val) {
				// 파일 삭제
				@unlink($dirs['data_path'].'/'.$val['uf_file']);
				// 썸네일 삭제
				@del_thumb($dirs['data_path'].'/thumb', $val['uf_file']);
			}
		}
		
		$this->db->delete($this->table);
		
		$cnt = $this->db->affected_rows();
		$this->db->flush_cache();

		if($cnt) {
			$this->file_count_update($uf_table, $uf_id);
		}
		
		return $cnt;
	}
	
	// 원글 첨부파일 카운트 업데이트
	function file_count_update($uf_table, $uf_id) {
		$is_board = $this->is_board($uf_table);
		
		if($is_board) {
			$wr_table = $this->is_board();
			$where = array('bid' => $uf_table, 'wr_id' => $uf_id);
		}
		else {
			$wr_table = $uf_table;

			$primary = $this->db->primary($uf_table);
			$where = array($primary => $uf_id);
		}

		$this->db->set('uf_count_image', $this->get_count($uf_table, $uf_id, 'uf_type <> 0'), FALSE);
		$this->db->set('uf_count_file', $this->get_count($uf_table, $uf_id, 'uf_type = 0'), FALSE);
		
		$this->db->update($wr_table, null, $where);
	}
	
	// 파일 카운트
	function get_count($uf_table, $uf_ids, $inWhere='') {
		$where['uf_table'] = $uf_table;
		
		if(is_array($uf_ids))	$this->db->where_in('uf_id', $uf_ids);
		else					$where['uf_id'] = $uf_ids;
		if($inWhere) $this->db->where($inWhere);
		$this->db->where($where);
		$this->db->select(' COUNT(*) as cnt ');
		$qry = $this->db->get($this->table);
		$row = $qry->row_array();
		
		return $row['cnt'];
	}
	
	
	// 게시물 파일정보
	function get_files($uf_table, $uf_id, $field='*', $where='') {
		$where['uf_table'] = $uf_table;
		
		if(is_array($uf_id))	$this->db->where_in('uf_id', $uf_id);
		else					$where['uf_id'] = $uf_id;
		
        $this->db->select($field);
        $this->db->order_by('uf_no');
		$this->db->where($where);
		$qry = $this->db->get($this->table);
		
		return $qry->result_array();
	}
	
	// 개별 파일정보
	function get_file($uf_table, $uf_id, $uf_no) {
		$this->db->select('uf_source, uf_file, uf_type');
		$qry = $this->db->get_where($this->table, array(
			'uf_table' => $uf_table,
			'uf_id' => $uf_id,
			'uf_no' => $uf_no
		));
		return $qry->row_array();
	}
	
	// 다운로드 카운트++
	function file_down_update($uf_table, $uf_id, $uf_no) {
		$this->db->set('uf_download', 'uf_download + 1', FALSE);

		$this->db->update($this->table, null, array(
			'uf_table' => $uf_table,
			'uf_id' => $uf_id,
			'uf_no' => $uf_no
		));
	}
	
	// Max no
	function get_maxNo($table, $uf_id) {
        $this->db->select('uf_no');
        $this->db->order_by('uf_no desc');
		$this->db->where(array('uf_table'=>$table, 'uf_id'=>$uf_id));
		$qry = $this->db->get($this->table, 1);
		$res = $qry->row_array();
		
		return isset($res['uf_no']) ? $res['uf_no'] : 0;
	}
	
	// 게시판 테이블인지 확인, 매개변수가 없으면 게시판 실제 테이블명 반환
	function is_board($table='') {
		$this->load->model('M_board');
		if($table)
			return $this->M_board->db->where(array('bid' => $table))->count_all_results($this->M_board->table);
		else
			return $this->M_board->table;
	}
	
	// 폼 파일 업로드
	function form_upload($uf_table, $id, $file) {
		$val_img = '';
		
		$dirs = $this->get_dir($uf_table);
		$no = $this->get_maxNo($uf_table, $id);
		
		$in_nos = array();
		if(!is_array($file['tmp_name'])) {
			$file = array(
				'name' => array($file['name']),
				'tmp_name' => array($file['tmp_name']),
				'type' => array($file['type']),
				'error' => array($file['error']),
				'size' => array($file['size'])
			);
		}
		
		foreach($file['tmp_name'] as $key => $val) {
			if($val) {
				$byte = @filesize($val);
				$size = @getimagesize($val);
				
				$tfile = array('name'=>$file['name'][$key],'tmp_name'=>$val,'type'=>$file['type'][$key],'error'=>$file['error'][$key],'size'=>$file['size'][$key]);
				
				$filename = fileupload($tfile, $dirs['data_path']);
				$val_img .= "('".$uf_table."','".$id."','".++$no."','0','".$tfile['name']."','".$filename."','0','".$byte."','".$size[0]."','".$size[1]."','".$size[2]."','".TIME_YMDHIS."'),";
				
				$in_nos[$no] = $val;
			}
		}
		
		if ($val_img = substr($val_img, 0, -1)) {
			$this->file_insert($uf_table, $id, $val_img, TRUE);
			$this->file_count_update($uf_table, $id);
		}
		return $in_nos;
	}
	
	// path & url 등 경로
	function get_dir($uf_table) {
		if($this->is_board($uf_table)) {
			$dirs['file_dir'] = '/file/'.$uf_table;
			$dirs['down_dir'] = '/board/'.$uf_table;
		}
		else {
			$dirs['file_dir'] = '/'.preg_replace('/^ki_/','',$uf_table);
			$dirs['down_dir'] = '/inc';
		}
		
		$dirs['base_url'] = $this->config->item('base_url');
		
		$dirs['temp_path'] = DATA_PATH.'/temp';
		$dirs['data_path'] = DATA_PATH.$dirs['file_dir'];
		
		$dirs['temp_url'] = $dirs['base_url'].DATA_DIR.'/temp';
		$dirs['data_url'] = $dirs['base_url'].DATA_DIR.$dirs['file_dir'];
		
		return $dirs;
	}
}
?>
