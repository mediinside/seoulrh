<?php
class M_banner extends CI_Model {
	var $type = array(1 => '일반 고정', 2 => '수평 스크롤', 3 => '수직 스크롤', 4 => '수평 슬라이드', 5 => '수직 슬라이드', 6 => '비주얼 애니메이트');
	var $table = 'ki_banner';
	var $table_gr = 'ki_banner_group';
	
	function __construct() {
		parent::__construct();
	}

	function get_banner($id='', $html_ret=TRUE) {
		if(!$id) {
			return FALSE;
		}
		
		$group_info =  $this->db->select('*')->where('bg_id',$id)->get($this->table_gr)->row_array();
		
		$this->db->select('*');
		$this->db->where('bn_group', $id);
		$this->db->where('bn_hidden', 0);
		$this->db->where('bn_sdate <=', TIME_YMDHIS);
		$this->db->where('bn_edate >=',  TIME_YMDHIS);
		$this->db->order_by('bn_id', 'desc');
		$result = $this->db->get($this->table);
		$rows = $result->result_array();
		
		$bn = array();
		foreach($rows AS $row) {
			$bn[] = $row;
		}
		
		$banner = $html_ret ? $this->html_banner($group_info, $bn) : $bn;
		
		return $banner;
	}
	
	function get_groups($field='*') {
		return $this->db->select($field)->get($this->table_gr)->result_array();
	}
	
	function latest($bn_id, $limit = 5, $thumbSize = '80x60') {
		if(!$bn_id) {
			return FALSE;
		}
		
		$result = $this->list_result('bn_id', 'desc', '', '', $limit, 0, $bn_id);
		foreach($result['qry'] AS $key => $val) {
			$result['qry'][$key]['thumb'] = '/useful/thumbnail/'. $thumbSize .'/banner/?file='. $val['bn_image'];
		}
		
		return $result['qry'];
	}
	
