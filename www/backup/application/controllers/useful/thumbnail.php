<?php
class Thumbnail extends CI_Controller {
	var $mime = array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
	
	function __construct() {
		parent::__construct();
		
		ini_set('memory_limit', '512M');
		
		$this->load->model('M_board');
		$this->load->model('M_upload_files');
	}

	function _remap($size, $seg) {
		$sizeEx = explode('x', $size);
		
		$table = isset($seg[0]) ? $seg[0] : '';
		$wr_id = isset($seg[1]) ? $seg[1] : '';
		$width = isset($sizeEx[0]) ? $sizeEx[0] : '';
		$height = isset($sizeEx[1]) ? $sizeEx[1] : '';
		$ratio = isset($sizeEx[2]) ? TRUE : FALSE;
		$thumbDir = '/thumb';
		
		$no = $this->input->get('no');
		$file = $this->input->get('file');
		$ratio = setValue(FALSE, $ratio);
		
		$is_board = $table ? $this->M_upload_files->is_board($table) : FALSE;
		$where = '';
		
		if($is_board) {
			$fileDir = DATA_PATH.'/file/'.$table;
			$board = $this->M_basic->get_board($table);
			if($board['bo_skin'] == 'webzine' || $board['bo_skin'] == 'gallery') {		// 대표이미지(별도 파일 폼)가 없으면 noimg (에디터 이미지 대표이미지로 사용 안함)
				$where = 'uf_editor = 0';
			}
		}
		else if($table) {
			$fileDir = DATA_PATH .'/'. preg_replace('/ki_/', '', $table);
			if($table == 'ki_edu') {													// 대표이미지(별도 파일 폼)가 없으면 noimg (에디터 이미지 대표이미지로 사용 안함)
				$where = 'uf_editor = 0';
			}
		}
		else {
			$fileDir = DATA_PATH;
			$thumbDir = '';
		}
		
		if($file){
			$row['uf_file'] = $file;
		}
		else {
			if($no)
				$this->db->where('uf_no', $no);

			if($where) {
				$this->db->where($where);
			}
			
			$this->db->order_by('uf_editor', 'asc');
			$this->db->order_by('uf_no', 'asc');
			$this->db->limit(1);
			$this->db->where('(uf_type = 1 OR uf_type = 2 OR uf_type = 3)');
			$row = $this->db->select('uf_file,uf_type')->get_where($this->M_upload_files->table, array(
				'uf_table' => $table,
				'uf_id' => $wr_id
			), 1)->row_array();
		}
		
		$noimg = 'noimg.png';
		$file = isset($row['uf_file']) ? $row['uf_file'] : $noimg;
		$thumb = $fileDir.$thumbDir.'/'.$size.'_'.$file;
		
		// 썸네일 파일 생성
		if (!file_exists($thumb)) {
			$this->load->library('image_lib');
			$initData = array(
				'new_image'			=> $thumb,
				'dynamic_output'	=> FALSE,
				'create_thumb'		=> FALSE,
				'thumb_marker'		=> FALSE,
				'maintain_ratio'	=> $ratio,
				'width'				=> $width,
				'height'			=> $height,
				'master_dim'		=> 'auto'
			);
			
			// 원본 이미지
			if($file == $noimg)
				$initData['source_image'] = DOC_ROOT.IMG_DIR.'/useful/'.$file;
			else
				$initData['source_image'] = $fileDir.'/'.$row['uf_file'];
			
			$this->image_lib->initialize($initData);
			if (!$this->image_lib->resize())
				return false;
		}
		
		$info = @getimagesize($thumb);
		$this->output($thumb, $this->mime[$info[2]]);
	}

	private function output($file, $type='') {
		if (!$type) {
			$info = @getimagesize($file);
			$type = $this->mime[$info[2]];
		}

		header('Content-Type: image/'.$type);
		switch ($type) {
			case 'gif':
				$im = imagecreatefromgif($file);
				imagegif($im);
			break;
			case 'jpeg':
				$im = imagecreatefromjpeg($file);
				imagejpeg($im);
			break;
			case 'png':
				$im = imagecreatefrompng($file);
				imagepng($im);
			break;
			default: return false; break;
		}
		imagedestroy($im);
	}
}
