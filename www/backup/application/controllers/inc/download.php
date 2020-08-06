<?php if ( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class Download extends Widget {
	public $seg;     // uri 정보
	function __construct() {
		parent::__construct();
		
		$this->load->helper('download');
	}
	
	function index() {
		$board    =& $this->board;
		$member   =& $this->member;
		
		$id = $this->seg->get_seg('wr_id');
		$no = $this->seg->get_seg('no');
		
		// 쿠키에 저장된 ID값과 넘어온 ID값을 비교하여 같지 않을 경우 오류 발생
		// 다른곳에서 링크 거는것을 방지하기 위한 코드
		if (!$this->session->userdata('ss_view_'.BO_TABLE.'_'.$id))
			alert('잘못된 접근입니다.');
		
		if ($member['mb_level'] < $board['bo_download_level']) {
			$alert_msg = '다운로드 권한이 없습니다.';
			if (IS_MEMBER)
				alert($alert_msg);
			else
				alert($alert_msg."\\n\\n회원이라면 로그인 후 이용하세요.", 'member/login/qry/'.url_encode('board/'.BO_TABLE.'/view/wr_id/'.$id));
		}
		
		// 유효성
		$file = $this->M_upload_files->get_file(BO_DB, $id, $no);
		if (!isset($file['uf_file']))
		    alert_close('파일 정보가 존재하지 않습니다.');
		
		// 다운수 증가
		$ss_name = 'ss_down_'.BO_DB.'_'.$id.'_'.$no;
		if (!$this->session->userdata($ss_name)) {
		    // 다운로드 카운트 증가
		    $this->M_upload_files->file_down_update(BO_DB, $id, $no);
			$this->session->set_userdata($ss_name, TRUE);
		}

		$fileDir = $this->M_upload_files->get_dir(BO_DB);
		$filepath = addslashes($fileDir['data_path'].'/'.$file['uf_file']);
		
		if (file_exists($filepath)) {
			if (preg_match("/^utf/i", $this->config->item('charset')))
			    $original = urlencode($file['uf_source']);
			else
			    $original = $file['uf_source'];
			
			if (!force_download($original, file_get_contents($filepath)))
				 alert('파일을 찾을 수 없습니다.');
		}
		else
		    alert('파일을 찾을 수 없습니다.');
	}
}
?>
