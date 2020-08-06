<?php if ( ! defined('WIDGET_PI')) exit('No direct script access allowed');

class Lists extends Widget {
	function index() {
        $seg	=& $this->seg;
		$cate	=& $this->cate;
		
		$qstr  = qstr_rep($seg->get_qstr(), 'id');	// 쿼리스트링
		
		$per_page = 10;								// 페이지당 출력 갯수
		$page  = $seg->get_seg('page');				// 페이지
		
		$sfl   = $seg->get_seg('sfl');   			// 검색필드
		$stx   = $seg->get_seg('stx');   			// 검색어
		
		$total_count = $this->M_shop->list_count(CID);
		
		// 페이지
		if($page < 1) $page = 1;
		
		// 페이징
		$config['base_url']    = RT_PATH.'/'.$this->type.'/lists/page/';
		$config['per_page']    = $per_page;
		$config['total_rows']  = $total_count;
        $config['uri_segment'] = $seg->get_order('page');
		$config['suffix']      = $seg->get_qstr();
        
		$CI =& get_instance();
		$CI->load->library('pagination');
		$CI->pagination->initialize($config);
		$paging = $CI->pagination->create_links();
		
		$offset = ($page - 1) * $per_page;
		
		$result = $this->M_shop->list_result(CID, $per_page, $offset);
		$list = $result;
		foreach ($result as $key => $row) {
			$list[$key]['num'] = $total_count - ($page - 1) * $per_page - $key;
			$list[$key]['pd_name'] = cut_str(get_text($list[$key]['pd_name']), 30);
			$list[$key]['pd_content'] = get_text(nl2br($list[$key]['pd_content']));
			$list[$key]['price'] = $list[$key]['pd_soldout'] ? '품절' : number_format($list[$key]['pd_price']) .' 원'; 
			$list[$key]['href'] = RT_PATH.'/'.$this->type.'/view/id/'.$row['pd_id'].$qstr;
			$list[$key]['regdate'] = preg_replace('/\-/', '.', substr($row['pd_regdate'], 0, 10));
			$list[$key]['options'] = $row["pd_options"] ? sel_options($row['pd_options'],'옵션',TRUE,'option'.$row['pd_id'],'','set_price($("#price'.$row['pd_id'].'"), $("#quantity"), $(this).val(), '.$row['pd_price'].','.$row['pd_id'].');') : '';
			
			// 대표 이미지
			$list[$key]['dimg'] = '';
			for($i = 1; $i < 9; $i++) {
				if(isset($row['pd_image'.$i]) && $row['pd_image'.$i]) {
					$list[$key]['dimg'] = $row['pd_image'.$i];
					break;
				}
			}
		}
		
		$vars = array(
			'_TITLE_'		=> $this->cate['ca_name'],
			'_BODY_'		=> 'shop/'.CA_SKIN.'/lists',
			
			'qstr'			=> $qstr,
			
			'total_count'	=> $total_count,
			
			'cate'			=> $this->cate,
			'list'			=> $list,
			'paging'		=> $paging
		);
		
		$this->load->view(null, $vars);
	}
}
?>
