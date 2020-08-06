<?php
class M_postlink extends CI_Model {
	var $table = 'ki_postlink';
	
	function __construct() {
		parent::__construct();
	}
	
	function list_result($bid, $wr_ids, $qstr='') {
		$this->db->where('pl_ref_table', $bid);
		$this->db->where_in('pl_ref_id', $wr_ids);
		$qry = $this->db->get($this->table);
		$result = $qry->result_array();

		$return = $result;
		foreach($result AS $key => $row) {
			$write = $this->M_basic->get_write($row['pl_link_table'], $row['pl_link_id'], 'wr_subject');
			if($write) {
				$return[$key]['wr_subject'] = $write['wr_subject'];
				$return[$key]['href'] = RT_PATH.'/board/'.$row['pl_link_table'].'/view/wr_id/'.$row['pl_link_id'].$qstr;
			}
		}
		
		return $return;
	}
	
	function list_merge($list, $postlink) {
		foreach($list AS $key => $list_row) {
			foreach($postlink AS $postlink_row) {
				if($list_row['bid'] == $postlink_row['pl_ref_table'] && $list_row['wr_id'] == $postlink_row['pl_ref_id']) {
					$list[$key]['postlink'][] = $postlink_row;
				}
			}
		}
		return $list;
	}
	
	function post_copy($from_table, $from_ids, $to_table, $to_ids) {
		if(!is_array($from_ids)) {
			$from_ids = array($from_ids);
		}
		if(!is_array($to_ids)) {
			$to_ids = array($to_ids);
		}
		
		$sql = "INSERT INTO ". $this->table ." (
					pl_ref_table,
					pl_ref_id,
					pl_link_table,
					pl_link_id,
					pl_regdate ) VALUES ";
		
		$this->db->where('pl_ref_table', $from_table);
		$this->db->where_in('pl_ref_id', $from_ids);
		$ref_result = $this->db->get($this->table)->result_array();
		
		foreach($ref_result AS $row) {
			foreach($from_ids AS $key => $from_id) {
				if($row['pl_ref_id'] == $from_id) {
					$to_id = $to_ids[count($to_ids)-$key-1];
					$update_values[] = " ('$to_table', '$to_id', '".$row['pl_link_table']."', '".$row['pl_link_id']."', '". TIME_YMDHIS ."') ";
				}
			}
		}
		
		if(isset($update_values)) {
			$this->db->query($sql.implode(',', $update_values));
		}
	}
	
	function post_delete($table, $ids) {
		$this->db->where('pl_ref_table', $table)->where_in('pl_ref_id', $ids)->delete($this->table);
		$this->db->where('pl_link_table', $table)->where_in('pl_link_id', $ids)->delete($this->table);
	}
	
	function post_move($from_table, $from_ids, $to_table, $to_ids) {
		if(!is_array($from_ids)) {
			$from_ids = array($from_ids);
		}
		if(!is_array($to_ids)) {
			$to_ids = array($to_ids);
		}
		if(count($from_ids) != count($to_ids)) {
			return FALSE;
		}
		
		foreach($from_ids AS $key => $from_id) {
			$this->db->update($this->table, array('pl_link_table' => $to_table, 'pl_link_id' => $to_ids[$key]), array('pl_link_table' => $from_table, 'pl_link_id' => $from_id));
		}
	}
	
	/*
	// 게시물 이동시 한꺼번에 여러 게시판으로 이동이 가능하므로 move를 사용하지 않고 copy후 delete함
	function post_move($from_table, $from_ids, $to_table, $to_ids) {
		foreach($from_ids AS $key => $from_id) {
			$where = "(pl_ref_table = '$from_table' AND pl_ref_id = '$from_id') OR (pl_link_table = '$from_table' AND pl_link_id = '$from_id')";
			$result = $this->db->where($where)->get($this->table)->result_array();
			foreach($result AS $row) {
			$update_fld = ($row['pl_ref_id'] == $from_id) ? 'ref' : 'link';
			$to_id = $to_ids[$key];
	
			$this->db->update($this->table, array('pl_'.$update_fld.'_table' => $to_table, 'pl_'.$update_fld.'_id' => $to_id), array('pl_id' => $row['pl_id']));
			}
		}
	}
	*/
	
	function update($table='', $idx='', $data_arr='') {
		if(!$table || !$idx) return FALSE;
		
		$sql = "INSERT INTO ". $this->table ." (
					pl_ref_table,
					pl_ref_id,
					pl_link_table,
					pl_link_id,
					pl_regdate ) VALUES ";
		
		// 기존 데이터
		$old_data = $this->db->where('pl_ref_table',$table)->where('pl_ref_id',$idx)->get($this->table)->result_array();
		$old = array();
		foreach($old_data AS $row) {
			$old[$row['pl_link_table'] .'|'. $row['pl_link_id']] = $row['pl_id'];
		}
		
		if(is_array($data_arr)) {
			foreach($data_arr AS $json_data) {
				$data = json_decode($json_data);
				
				// 기존 데이터에 없고 새로 추가되지도 않았으면 Insert 추가.
				if((!isset($old[$data->table .'|'. $data->idx]) && !isset($new[$data->table .'|'. $data->idx])) && ($data->table != $table || $data->idx != $idx)) {
					$new[$data->table .'|'. $data->idx] = TRUE;
					$update_values[] = " ('$table', '$idx', '".$data->table."', '".$data->idx."', '". TIME_YMDHIS ."') ";
				}
				else {
					$old[$data->table .'|'. $data->idx] = FALSE;
				}
			}
			
			if(isset($update_values)) {
				$this->db->query($sql.implode(',', $update_values));
			}
		}
		
		// 제거된 정보 삭제
		if($del = array_values($old)) {
			$this->db->where_in('pl_id', $old);
			$this->db->delete($this->table);
		}
		
		return TRUE;
	}
}
?>
