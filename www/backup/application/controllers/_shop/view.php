<?php if ( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class View extends Widget {
	function index() {
		$this->load->helper('textual_helper');
		
		$CI =& get_instance();
		$CI->load->model('M_upload_files');
		
		$seg    =& $this->seg;
		$cate	=& $this->cate;
		
		$qstr  = qstr_rep($seg->get_qstr(), 'id'); // 쿼리스트링
		
		$id = $seg->get_seg('id');
		
		if($id) {
			$view = $this->M_shop->row($id);
			
			if (!isset($view['pd_id']))
				alert('자료가 존재하지 않습니다.');
			
			// 한번 읽은글은 브라우저를 닫기전까지는 카운트를 증가시키지 않음
			$ss_name = 'ss_view_'.$CI->M_shop->table.'_'.$id;
			if (!$this->session->userdata($ss_name)) {
				$CI->M_shop->hit_update($id);
				$this->session->set_userdata($ss_name, TRUE);
			}
		}
		else
			goto_url($menu.'/'.$data['cate_seg']);
		
		// 이전상품, 다음상품 링크
		/*
		$pn = $CI->M_shop->prev_next_link($data['cate'], $id);
		$prev = $pn['prev'];
		$next = $pn['next'];
		if ($prev['pd_id']) {
			$prev_wr_subject = cut_str(get_text($prev['pd_name']), 255);
			$subject_prev = "<a href='".RT_PATH."/$menu/".$data['cate_seg']."/view/id/".$prev['pd_id'].$qstr."'>$prev_wr_subject</a> ";
		}
		if ($next['pd_id']) {
			$next_wr_subject = cut_str(get_text($next['pd_name']), 255);
			$subject_next = "<a href='".RT_PATH."/$menu/".$data['cate_seg']."/view/id/".$next['pd_id'].$qstr."'>$next_wr_subject</a> ";
		}
		*/
		
		// 목록 버튼
		$btn_list = RT_PATH.'/'.$this->type.'/lists'.$qstr;
		
		// 가공
		$view = $this->get_convert($view);
		
		$view['href'] = RT_PATH.'/'.$this->type.'/order/id/'.$view['pd_id'].$qstr;
		$view['price'] = $view['pd_soldout'] ? '품절' : number_format($view['pd_price']) .' 원'; 
		
		// MD 정보
		$md = $this->M_staff->row($view['pd_md']);
		if($md['st_id']) {
			$mdRes = $CI->M_upload_files->get_files($this->M_staff->table, $md['st_id'], 'uf_no,uf_source,uf_file,uf_type');
			$md['photo'] = isset($mdRes[0]['uf_file']) ? $this->config->item('base_url').'data/staff/'.$mdRes[0]['uf_file'] : '';
		}
		
		// 이미지 리사이즈
		if ($view['uf_count_image'] > 0) {
			$this->load->helper('resize');
			$view['pd_content'] = resize_content($view['pd_content']);
		}
		
		// 첨부파일
		$images = array();
		$result = $CI->M_upload_files->get_files($this->M_shop->table, $id, 'uf_no,uf_source,uf_file,uf_type');
		foreach($result as $file) {
			$fileType = strtolower(substr($file['uf_source'], -3, 3));
			
			if($file['uf_type']==1 || $file['uf_type']==2 || $file['uf_type']==3) {
				$view['image'][$file['uf_no']] = RT_PATH.'/data/shop/'.$file['uf_file'];
			}
		}
		
		// 대표 이미지
		$view['dimg'] = array();
		for($i = 1; $i < 9; $i++) {
			if(isset($view['pd_image'.$i]) && $view['pd_image'.$i]) {
				$view['dimg'][] = $view['pd_image'.$i];
			}
		}
		
		$dlv_price = dlvCharge($view['pd_price'], $this->shop_conf['dlv_free'], $this->shop_conf['dlv_price'], $view['pd_dlvFree']);
		
		$vars = array(
			'_TITLE_'		=> $this->cate['ca_name'],
			'_BODY_'		=> 'shop/'.CA_SKIN.'/view',
			
			'cate'			=> $cate,
			
			'view'			=> $view,
			'md'			=> $md,
			'dlv_price'		=> $dlv_price,
			'amount'		=> $view['pd_price'] + $dlv_price,
			'shop_conf'		=> $this->shop_conf,
			
			'subject_prev'	=> isset($subject_prev) ? $subject_prev : '',
			'subject_next'	=> isset($subject_next) ? $subject_next : '',
			'btn_list'		=> $btn_list,
			'qstr'			=> $qstr
		);
		
		$this->load->view(null, $vars);
	}
	
	function get_convert($row) {
		$CI =& get_instance();
		$CI->load->model('M_shop');
		
		$row['regdate']			= preg_replace('/\-/', '.', substr($row['pd_regdate'], 0, 10));
		
		return $row;
	}
}
?>
