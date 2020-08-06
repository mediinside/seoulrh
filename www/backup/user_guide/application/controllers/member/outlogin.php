<?php if ( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class Outlogin extends Widget {
	function index() {
		if (IS_MEMBER) {
			$member = unserialize(MEMBER);

			$vars = array(
				'img_path' => IMG_DIR.'/outlogin',
				'mb_nick' => $member['mb_nick'],
				'mb_point' => $member['mb_point'],
				'mb_memo_cnt' => $member['mb_memo_cnt'],
				'mb_memo_call' => $member['mb_memo_call']
			);
			
			return $this->load->view('outlogin/logout', $vars, TRUE);
		}
		else {
			// 회원가입, id/pw 찾기 등 로그인시 열리지 않는 페이지가 있으므로 메인으로 이동함. 
			$url = preg_match('/member/', $this->uri->segment(1)) ? '/' : preg_replace('/\/index.php/','',$this->input->server('PHP_SELF'));
			
			$vars = array(
				'img_path' => IMG_DIR.'/outlogin',
				'url' => $url
			);

			return $this->load->view('outlogin/login', $vars, TRUE);
		}
	}
}
?>
