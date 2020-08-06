<?php
class Uninsured extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model(array(ADM_F.'/M_a_uninsured', 'M_uninsured'));
		$this->output->enable_profiler(TRUE);
	}

	function lists() {
		include "init.php";
		$token = get_token();
		$mapCate = $this->M_uninsured->getBaseCategory(true);

		$cate_cd = trim($this->input->get('cate_cd'));
		if (!array_key_exists($cate_cd, $mapCate)) {
			$cate_cd = current(array_flip($mapCate));
		}

		$list = $this->M_a_uninsured->getItemDataList($cate_cd);


		$vars = array(
			'_TITLE_'  => '비급여 진료비',
			'_BODY_'   => ADM_F.'/uninsured/uninsured_list',
			'token'    => $token,
			'list'     => $list,
			'mapCate'  => $mapCate,
			'cate_cd'  => $cate_cd
		);

		$this->load->view('layout/layout_admin', $vars);
	}

	function form($cate_cd) {
		include "init.php";

		$ui_id = $this->input->get_post('ui_id');
		if (!$cate_cd && !$ui_id) show_404();

		$mapCate = $this->M_uninsured->getSubCategory($cate_cd, true);
		if ($ui_id) {
			$title = '수정';
			$mode = 'mod';
			$row = $this->M_a_uninsured->getItemDataRow($ui_id);
		} else {
			$title = '등록';
			$mode = 'add';
			$row = $this->db->get_columns($this->M_a_uninsured->table);
			$row['ui_use'] = 1;
		}

		$vars = array(
			'_TITLE_' => '비급여 진료비 '. $title,
			'_BODY_'  => ADM_F.'/uninsured/uninsured_form',
			'_CSS_'   => array('admin'),

			'mapCate' => $mapCate,
			'cate_cd' => $cate_cd,
			'ui_id'   => $ui_id,
			'mode'    => $mode,
			'row'     => $row
		);

		$this->load->view('layout/layout_blank', $vars);
	}

	function act() {
		$mode = $this->input->post('mode');
		$cate_cd = $this->input->post('cate_cd');
		$ui_id = intval($this->input->post('ui_id'));

		if (in_array($mode, array('add', 'mod'))) {
			$data = array(
					  'uic_cd'        => trim($this->input->post('uic_cd'))
					, 'ui_title'      => trim($this->input->post('ui_title'))
					, 'ui_code'       => trim($this->input->post('ui_code'))
					, 'ui_price_unit' => trim($this->input->post('ui_price_unit'))
					, 'ui_price_cost' => ($val = intval($this->input->post('ui_price_cost'))) > 0 ? $val : null
					, 'ui_price_min'  => ($val = intval($this->input->post('ui_price_min')))  > 0 ? $val : null
					, 'ui_price_max'  => ($val = intval($this->input->post('ui_price_max')))  > 0 ? $val : null
					, 'ui_comment'    => trim($this->input->post('ui_comment'))
					, 'ui_use'        => $this->input->post('ui_use') == 1 ? 1 : 0
				);
			if ($data['ui_title'] == '') alert('명칭이 필요합니다.');
			if ($data['ui_price_unit'] == '') alert('구분이 필요합니다.');
			$mapCate = $this->M_uninsured->getSubCategory($cate_cd, true);
		}

		switch ($mode) {
			case 'add' :
				if (!array_key_exists($data['uic_cd'], $mapCate)) alert('분류코드가 유효하지 않습니다.');
				$data['ui_sort'] = $this->M_a_uninsured->getNewSort($data['uic_cd']);
				if (!$this->M_a_uninsured->execInsertItem($data)) alert('데이터 추가 중 오류가 발생하였습니다.');
				$redirect_mode = 'parent';
				$msg = '등록하였습니다.';
				break;
			case 'mod' :
				if (!$this->M_a_uninsured->getItemDataRow($ui_id)) alert('수정할 데이터를 찾을 수 없습니다.');
				unset($data['uic_cd']);
				if (!$this->M_a_uninsured->execUpdateItem($ui_id, $data)) alert('데이터 수정 중 오류가 발생하였습니다.');
				$redirect_mode = 'parent';
				$msg = '수정하였습니다.';
				break;
			case 'del' :
				if (!$this->M_a_uninsured->getItemDataRow($ui_id)) alert('삭제할 데이터를 찾을 수 없습니다.');
				if (!$this->M_a_uninsured->execDeleteItem($ui_id)) alert('데이터 삭제 중 오류가 발생하였습니다.');
				$redirect_mode = 'self';
				$msg = '삭제하였습니다.';
				break;
			case 'mup' :
				if (!($data = $this->M_a_uninsured->getItemDataRow($ui_id))) alert('이동할 데이터를 찾을 수 없습니다.');
				echo '<pre>'. print_r ($data, true) .'</pre>';
				$this->M_a_uninsured->execMoveUpItem($data);
				$redirect_mode = 'redirect';
				$msg = '';
				break;
			case 'mdn' :
				if (!($data = $this->M_a_uninsured->getItemDataRow($ui_id))) alert('이동할 데이터를 찾을 수 없습니다.');
				$this->M_a_uninsured->execMoveDnItem($data);
				$redirect_mode = 'redirect';
				$msg = '';
				break;
			default :
				alert('실행모드를 확인바랍니다.');
				break;
		}

		$url = '/adm/uninsured/lists?cate_cd='. $cate_cd;
		switch ($redirect_mode) {
			case 'parent' :
				alert_dlg_close($msg, 'parent.location.href="'. $url .'";');
				break;
			case 'self' :
				alert($msg, $url);
				break;
			case 'redirect' :
				$this->load->helper('url');
				redirect($url);
				break;
		}
	}

	function cate() {
		include "init.php";
		$token = get_token();

		$list = $this->M_a_uninsured->getCateAll();

		$vars = array(
			'_TITLE_' => '비급여 진료비 분류',
			'_BODY_'  => ADM_F.'/uninsured/uninsured_cate',
			'token'   => $token,
			'list'    => $list,
		);

		$this->load->view('layout/layout_admin', $vars);
	}

	function cate_act() {
		$mode = $this->input->post('mode');
		$uic_cd     = trim($this->input->post('uic_cd'));
		$uic_parent = trim($this->input->post('uic_parent'));
		$uic_title  = trim($this->input->post('uic_title'));
		$uic_use    = intval($this->input->post('uic_use')) == 1 ? 1 : 0;

		switch ($mode) {
			case 'add' :
				if ($uic_title == '') alert('분류명이 필요합니다.');
				$uic_cd = $this->M_a_uninsured->getNewCateCode($uic_parent);
				$uic_sort = $this->M_a_uninsured->getNewCateSort($uic_parent);
				$data = array(
						  'uic_cd'     => $uic_cd
						, 'uic_title'  => $uic_title
						, 'uic_parent' => $uic_parent
						, 'uic_sort'   => $uic_sort
						, 'uic_use'    => $uic_use
					);

				$result = $this->M_a_uninsured->execInsertCate($data);
				if (!$result) alert('분류명 추가 중 오류가 발생하였습니다.');
				$msg = '분류명을 추가하였습니다.';
				break;
			case 'mod' :
				if ($uic_title == '') alert('분류명이 필요합니다.');
				if (!$this->M_a_uninsured->getCateRow($uic_cd)) alert('수정할 분류명을 찾을 수 없습니다.');
				$data = array(
						  'uic_title'  => $uic_title
						, 'uic_use'    => $uic_use
					);
				$result = $this->M_a_uninsured->execUpdateCate($uic_cd, $data);
				if (!$result) alert('분류명 수정 중 오류가 발생하였습니다.');
				$msg = '분류명을 수정하였습니다.';
				break;
			case 'del' :
				if (!$this->M_a_uninsured->getCateRow($uic_cd)) alert('삭제할 분류명을 찾을 수 없습니다.');
				$result = $this->M_a_uninsured->execDeleteCate($uic_cd);
				if (!$result) alert('분류명 삭제 중 오류가 발생하였습니다.');
				$msg = '분류명을 삭제하였습니다.';
				break;
			case 'mup' :
				if (!($data = $this->M_a_uninsured->getCateRow($uic_cd))) alert('이동할 분류명을 찾을 수 없습니다.');
				$this->M_a_uninsured->execMoveUpCate($data);
				break;
			case 'mdn' :
				if (!($data = $this->M_a_uninsured->getCateRow($uic_cd))) alert('이동할 분류명을 찾을 수 없습니다.');
				$this->M_a_uninsured->execMoveDnCate($data);
				break;
			default :
				alert('실행모드를 확인바랍니다.');
				break;
		}

		$url = '/adm//uninsured/cate';
		if ($msg) {
			alert($msg, $url);
		} else {
			$this->load->helper('url');
			redirect($url);
		}
	}
}
?>