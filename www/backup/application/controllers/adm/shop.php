<?php
class Shop extends CI_Controller {
	var $shop_cate = '';
	var $staff = array();
	var $full_cate = array();
	var $img_max = array('w' => 1024, 'h' => 768);		// 최대 이미지 사이즈. 에디터 제외
	
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_layout', ADM_F.'/M_a_shop', 'M_staff', 'M_upload_files', 'M_shop'));
		$this->load->helper(array('category', 'search'));
		
		$this->shop_cate = $this->M_shop->list_result_cate();
		$this->full_cate = category_fullCate($this->shop_cate);
		$this->full_cate_id = category_fullCateId($this->shop_cate);
		
		/* MD 리스트 */
		$staff_arr = array();
		$st_res = $this->M_staff->list_result('st_id,st_name', "st_type = '". array_search('MD', $this->M_staff->type) ."'");
		foreach($st_res as $staff)
			$this->staff[$staff['st_id']] = $staff['st_name'];
	}
	
	function index() {
		$this->lists();
	}
	
	function config() {
		include "init.php";
		
		$this->load->library('encrypt');
		
		if (!isset($member['mb_id'])) exit;
	
		$this->load->library('form_validation');
		
		$shop_conf = $this->M_shop->getConfig();
		
		$config = array(
				array('field'=>'old_password', 'label'=>'현재 비밀번호', 'rules'=>'trim|required|min_length[4]|md5')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
				
			$vars = array(
					'_TITLE_'		=> '쇼핑몰 설정',
					'_BODY_'		=> ADM_F.'/shop/shop_conf',
					'_JS_'			=> array('jvalidate', 'jvalid_ext'),
	
					'token'			=> get_token(),
	
					'shop_conf'		=> $shop_conf,
					'pg_list'		=> $this->M_a_shop->pg_co
			);
				
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
				
			$id = $this->input->post('scf_id');
				
			if (!($this->encrypt->decode($member['mb_password']) == $this->input->post('old_password') && $this->input->post('old_password')))
				alert('현재 비밀번호가 맞지 않습니다.', ADM_F.'/shop/config');
				
			$data = array(
					'pg_id'				=> $this->input->post('pg_id'),
					'pg_code'			=> $this->input->post('pg_code'),
					'pg_store'			=> $this->input->post('pg_store'),
					'pg_is_real'		=> $this->input->post('pg_is_real'),
					'dlv_price'			=> $this->input->post('dlv_price'),
					'dlv_additional'	=> $this->input->post('dlv_additional'),
					'dlv_free'			=> $this->input->post('dlv_free'),
					'dlv_deliverer'		=> $this->input->post('dlv_deliverer')
			);
			$res = $this->M_a_shop->setConfig($data);
				
			if(!$res)
				alert('ERROR: 저장 실패!', ADM_F.'/shop/config/'. $id);
			else
				alert('저장 되었습니다.', ADM_F.'/shop/config/'. $id);
		}
	}
	
	function lists() {
		include "init.php";
		
		$this->load->helper(array('sideview', 'textual'));
		$this->load->library('pagination');
		
		// 서치
		$seg = new search_seg;
		$page = $seg->get_seg('page');
		$sst = $seg->get_seg('sst');
		$sod = $seg->get_seg('sod');
		$sfl = $seg->get_seg('sfl');
		$stx = $seg->get_seg('stx');
		
		// 정렬
		if ($page < 1) $page = 1;
		if ($stx) $stx = search_decode($stx);
		if (!$sst) $sst = 'pd_id';
		if (!$sod) $sod = 'desc';
		
		$qstr = $seg->get_qstr();
		
		// 페이징
		$pageConf['suffix'] =	$qstr;
		$pageConf['base_url'] =	RT_PATH.'/'.ADM_F.'/shop/lists/page/';
		$pageConf['per_page'] =	15;
		
		$offset = ($page - 1) * $pageConf['per_page'];
		
		// 데이터
		$result = $this->M_a_shop->list_result($sst, $sod, $sfl, $stx, $pageConf['per_page'], $offset);
		$list = $result['qry'];
		
		// 페이징(뷰어)
		$pageConf['total_rows'] = $result['total_cnt'];
		$this->pagination->initialize($pageConf);
		
		$token = get_token();
		
		// 추가 데이터
		foreach ($list as $i => $row) {
			$list[$i]['no'] =			$result['total_cnt'] - (($page-1) * $pageConf['per_page']) - $i;
			$list[$i]['lst'] =			$i % 2;
			
			$list[$i]['pd_cate'] =		isset($this->full_cate[$list[$i]['pd_cate']]) ? $this->full_cate[$list[$i]['pd_cate']] : '';
			$list[$i]['pd_regdate'] =	date('Y-m-d', strtotime($list[$i]['pd_regdate']));
			$list[$i]['soldout_chk'] =	$list[$i]['pd_soldout'] ? "checked='checked'" : '';
			$list[$i]['hidden_chk'] =	$list[$i]['pd_hidden'] ? "checked='checked'" : '';
			$list[$i]['pd_price'] =		number_format($list[$i]['pd_price']);
			
			$list[$i]['s_mod'] =		icon('수정', 'shop/form/u/'.$list[$i]['pd_id'].".$qstr");
			$list[$i]['s_del'] =		icon('삭제', "javascript:post_s('".ADM_F."/shop/delete', {id:'".$list[$i]['pd_id']."', token:'".$token."', qstr:'".$qstr."'}, true);");
			$list[$i]['s_view'] =		'';
		}
		
		$sort_link['pd_name'] =		sort_link('pd_name') ."?qstr=$qstr";
		$sort_link['pd_price'] =	sort_link('pd_price') ."?qstr=$qstr";
		$sort_link['pd_cate'] =		sort_link('pd_cate') ."?qstr=$qstr";
		$sort_link['pd_soldout'] =	sort_link('pd_soldout') ."?qstr=$qstr";
		$sort_link['pd_regdate'] =	sort_link('pd_regdate') ."?qstr=$qstr";
		
		$vars = array(
			'_TITLE_'		=> '상품 리스트',
			'_BODY_'		=> ADM_F.'/shop/shop_list',
			
			'token'			=> $token,
			'list'			=> $list,
			'qstr'			=> $qstr,
			's_add'			=> icon('추가', "shop/form"),
			'sfl'			=> $sfl,
			'stx'			=> $stx,
			'total_cnt'		=> $result['total_cnt'],
			'paging'		=> $this->pagination->create_links(),
			'sort_link'		=> $sort_link
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function form($w = '', $id = '') {
		include "init.php";
		
		$this->load->model('M_editor');
		$this->load->library('form_validation');
		
		$seg = new search_seg;
		$qstr = $seg->get_qstr();
		
		$config = array(
			array('field'=>'pd_name', 'label'=>'상품명', 'rules'=>'trim|required|max_length[150]|xss_clean'),
			array('field'=>'pd_cate', 'label'=>'카테고리', 'rules'=>'trim|required|xss_clean'),
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			// 유효화
			if ($w == '' || $w != 'u') {
				$title = '등록';
				$row = $this->db->get_columns($this->M_a_shop->table);

				$row['pd_show'] = 1;
				$row['pd_options'] = array();
				$row['files'] = array();
			}
			else if ($w == 'u') {
				$title = '수정';
				
				$row = $this->M_shop->row($id);
				if (!isset($row['pd_id']))
					alert('등록된 자료가 없습니다.');
				
				$row['files'] = $this->M_upload_files->get_files($this->M_shop->table, $id, '*');
				$row['pd_md'] = $row['pd_md'] ? $row['pd_md'] : '';
			}
			
			// 에디터 정보 수집
			if ($w == 'u') {
				$edt_info = $this->M_editor->get_info($this->M_a_shop->table, $id);
				$edt_info = json_encode($edt_info);
			}
			// 에디터 매개변수
			$edt = array(
				'content' => $row['pd_content'],
				'edt_info' => isset($edt_info) ? $edt_info : '[]',
				'wr_table' => $this->M_a_shop->table,
				'upload_size' => EDITOR_UPLOAD_SIZE * 1048576
			);
			$edt['buttons']['gallery'] = 1;
			$edt['buttons']['file'] = 0;
			$edt['buttons']['outcont'] = 1;
			$editor = $this->load->view('editor/editor', $edt, TRUE);
			$content = ''; // 그냥 비우기
			
			// 체크박스 & 셀렉트 메뉴
			$row['hidden_chk'] = $row['pd_hidden'] ? "checked='checked'" : '';
			$row['soldout_chk'] = $row['pd_soldout'] ? "checked='checked'" : '';
			$row['pd_cate_sel'] = isset($this->full_cate_id[$row['pd_cate']]) ? $this->full_cate_id[$row['pd_cate']] : array('');
			
			// 상품 카테고리 리스트
			// 메인 카테고리
			$cate_child_arr = $cate_arr = $cate_child = array();
			foreach($this->shop_cate as $category) {
				if($category['ca_pid'] == 0)
					$cate_arr[$category['ca_id']] = $category['ca_name'];
				else {
					$cate_child_arr[$category['ca_pid']][$category['ca_id']] = $category['ca_name'];
				}
			}			
			// 하위 카테고리
			foreach($cate_child_arr as $pid => $child) {
				$HTML = '<select id="cate_child'.$pid.'" name="pd_cate" onchange="sel_cate($(this));">';
				$HTML .= '<option value="">선택</option>';
				foreach($child as $val => $str) {
					$HTML .= '<option value="'.$val.'">'.$str.'</option>';
				}
				$HTML .= '</select>';
				$cate_child[$pid] = $HTML;
			}
			
			$vars = array(
				'_TITLE_'		=> '상품 '.$title,
				'_BODY_'		=> ADM_F.'/shop/shop_form',
				'_CSS_'			=> array('editor'),
				'_JS_'			=> array('../editor/js/editor_loader','jvalidate','jvalid_ext'),
				
				'w'				=> $w,
				'qstr'			=> $qstr,
				
				'token'			=> get_token(),
				'editor'		=> $editor,
				'staff'			=> $this->staff,
				
				'cate'			=> $cate_arr,
				'cate_child'	=> $cate_child,
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);

		}
		else {
			check_token();
						
			$this->load->model('M_editor');
			
			$w = $this->input->post('w');
			$id = $this->input->post('pd_id');
			$pd_content = $this->input->post('wr_content');
			$pd_options = $this->input->post('pd_options');
			$qstr = $this->input->post('qstr');
			
			// 대표이미지
			$pd_nos = $this->input->post('oldFileNo');
			$delFile = $this->input->post('delFile') ? $this->input->post('delFile') : array();
			
			$row = $this->M_shop->row($id);
			if (!$w) {
				if (isset($row['pd_id']))
					alert('이미 존재하는 ID 입니다.');
			}
			else if ($w != 'u')
				alert('잘못된 접근입니다.');
			
			$flds = array('pd_name', 'pd_maker', 'pd_cate', 'pd_md', 'pd_price_ori', 'pd_price', 'pd_dlvFree', 'pd_soldout', 'pd_hidden', 'pd_benefit', 'pd_content');
			foreach($flds AS $fld) {
				$data[$fld] = $this->input->post($fld);
			}
			$data['pd_mdydate'] = TIME_YMDHIS;
			
			if($id = $this->M_a_shop->record($w, $data)) {
				$this->M_a_shop->set_options($id, $pd_options);
			}
			
			// 폼 파일 삭제
			if(is_array($delFile)) {
				foreach($delFile as $field => $no) {
					$this->M_upload_files->file_delete($this->M_a_shop->table, $id, $no);
					$this->M_a_shop->db->update($this->M_a_shop->table, array($field => ''), array('pd_id' => $id));
				}
			}
			
			// 폼 파일 업로드
			if(isset($_FILES['pd_image']) && $_FILES['pd_image']['tmp_name']) {
				// 이미지 파일이 아니면 제거
				if(is_array($_FILES['pd_image']['tmp_name'])) {
					foreach($_FILES['pd_image']['tmp_name'] as $key => $val) {
						if($val) {
							$size = @getimagesize($val);
							$fldName[$val] = 'pd_image'.$key;
							
							// gif, jpg, png 형식이 아니거나 이미지 사이즈가 너무 크면 제거
							if($size[2] == 0 || $size[2] > 3) {
								alert_continue($_FILES['pd_image']['name'][$key].': 이 파일은 업로드 할 수 없는 형식입니다.');
								unset($_FILES['pd_image']['tmp_name'][$key]);
							}
							else if($size[0] > $this->img_max['w'] || $size[1] > $this->img_max['h']) {
								alert_continue($_FILES['pd_image']['name'][$key].': 이미지의 크기가 너무 큽니다.\\n\\n최대 W: '.$this->img_max['w'].'\\n최대 H: '.$this->img_max['h']);
								unset($_FILES['pd_image']['tmp_name'][$key]);
							}
						}
					}
				}
				else {
					$size = @getimagesize($_FILES['pd_image']['tmp_name']);
					if($size[2] == 0) unset($_FILES['pd_image']);
				}
				
				$pd_nos = $this->M_upload_files->form_upload($this->M_a_shop->table, $id, $_FILES['pd_image']);
				
				// 이미지 DB 업데이트
				if(count($pd_nos) > 0) {
					foreach($pd_nos as $no => $filename) {
						if($row[$fldName[$filename]]) {
							$this->M_upload_files->file_delete($this->M_a_shop->table, $id, $row[$fldName[$filename]]);
						}
						$this->M_a_shop->db->update($this->M_a_shop->table, array($fldName[$filename] => $no), array('pd_id' => $id));
					}
				}
			}
			
			// 에디터 파일 업로드
			$pd_content = $this->M_editor->uploadFile($this->M_a_shop->table, $id, $pd_content);
			
			// 에디터 내용에서 첨부 파일 경로 수정 (temp -> real)
			$this->M_a_shop->db->update($this->M_a_shop->table, array('pd_content' => $pd_content), array('pd_id' => $id));
			
			goto_url(ADM_F.'/shop/form/u/'.$id.$qstr);
		}
	}
	
	function update() {
		check_token(URL);
	
		$ids = $this->input->post('chk');
		$pd_soldout = $this->input->post('pd_soldout');
		$pd_hidden = $this->input->post('pd_hidden');
	
		if(!$ids)
			return false;
	
		$data = array(
				'pd_soldout' => $pd_soldout,
				'pd_hidden' => $pd_hidden
		);
	
		$this->M_a_shop->list_update($ids, $data);
	
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
			$this->M_upload_files->file_delete($this->M_a_shop->table, $i);
			$this->M_a_shop->delete($i);
		}
		
		goto_url(URL);
	}
	
	function arr_conv($arr) {
		if(!is_array($arr)) return;
		$ret_arr = '';
		foreach($arr as $group => $flds) {
			foreach($flds as $fld => $f_arr) {
				if($f_arr['input'] != 'file')
					$ret_arr[] = preg_replace('/\[|\]/', '', $fld);
			}
		}
		return $ret_arr;
	}
	
	
	/*--- 카테고리 관련 ---*/
	function cate() {
		include "init.php";
		
		$id = $this->input->get('id');
		$pid = $this->input->get('pid');
		
		$row = array_merge(array('ca_pname'=>''), $this->db->get_columns($this->M_shop->table_cate));
		
		//$row['ca_parameter'] = json_encode(array('pageNum'=>0,'subNum'=>0));
		
		if(is_numeric($id)) $row = $this->M_shop->row_cate($id, 'a.*, b.ca_name AS ca_pname', TRUE);
		else if($pid) $row['ca_pid'] = $pid;
		// id가 숫자가 아닌 경우에는 샵 설정 테이블에 저장됨
		else if($id) {
			$shop_conf = $this->M_shop->getConfig();
			$row['ca_id'] =			$id;
			$row['ca_layout'] =		$shop_conf[$id .'_layout'];
			$row['ca_skin'] =		$shop_conf[$id .'_skin'];
			$row['ca_parameter'] =	$shop_conf[$id .'_parameter'];
			$row['ca_hidden'] =		0;
		}
		
		// 체크박스 & 셀렉트 메뉴
		$row['hidden_chk'] = $row['ca_hidden'] ? "checked='checked'" : '';
		
		$list = $this->M_shop->list_result_cate();
		
		$token = get_token();
		$vars = array(
			'_TITLE_'		=> '관리자 메뉴 설정',
			'_BODY_'		=> ADM_F.'/shop/shop_cate',
			'_CSS_'			=> array('table'),
			'_JS_'			=> array('jvalidate'),
			
			'token'			=> $token,
			'list'			=> $list,
			'row'			=> $row,
			
			'layout_select'	=> get_layout_select('ca_layout', $row['ca_layout']),
			'skin_select'	=> get_skin_dir('shop', 'ca_skin', $row['ca_skin'], TRUE)
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function update_cate() {
		check_token();
	
		$w = $this->input->post('w');
		$ca_id = $this->input->post('ca_id');
		$ca_parameter = $this->input->post('ca_parameter');
	
		if (!$w) {
			$pca = $this->M_shop->row_cate($ca_id, 'ca_id');
			if (isset($pca['ca_id']))
				alert('이미 존재하는 ID 입니다.');
		}
		else if ($w == 'u') {
			// what!?
		}
		else
			alert('잘못된 접근입니다.');
	
		$parameter = param_encode($ca_parameter);
	
		// 수정 모드이고 id값이 숫자가 아니면 샵 설정 테이블에 저장됨
		if($w == 'u' && !is_numeric($ca_id)) {
			$data = array(
					$ca_id .'_layout'		=> $this->input->post('ca_layout'),
					$ca_id .'_skin'			=> $this->input->post('ca_skin'),
					$ca_id .'_parameter'	=> array('', $parameter)
			);
			
			$res = $this->M_a_shop->setConfig($data);
		}
		else {
			$data = array(
					'ca_pid'			=> $this->input->post('ca_pid'),
					'ca_name'			=> $this->input->post('ca_name'),
					'ca_sort'			=> $this->input->post('ca_sort'),
					'ca_layout'			=> $this->input->post('ca_layout'),
					'ca_skin'			=> $this->input->post('ca_skin'),
					'ca_parameter'		=> $parameter,
					'ca_hidden'			=> $this->input->post('ca_hidden'),
					'ca_mdydate'		=> TIME_YMDHIS
			);
			$ca_id = $this->M_a_shop->record_cate($w, $data);
		}
	
		if($ca_id) {
			alert('저장 되었습니다.', ADM_F.'/shop/cate?id='.$ca_id);
		}
		else {
			alert('저장 실패!');
		}
	}
	
	function delete_cate() {
		check_token();
	
		$id = $this->input->post('id');
	
		if($this->M_a_shop->delete_cate($id))
			alert('삭제 되었습니다.', ADM_F.'/shop/cate');
		else
			alert('삭제 실패!', ADM_F.'/shop/cate?id='.$id);
	}
}
?>
