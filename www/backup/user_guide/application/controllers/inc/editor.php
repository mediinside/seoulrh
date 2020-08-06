<?php
class Editor extends CI_Controller {
	public $seg;     // uri 정보
	
	function __construct() {
		parent::__construct();
		define('CSS_SKIN', 'editor_popup,swfupload');
	}
	
    function _remap() {
		$this->load->model('M_upload_files');
		
		$type = $this->uri->segment(3);
		$bid = $this->uri->segment(4);
		
        $member = unserialize(MEMBER);
		
		if($this->M_upload_files->is_board($bid)) {
			$board = $this->M_basic->get_board($bid, 'bo_upload_level,bo_upload_size,bo_upload_ext', TRUE);
			if ($member['mb_level'] < $board['bo_upload_level'])
				alert_close('업로드 권한이 없습니다.');
			
			$upload_size = $board['bo_upload_size'];
			$upload_ext = array();
			foreach(explode(',', $board['bo_upload_ext']) AS $ext) {
				$upload_ext[] = '*.'. trim($ext);
			}
		}
		else $upload_size = EDITOR_UPLOAD_SIZE * 1024;

		$upload_ext = (isset($upload_ext) && $upload_ext) ? implode(';', $upload_ext) : '*.zip';
		switch ($type) {
			case 'image':
				$title = '이미지';
				$upload_ext = '*.jpg;*.gif;*.png';
				break;
			case 'file':
				$title = '파일';
				break;
			case 'media': $title = '멀티미디어'; break;
			default: alert_close('잘못된 접근입니다.'); break;
		}
		
        $vars = array(
			'_TITLE_'		=> $title.' 첨부',
			'_BODY_'		=> 'editor/editor_'.$type,
			'_CSS_'			=> array('editor_popup','swfupload'),
        	
        	'upload_size'	=> $upload_size,
        	'upload_ext'	=> $upload_ext
        );
        
        $this->load->view('layout/layout_blank', $vars);
	}
}
?>
