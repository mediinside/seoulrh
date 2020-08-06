<?php
class Popup extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('M_popup');
	}

	function _remap($pu_id) {
		$pu = $this->M_popup->get($pu_id,'pu_id, pu_name, pu_content');
		if (!isset($pu['pu_id']))
			alert_close('등록된 팝업이 아닙니다.');
		
		if (SU_ADMIN && $pu['pu_content'] == '')
			alert_close('팝업 내용이 없습니다.');

		$vars = array(
			'_TITLE_'		=> $pu['pu_name'],
			'_BODY_'		=> 'popup/layout01',
				
			'id'			=> 'popup'.$pu_id,
			'content'		=> $pu['pu_content']
		);
		
		$this->load->view(LAYOUT_PATH.'/layout_blank', $vars);
	}
}
?>
