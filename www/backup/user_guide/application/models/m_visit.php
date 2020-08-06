<?php
class M_visit extends CI_Model {
	var $table = 'ki_visit';
	var $table_cnt = 'ki_visit_cnt';
	
	function __construct() {
		parent::__construct();
	}
	
	function chkIn() {
		$ip_address		= $this->input->server('REMOTE_ADDR');
		$php_self		= str_replace('/index.php', '', $this->input->server('PHP_SELF'));
		$http_referer	= $this->input->server('HTTP_REFERER');
		$agent			= $this->input->server('HTTP_USER_AGENT');
		$hour			= date('H', strtotime(TIME_HIS));
		$browser		= get_brow($agent);
		$os				= get_os($agent);
		
		$sql = "INSERT INTO ". $this->table ."
			(vs_id, vs_ip, vs_agent, vs_url, vs_referer, vs_time, vs_regdate) VALUES
			('', '$ip_address', '$agent', '$php_self', '$http_referer', '". TIME_HIS ."', '". TIME_YMD ."') ";

		if($this->db->simple_query($sql)) {
			$sql = "INSERT INTO ".$this->table_cnt." (
					vsc_hour,
					vsc_browser,
					vsc_os,
					vsc_count,
					vsc_regdate )
				VALUES ('$hour', '$browser', '$os', '1', '".TIME_YMD."')
				ON DUPLICATE KEY UPDATE
					vsc_count = vsc_count + 1
			";
			$this->db->query($sql);
		}
		
		return TRUE;
	}
}
