<?php
class Point extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model(ADM_F.'/M_a_menus');
		$this->load->model(ADM_F.'/M_a_point');
		$this->load->library(array('form_validation', 'pagination'));
		$this->load->helper(array('admin', 'sideview', 'search'));

		if (!$this->config->item('cf_use_point'))
			goto_url(ADM_F);
	}
	
	function lists() {
		include "init.php";
		
		$config = array(
			array('field'=>'mb_id', 'label'=>'아이디', 'rules'=>'trim|required|max_length[20]|xss_clean'),
			array('field'=>'po_content', 'label'=>'포인트내용', 'rules'=>'trim|required'),
			array('field'=>'po_point', 'label'=>'포인트', 'rules'=>'trim|required|numeric')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {

			$seg  = new search_seg;
			$page = $seg->get_seg('page');
			$sst  = $seg->get_seg('sst');
			$sod  = $seg->get_seg('sod');
			$sfl  = $seg->get_seg('sfl');
			$stx  = $seg->get_seg('stx');

			if ($page < 1) $page = 1;
			if ($stx) $stx = search_decode($stx);
			if (!$sst) $sst = 'po_id';
			if (!$sod) $sod = 'desc';
	
			$qstr = $seg->get_qstr();
			$config['suffix'] = $qstr;
			$config['base_url'] = RT_PATH.'/'.ADM_F.'/point/lists/page/';
			$config['per_page'] = 15;

			$offset = ($page - 1) * $config['per_page'];
			$result = $this->M_a_point->list_result($sst, $sod, $sfl, $stx, $config['per_page'], $offset);

			$config['total_rows'] = $result['total_cnt'];
			$this->pagination->initialize($config);

			if ($sfl == 'mb_id' && $stx && $result['total_cnt'] > 0) {
				$total_pnt = $stx.' 님 포인트 합계 : ' . number_format($result['total_pnt']) . '점';
				$stx_mb_id = TRUE;
			} else
				$total_pnt = '전체 포인트 합계 : ' . number_format($result['total_pnt']) . '점';

			$list = array();
			foreach ($result['qry'] as $i => $row) {
				if ($this->config->item('cf_use_nick'))
					$list[$i]->mb_nick = $row['mb_nick'];

				$link1 = $link2 = '';
				if (!preg_match("/^\@/", $row['po_rel_table']) && $row['po_rel_table'])
					$po_content = "<a href='".RT_PATH."/board/view/tbl/".$row['po_rel_table']."/".$row['po_rel_id']." target=_blank'>".$row['po_content']."</a>";
				else
					$po_content = $row['po_content'];

				$list[$i]->lst = $i%2;
				$list[$i]->po_id = $row['po_id'];
				$list[$i]->mb_id = $row['mb_id'];
				$list[$i]->po_datetime = substr($row['po_datetime'], 2, 8);
				$list[$i]->po_content = $po_content;
				$list[$i]->po_point = number_format($row['po_point']);
				$list[$i]->mb_name = get_sideview($row['mb_id'], $row['mb_name']);
				$list[$i]->mb_point = number_format($row['mb_point']);
			}

			$vars = array(
				'_TITLE_'		=> '포인트관리',
				'_BODY_'		=> ADM_F.'/point_list',
				'_CSS_'			=> array('jquery-ui'),
				'_JS_'			=> array('jvalidate', 'jvalid_ext', 'sideview'),
				
				'token' => get_token(),

				'list' => $list,
				'use_nick' => $this->config->item('cf_use_nick'),
		
				'sfl' => $sfl,
				'stx' => $stx,
				'stx_mb_id' => (isset($stx_mb_id)) ? $stx : '',

				'total_cnt' => number_format($result['total_cnt']),
				'total_pnt' => $total_pnt,
				'paging' => $this->pagination->create_links(),

				'sort_mb_id' => sort_link('mb_id'),
				'sort_po_datetime' => sort_link('po_datetime'),
				'sort_po_content' => sort_link('po_content'),
				'sort_po_point' => sort_link('po_point')
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else {
			check_token();
			$member = unserialize(MEMBER);
			$mb_id = $this->input->post('mb_id');
			$po_point = $this->input->post('po_point');
			$mb = $this->M_basic->get_member($mb_id, 'mb_id,mb_point');

			if (!isset($mb['mb_id']))
				alert('존재하는 회원아이디가 아닙니다.');

			if (($po_point < 0) && ($po_point * (-1) > $mb['mb_point']))
				alert('포인트를 깎는 경우 현재 포인트보다 작으면 안됩니다.');

			$this->load->model('M_point');
			$this->M_point->insert($mb_id, $po_point, $this->input->post('po_content'), '@passive', $mb_id, $member['mb_id'].'-'.uniqid(''));

			goto_url(ADM_F.'/point/lists');
		}
	}
}
?>
