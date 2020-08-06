<?php
class Board extends CI_Controller {
	// 버전 (설정 내보내기, 가져오기 경우 적용 여부 판단)
	var $version = '1.1';
	
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_layout', ADM_F.'/M_a_board', ADM_F.'/M_a_mail', 'M_board'));
	}
	
	function lists() {
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
		if (!$sst) $sst = 'gr_id';
		if (!$sod) $sod = 'asc';

		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/board/lists/page/';
		$config['per_page'] = 15;

		$offset = ($page - 1) * $config['per_page'];			
		$result = $this->M_a_board->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);

		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);

		$list = array();
		$token = get_token();
		foreach ($result['qry'] as $i => $row) {
			$s_mod = icon('수정', 'board/form/u/'.$row['bid']);
			$s_del = icon('삭제', "javascript:post_s('".ADM_F."/board/delete', {bid:'".$row['bid']."', token:'".$token."'}, true);");
			
			$list[$i]->lst = $i%2;
			
			$list[$i]->bid = $row['bid'];
			$list[$i]->bo_subject = $row['bo_subject'];
			$list[$i]->bo_order_search = $row['bo_order_search'];
			$list[$i]->bo_use_chk = ($row['bo_use_search']) ? "checked='checked'" : '';
			$list[$i]->bo_group = get_group_select("gr_id[".$row['bid']."]", $row['gr_id'], TRUE);
			$list[$i]->bo_layout = get_layout_select("bo_layout[".$row['bid']."]", $row['bo_layout'], TRUE);
			$list[$i]->bo_skin = get_skin_dir('board', "bo_skin[".$row['bid']."]", $row['bo_skin'], TRUE);
			$list[$i]->s_mod = $s_mod;
			$list[$i]->s_del = $s_del;
		}

		$vars = array(
			'_TITLE_'				=> '게시판 리스트',
			'_BODY_'				=> ADM_F.'/board/board_list',
			
			'token'					=> $token,

			'list'					=> $list,
			's_add'					=> icon('추가', 'board/form'),

			'sfl'					=> $sfl,
			'stx'					=> $stx,		

			'total_cnt'				=> number_format($result['total_cnt']),
			'paging'				=> $this->pagination->create_links(),

			'sort_bid'				=> sort_link('bid', 'desc'),
			'sort_bo_subject'		=> sort_link('bo_subject'),
			'sort_bo_use_search'	=> sort_link('bo_use_search'),
			'sort_bo_order_search'	=> sort_link('bo_order_search'),
			'sort_gr_id'			=> sort_link('gr_id'),
			'sort_bo_layout'		=> sort_link('bo_layout'),
			'sort_bo_skin'			=> sort_link('bo_skin', 'desc')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $bid='') {
		include "init.php";
		
		$this->load->library('form_validation');

		$config = array(
			array('field'=>'bid', 'label'=>'TABLE', 'rules'=>'trim|required|min_length[3]|max_length[20]|alpha_dash|xss_clean'),
			array('field'=>'gr_id', 'label'=>'게시판 그룹', 'rules'=>'trim|required|min_length[3]|max_length[20]|alpha_dash'),
			array('field'=>'bo_subject', 'label'=>'게시판 제목', 'rules'=>'trim|required|max_length[20]'),
			array('field'=>'bo_admin', 'label'=>'게시판 관리자', 'rules'=>'trim|min_length[3]|max_length[20]|alpha_dash'),
			array('field'=>'bo_param_name[]', 'label'=>'파라메터 변수명', 'rules'=>'trim|alpha_numeric_under')
		);

		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if (!$this->M_a_board->is_group())
			    alert('게시판그룹이 한개 이상 생성되어야 합니다.', ADM_F.'/boardgroup/form');

			if ($w == '' || $w != 'u') {
				$title = '생성';
				$board = $this->db->get_columns($this->M_basic->table);

				$board['bo_show_gr']	  = 1;
				$board['bo_page_rows']    = 15;
				$board['bo_subject_len']  = 85;
				$board['bo_new']          = 24;
				$board['bo_hot']		  = 1000;
				$board['bo_image_width']  = 700;
				$board['bo_upload_size']  = 10;
				$board['bo_count_modify'] = 0;
				$board['bo_count_delete'] = 0;
				$board['bo_reply_order']  = 1;
				$board['bo_use_sns']	  = 1;
				$board['bo_use_comment']  = 1;
				$board['bo_use_editor']   = 1;
				$board['bo_use_edt_img']  = 1;
				$board['bo_use_edt_file'] = 1;
				$board['bo_use_edt_ocon'] = 1;
				$board['bo_use_search']   = 1;
				$board['bo_use_secret']   = 0;
				$board['bo_upload_ext']   = 'zip,rar,tar,gz,pdf,swf,psd,hwp,doc,docx,xls,xlsx,ppt,pptx';
				$board['bo_skin']		  = 'basic';
				$board['gr_id']			  = $w;
				//$board['bo_parameter']	  = json_encode(array('pageNum'=>0,'subNum'=>0));
			}
			else if ($w == 'u') {
				$title = '수정';

				$board = $this->M_basic->get_board($bid);
				if (!isset($board['bid']))
					alert('존재하지 않은 게시판 입니다.');
			}

			$upload_max_size = ini_get('upload_max_filesize');
			if (!preg_match("/([m|M])$/", $upload_max_size))
				$upload_max_size = (int)($upload_max_size / 1048576);

			$vars = array(
				'_TITLE_'			=> '게시판 '.$title,
				'_BODY_'			=> ADM_F.'/board/board_form',
				'_JS_'				=> array('jvalidate', 'jvalid_ext'),
				
				'w'					=> $w,
				'token'				=> get_token(),
				
				'board'				=> $board,
				'board_list'		=> $this->M_basic->get_board(false, 'bid, bo_subject'),
				'mail_skin'			=> $this->M_a_mail->get_skins(),
				
				'upload_max_size'	=> $upload_max_size,

				'use_captcha_chk'	=> ($board['bo_use_captcha'])	? "checked='checked'" : '',
                'use_rss_chk'		=> ($board['bo_use_rss'])		? "checked='checked'" : '',
				'use_sns_chk'		=> ($board['bo_use_sns'])		? "checked='checked'" : '',
				'use_comment_chk'	=> ($board['bo_use_comment'])	? "checked='checked'" : '',
				'use_tag_chk'		=> ($board['bo_use_tag'])		? "checked='checked'" : '',
				'use_upload_chk'	=> ($board['bo_use_upload'])	? "checked='checked'" : '',
				'use_category_chk'	=> ($board['bo_use_category'])	? "checked='checked'" : '',
				'use_sideview_chk'	=> ($board['bo_use_sideview'])	? "checked='checked'" : '',
				'use_editor_chk'	=> ($board['bo_use_editor'])	? "checked='checked'" : '',
				'use_edt_img_chk'	=> ($board['bo_use_edt_img'])	? "checked='checked'" : '',
				'use_edt_file_chk'	=> ($board['bo_use_edt_file'])	? "checked='checked'" : '',
				'use_edt_ocon_chk'	=> ($board['bo_use_edt_ocon'])	? "checked='checked'" : '',
				'use_name_chk'		=> ($board['bo_use_name'])		? "checked='checked'" : '',
				'use_ip_view_chk'	=> ($board['bo_use_ip_view'])	? "checked='checked'" : '',
				'use_list_view_chk'	=> ($board['bo_use_list_view'])	? "checked='checked'" : '',
				'use_email_chk'		=> ($board['bo_use_email'])		? "checked='checked'" : '',
				'use_search_chk'	=> ($board['bo_use_search'])	? "checked='checked'" : '',
				'use_extra_chk'		=> ($board['bo_use_extra'])		? "checked='checked'" : '',
				'use_postlink_chk'	=> ($board['bo_use_postlink'])	? "checked='checked'" : '',
				'use_board_sel_chk'	=> ($board['bo_use_board_sel'])	? "checked='checked'" : '',

				'layout_select'		=> get_layout_select('bo_layout', $board['bo_layout']),
				'group_select'		=> get_group_select('gr_id', $board['gr_id']),
				'order_select'		=> get_order_select('bo_reply_order', $board['bo_reply_order']),
				'sort_select'		=> get_sort_select('bo_sort_field', $board['bo_sort_field']),
				'skin_select'		=> get_skin_dir('board', 'bo_skin', $board['bo_skin']),
				
				'bo_list_level'		=> get_mb_level_select('bo_list_level', (isset($board['bo_list_level'])) ? $board['bo_list_level'] : 1, '', $member['mb_level'], TRUE),
				'bo_read_level'		=> get_mb_level_select('bo_read_level', (isset($board['bo_read_level'])) ? $board['bo_read_level'] : 1, '', $member['mb_level'], TRUE),
				'bo_write_level'	=> get_mb_level_select('bo_write_level', (isset($board['bo_write_level'])) ? $board['bo_write_level'] : 2, '', $member['mb_level'], TRUE),
				'bo_reply_level'	=> get_mb_level_select('bo_reply_level', (isset($board['bo_reply_level'])) ? $board['bo_reply_level'] : 2, '', $member['mb_level'], TRUE),
				'bo_comment_level'	=> get_mb_level_select('bo_comment_level', (isset($board['bo_comment_level'])) ? $board['bo_comment_level'] : 2, '', $member['mb_level'], TRUE),
				'bo_upload_level'	=> get_mb_level_select('bo_upload_level', (isset($board['bo_upload_level'])) ? $board['bo_upload_level'] : 2, '', $member['mb_level'], TRUE),
				'bo_download_level'	=> get_mb_level_select('bo_download_level', (isset($board['bo_download_level'])) ? $board['bo_download_level'] : 2, '', $member['mb_level'], TRUE)
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
			
			$w = $this->input->post('w');
			$bid = $this->input->post('bid');

			if (!$w) {
				$this->load->model(ADM_F .'/M_a_menus');
				
				$bo = $this->M_basic->get_board($bid, 'bid');
				if (isset($bo['bid']))
					alert($bo['bid'].'은(는) 이미 존재하는 TABLE 입니다.');
				
				$board_path = DATA_PATH.'/file/'.$bid;
				
				// 게시판 디렉토리 생성
				mkdir($board_path, 0707);
				chmod($board_path, 0707);
				// 게시판 썸네일 디렉토리 생성
				mkdir($board_path.'/thumb', 0707);
				chmod($board_path.'/thumb', 0707);
				
				$this->load->helper('file');
				$board_index = $board_path.'/index.html';
				write_file($board_index, '');
				chmod($board_index, 0606);
				
				$this->M_a_board->insert();
				
				// 관리자 메뉴에 게시판 추가
				$bo_menu_no = 0;
				$menus = $this->M_a_menus->list_result();
				foreach($menus AS $menu) {
					if(preg_replace('/ /', '', $menu['am_name']) == '게시판관리') {
						$bo_menu_no = $menu['am_id'];
						break;
					}
				}
				$data = array(
						'am_pid'	=> $bo_menu_no,
						'am_name'	=> $this->input->post('bo_subject'),
						'am_level'	=> '',
						'am_icon'	=> '',
						'am_link'	=> '/board/'. $this->input->post('bid') .'/lists/layout/admin',
						'am_target'	=> '',
						'am_sort'	=> 0
				);
				
				$am_id = $this->M_a_menus->record($w, $data);
			}
			else if ($w == 'u') {
				// 글수 조정
				if ($this->input->post('proc_count'))
					$this->M_a_board->proc_count();

				// 공지 가져오기
                $is_notice = '';
				$bo = $this->M_basic->get_board($bid, 'bo_notice');
				if (isset($bo['bo_notice']))
					$is_notice = $bo['bo_notice'];

				$this->M_a_board->update($is_notice);
			}
			else
				alert('잘못된 접근입니다.');

			if ($this->input->post('chk'))
				$this->M_a_board->group_update();

			goto_url(ADM_F.'/board/form/u/'.$bid);
		}
	}
	
	function saveConf($bid) {
		$this->load->helper('download');
		
		$board = $this->M_basic->get_board($bid, '*', FALSE, FALSE);

		// 여분 필드 추가
		if($this->db->table_exists('ki_extra_'.$bid)) {
			$qry = $this->db->query('desc ki_extra_'.$bid);
			$result = $qry->result_array();
			foreach ($result as $i => $row) {
				$board['extra'][$i]['name'] = $row['Field'];
				$board['extra'][$i]['attr'] = $row['Type'];
				$board['extra'][$i]['unsg'] = FALSE;
				$board['extra'][$i]['size'] = '';
			
				preg_match('/\(([0-9]+)\)/', $row['Type'], $size);
				if (isset($size[1])) {
					$attr = str_replace($size[0], '', $row['Type']);
					if (strpos($attr, 'unsigned') !== FALSE) {
						$board['extra'][$i]['unsg'] = TRUE;
						$attr = str_replace('unsigned', '', $attr);
					}
					
					$board['extra'][$i]['size'] = $size[1];
					$board['extra'][$i]['attr'] = trim($attr);
				}
			}
		}
		
		if (!isset($board['bid']))
			alert('존재하지 않은 게시판 입니다.');
		
		$board['version'] = $this->version;
		
		force_download($bid .'.conf', serialize($board));
	}

	function loadConf() {
		$this->load->helper('file');
		
		$file = $_FILES['confFile'];
		
		$bid = $this->input->post('bid');
		$string = read_file($file['tmp_name']);
		
		if($string)
		{
			@$data = unserialize($string);
			@$extra = $data['extra'];
			
			if(isset($data['version']) && $data['version'] >= $this->version) {
				unset($data['version']);
				unset($data['extra']);

				$delFld = array('bid', 'gr_id', 'bo_db', 'bo_admin', 'bo_subject', 'bo_count_write', 'bo_count_comment', 'bo_notice', 'bo_min_wr_num');
				foreach($delFld AS $fld) {
					unset($data[$fld]);
				}
				
				$this->M_a_board->update('', $data);
				
				// 여분 필드 재생성
				if(isset($extra)) {
					$this->load->dbforge();
					$this->dbforge->drop_table('ki_extra_'.$bid);
					foreach($extra AS $ex) {
						$field = array($ex['name'] => array('type' => $ex['attr'], 'null' => FALSE));
						if ($ex['size']) $field[$ex['name']]['constraint'] = $ex['size'];
						if ($ex['unsg']) $field[$ex['name']]['unsigned'] = $ex['unsg'];
						
						$this->dbforge->add_field($field);
					}
					$this->dbforge->create_table('ki_extra_'.$bid, TRUE);
				}
				alert('게시판 설정이 적용 되었습니다.', ADM_F.'/board/form/u/'.$bid);
			}
			else {
				alert('데이터가 올바르지 않거나 구 버전의 설정 파일은 적용할 수 없습니다.', ADM_F.'/board/form/u/'.$bid);
			}
		}
		
		alert('설정 데이타를 읽을 수 없습니다.', ADM_F.'/board/form/u/'.$bid);
	}
	
	function delete() {
		check_token(URL);
		
		if ($this->input->post('bid'))
			$bids = array($this->input->post('bid'));
		else if ($this->input->post('chk'))
			$bids = $this->input->post('chk');
		else
			alert('잘못된 접근입니다.');
		
		// 게시판 폴더 삭제
		$this->load->dbforge();
		$this->load->helper('admin');
		$ca_types = array();
		foreach($bids as $bid) {
			$this->dbforge->drop_table('ki_extra_'.$bid);
			rm_rf(DATA_PATH.'/file/'.$bid);
			$ca_types[] = 'bo_'.$bid;
		}
		
		$this->M_a_board->delete($bids, $ca_types);
	
		goto_url(URL);
	}
	
	function update() {
		check_token(URL);
		
		if ($this->input->post('chk')) {
			$bids =				$this->input->post('chk');
			$bo_subjects =		$this->input->post('bo_subject');
			$gr_ids =			$this->input->post('gr_id');
			$bo_layout =		$this->input->post('bo_layout');
			$bo_skins =			$this->input->post('bo_skin');
			$bo_use_searchs =	$this->input->post('bo_use_search');
			$bo_order_searchs =	$this->input->post('bo_order_search');
		}
		else
			alert('잘못된 접근입니다.');
		
		foreach ($bids as $bid) {
			$bo_use_search = (isset($bo_use_searchs[$bid])) ? $bo_use_searchs[$bid] : '';
			$this->M_a_board->list_update($bid, $bo_subjects[$bid], $gr_ids[$bid], $bo_layout[$bid], $bo_skins[$bid], $bo_use_search, $bo_order_searchs[$bid]);
		}
		
		goto_url(URL);
	}
}
?>
