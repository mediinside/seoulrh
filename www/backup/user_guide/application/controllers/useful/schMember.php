<?php
class SchMember extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(ADM_F.'/M_a_member');
	}

	function qry($form, $finput, $retField) {
		if(!SU_ADMIN) {
			show_404();
		}

		$sfl = $this->input->get('sfl');
		$stx = $this->input->get('stx');
		
		$i = $cnt = 0;
		$list = array();
		
		if($stx) {
			$result = $this->M_a_member->list_result('mb_id', 'asc', $sfl, $stx, 99999, 0);
			$list = $result['qry'];
			$cnt = $result['total_cnt'];
		}
		
		$vars = array(
			'_TITLE_'		=> '회원 검색',
			'_BODY_'		=> 'useful/schMember',
			
			'form'			=> $form,
			'finput'		=> $finput,
			'retField'		=> $retField,
			
			'stx'			=> $stx,
			'sfl'			=> $sfl,
			'search_count'	=> $cnt,
			
			'list'			=> $list
		);
		
		$this->load->setLayout('layout/layout_blank');
		$this->load->view(null, $vars);
	}
}
?>
