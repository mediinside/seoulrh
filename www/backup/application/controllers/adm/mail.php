<?php
class Mail extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_mail', ADM_F.'/M_a_layout', 'M_upload_files'));
		$this->load->library('email');
	}

	function lists($page=1) {
		include "init.php";
		
		$this->load->library('pagination');
		$this->load->helper('search');

		$seg  = new search_seg;
		$page = $seg->get_seg('page');
		$sst  = $seg->get_seg('sst');
		$sod  = $seg->get_seg('sod');
		$sfl  = $seg->get_seg('sfl');
		$stx  = $seg->get_seg('stx');
		
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx); 
		if (!$sst) $sst = 'ma_id';
		if (!$sod) $sod = 'desc';

		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/mail/lists/page/';
		$config['per_page'] = 15;
		
		$offset = ($page - 1) * $config['per_page'];			
		$result = $this->M_a_mail->list_result($this->M_a_mail->table, $sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		$list = $result['qry'];

		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);

		$token = get_token();
		foreach ($list as $i => $row) {
			$list[$i]['s_mod'] = icon('수정', 'mail/form/u/'.$row['ma_id']);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/mail/delete', {id:'".$row['ma_id']."', token:'".$token."'}, true);");
			$list[$i]['s_pre'] = icon('보기', 'mail/preview/'.$row['ma_id'], "_blank");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['num'] = number_format($result['total_cnt'] - ($page - 1) * $config['per_page'] - $i);
			$list[$i]['ma_mdydate_conv'] = date('Y-m-d', strtotime($row['ma_mdydate']));
			$list[$i]['ma_regdate_conv'] = date('Y-m-d', strtotime($row['ma_regdate']));
		}
		
		$vars = array(
			'_TITLE_'		=> '회원메일발송',
			'_BODY_'		=> ADM_F.'/mail/mail_list',
			
			'token'			=> $token,

			'list'			=> $list,
			'skin_list'		=> $this->M_a_mail->get_skins(),
			's_add'			=> icon('입력', 'mail/form'),	
			
			'sfl'				=> $sfl,
			'stx'				=> $stx,
			
			'total_cnt'		=> number_format($result['total_cnt']),
			'paging'		=> $this->pagination->create_links(),
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $id='') {
		include "init.php";
		
		$this->load->model('M_editor');
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'ma_subject', 'label'=>'제목', 'rules'=>'trim|required'),
			array('field'=>'wr_content', 'label'=>'내용', 'rules'=>'trim|required')
		);

		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = "입력";

				$row = array_false(array('ma_skin','ma_subject','ma_content'));
			}
			else if ($w == 'u') {
				$title = "수정";
				
				$row = $this->M_a_mail->row($this->M_a_mail->table, $id);
				if (!isset($row['ma_id']))
					alert("등록된 자료가 없습니다.");
			}
			
			// 에디터 정보 수집
			if ($w == 'u') {
				$edt_info = $this->M_editor->get_info($this->M_a_mail->table, $id);
				$edt_info = json_encode($edt_info);
			}
			// 에디터 매개변수
			$edt = array(
				'content' => $row['ma_content'],
				'edt_info' => isset($edt_info) ? $edt_info : '[]',
				'wr_table' => $this->M_a_mail->table,
				'upload_size' => EDITOR_UPLOAD_SIZE * 1048576
			);
			$edt['buttons']['gallery'] = 1;
			$edt['buttons']['file'] = 0;
			$edt['buttons']['outcont'] = 0;
			
			$editor = $this->load->view('editor/editor', $edt, TRUE);
			
			$content = ''; // 그냥 비우기
			
			$vars = array(
				'_TITLE_'		=> '회원메일 '.$title,
				'_BODY_'		=> ADM_F.'/mail/mail_form',
				'_CSS_'			=> array('editor'),
				'_JS_'			=> array('../editor/js/editor_loader', 'jvalidate', 'jvalid_ext'),
				
				'w'				=> $w,
				'editor'		=> $editor,
				'row'			=> $row,
				'skin_list'		=> $this->M_a_mail->get_skins(),
				'token'			=> get_token()
			);

			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
			$w = $this->input->post('w');
			$id = $this->input->post('pu_id');
			$ma_content = $this->input->post('wr_content');
			
			$ma = $this->M_a_mail->row($this->M_a_mail->table, $id);
			
			if (!$w) {
				if (isset($ma['ma_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$data = array(
				'ma_skin'		=> $this->input->post('ma_skin'),
				'ma_subject'	=> $this->input->post('ma_subject'),
				'ma_ip'			=> $this->input->server('REMOTE_ADDR'),
				'ma_mdydate'	=> TIME_YMDHIS
			);
			
			$id = $this->M_a_mail->record($this->M_a_mail->table, $w, $data);
			
			// Files upload
			$ma_content = $this->M_editor->uploadFile($this->M_a_mail->table, $id, $ma_content);
			
			// 내용에서 첨부 파일 경로 수정
			$this->M_a_mail->db->update($this->M_a_mail->table, array('ma_content' => $ma_content), array('ma_id' => $id));
			
			goto_url(ADM_F.'/mail/form/u/'.$id);
		}
	}
	
	function skin_lists() {
		include "init.php";
		
		$this->load->library('pagination');
		$this->load->helper('search');

		$seg  = new search_seg;
		$page = $seg->get_seg('page');
		$sst  = $seg->get_seg('sst');
		$sod  = $seg->get_seg('sod');
		$sfl  = $seg->get_seg('sfl');
		$stx  = $seg->get_seg('stx');
		
		if ($page < 1) $page = 1;
		if ($stx) $stx = array_search(search_decode($stx), $banner_groups); 
		if (!$sst) $sst = 'ms_id';
		if (!$sod) $sod = 'desc';

		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/mail/skin_lists/page/';
		$config['per_page'] = 15;
		
		$offset = ($page - 1) * $config['per_page'];			
		$result = $this->M_a_mail->list_result($this->M_a_mail->table_skin, $sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		$list = $result['qry'];

		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);

		$token = get_token();
		foreach ($list as $i => $row) {
			$list[$i] = $row;
			
			$list[$i]['s_mod'] = icon('수정', 'mail/skin_form/u/'.$row['ms_id']);
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/mail/skin_delete', {id:'".$row['ms_id']."', token:'".$token."'}, true);");
			$list[$i]['s_pre'] = icon('보기', 'mail/skin_preview/'.$row['ms_id'], "_blank");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['ms_mdydate_conv'] = date('Y-m-d', strtotime($row['ms_mdydate']));
			$list[$i]['ms_regdate_conv'] = date('Y-m-d', strtotime($row['ms_regdate']));
		}

		$vars = array(
			'_TITLE_'			=> '메일스킨관리',
			'_BODY_'			=> ADM_F.'/mail/mail_skin_list',

			'token'				=> $token,

			'list'				=> $list,
			's_add'				=> icon('추가', 'mail/skin_form'),

			'sfl'				=> $sfl,
			'stx'				=> $stx,		

			'total_cnt'			=> number_format($result['total_cnt']),
			'paging'			=> $this->pagination->create_links(),

			'sort_ms_name'		=> sort_link('ms_name'),
			'sort_ms_mdydate'	=> sort_link('ms_mdydate'),
			'sort_ms_regdate'	=> sort_link('ms_regdate')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function skin_form($w='', $id='') {
		include "init.php";
	
		$this->load->model('M_editor');
		$this->load->library('form_validation');
	
		$config = array(
				array('field'=>'ms_name', 'label'=>'이름', 'rules'=>'trim|required'),
				array('field'=>'wr_content', 'label'=>'내용', 'rules'=>'trim|required')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if ($w == '' || $w != 'u') {
				$title = "입력";
				
				$row = array_false(array('ms_name','ms_code'));
			}
			else if ($w == 'u') {
				$title = "수정";
	
				$row = $this->M_a_mail->row($this->M_a_mail->table_skin, $id);
				if (!isset($row['ms_id']))
					alert("등록된 자료가 없습니다.");
			}
				
			// 에디터 정보 수집
			if ($w == 'u') {
				$edt_info = $this->M_editor->get_info($this->M_a_mail->table_skin, $id);
				$edt_info = json_encode($edt_info);
			}
			// 에디터 매개변수
			$edt = array(
					'content' => $row['ms_code'],
					'edt_info' => isset($edt_info) ? $edt_info : '[]',
					'wr_table' => $this->M_a_mail->table_skin,
					'upload_size' => EDITOR_UPLOAD_SIZE * 1048576
			);
			$edt['buttons']['gallery'] = 1;
			$edt['buttons']['file'] = 0;
			$edt['buttons']['outcont'] = 0;
				
			$editor = $this->load->view('editor/editor', $edt, TRUE);
				
			$content = ''; // 그냥 비우기
				
			$vars = array(
					'_TITLE_'		=> '메일 스킨 '.$title,
					'_BODY_'		=> ADM_F.'/mail/mail_skin_form',
					'_CSS_'			=> array('editor'),
					'_JS_'			=> array('../editor/js/editor_loader', 'jvalidate', 'jvalid_ext'),
	
					'w'				=> $w,
					'editor'		=> $editor,
					'row'			=> $row,
					'token'			=> get_token()
			);
	
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
			$w = $this->input->post('w');
			$id = $this->input->post('ms_id');
			$ms_code = $this->input->post('wr_content');
				
			$ms = $this->M_a_mail->row($this->M_a_mail->table_skin, $id);
				
			if (!$w) {
				if (isset($ms['ms_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
				
			$data = array(
					'ms_name'		=> $this->input->post('ms_name'),
					'ms_mdydate'	=> TIME_YMDHIS
			);
				
			$id = $this->M_a_mail->record($this->M_a_mail->table_skin, $w, $data);
			
			// Files upload
			$ms_code = $this->M_editor->uploadFile($this->M_a_mail->table_skin, $id, $ms_code);
				
			// 내용에서 첨부 파일 경로 수정
			$this->M_a_mail->db->update($this->M_a_mail->table_skin, array('ms_code' => $ms_code), array('ms_id' => $id));
				
			goto_url(ADM_F.'/mail/skin_form/u/'.$id);
		}
	}
	
	function test($ma_id='') {
		$ma = $this->M_a_mail->row($this->M_a_mail->table, $ma_id, 'ma_skin,ma_subject,ma_content');
		if (!isset($ma['ma_subject']))
			alert('등록된 자료가 없습니다.');

		$member = unserialize(MEMBER);
		$birth = (int)substr($member['mb_birth'],4,2).'월 '.(int)substr($member['mb_birth'],6,2).'일';

		$sitename = $this->config->item('cf_title');
		$domain = $this->config->item('cf_domain');
		
		$ms = $this->M_a_mail->row($this->M_a_mail->table_skin, $ma['ma_skin'], 'ms_code');
		$ma['ma_content'] = str_replace('{{[_BODY_]}}', $ma['ma_content'], $ms['ms_code']);
		
		$content = str_replace(
			array('[이름]', '[별명]', '[회원아이디]', '[이메일]', '[생일]', '[사이트명]', '[사이트주소]'),
			array($member['mb_name'], $member['mb_nick'], $member['mb_id'], $member['mb_email'], $birth, $sitename, $domain),
		$ma['ma_content']);

		$this->email->clear();
		$this->email->from($member['mb_email'], '메일테스트');
		$this->email->to($member['mb_email']);
		$this->email->subject($ma['ma_subject']);
		$this->email->message($content);
		if (!$this->email->send()) {
			alert('※ 메일전송 오류\\n\\n'.$this->email->print_debugger());
		}
		else {
			alert($member['mb_nick'].'('.$member['mb_email'].')님께 테스트 메일을 발송하였습니다.\\n\\n확인하여 주십시오.');
		}
	}
	
	function preview($ma_id='') {
		$ma = $this->M_a_mail->row($this->M_a_mail->table, $ma_id, 'ma_skin,ma_subject,ma_content');
		if(!$ma) {
			alert('등록된 자료가 없습니다.');
		}
		
		$ms = $this->M_a_mail->row($this->M_a_mail->table_skin, $ma['ma_skin'], 'ms_code');
		
		echo "<span style='font-size:9pt;'>".$ma['ma_subject']."</span>";
		echo '<hr/>';
		echo str_replace('{{[_BODY_]}}', $ma['ma_content'], $ms['ms_code']);
	}
	
	function skin_preview($ms_id='') {
		$ms = $this->M_a_mail->row($this->M_a_mail->table_skin, $ms_id, 'ms_code');
		if(!$ms) {
			alert('등록된 자료가 없습니다.');
		}
		
		echo $ms['ms_code'];
	}
	
	function select_form($ma_id='') {
		include "init.php";
		
		if (!$this->config->item('cf_use_email'))
			alert("환경설정에서 \'메일발송 사용\'에 체크하셔야 메일을 발송할 수 있습니다.");
		
		$ma = $this->M_a_mail->row($this->M_a_mail->table, $ma_id, 'ma_id,ma_last_option');
		if (!isset($ma['ma_id']))
			alert('보내실 내용을 선택하여 주십시오.');
		
		$result = $this->M_a_mail->member_cnt();
		
		$ma_lopt = array();
		$last_option = explode('||', $ma['ma_last_option']);
		foreach($last_option as $row) {
			$option = explode('=', $row);

			$var = $option[0];
			$$var = (isset($option[1])) ? $option[1] : '';
		}

		$vars = array(
			'_TITLE_'		=> '회원메일발송',
			'_BODY_'		=> ADM_F.'/mail/mail_select_form',
			
			'token' => get_token(),
			
			'ma_id' => $ma_id,
			'mb_level_from' => get_mb_level_select('mb_level_from', (isset($mb_level_from)) ? $mb_level_from : 1),
			'mb_level_to'   => get_mb_level_select('mb_level_to', (isset($mb_level_to)) ? $mb_level_to : 10),
			
			'mb_mailling'	=> (isset($mb_mailling))   ? $mb_mailling : 1,
			'mb_area'		=> (isset($mb_area))	   ? $mb_area : '',
			'mb_birth_from' => (isset($mb_birth_from)) ? $mb_birth_from : '',
			'mb_birth_to'	=> (isset($mb_birth_to))   ? $mb_birth_to : '',
			'mb_email'		=> (isset($mb_email))	   ? $mb_email : '',
			
			'total_cnt' => number_format($result['total_cnt']),
			'leave_cnt' => number_format($result['leave_cnt']),
			'member_cnt' => number_format($result['member_cnt'])
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function select_list() {
		include "init.php";
		
		check_token();
		$result = $this->M_a_mail->select_list();

		if ($result['select_cnt'] == 0)
			alert('선택하신 내용으로는 해당되는 회원자료가 없습니다.', URL);

		$this->M_a_mail->option_update();

		$list = array();
		foreach($result['qry'] as $i => $row) {
			if ($this->config->item('cf_use_nick'))
				$list[$i]->mb_nick = $row['mb_nick'];

			$birth = ($row['mb_birth']) ? substr($row['mb_birth'],5,2).'월 '.substr($row['mb_birth'],8,2).'일' : '미입력';
			
			$list[$i]->lst = $i%2;
			$list[$i]->mb_id = $row['mb_id'];
			$list[$i]->mb_name = $row['mb_name'];
			$list[$i]->mb_birth = $birth;
			$list[$i]->mb_birth_year = ($birth != '미입력') ? substr($row['mb_birth'],0,4).'년 ' : '';
			$list[$i]->mb_email = $row['mb_email'];
		}

		$vars = array(
			'_TITLE_'		=> '회원메일발송',
			'_BODY_'		=> ADM_F.'/mail/mail_select_list',
			
			'token'			=> get_token(),
			'use_nick'		=> $this->config->item('cf_use_nick'),
			'ma_id'			=> $this->input->post('ma_id'),
			'list'			=> $list,
			'select_cnt'	=> number_format($result['select_cnt'])
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function select_send() {
		include "init.php";
		
		if ($this->input->post('mb_id')) {
			$member = unserialize(MEMBER);
			$mb_ids = $this->input->post('mb_id');

			$mb_name = $this->input->post('mb_name');
			$mb_nick = $this->input->post('mb_nick');
			$mb_email = $this->input->post('mb_email');
			$mb_birth = $this->input->post('mb_birth');

			$sitename = $this->config->item('cf_title');
			$domain = $this->config->item('cf_domain');
			
			$ma = $this->M_a_mail->row($this->M_a_mail->table, $this->input->post('ma_id'), 'ma_skin,ma_subject,ma_content');

			$ms = $this->M_a_mail->row($this->M_a_mail->table_skin, $ma['ma_skin'], 'ms_code');
			$ma['ma_content'] = str_replace('{{[_BODY_]}}', $ma['ma_content'], $ms['ms_code']);
			
			$mail_msg = '';
			$mail_fail = 0;
			foreach ($mb_ids as $mb_id) {
				$content = str_replace(
					array('[이름]', '[별명]', '[회원아이디]', '[이메일]', '[생일]', '[사이트명]', '[사이트주소]'),
					array($mb_name[$mb_id], $mb_nick[$mb_id], $mb_id, $mb_email[$mb_id], $mb_birth[$mb_id], $sitename, $domain),
					$ma['ma_content']
				);
				
				$this->email->clear();
				$this->email->from($member['mb_email'], $this->config->item('cf_title'));
				$this->email->to($mb_email[$mb_id]);
				$this->email->subject($ma['ma_subject']);
				$this->email->message($content);
				if (!$this->email->send()) {
					$mail_msg .= $mb_email[$mb_id].'<br/>';
					$mail_fail++;
				}
			}
		}
		else 
			alert('잘못된 접근입니다.');

		$vars = array(
			'_TITLE_'		=> '메일전송 결과',
			'_BODY_'		=> ADM_F.'/mail/mail_select_send',
			
			'mail_msg' => (!$mail_msg) ? '없음' : $mail_msg,
			'total_cnt' => count($mb_ids) - $mail_fail
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function update() {
		check_token(URL);
	
		$ids = $this->input->post('chk');
		$ma_subject = $this->input->post('ma_subject');
		$ma_skin = $this->input->post('ma_skin');
		
		if(!$ids)
			return false;
		
		$data = array(
				'ma_subject'	=> $ma_subject,
				'ma_skin'		=> $ma_skin
		);
		
		$this->M_a_mail->list_update($this->M_a_mail->table, $ids, $data);
	
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
		
		foreach($ids as $i) {
			$this->M_upload_files->file_delete($this->M_a_mail->table, $i);
			$this->M_a_mail->delete($this->M_a_mail->table, $i);
		}
		
		goto_url(URL);
	}
	
	function skin_update() {
		check_token(URL);
		
		$ids = $this->input->post('chk');
		$ms_name = $this->input->post('ms_name');
		$ms_hidden = $this->input->post('ms_hidden');
		
		if(!$ids)
			return false;
		
		$data = array(
			'ms_name'		=> $ms_name
		);
		
		$this->M_a_mail->list_update($this->M_a_mail->table_skin, $ids, $data);
		
		goto_url(URL);
	}
	
	function skin_delete() {
		check_token(URL);
		
		$id = $this->input->post('id');
		$ids = $this->input->post('chk');
		
		if($id)
			$ids[] = $id;
		
		if(!$ids)
			return false;
		
		foreach($ids as $i) {
			$row = $this->M_a_mail->row($this->M_a_mail->table_skin, $i);
			$this->M_upload_files->file_delete($this->M_a_mail->table_skin, $i);
			$this->M_a_mail->delete($this->M_a_mail->table_skin, $i);
		}
		
		goto_url(URL);
	}
}
?>
