<?php
class M_editor extends CI_Model {
	function __construct() {
		parent::__construct();
		$this->load->model('M_upload_files');	
	}
	
	// 파일 업로드
	function uploadFile($uf_table, $uf_id, $wr_content='', $is_editor=1) {
		$dirs = $this->M_upload_files->get_dir($uf_table);
		
		$images = $this->input->post('images'); // 이미지소스 (변경 파일명)
		$inames = $this->input->post('inames'); // 이미지원본 (원본 파일명)
		$files  = $this->input->post('files');  // 파일소스
		$fnames = $this->input->post('fnames'); // 파일원본

		$images = is_array($images) ? $images : array();
		$inames = is_array($inames) ? $inames : array();
		$files = is_array($files) ? $files : array();
		$fnames = is_array($fnames) ? $fnames : array();

		$upFiles = array_merge($images, $files);
		$upNames = array_merge($inames, $fnames);

		$val_file = '';
		$no = 0;
		$time = time().'_';
		$cont_file = $old_file = $rest_file = array();

		if ($upFiles && count($upFiles) > 0)
			$cont_file = array_unique($upFiles);

		// 기존 등록된 파일이 넘어오지 않으면 삭제
		$result = $this->M_upload_files->get_files($uf_table, $uf_id, 'uf_no,uf_file', array('uf_editor'=>1));
		foreach ($result as $row) {
			$old_file[$row['uf_no']] = $row['uf_file'];
			$file_key = array_search($row['uf_no'], $cont_file);
			if($file_key !== false)
				$cont_file[$file_key] = $row['uf_file'];
		}
		
		$rest_file = array_diff($old_file, $cont_file);

		if ($rest_file)
			$this->M_upload_files->file_delete($uf_table, $uf_id, array_keys($rest_file));
		
		
		
		$no = $this->M_upload_files->get_maxNo($uf_table, $uf_id);
		
		// 파일 저장
		if ($cont_file) {
			$mime = array_fill(0, 4, 0);
			$tstr_file = $nstr_file = array();
			$rest_file = array_diff($cont_file, $old_file);

			foreach ($rest_file as $key => $file) {
				$filename = $time.$file;
				$newfile = $dirs['data_path'].'/'.$filename;

				if (rename($dirs['temp_path'].'/'.$file, $newfile)) {
					$byte = @filesize($newfile);
					$size = @getimagesize($newfile);
					
					$val_file .= "('".$uf_table."','".$uf_id."','".++$no."','".$is_editor."','".$upNames[$key]."','".$filename."','0','".$byte."','".$size[0]."','".$size[1]."','".$size[2]."','".TIME_YMDHIS."'),";
					
					if($size[2]==1 || $size[2]==2 || $size[2]==3) {
						// 이미지 파일 형식
						$tstr_file[] = $dirs['temp_url'].'/'.$file;
						$nstr_file[] = $dirs['data_url'].'/'.$filename;
					}
					else {
						//  그 외 파일 형식
						$tstr_file[] = $dirs['temp_url'].'/'.$file;
						$nstr_file[] = $dirs['down_dir'].'/download?table='.$uf_table.'&id='.$uf_id.'&no='.$no;
					}
					
					$mime[$size[2]] = isset($mime[$size[2]]) ? $mime[$size[2]] + 1 : 1;
				}
			}
			if ($val_file = substr($val_file, 0, -1)) {
				$wr_content = str_replace($tstr_file, $nstr_file, $wr_content);
				$this->M_upload_files->file_insert($uf_table, $uf_id, $val_file);
				$is_img[0] = $mime[0];
				$is_img[1] = $mime[1] + $mime[2] + $mime[3];
				foreach($is_img as $key => $val)
					$this->M_upload_files->file_count_update($uf_table, $uf_id, $val, $key);
			}
		}
		return $wr_content;
	}
	
	// 에디터 정보
	function get_info($uf_table, $id) {
		$dirs = $this->M_upload_files->get_dir($uf_table);
		
		$result = $this->M_upload_files->get_files($uf_table, $id, 'uf_no, uf_source, uf_file, uf_filesize, uf_type', array('uf_editor'=>1));
		
		$edt_info = array();
		
		foreach ($result as $row) {
			if(strtolower(preg_replace('/-/','',ENCODING)) == 'euckr')
				$filename = mb_convert_encoding($row['uf_source'], 'UTF-8', 'EUC-KR');	// EUC-KR
			else
				$filename = $row['uf_source'];	// UTF-8
			
			$filepath = $dirs['data_url'].'/'.$row['uf_file'];
			
			if ($row['uf_type'] > 0) {
				$edt_info['image'][] = array(
					'attacher' => 'image',
					'data' => array(
						'imageurl' => $filepath,
						'filename' => $filename,
						'filesize' => (int)$row['uf_filesize'],
						'thumburl' => $filepath
					)
				);
			}
			else {
				$edt_info['file'][] = array(
					'attacher' => 'file',
					'data' => array(
						'attachurl' => $dirs['down_dir'].'/download?table='.$uf_table.'&id='.$id.'&no='.$row['uf_no'],
						'filemime' => 'application/octet-stream', // mime_content_type();
						'filename' => $filename,
						'filesize' => (int)$row['uf_filesize']
					)
				);
			}
		}
		return $edt_info;
	}
}
