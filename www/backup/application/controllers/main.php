<?php
class Main extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('M_content', 'M_popup', 'M_banner'));
	}

	function index() {
		$content = $this->M_content->get_content('index');	// 인덱츠 컨텐츠가 메인
		$member = unserialize(MEMBER);
		
		if(!$content) {
			show_404();
		}
		else if($content['ct_level'] >= 2 && !IS_MEMBER) {
			goto_url('/member/login');
		}
		else if($content['ct_level'] >= 2 && $content['ct_level'] > $member['mb_level']) {
			alert($content['ct_level'] .' 레벨 이상의 회원만 접근하실 수 있습니다.');
		}

		// PC/모바일 구분
		$HTML_PATH = IS_MOBILE ? HTML_PATH . MOBILE_DIR : HTML_PATH . PC_DIR;
		$LAYOUT = IS_MOBILE ? $content['ct_layout_m'] : $content['ct_layout'];

		$layout = $this->M_layout->row($LAYOUT);
		
		// 관리자 컨텐츠 설정 데이터
		$dbVars = $this->M_dbvars->get_data('content', $content['ct_id']);
		$this->load->vars( getContent($dbVars) );
		
		// 시스템 데이터
		$vars = array(
			'_TITLE_'		=> $this->config->item('cf_title'),
			'_BODY_'		=> 'main/content',
			
			'_CONTAINER_'	=> $HTML_PATH .'/'. $content['ct_filename'],
			
			'popup'			=> $this->M_popup->output()
		);

		$this->load->view(LAYOUT_PATH.'/'.$layout['ly_file'], array_merge($vars, param_decode($content['ct_parameter'])));
	}
}
?>
