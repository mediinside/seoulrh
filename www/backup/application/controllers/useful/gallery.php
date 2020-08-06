<?php
class Gallery extends CI_Controller {
	var $seg;
	
	function __construct() {
		parent::__construct();
	}
	
	function _remap($module) {
		$this->load->helper('search');
		
		$this->seg = new search_seg();
		
		if(!method_exists($this, $module)) {
			show_404();
		}
		
		$this->$module();
	}
	
	function shop() {
		$this->load->model(array('M_shop', 'M_upload_files'));
		
		$id = $this->seg->get_seg('id');
		$no = $this->seg->get_seg('no');
		
		$row = $this->M_shop->row($id);
		
		// 첨부파일
		$files = $images = array();
		$result = $this->M_upload_files->get_files($this->M_shop->table, $id, 'uf_no,uf_source,uf_file,uf_type');
		foreach($result as $file) {
			if($file['uf_type'] > 0 && $file['uf_type'] < 4) {
				$files[$file['uf_no']] = RT_PATH .'/data/shop/'. $file['uf_file'];
			}
		}
		
		// 대표 이미지
		for($i = 1; $i < 9; $i++) {
			if(isset($row['pd_image'.$i]) && $row['pd_image'.$i]) {
				$images[] = $files[$row['pd_image'.$i]];
			}
		}
		
		$vars = array(
			'_TITLE_'		=> '상품이미지',
			'_BODY_'		=> 'useful/gallery',
			'_CSS_'			=> array('../js/skitter/css/gallery.shop'),
				
			'images'		=> $images
		);
		
		$this->load->view('layout/layout_blank', $vars);
	}
}
?>
