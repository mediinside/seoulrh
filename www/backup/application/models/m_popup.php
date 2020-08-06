<?php
class M_popup extends CI_Model {
	var $type = array(0 => '일반 팝업', 1 => '레이어 팝업');
	var $table = 'ki_popup';
	
	function __construct() {
		parent::__construct();
	}

	function get($pu_id, $fields='*') {
		if (!$pu_id)
			return FALSE;
		
		return $this->db->select($fields)->get_where('ki_popup', array('pu_id' => $pu_id))->row_array();
	}

    function output($type=FALSE, $skin='popup/basic_skin') {
    	$popup = '';
    	
		if ($this->config->item('cf_use_popup')) {
			$this->db->select('*');
			$this->db->where('pu_hidden', 0);
			$this->db->where("pu_sdate <= ", TIME_YMDHIS);
			$this->db->where("(pu_edate >= '". TIME_YMDHIS ."' OR pu_edate = '0000-00-00 00:00:00')");
			
			if($type !== FALSE) {
				$this->db->where('pu_type', $type);
			}
			
			$result = $this->db->get($this->table)->result_array();
			
			foreach ($result as $i => $row) {
				$id = $row['pu_id'];
				$content = $row['pu_content'];
				
				if (!$this->input->cookie('popup'.$id)) {
					if ($row['pu_type'] == 1) {
						$popup .= "<div id='popup".$id."' class='layer_popup' style='width:".$row['pu_width']."px; height:".$row['pu_height']."px; top:".$row['pu_y']."px; left:".$row['pu_x']."px;'>".$this->load->view($skin, array('id'=>'popup'.$id,'content'=>$content), TRUE)."</div>\n";
					}
					else {
						$popup .= "<script type='text/javascript'>var popup".$id." = win_open('popup/".$id."', 'popup".$id."', 'left=".$row['pu_x']."px,top=".$row['pu_y']."px,width=".$row['pu_width']."px,height=".$row['pu_height']."px,scrollbars=0');</script>\n";
					}
				}
			}
		}

		return $popup;
	}
}
