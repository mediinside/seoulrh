<?php
class Member extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(ADM_F.'/M_a_member');
	}

	function lists() {
		include "init.php";
		
		$this->load->library('pagination');
		$this->load->helper(array('sideview','search'));
		
		$seg = new search_seg;
		$page = $seg->get_seg('page');
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');

		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'mb_datetime';
		if (!$sod) $sod = 'desc';

		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/member/lists/page/';
		$config['per_page'] = 15;

		$offset = ($page - 1) * $config['per_page'];
		$result = $this->M_a_member->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);

		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);

		$list = array();
		$token = get_token();
		foreach($result['qry'] as $i => $row) {
			$list[$i] = $row;
			
			$list[$i]['s_view'] = icon('로그인', "javascript:post_goto('".ADM_F."/member/login', {mb_id:'".$row['mb_id']."'}, '_self', '로그인 하시겠습니까?\\n\\n기존 관리자 회원은 로그아웃됩니다.');");
			$list[$i]['s_mod'] = icon('수정', 'member/form/u/'.$row['mb_id']);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/member/delete', {id:'".$row['mb_id']."', token:'".$token."'}, true);");

			if(!$row['mb_leave_date'])
				$list[$i]['mb_id_s'] = get_sideview($row['mb_id'], $row['mb_id']);
			else
				$list[$i]['mb_id_s'] = "<font color='crimson'>".$row['mb_id'].'</font>';

			$list[$i]['lst'] = $i%2;
			$list[$i]['mb_level_select'] = get_mb_level_select("mb_level[".$row['mb_id']."]", $row['mb_level'], TRUE, $member['mb_level']);
			$list[$i]['mb_point'] = number_format($row['mb_point']);
			$list[$i]['mb_today_login'] = substr($row['mb_today_login'], 2, 8);
			$list[$i]['mailling_chk'] = $row['mb_mailling'] ? '&radic;' : '&nbsp;';
			$list[$i]['mb_open_chk'] = $row['mb_open'] ? '&radic;' : '&nbsp;';
			$list[$i]['mail_certify_chk'] = preg_match('/[1-9]/', $row['mb_email_certify']) ? '&radic;' : '&nbsp;';
		}

		$vars = array(
			'_TITLE_'		=> '회원관리',
			'_BODY_'		=> ADM_F.'/member/member_list',
			
			'token'			=> $token,

			'list'			=> $list,
			's_add'			=> icon('추가', 'member/form'),
			'use_nick'		=> $this->config->item('cf_use_nick'),
			'use_point'		=> $this->config->item('cf_use_point'),

			'sfl'			=> $sfl,
			'stx'			=> $stx,

			'total_cnt'		=> number_format($result['total_cnt']),
			'leave_cnt'		=> number_format($result['leave_cnt']),
			'paging'		=> $this->pagination->create_links(),

			'sort_mb_id'	=> sort_link('mb_id'),
			'sort_mb_name'	=> sort_link('mb_name'),
			'sort_mb_nick'	=> sort_link('mb_nick'),
			'sort_mb_level'	=> sort_link('mb_level', 'desc'),
			'sort_mb_point'	=> sort_link('mb_point', 'desc'),
			'sort_mb_today_login'=> sort_link('mb_today_login', 'desc'),
			'sort_mb_mailling'=> sort_link('mb_mailling', 'desc'),
			'sort_mb_open'	=> sort_link('mb_open', 'desc'),
			'sort_mb_email_certify'=> sort_link('mb_email_certify', 'desc')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $mb_id='') {
		include "init.php";
		
		$this->load->config('cf_register');
		$this->load->config('cf_icon');
		$this->load->library('form_validation');
		$this->load->helper('chkstr');
		$this->load->model('M_register');

		$config = array(
			array('field'=>'mb_name', 'label'=>'이름', 'rules'=>'trim|required|max_length[10]'),
			array('field'=>'mb_email', 'label'=>'이메일', 'rules'=>'trim|max_length[50]|valid_email|callback_mb_email_check'),
			array('field'=>'mb_sex', 'label'=>'성별', 'rules'=>'trim|exact_length[1]'),
			array('field'=>'mb_birth', 'label'=>'생일', 'rules'=>'trim|exact_length[10]')
		);

		$pwd_req = ''; 
		if (!$this->input->post('w')) {
			$config[] = array('field'=>'mb_id', 'label'=>'아이디', 'rules'=>'trim|required|min_length[3]|max_length[20]|alpha_dash|xss_clean|callback_mb_id_check');
			$pwd_req = 'required|';
		}
		
		$config[] = array('field'=>'mb_password', 'label'=>'비밀번호', 'rules'=>'trim|'.$pwd_req.'min_length[3]|max_length[20]|md5');

		if ($this->config->item('cf_use_nick'))
			$config[] = array('field'=>'mb_nick', 'label'=>'별명', 'rules'=>'trim|required|max_length[20]|callback_mb_nick_check');

		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			$data = array();

			if ($w == '') {
				$mb = array_false(unserialize(MEMBER), TRUE);

                $mb['mb_zip1'] = $mb['mb_zip2'] = '';
				$mb['mb_mailling'] = 1;
				$mb['mb_open'] = 1;
				$mb['mb_level'] = $this->config->item('cf_register_level');

				$title = '등록';
			}
			else if ($w == 'u') {
				$mb = $this->M_basic->get_member($mb_id);
				if (!isset($mb['mb_id']))
					alert('존재하지 않는 회원자료입니다.');

				if ($this->config->item('cf_use_point'))
					$mb['mb_point'] = number_format($mb['mb_point']);

				if ($this->config->item('cf_use_email_certify')) {
					$data['passive_certify'] = FALSE;
					if ($mb['mb_email_certify'] == '0000-00-00 00:00:00')
						$data['passive_certify'] = "<input type='checkbox' name='passive_certify'> 수동인증";
				}
				$data['use_email_certify'] = $this->config->item('cf_use_email_certify');

				$title = '수정';
			}
			else
				alert('잘못된 접근입니다.');

            if ($this->config->item('cf_use_icon')) {
                $mb_path = '/member/'.substr($mb['mb_id'],0,2).'/';
                
				$icon_path = $mb_path.$mb['mb_id'].'.gif';
				$icon_file = DATA_DIR.$icon_path;
				if (!file_exists(DATA_PATH.$icon_path))
					$icon_file = FALSE;
                    
                $data['icon_file'] = $icon_file;
				$data['icon_width'] = $this->config->item('cf_icon_width');
				$data['icon_height'] = $this->config->item('cf_icon_height');
				$data['icon_size'] = $this->config->item('cf_icon_size');
                    
                $named_path = $mb_path.'n_'.$mb['mb_id'].'.gif';
				$named_file = DATA_DIR.$named_path;
				if (!file_exists(DATA_PATH.$named_path))
					$named_file = FALSE;
                    
                $data['named_file'] = $named_file;
				$data['named_width'] = $this->config->item('cf_named_width');
				$data['named_height'] = $this->config->item('cf_named_height');
				$data['named_size'] = $this->config->item('cf_named_size');
			}
			
			$vars = array_merge(array(
				'_TITLE_'		=> '회원정보 '.$title,
				'_BODY_'		=> ADM_F.'/member/member_form',
				'_CSS_'			=> array('jquery-ui'),
				'_JS_'			=> array('jvalidate', 'jvalid_ext', 'jvalid_reg', 'jquery-ui.min', 'jtimepicker'),
				
				'w'				=> $w,
				'token'			=> get_token(),
				
				'use_nick'		=> $this->config->item('cf_use_nick'),
				'use_point'		=> $this->config->item('cf_use_point'),
				'use_icon'		=> ($w) ? $this->config->item('cf_use_icon') : FALSE,
				
				'mailling_chk'	=> ($mb['mb_mailling']) ? "checked='checked'" : FALSE,
				'open_chk'		=> ($mb['mb_open']) ? "checked='checked'" : FALSE,

				'mb_level_select'=> get_mb_level_select('mb_level', $mb['mb_level'], '', $member['mb_level'])
			), $data, $mb);

			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();

			$w = $this->input->post('w');
			$mb_id = $this->input->post('mb_id');

			if(!$w) {
				$mb = $this->M_basic->get_member($mb_id, 'mb_id,mb_name,mb_nick,mb_email');
				if (isset($mb['mb_id']))
					alert("이미 존재하는 회원입니다.\\n\\nＩＤ : ".$mb['mb_id']."\\n\\n이름 : ".$mb['mb_name']."\\n\\n별명 : ".$mb['mb_nick']."\\n\\n메일 : ".$mb['mb_email']);

				$this->M_a_member->insert();
			}
			else if($w == 'u') {
				$mb = $this->M_basic->get_member($mb_id, 'mb_id,mb_level');
				$member = unserialize(MEMBER);
				
				if($member['mb_level'] < $mb['mb_level']) {
					alert('자신보다 높은 레벨의 회원 정보는 수정할 수 없습니다.');
				}
				if(!isset($mb['mb_id']))
					alert('존재하지 않는 회원자료입니다.');

                $mb_dir   = DATA_PATH.'/member/'.substr($mb_id,0,2);
    			$mb_icon  = $mb_dir.'/'.$mb_id.'.gif';
                $mb_named = $mb_dir.'/n_'.$mb_id.'.gif';

                // 아이콘 삭제
    			if($this->input->post('del_mb_icon'))
    				@unlink($mb_icon);
                    
                // 이미지이름 삭제
    			if($this->input->post('del_mb_named'))
    				@unlink($mb_named);
				
				if($_FILES) {
					$this->load->library('upload');
					if(is_uploaded_file($_FILES['mb_icon']['tmp_name'])) {
						@mkdir($mb_dir, 0707);
						@chmod($mb_dir, 0707);

						$config['upload_path']   = $mb_dir;
						$config['allowed_types'] = 'gif';
						$config['max_size']		 = $this->config->item('cf_icon_size');
						$config['max_width']	 = $this->config->item('cf_icon_width');
						$config['max_height']	 = $this->config->item('cf_icon_height');
						$config['overwrite']	 = TRUE;
						$config['file_name']	 = $mb_id.'.gif';

						$this->upload->initialize($config);
						if($this->upload->do_upload('mb_icon'))
							chmod($mb_icon, 0606);
					}
					if(is_uploaded_file($_FILES['mb_named']['tmp_name'])) {
						@mkdir($mb_dir, 0707);
						@chmod($mb_dir, 0707);

						$config['upload_path']   = $mb_dir;
						$config['allowed_types'] = 'gif';
						$config['max_size']		 = $this->config->item('cf_named_size');
						$config['max_width']	 = $this->config->item('cf_named_width');
						$config['max_height']	 = $this->config->item('cf_named_height');
						$config['overwrite']	 = TRUE;
						$config['file_name']	 = 'n_'.$mb_id.'.gif';
		
						$this->upload->initialize($config);
						if($this->upload->do_upload('mb_named'))
							chmod($mb_named, 0606);
					}
				}

				$this->M_a_member->update();
			}
			else
				alert('잘못된 접근입니다.');

			goto_url(ADM_F.'/member/form/u/'.$mb_id);
		}
	}

	function update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$mb_level = $this->input->post('mb_level');
		
		if(!$ids)
			return false;
		
		$member = unserialize(MEMBER);
		$result = $this->M_a_member->get_mbs_infor($ids, 'mb_id,mb_level', 'mb_level > '.$member['mb_level']);
		
		$msg = '';
		foreach($result AS $row) {
			$key = array_search($row['mb_id'], $ids);
			if($key) {
				unset($ids[$key]);
				$msg = "<script type='text/javascript'>alert('자신보다 높은 레벨의 회원 정보는 수정할 수 없습니다.');</script>";
			}
		}
		echo $msg;
		
		$data = array(
				'mb_level' => $mb_level
		);
		
		$this->M_a_member->list_update($ids, $data);
		
		goto_url(URL);
	}
	
	function delete() {
		check_token(URL);
		
		$id = $this->input->post('id');
		$ids = $this->input->post('chk');
	
		if($id)
			$ids[] = $id;
	
		if(!$ids)
			return false;

		$msg = '';
		$row = $this->M_a_member->get_mbs_infor($ids, 'mb_id,mb_level,mb_leave_date');
		foreach($row as $mb) {
			if($mb['mb_leave_date'])
				$msg .= $mb['mb_id']." : 이미 탈퇴/삭제한 회원입니다.\\n";
			else if($mb['mb_level'] == 10)
				$msg .= $mb['mb_id']." : 최고관리자는 삭제할 수 없습니다.\\n";
			else
				$mb_true[] = $mb['mb_id'];
		}

		if($msg)
			echo "<script type='text/javascript'>alert('".$msg."');</script>";

		if (!isset($mb_true))
			goto_url(URL);

		foreach($mb_true as $i) {
			$this->M_a_member->delete($i);
		}
		
		// 아이콘 삭제
		foreach($mb_true as $mb_id) {
			@unlink(DATA_PATH.'/member/'.substr($mb_id,0,2).'/'.$mb_id.'.gif');
			@unlink(DATA_PATH.'/member/'.substr($mb_id,0,2).'/n_'.$mb_id.'.gif');
		}
		
		goto_url(URL);
	}
	
	function login() {
		$mb = $this->M_basic->get_member($this->input->post('mb_id'), 'mb_id, mb_password, mb_email, mb_leave_date, mb_email_certify');
			
		if(!$mb) {
			alert('회원정보를 찾을 수 없습니다.');
		}
		
		if($mb['mb_leave_date'] && $mb['mb_leave_date'] <= date('Ymd', time())) {
			$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $mb['mb_leave_date']);
			alert("탈퇴한 아이디이므로 접근하실 수 없습니다.\\n\\n탈퇴일 : ".$date);
		}
		
		if($this->config->item('cf_use_email_certify') && !preg_match("/[1-9]/", $mb['mb_email_certify']))
			alert("메일인증을 받으셔야 로그인 하실 수 있습니다.\\n\\n회원님의 메일주소는 ".$mb['mb_email']." 입니다.");
		
		$this->session->set_userdata('ss_mb_id', $mb['mb_id']);
		
		goto_url('/');
	}
	
	function mb_id_check($str) {
		/* 관리자 권한이니 패스
		if (preg_match("/[\,]?".$str."/i", $this->config->item('cf_prohibit_id'))) {
			$this->form_validation->set_message('mb_id_check', $str.' 은(는) 예약어로 사용하실 수 없는 회원아이디입니다.');
			return FALSE;
		}
		*/

		$row = $this->M_register->is('mb_id', $str);
		if ($row != 0) {
			$this->form_validation->set_message('mb_id_check', $str.' 은(는) 이미 다른분이 사용중인 회원아이디이므로 사용이 불가합니다.');
			return FALSE;
		}
		return TRUE;
	}
	
	function mb_nick_check($str) {
		if (!check_string($str, _RT_HANGUL_ + _RT_ALPHABETIC_ + _RT_NUMERIC_)) {
			$this->form_validation->set_message('mb_nick_check', '별명은 공백없이 한글, 영문, 숫자만 입력 가능합니다.');
			return FALSE;
		}

		/* 관리자 권한이니 패스
		if (preg_match("/[\,]?".$str."/i", $this->config->item('cf_prohibit_id'))) {
			$this->form_validation->set_message('mb_nick_check', $str.' 은(는) 예약어로 사용하실 수 없는 별명입니다.');
			return FALSE;
		}
		*/

		if (!$this->input->post('w') || $this->input->post('mb_nick_default') != $this->input->post('mb_nick')) {
			$row = $this->M_register->is('mb_nick', $str);
			if ($row != 0) {
				$this->form_validation->set_message('mb_nick_check', $str.' 은(는) 이미 다른분이 사용중인 별명이므로 사용이 불가합니다.');
				return FALSE;
			}
		}
		return TRUE;
	}
	
	function mb_email_check($str) {
		if (!$this->input->post('w') || $this->input->post('old_email') != $this->input->post('mb_email')) {
			$row = $this->M_register->is('mb_email', $str);
			/* 관리자 권한이니 패스
			if ($row != 0) {
				$this->form_validation->set_message('mb_email_check', $str.' 은(는) 이미 다른분이 사용중인 E-mail이므로 사용이 불가합니다.');
				return FALSE;
			}
			*/
		}
		return TRUE;
	}
}
?>
