<?php
class Apply extends CI_Controller {	
	function __construct() {
		parent::__construct();
		
		$this->load->model(array('M_apply', 'M_upload_files'));
		$this->load->library('form_validation');
	}
		
	function _remap($cid) {
		$ap = $this->M_apply->row_form($cid);
		
		$config = array();
		foreach($ap['form'] AS $form) {
			$is_trim = !is_array($this->input->post($form['apf_field'])) ? '|trim' : '';
			$is_required = $form['apf_required'] ? '|required' : '';
			$config[] = array('field' => $form['apf_field'], 'label' => $form['apf_name'], 'rules' => 'xss_clean'. $is_trim . $is_required);
		}
		
		$this->form_validation->set_rules($config);
		if ($this->form_validation->run() == FALSE) {
			if(!$this->form_validation->error_string()) {
				$vars = array(
					'_TITLE_'		=> $this->config->item('cf_title'),
					'_BODY_'		=> 'apply/apply_form',
					'_CSS_'			=> array('apply'),
					'_JS_'			=> array('jvalidate', 'jvalid_ext'),
					
					'ap'			=> $ap
				);

				$LAYOUT = IS_MOBILE ? $ap['apc_layout_m'] : $ap['apc_layout'];
				
				$layout = $this->M_layout->row($LAYOUT);
				$this->load->view(LAYOUT_PATH .'/'. $layout['ly_file'], $vars);
			}
			else {
				alert(preg_replace('/\n/', '\n', $this->form_validation->error_string(' ',' ')), $this->input->server('HTTP_REFERER'));
			}
		}
		else {
			$sql = array(
				'ap_mb_id'		=> $this->session->userdata('ss_mb_id'),
				'ap_mdydate'	=> TIME_YMDHIS,
				'ap_regdate'	=> TIME_YMDHIS
			);
			
			foreach($ap['form'] AS $form) {
				$value = $this->input->post($form['apf_field']) !== FALSE ? $this->input->post($form['apf_field']) : '';
				
				if($form['apf_type'] == 'checkbox') {
					$value = is_array($value) ? implode('|', $value) : '';
				}
				else if($form['apf_type'] == 'file') {
					$uppath = $this->M_upload_files->get_dir('apply');
					
					$filename = fileupload2($form['apf_field'], $uppath);
					$value = $filename ? $filename : '';
				}
				
				$sql['ap_'. $form['apf_field']] = is_array($value) ? implode("\n",$value) : $value;
			}
			
			$id = $this->M_apply->record($ap['apc_id'], $sql);
			
			if($this->input->get_post('url')) {
				goto_url($this->input->get_post('url'));
			}
			
			if($this->config->item('cf_use_email')) {
				$this->_sendMail($ap, $id, $sql);
			}
			
			alert('등록이 완료되었습니다.', $this->input->server('HTTP_REFERER'));
		}
	}
	
	private function _sendMail($ap, $id, $sql) {
		if(!$ap['apc_email']) {
			return FALSE;
		}
		
		$this->load->model('M_mail');
		$this->load->library('email');
		$this->load->helper('textual');
		
		$webmaster = $this->config->item('cf_webmaster');
		$sitename = $this->config->item('cf_title');
		$domain = $this->config->item('cf_domain');
		
		$subject = get_text(stripslashes('['. $ap['apc_name'] .'] 신규 등록된 글이 있습니다.'));
		
		$link_url = $this->config->item('base_url').'/adm/apply/form/u/'. $id .'?cid='. $ap['apc_id'];
		
		$data = array(
			'ap'	=> $ap,
			'data'	=> $sql
		);
		
		$mail_skin = $this->M_mail->row_skin();
		
		$content = $this->load->view('mail/apply_update', $data, TRUE);
		$content = str_replace(
				array('{{[_BODY_]}}', '[사이트명]', '[사이트주소]'),
				array($content, $sitename, $domain),
				$mail_skin['ms_code']
		);
		$to_email = explode('|', $ap['apc_email']);
		
		$this->email->clear();
		$this->email->from($webmaster, $sitename);
		$this->email->to($to_email);
		$this->email->subject($subject);
		$this->email->message($content);
		$res = $this->email->send();
		
		return $res;
	}
}
?>
