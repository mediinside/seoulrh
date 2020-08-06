<?php
class Category extends CI_Controller {
	function __construct() {
		parent::__construct();
		
		$this->load->model('M_categoryform');
		$this->load->helper('categoryform');
	}

    function lists($type='', $tid='') {
		include "init.php";
		
        switch ($type) {
            case 'board':
                $bo = $this->M_basic->get_board($tid, 'bid,bo_subject');
        		if (!isset($bo['bid']))
        			alert('존재하지 않는 게시판 입니다.');
                    
                $name = $bo['bo_subject'];
                $type = 'bo_'.$tid;
            break;
            case 'products':
            break;
            default: alert('잘못된 접근입니다.'); break;
        }

		$bc = $this->M_categoryform->list_result($type);

		$code_html = FALSE;
		if ($bc) {
			$t_code = $s_code = array();
			foreach ($bc as $row) {
				$code_exp = explode('-', $row['code']);
				
				if (!isset($code_exp[1]))
					$t_code[$code_exp[0]] = $row['ca_name'];
				else
					$s_code[$code_exp[0]][$code_exp[1]] = $row['ca_name'];
			}

			 $code_html = get_categoryform($t_code, $s_code);
		}

		$vars = array(
			'_TITLE_'		=> $name.' 분류관리',
			'_BODY_'		=> ADM_F.'/category',
			
            'name' => $name,
			'type' => $type,
            'tid' => $tid,
			'code_html' => $code_html
		);
		
		$this->load->view('layout/layout_admin', $vars);
	}
}
?>
