<?php
class Point_delete extends CI_Controller {
	function __construct() {
		parent::__construct();
		check_token(ADM_F.'/point/lists');
		$this->load->model(ADM_F.'/M_a_point');
	}

	function index() {
		if ($this->input->post('chk')) {
			$po_ids = $this->input->post('chk');
			$mb_ids = array_unique($this->input->post('mb_ids'));
		}
		else
			alert('잘못된 접근입니다.');

		$this->M_a_point->point_delete($po_ids);					

		foreach ($mb_ids as $mb_id) {
			$this->M_a_point->point_reset($mb_id);
		}
		
		goto_url(URL);
	}
}
?>
