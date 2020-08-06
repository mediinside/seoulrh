<?php
class Confirm extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->config('cf_register');
	}

	function qry($aurl) {
		if (!IS_MEMBER)
			goto_url('member/login');

		$member = unserialize(MEMBER);

		$this->session->unset_userdata('ss_tmp_password');
		
		$vars = array_merge( array(
			'_TITLE_'		=> '회원 비밀번호 확인',
			'_BODY_'		=> 'member/confirm',
			'_CSS_'			=> array('member'),
			
			'img_path'		=> IMG_DIR.'/member',
			'mb_id'			=> $member['mb_id'],
			'action'		=> RT_PATH.'/'.str_replace('.', '/', $aurl),
			'token'			=> get_token()
		), $this->config->item('cf_page_confirm') );
		
		$this->load->view(LAYOUT_PATH.'/layout_sub', $vars);
	}
}
?>
