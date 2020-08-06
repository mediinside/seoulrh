<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * xls 쓰기
* description: 2차원 or 3차원 배열로 입력 - [row][cel][option]
* 				ex)	$test[0][0] = '셀내용'				// 2차원으로 내용만 넘어올 경우 옵션은 사용 안함
* 				ex)	$test[0][1]['text'] = '셀내용';		// 내용 텍스트
* 					$test[0][1]['size'] = 20;			// 글씨 크기
* 					$test[0][1]['bold'] = 1;			// 볼드 여부
* 					$test[0][1]['center'] = 1;			// 가운데 여부
* 					$test[0][1]['merge'] = 'E';			// ~까지 셀 병합
*/
// Excel5 포맷으로 저장 엑셀 2007 포맷으로 저장하고 싶은 경우 'Excel2007'로 변경합니다.
function excel_write($excelData, $cellWidth=array(), $excelFormat='Excel5', $excelTitle='NewSheet') {
	$CI =& get_instance();
	$CI->load->library("pxl");

	try {
		$execl = new PHPExcel();
		$execl->setActiveSheetIndex(0);
		$execl->getActiveSheet()->setTitle($excelTitle);
		
		if($cellWidth) {
			foreach($cellWidth AS $column => $width) {
				$execl->getActiveSheet()->getColumnDimension($column)->setWidth($width);
			}
		}
		
		$cnt = 0;
		foreach($excelData AS $key => $row) {
			$cnt = $key + 1;
			$col = 'A';
			foreach($row AS $cell) {
				$column = ($col++) . $cnt;
				
				if(is_array($cell)) {
					$execl->getActiveSheet()->setCellValue($column, $cell['text']);
				
					if(isset($cell['size']) && $cell['size']) {
						$execl->getActiveSheet()->getStyle($column)->getFont()->setSize($cell['size']);		// 폰트 사이즈
					}
					if(isset($cell['bold']) && $cell['bold']) {
						$execl->getActiveSheet()->getStyle($column)->getFont()->setBold(true);				// 폰트 볼드
					}
					if(isset($cell['center']) && $cell['center']) {
						$execl->getActiveSheet()->getStyle($column)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);		// 텍스트 가운데
					}
					if(isset($cell['merge']) && $cell['merge']) {
						$execl->getActiveSheet()->mergeCells($column .':'. $cell['merge'] . $cnt);			// 셀 병합
					}
				}
				else {
					$execl->getActiveSheet()->setCellValue($column, $cell);
				}
			}
		}
		
		$objWriter = PHPExcel_IOFactory::createWriter($execl, $excelFormat);  
	}
	catch (exception $e) {
		die('{"e":"100"}');
		return FALSE;
	}
	
	return $objWriter;
}

/*
 * xls 읽기
 * description: 2차원 배열로 반환 - [row][cel]
 */
function excel_read($excelFile) {
	ini_set('memory_limit', '128M');
	
	$limit_size = 10;	// 파일 크기 제한 (mb 단위)
	
	$CI =& get_instance();
	$CI->load->library("pxl");
	
	$data = array();
	try {
		if(filesize($excelFile) > ($limit_size * 1024 * 1024)) {
			die('{"e":"200"}');
			return FALSE;
		}
		
		$objReader = PHPExcel_IOFactory::createReaderForFile($excelFile);
		$objReader->setReadDataOnly(true);
		
		$objExcel = $objReader->load($excelFile);
		$objExcel->setActiveSheetIndex(0);
		$objWorksheet = $objExcel->getActiveSheet();
		
		$rowIterator = $objWorksheet->getRowIterator();
		
		// 행
		foreach ($rowIterator AS $rKey => $row) {
			$cellIterator = $row->getCellIterator();
			$cellIterator->setIterateOnlyExistingCells(false); 

			// 셀
			foreach ($cellIterator AS $cKey => $cell) {
			      $data[$rKey][$cKey] = $cell->getValue();
			}
		}
	}
	catch (exception $e) {
		die('{"e":"100"}');
		return FALSE;
	}
	
	return $data;
}

/* 
 * 엑셀 파서
 * description: 커스터마이징
 */
function excel_parse($excel) {
	$worksheet = array();
	
	$cnt= 0;
	for($i = 1; $i < count($excel); $i = $i + 3) {
		$line[1] = $excel[$i][0];
		$line[2] = isset($excel[($i+1)][0]) ? $excel[($i+1)][0] : '';
		$line[3] = isset($excel[($i+2)][0]) ? $excel[($i+2)][0] : '';
		
		$group = $line[1];
		list($month) = explode(' ', $line[2]);
		$dayAndSite = preg_replace('/'. $month .' /', '', $line[2]);
		$site = preg_replace('/^.*((([0-9]+)(st|nd|rd|th)){1,})/', '', $dayAndSite);
		$day = preg_replace('/'. addcslashes($site,'+?()[]-|/') .'$/', '', $dayAndSite);
		$link = preg_replace('/.*(http(s)?:\/\/)/i', 'http://', $line[3]);		// http:// 앞에 문자 모두 제거
		
		$worksheet[$cnt]['group'] =	$group;
		$worksheet[$cnt]['month'] =	$month;
		$worksheet[$cnt]['day'] =	$day;
		$worksheet[$cnt]['site'] =	$site;
		$worksheet[$cnt]['link'] =	$link;
		
		$cnt++;
	}
	
	return $worksheet;
}
?>
