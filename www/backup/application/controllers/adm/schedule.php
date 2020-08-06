<?php
class Schedule extends CI_Controller {
	var $empty_task = array('am' => array('text' => '', 'color' => '', 'show_time' => '', 'time' => ''), 'pm' => array('text' => '', 'color' => '', 'show_time' => '', 'time' => ''));
	
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_schedule', 'M_schedule'));
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
		if ($stx) $stx = array_search(search_decode($stx), $schedule_groups); 
		if (!$sst) $sst = 'sc_id';
		if (!$sod) $sod = 'asc';

		$qstr = $seg->get_qstr();
		$config['suffix'] = $qstr;
		$config['base_url'] = RT_PATH.'/'.ADM_F.'/schedule/lists/page/';
		$config['per_page'] = 15;
		
		$offset = ($page - 1) * $config['per_page'];
		$result = $this->M_schedule->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);
		$list = $result['qry'];
		
		$config['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($config);
		
		$token = get_token();
		foreach ($list as $i => $row) {
			$list[$i] = $row;
			
			$row['task'] = unserialize($row['sc_task']);
			
			$list[$i]['s_mod'] = icon('수정', "javascript:layerWin('/adm/schedule/form/u/". $row['sc_id'] ."', 'write', 500, 380, 'no');");
			$list[$i]['s_del'] = icon('삭제', "javascript:post_s('".ADM_F."/schedule/delete', {id:'".$row['sc_id']."', token:'".$token."'}, true);");
			
			$list[$i]['lst'] = $i%2;
			$list[$i]['week']['mon'] = isset($row['task']['mon']) ? array_merge($this->empty_task, $row['task']['mon']) : $this->empty_task;
			$list[$i]['week']['tue'] = isset($row['task']['tue']) ? array_merge($this->empty_task, $row['task']['tue']) : $this->empty_task;
			$list[$i]['week']['wed'] = isset($row['task']['wed']) ? array_merge($this->empty_task, $row['task']['wed']) : $this->empty_task;
			$list[$i]['week']['thu'] = isset($row['task']['thu']) ? array_merge($this->empty_task, $row['task']['thu']) : $this->empty_task;
			$list[$i]['week']['fri'] = isset($row['task']['fri']) ? array_merge($this->empty_task, $row['task']['fri']) : $this->empty_task;
			
			foreach($list[$i]['week'] AS $week => $day) {
				foreach($day AS $key => $ampm) {
					$add_href = "/adm/schedule/form_time/". $row['sc_id'] ."/$week/$key";
					$add_href = "javascript:layerWin('$add_href', 'write', 470, 260, 'no');";
					$list[$i]['week'][$week][$key] = $ampm['text'] ? '<a href="'. $add_href .'">'. $ampm['text'] .'<br/>▼</a>' :  icon('추가', "$add_href");
				}
			}
		}
		
		$vars = array(
			'_TITLE_'			=> '진료 시간표',
			'_BODY_'			=> ADM_F.'/schedule/schedule_list',
			
			'token'				=> $token,
			
			'bimg_path'			=> preg_replace('/^'.addcslashes(DATA_PATH, '/').'/', DATA_DIR, $this->M_schedule->filepath()),
			'list'				=> $list,
			's_add'				=> icon('추가', 'schedule/form'),

			'sfl'				=> $sfl,
			'stx'				=> $stx,		

			'total_cnt'			=> number_format($result['total_cnt']),
			'paging'			=> $this->pagination->create_links(),

			'sort_name'		=> sort_link('sc_name'),
			'sort_grade'		=> sort_link('sc_grade')
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w='', $id='') {
		include "init.php";
		
		$max_size = array('w' => 1024, 'h' => 1024);
		
		$this->load->library(array('upload', 'form_validation'));

		$config = array(
			array('field'=>'sc_name', 'label'=>'이름', 'rules'=>'trim|max_length[50]required|xss_clean')
		);

		$this->form_validation->set_rules($config);
		if($this->form_validation->run() == FALSE) {
			if($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_schedule->table);
				$row['fields'] = array('etc' => '');
			}
			else if($w == 'u') {
				$title = '수정';
				$row = $this->M_schedule->row($id);
				$fields = explode('|', $row['sc_fields']);
				
				$row['fields'] = array();
				foreach($fields AS $fld) {
					if(array_search($fld, $this->M_schedule->fld_arr) !== FALSE) {
						$row['fields'][$fld] = TRUE;
						unset($fields[array_search($fld, $fields)]);
					}
				}
				$row['fields']['etc'] = implode('', $fields);
				
				if(!$row) {
					alert('등록된 자료가 없습니다.');
				}
			}
			
			$vars = array(
				'_TITLE_'		=> '진료 시간표 '.$title,
				'_BODY_'		=> ADM_F.'/schedule/schedule_form',
				'_JS_'			=> array('jvalidate'),
				
				'w'				=> $w,
				'max_size'		=> $max_size,
				
				'row'			=> $row,
				'bimg_path'		=> preg_replace('/^'.addcslashes(DATA_PATH, '/').'/', DATA_DIR, $this->M_schedule->filepath())
			);
			
			$this->load->view('layout/layout_blank', $vars);
		}
		else
		{
			$w = $this->input->post('w');
			$id = $this->input->post('sc_id');
			$delfile = $this->input->post('delfile');
			
			// 등록된 정보
			$row = $this->M_schedule->row($id);
			$row['sc_image'] = isset($row['sc_image']) ? $row['sc_image'] : '';
			
			// 폼 파일 업로드 설정
			$config['upload_path'] =	$this->M_schedule->filepath();
			$config['allowed_types'] =	'gif|jpg|png';
			$config['max_size']	=		'2048';
			$config['max_width'] =		$max_size['w'];
			$config['max_height'] =		$max_size['h'];
			$config['encrypt_name'] =	TRUE;
			$this->upload->initialize($config);
			
			// 폼 파일 업로드
			$filename = $row['sc_image'] ? $row['sc_image'] : '';
			if($_FILES['sc_image']['name']) {
				if($this->upload->do_upload('sc_image')) {
					$upload_file = $this->upload->data();
					$filename = $upload_file['file_name'];
					
					$delfile = TRUE;
				}
				else {
					alert($this->upload->display_errors());
				}
			}
			
			if($delfile) {
				// 기존 파일 삭제
				@unlink($this->M_schedule->filepath().'/'.$row['sc_image']);
				@del_thumb($this->M_schedule->filepath().'/thumb', $row['sc_image']);
				
				$filename = $filename == $row['sc_image'] ? '' : $filename;
			}
			
			// DB 저장
			$data = array(
				'sc_name'		=> $this->input->post('name'),
				'sc_grade'		=> $this->input->post('grade'),
				'sc_fields'		=> is_array($this->input->post('fields')) ? implode('|', $this->input->post('fields')) : $this->input->post('fields'),
				'sc_image'		=> $filename,
				'sc_mdydate'	=> TIME_YMDHIS
			);
			$id = $this->M_a_schedule->record($w, $data);
			
			goto_url(ADM_F.'/schedule/lists', 'parent');
		}
	}
	
	function form_time($id, $week, $ampm) {
		include "init.php";
		
		$this->load->library('form_validation');
		
		$config = array(
				array('field'=>'task', 'label'=>'일정', 'rules'=>'trim|max_length[50]required|xss_clean')
		);
		
		$row = $this->M_schedule->row($id);
		$task = unserialize($row['sc_task']);
		$task = isset($task[$week]) ? array_merge($this->empty_task, $task[$week]) : $this->empty_task;
		$task = $task[$ampm];
		$task['time'] = $task['time'] ? unserialize($task['time']) : array_fill(0, 4, '00');
		
		$this->form_validation->set_rules($config);
		if($this->form_validation->run() == FALSE) {				
			if(!$row) {
				alert('등록된 자료가 없습니다.');
			}
			
			$vars = array(
				'_TITLE_'		=> '진료 시간표',
				'_BODY_'		=> ADM_F.'/schedule/schedule_form_time',
				'_CSS_'			=> array('jquery-ui'),
				'_JS_'			=> array('jvalidate', 'jquery-ui.min', 'jtimepicker'),
				
				'row'			=> $row,
				
				'task'			=> $task,
				'week'			=> $week,
				'ampm'			=> $ampm					
			);
				
			$this->load->view('layout/layout_blank', $vars);
		}
		else
		{
			$task = $this->input->post('task');
			$task_text = $this->input->post('task_text');
			$color = $this->input->post('color');
			$show_time = $this->input->post('show_time');
			$time = $this->input->post('time');
			
			$row['task'] = unserialize($row['sc_task']);
			$row['task'][$week][$ampm]['text'] = $task != '기타' ? $task : $task_text;
			$row['task'][$week][$ampm]['color'] = $color;
			$row['task'][$week][$ampm]['show_time'] = $show_time;
			$row['task'][$week][$ampm]['time'] = serialize($time);
			
			// DB 저장
			$data = array(
				'sc_task' => serialize($row['task']),
			);
			
			$id = $this->M_a_schedule->update($id, $data);
			
			goto_url(ADM_F.'/schedule/lists', 'parent');
		}
	}
	/*
	function update() {
		check_token(URL);
		
		$ids =			$this->input->post('chk');
		$sc_hidden =	$this->input->post('sc_hidden');
		
		if(!$ids)
			return false;

		$data = array(
			'sc_hidden'		=> $sc_hidden
		);
		
		$this->M_a_schedule->list_update($ids, $data);
		
		goto_url(URL);
	}
	*/
	
	function delete() {
		check_token(URL);
		
		$id = $this->input->post('id');
		$ids = $this->input->post('chk');
	
		if($id)
			$ids[] = $id;
	
		if(!$ids)
			return false;
		
		foreach($ids as $i) {
			$row = $this->M_schedule->row($i);
			@unlink($this->M_schedule->filepath().'/'.$row['sc_image']);
			@del_thumb($this->M_schedule->filepath().'/thumb', $row['sc_image']);
			$this->M_a_schedule->delete($i);
		}
		
		goto_url(URL);
	}
}
?>
