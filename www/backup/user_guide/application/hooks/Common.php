<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class _Common {
	function index() {
		$CI =& get_instance();

		header("Content-Type: text/html; charset=".$CI->config->item('charset'));
		header("Expires: 0"); // rfc2616 - Section 14.21
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
		header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
		header("Pragma: no-cache"); // HTTP/1.0
		
		$is_member = $is_super = FALSE;
		$login_id = $CI->session->userdata('ss_mb_id');
		if($login_id) {
			$member = $CI->M_basic->get_member($login_id);

			if(substr($member['mb_today_login'], 0, 10) != TIME_YMD) {
				$CI->load->model('M_point');
				$CI->M_point->insert($member['mb_id'], $CI->config->item('cf_login_point'), TIME_YMD.' 첫로그인', '@login', $member['mb_id'], TIME_YMD);

				$CI->db->where('mb_id', $member['mb_id']);
				$CI->db->update('ki_member', array(
					'mb_today_login' => TIME_YMDHIS,
					'mb_login_ip'	 => $CI->input->server('REMOTE_ADDR')
				));
			}
			
			if($member['mb_id']) {
				$is_member = TRUE;
				
				if($member['mb_level'] >= ADMIN_MIN_LEVEL) // 관리자 조건
					$is_super = $member['mb_id'];

				if(!$CI->config->item('cf_use_nick'))
					$member['mb_nick'] = $member['mb_name'];
			}
		}
		else {
			$member = $CI->db->get_columns('ki_member');
			$member['mb_level'] = 1;
			//$member['mb_nick'] = '손님';
			//$member['mb_name'] = '손님';
		}
		
		// visit
		if ($CI->session->userdata('ck_visit_ip') != $CI->input->server('REMOTE_ADDR')) {
			$CI->session->set_userdata('ck_visit_ip', $CI->input->server('REMOTE_ADDR'));
			$CI->M_visit->chkIn();
		}
		// visit_cnt

		// 관리자 페이지
        if ($CI->uri->segment(1) === ADM_F && !$is_super) {
            show_404();
        }
		
        $http_referer = $CI->input->server('HTTP_REFERER');
		$referer = parse_url($http_referer);
		$repself = str_replace('/index.php', '', $CI->input->server('PHP_SELF'));
		if (!empty($referer['path']) && $referer['path'] != $repself)
			$url = $http_referer;
		else
			$url = '/';
		
		// 쇼핑카트 세션 id 저장
		$CI->session->set_userdata('ss_cart_id', setValue($CI->session->userdata('session_id'), $CI->session->userdata('ss_cart_id')));
		
		define('URL', $url);
		define('SU_ADMIN', $is_super);
		define('IS_MEMBER', $is_member);
		define('MEMBER', serialize($member));
		
		$CI->load->vars(array(
			'_MEMBER_'		=> $member,
			'_OUTLOGIN_'	=> widget::run('member/outlogin')
		));
	}
}
?>
