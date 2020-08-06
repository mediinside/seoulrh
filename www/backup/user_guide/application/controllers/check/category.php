<?php
class Category extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('M_categoryform');
	}

	function update() {
		$w = $this->input->post('w');
		$ca_code = str_replace('-', '.', $this->input->post('ca_code'));
		if ($this->input->post('ca_name'))
		{
			if(strtolower(preg_replace('/-/','',ENCODING)) == 'euckr')
				$ca_name = mb_convert_encoding($this->input->post('ca_name'), 'EUC-KR', 'UTF-8');	// EUC-KR
			else
				$ca_name = $this->input->post('ca_name');	// UTF-8
		}
		
		if ($w == '')
			$this->M_categoryform->insert($ca_code, $ca_name);
		else if ($w == 'u')
			$this->M_categoryform->update($ca_code, $ca_name);
		else if ($w == 'd') {
			$code_exp = explode('.', $ca_code);
			if (!isset($code_exp[1]))
				$limit_code = $ca_code + 1;
			else {
				$code_ori = substr($code_exp[1], 0, -3);
				$code_num = substr($code_exp[1], -3) + 1;
				$code_plus = repeater('0', 3-strlen($code_num)).$code_num;
				$limit_code = $code_exp[0].'.'.$code_ori.$code_plus;
			}
			$this->M_categoryform->delete($ca_code, $limit_code);
		}
		else {
			echo 'Access Error';
			return FALSE;
		}
		
		echo 'TRUE';
	}
}
