<?php
class Research extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model(array(ADM_F.'/M_a_research', 'M_research'));
	}
	
	function config() {
		include "init.php";
		
		$this->load->library('form_validation');
		
		$config = array(
			array('field'=>'count',  'label'=>'강의 개수', 'rules'=>'trim|required|xss_clean')
		);
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if(!$row = $this->M_a_research->get_conf('semi')) {
				$row['rshc_count'] = 0;
			}
			
			$vars = array(
				'_TITLE_'		=> '세미나 강의 관리',
				'_BODY_'		=> ADM_F.'/research/rsh_config',
				'_JS_'			=> array('jvalidate'),
				
				'token'			=> get_token(),
				
				'row'			=> $row
			);
			
			$this->load->view('layout/layout_admin', $vars);
		}
		else
		{
			check_token();
			
			$id = $this->M_research->get_max_id('semi');

			$this->set_config(($id ? $id : 1), $this->input->post('count'));
			
			alert('저장 되었습니다.', ADM_F.'/research/config');
		}
	}
	
	function semi_stats() {
		include "init.php";
		
		$row = $this->M_a_research->get_score('semi');
		$total = array('sex' => 0, 'type' => 0);
		
		$row['ratio']['sex'] = $this->get_ratio($row['sex']);
		$row['ratio']['type'] = $this->get_ratio($row['type']);
		$row['ratio']['grade'] = $this->get_ratio($row['grade']);
		$row['sum']['plan'] = $this->get_sum($row['plan']);
		$row['sum']['time'] = $this->get_sum($row['time']);
		$row['sum']['circum'] = $this->get_sum($row['circum']);
		$row['sum']['data'] = $this->get_sum($row['data']);
		$row['average']['plan'] = $this->get_average($row['plan']);
		$row['average']['time'] = $this->get_average($row['time']);
		$row['average']['circum'] = $this->get_average($row['circum']);
		$row['average']['data'] = $this->get_average($row['data']);
		
		$vars = array(
			'_TITLE_'		=> '세미나 설문 통계',
			'_BODY_'		=> ADM_F.'/research/rsh_semi_stats',
			'_JS_'			=> array('jprintThis'),
			
			'row'			=> $row
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function sati_stats() {
		include "init.php";
		
		$row = $this->M_a_research->get_score('sati');

		$row['sum']['circum'] = $this->get_sum($row['circum']);
		$row['sum']['process'] = $this->get_sum($row['process']);
		$row['sum']['kind'] = $this->get_sum($row['kind']);
		$row['sum']['service'] = $this->get_sum($row['service']);
		$row['sum']['homepage'] = $this->get_sum($row['homepage']);
		$row['sum']['newspaper'] = $this->get_sum($row['newspaper']);
		$row['average']['circum'] = $this->get_average($row['circum']);
		$row['average']['process'] = $this->get_average($row['process']);
		$row['average']['kind'] = $this->get_average($row['kind']);
		$row['average']['service'] = $this->get_average($row['service']);
		$row['average']['homepage'] = $this->get_average($row['homepage']);
		$row['average']['newspaper'] = $this->get_average($row['newspaper']);
		
		$vars = array(
			'_TITLE_'		=> '세미나 설문 통계',
			'_BODY_'		=> ADM_F.'/research/rsh_sati_stats',
			'_JS_'			=> array('jprintThis'),
			
			'row'			=> $row
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function essay() {
		include "init.php";
		
		$row = $this->M_a_research->get_essay();
		
		$list = array('motive' => array(), 'supplement' => array());
		$total = array();
		foreach($row AS $key => $val) {
			$total[$val['rsho_type']] = isset($total[$val['rsho_type']]) ? $total[$val['rsho_type']] + 1 : 1;
		}
		foreach($row AS $key => $val) {
			$list['no'][$key] = $total[$val['rsho_type']] - count($list[$val['rsho_type']]);
			$list[$val['rsho_type']][$key] = $val['rsho_text'];
			$list['regdate'][$key] = $val['rsho_regdate'];
			$list['delete'][$key] = icon('삭제', "javascript:post_s('".ADM_F."/research/delete_essay', {rsh_id:'".$val['rsho_rsh_id']."', rsho_is_text:'".$val['rsho_is_text']."', rsho_type:'".$val['rsho_type']."', rsho_order:'".$val['rsho_order']."', rsho_answer:'".$val['rsho_answer']."'}, true);");
		}
		
		$vars = array(
			'_TITLE_'		=> '세미나 설문 통계',
			'_BODY_'		=> ADM_F.'/research/rsh_essay',
			
			'row'			=> $row,
			'list'			=> $list
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
	
	function delete_essay() {
		$rsh_id = $this->input->post('rsh_id');
		$rsho_is_text = $this->input->post('rsho_is_text');
		$rsho_type = $this->input->post('rsho_type');
		$rsho_order = $this->input->post('rsho_order');
		$rsho_answer = $this->input->post('rsho_answer');
		
		if(!$rsho_is_text) {
			return FALSE;
		}
		
		$this->M_a_research->delete_opt($rsh_id, $rsho_is_text, $rsho_type, $rsho_order, $rsho_answer);
		
		alert('삭제 되었습니다.', URL);
	}
	
	function reset($cate) {
		if(!$cate || !isset($this->M_research->score_flds[$cate])) {
			return FALSE;
		}
		
		$data = array(
			'rsh_cate' => $cate,
			'rsh_mdydate' => TIME_YMDHIS
		);
		
		$id = $this->M_a_research->record($data);
		
		if($cate == 'semi') {
			if(!$row = $this->M_a_research->get_conf('semi')) {
				$row['rshc_count'] = 1;
			}
			
			$this->set_config($id, $row['rshc_count']);
		}
		
		alert('데이터가 삭제 되었습니다.', URL);
	}
	
	function set_config($rsh_id, $count) {
		$data = array(
			'rshc_rsh_id' => $rsh_id,
			'rshc_count' => $count,
			'rshc_mdydate' => TIME_YMDHIS
		);
		
		return $this->M_a_research->record_conf($data);
	}
	
	function get_ratio($arr) {
		$total = 0;
		$ratio = array_false($arr, true);
		
		foreach($arr AS $key => $val) {
			$total = $total + $val;
		}
		foreach($arr AS $key => $val) {
			$ratio[$key] = $total ? sprintf('%.2f', ($val / $total) * 100) : 0;
		}

		return $ratio;
	}
	
	function get_sum($arr) {
		$total = 0;
		
		foreach($arr AS $key => $val) {
			$total = $total + ($key * $val);
		}
		
		return $total;
	}
	
	function get_average($arr) {
		$total = $count = 0;
		
		foreach($arr AS $key => $val) {
			$total = $total + ($key * $val);
			$count = $count + $val;
		}
		
		return $count ? sprintf('%.2f', $total / $count) : 0;
		
	}
}
?>
