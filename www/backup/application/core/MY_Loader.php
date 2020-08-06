<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* CI 2.1.0
SKIN 폴더 위치 변경으로 _ci_view_paths 수정
*/
class MY_Loader extends CI_Loader {
	var $layout = '';
	
	function __construct() {
		parent::__construct();
	}

	function view($view, $vars = array(), $return = FALSE) {
		$view_code = array();
		$view = $view ? $view : $this->layout;
		
		$this->_ci_view_paths = preg_match('/^\//', $view) || !$view ? array(FCPATH => TRUE) : array(SKIN_PATH => TRUE);
		
		// 컨트롤러에서 먼저 던져진 CSS, JS 병합
		$this->vars_merge('_CSS_', $vars, $this->get_var('_CSS_'));
		$this->vars_merge('_JS_', $vars, $this->get_var('_JS_'));
		
		// 레이아웃이면 설정 가져오기
		if(preg_match('/^'.addcslashes(LAYOUT_PATH,'/').'\/.*/', $view)) {
			if(!is_file(FCPATH.'/'.$view.'.html')) {
				alert('레이아웃 파일을 찾을 수 없습니다.');
			}
			
			$ly_file = preg_replace('/^.*\//', '', $view);
			$layout = $this->M_layout->get_layout($ly_file);
			
			// 관리자 레이아웃 설정 데이터
			if($layout) {
				$dbVars = $this->M_dbvars->get_data('layout', $layout['ly_id']);
				$this->load->vars( getContent($dbVars) );
				
				// 관리자 레이아웃 설정에서 입력된 CSS, JS 병합
				$this->vars_merge('_CSS_', $vars, $this->setUserPath(explode('|', $layout['ly_css']), USER_CSS_DIR));
				$this->vars_merge('_JS_', $vars, $this->setUserPath(explode('|', $layout['ly_js']), USER_JS_DIR));
				
				// 모바일웹 미지원 버전에 업데이트할 경우를 위해.. (추후 삭제)
				$ly_platform = isset($layout['ly_platform']) ? $layout['ly_platform'] : 'pc';
				
				$this->load->vars(array_merge($vars, param_decode($layout['ly_parameter']), array('layout_platform' => $ly_platform)));
			}
			$view_code[10] = $this->_ci_load(array('_ci_view' => SKIN_PATH.'layout/_head.html', '_ci_vars' => $this->fullpath($vars), '_ci_return' => TRUE));
			$view_code[30] = $this->_ci_load(array('_ci_view' => SKIN_PATH.'layout/_tail.html', '_ci_vars' => '', '_ci_return' => TRUE));
		}
		
		$view_code[20] = $this->_ci_load(array('_ci_view' => $view.'.html', '_ci_vars' => $this->fullpath($vars), '_ci_return' => TRUE));
		
		ksort($view_code);
		if($return) {
			return implode("\n", $view_code);
		}
		else {
			foreach($view_code AS $code) {
				echo $code;
			}
		}
	}
	
	function setLayout($layout) {
		$this->layout = $layout;
	}
	
	// $vars 배열에 같은 키값의 $arr 배열 값을 병합
	function vars_merge($varName, &$vars, $arr) {
		$arr = is_array($arr) ? array_diff($arr, array('')) : $arr;
		
		if(!array_key_exists($varName, $vars) || !is_array($vars[$varName]) || count($vars[$varName]) < 1) {
			$vars[$varName] = $arr;
		}
		else if(!is_array($arr) || count($arr) < 1) {}
		else {
			$vars[$varName] = array_diff($vars[$varName], array(''));
			$vars[$varName] = array_merge($vars[$varName], $arr);
		}
	}
	
	function fullpath($vars) {
		$fix_arr = array('_JS_' => JS_DIR, '_CSS_' => CSS_DIR);
		$return = $this->_ci_object_to_array($vars);
		
		foreach($fix_arr AS $keyName => $prefix) {
			if(!isset($return[$keyName]) || !is_array($return[$keyName])) {
				continue;
			}
			
			foreach($return[$keyName] AS $key => $val) {
				if(!preg_match('/^(http:\/\/|\/|'.addcslashes(JS_DIR, '/').'|'.addcslashes(CSS_DIR, '/').')/i', $val)) {
					$return[$keyName][$key] = $prefix.'/'.$val;
				}
			}
		}
		
		return $return;
	}
	
	function setUserPath($setArray, $path) {
		if(!$setArray) {
			return FALSE;
		}
		
		if(!is_array($setArray)) {
			if(!preg_match('/^(http:\/\/|\/)/i', $setArray)) {
				return $path .'/'. $setArray;
			}
			else {
				return $setArray;
			}
		}

		for($i = 0; $i < count($setArray); $i++) {
			if(!preg_match('/^(http:\/\/|\/)/i', $setArray[$i]) && $setArray[$i]) {
				$setArray[$i] = $path .'/'. $setArray[$i];
			} 
		}
		
		return $setArray;
	}
}
?>