	function list_result($sst, $sod, $sfl, $stx, $limit, $offset, $group='') {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				default :
					$this->db->like($sfl, $stx, 'both');
				break;
			}
		}
		if($group) {
			$this->db->where('bn_group', $group);
		}
		$this->db->stop_cache();

		$result['total_cnt'] = $this->db->count_all_results($this->table);

		$this->db->select('*');
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get($this->table, $limit, $offset);
		$result['qry'] = $qry->result_array();

		$this->db->flush_cache();

		return $result;
	}
	
	function group_lists_result($sst, $sod, $sfl, $stx, $limit, $offset) {
		$this->db->start_cache();
		if ($stx) {
			switch ($sfl) {
				default :
					$this->db->like($sfl, $stx, 'both');
					break;
			}
		}
		$this->db->stop_cache();
		
		$result['total_cnt'] = $this->db->count_all_results($this->table_gr);
		
		$this->db->select('*');
		$this->db->order_by($sst, $sod);
		$qry = $this->db->get($this->table_gr, $limit, $offset);
		$result['qry'] = $qry->result_array();
		
		$this->db->flush_cache();
	
		return $result;
	}
	
	function row($id, $fields='*', $table='') {
		if (!$id) return FALSE;
		
		if(!$table) {
			$table = $this->table;
		}
	
		$primary = $this->db->primary($table);
		
		return $this->db->select($fields)->get_where($table, array($primary => $id))->row_array();
	}

	function groups() {
		$rows = $this->db->select('*')->get_where($this->table_gr)->result_array();
		$result = array();
		foreach($rows AS $row) {
			$result[$row['bg_id']] = $row['bg_name'];
		}
		return $result;
	}
	
	function filepath() {
		return DATA_PATH.'/banner';
	}
	
	// 배너를 html코드로 반환
	function html_banner($gr_info, $banner) {
		if(!$gr_info || count($banner) < 1 || !is_array($banner)) return FALSE;
		
		$img_path = preg_replace('/^'.addcslashes(DATA_PATH, '/').'/', DATA_DIR, $this->filepath());
		$width		= $gr_info['bg_width'] ? "width:". $gr_info['bg_width'] . $gr_info['bg_width_type'] .";" : '';
		$height		= $gr_info['bg_height'] ? "height:". $gr_info['bg_height'].$gr_info['bg_height_type'] .";" : '';
		$ul_id		= 'banner'. $gr_info['bg_id'];
		$ul_class	= $gr_info['bg_ul_css'];
		$li_class	= $gr_info['bg_li_css'];
		
		$css_file = $js_file = array();
		$banner_type = $js_code = '';
		
		switch($gr_info['bg_type']) {
			case '1' :
				$banner_type = "banner_nor";
				break;
			case '2' :
				$banner_type = "banner_scr_w";
				$css_file = array('../js/simplyscroll/simplyscroll');
				$js_file = array('simplyscroll/simplyscroll.min');
				$js_code = 'jQuery("#'.$ul_id.'").simplyScroll({'. $gr_info['bg_option'] .'});';
				break;
			case '3' :
				$default_option = 'orientation: "vertical"';
				$default_option = $gr_info['bg_option'] ? $default_option.','.$gr_info['bg_option'] : $default_option;
				
				$banner_type = "banner_scr_h";
				$css_file = array('../js/simplyscroll/simplyscroll');
				$js_file = array('simplyscroll/simplyscroll.min');
				$js_code = 'jQuery("#'.$ul_id.'").simplyScroll({'.$default_option.'});';
				break;
			case '4' :
				$default_option = 'visible: '.$gr_info['bg_count'].', auto: 3000, speed: 1000, circular: true, vertical: false';
				$default_option = $gr_info['bg_option'] ? $default_option.','.$gr_info['bg_option'] : $default_option;
				
				$banner_type = "banner_sli_w";
				$js_file = array('jcarousellite_1.0.1.min');
				$js_code = 'jQuery(".'.$banner_type.'").jCarouselLite({'.$default_option.'});';
				break;
			case '5' :
				$default_option = 'visible: '.$gr_info['bg_count'].', auto: 3000, speed: 1000, circular: true, vertical: true';
				$default_option = $gr_info['bg_option'] ? $default_option.','.$gr_info['bg_option'] : $default_option;
				
				$banner_type = "banner_sli_h";
				$js_file = array('jcarousellite_1.0.1.min');
				$js_code = 'jQuery(".'.$banner_type.'").jCarouselLite({'.$default_option.'});';
				break;
			case '6' :
				$default_option = 'animation: "random"';
				$default_option = $gr_info['bg_option'] ? $default_option.','.$gr_info['bg_option'] : $default_option;
				
				$banner_type = "box_skitter banner_vis";
				$css_file = array('../js/skitter/css/skitter.styles');
				$js_file = array('skitter/js/jquery.easing.1.3', 'skitter/js/jquery.animate-colors-min', 'skitter/js/jquery.skitter.min');
				$js_code = 'jQuery(".banner_vis").skitter({'.$default_option.'});';
				break;
			default :
				return FALSE;
				break;
		}
		
		$imgW = $gr_info['bg_imgW'] ? "width='".$gr_info['bg_imgW']."'" : '';
		$imgH = $gr_info['bg_imgH'] ? "height='".$gr_info['bg_imgH']."'" : '';
		
		$html_code = "<div class='banner $banner_type' style='$width $height'><ul id='$ul_id' class='$ul_class'>\n";
		foreach($banner AS $bn) {
			$bn["bn_url"] = $bn["bn_url"] ? $bn["bn_url"] : 'javascript:;';
			$html_code .= "<li class='$li_class'><a href='".$bn["bn_url"]."' target='".$bn["bn_target"]."'><img src='". $img_path ."/". $bn["bn_image"] ."' $imgW $imgH/></a></li>\n";
		}
		$html_code .= "</ul></div>\n";
		
		foreach($css_file AS $css) {
			$html_code .= "<link rel='stylesheet' href='".CSS_DIR.'/'.$css.".css' type='text/css'/>\n";
		}
		foreach($js_file AS $js) {
			$html_code .= "<script type='text/javascript' src='".JS_DIR.'/'.$js.".js'></script>\n";
		}
		
		$html_code .= "<script type='text/javascript'>\n$js_code\n</script>\n";
		
		return $html_code;
	}
}
?>
