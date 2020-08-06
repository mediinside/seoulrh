<?php
class Confirm extends CI_Controller {
	var $conf;
	
	function __construct() {
		parent::__construct();
		
		$this->load->model('M_mb_infor');

		$this->conf = $this->M_mb_infor->getConfig('mcf_skin, mcf_confirm_param');
	}

	function qry($aurl) {
		if (!IS_MEMBER)
			goto_url('member/login');
		
		$member = unserialize(MEMBER);

		$this->session->unset_userdata('ss_tmp_password');
		
		$vars = array_merge( array(
			'_TITLE_'		=> '회원 비밀번호 확인',
			'_BODY_'		=> 'member/'. $this->conf['mcf_skin'] .'/confirm',
			'_CSS_'			=> array('member'),
			'_JS_'			=> array('capslock', 'jvalidate', 'jvalid_ext'),

			'token'			=> get_token(),
			
			'IMG_PATH'		=> IMG_DIR.'/member/'.$this->conf['mcf_skin'],
			
			'mb_id'			=> $member['mb_id'],
			'action'		=> RT_PATH.'/'.str_replace('.', '/', $aurl)
		), $this->conf['confirm_param']);
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}
}
?>
