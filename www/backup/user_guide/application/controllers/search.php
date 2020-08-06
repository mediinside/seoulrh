<?php
class Search extends CI_Controller {
	var $layout;
	var $boards;
	var $path;
	
	function __construct() {
		parent::__construct();
		$this->load->library('pagination');
		$this->load->model('M_search');
		$this->load->helper(array('board', 'search', 'textual'));
		
		define('BO_IMG_PATH',	IMG_DIR.'/board');
	}
	
	function index() {
		$vars = array(
			'_TITLE_'		=> $this->config->item('cf_title'),
			'_BODY_'		=> 'main/search'
		);
		$this->load->vars($vars);
		
		$this->qry();
	}
	
	function postlink() {
		$seg  = new search_seg(3);
		$bid = $seg->get_seg('bid');
		$qstr = qstr_rep($seg->get_qstr(), 'wr_id,sca,bid');
		
		$this->path = '/postlink';
		
		$this->boards = array($bid);
		$board_list = $this->M_basic->get_board();
		
		$board_names = array();
		if($board_list) {
			foreach($board_list AS $bo) {
				$board_names[$bo['bid']] = cut_str($bo['bo_subject'], 18);
			}
		}
		
		$vars = array(
				'_TITLE_'		=> '관련글 선택',
				'_BODY_'		=> 'board/postlink',
				'_CSS_'			=> array('board'),
				'_JS_'			=> array('board'),
				
				'board_names'	=> $board_names,
		);
		$this->load->vars($vars);
		
		$this->layout = 'layout/layout_blank';
		$this->qry('wr_is_comment = 0');
	}
	
	function qry($opt_where='') {
		$seg  = new search_seg(3);
		$page = $seg->get_seg('page');
		$stx  = $seg->get_seg('stx');
		$bid = $seg->get_seg('bid');
		
		//if (!$stx) goto_url('/');
		if ($page < 1) $page = 1;
		
		$ori_stx = $stx;
		$stx = search_decode($stx);
		$sfl = 'wr_subject.wr_content'; // 제목+내용 기본
		$member = unserialize(MEMBER);

		if($this->boards = $bid ? array($bid) : FALSE) {
			$result = $this->M_basic->get_board($this->boards);
			$result = array($result);
		}
		else {
			$result = $this->M_search->search_board($member['mb_level']);
			$this->boards = array();
		}
		
		if($result) {
			foreach ($result as $row) {
				$this->boards[] = $row['bid'];
				$levels[$row['bid']] = $row['bo_read_level'];
			}
		}
		
		$config['suffix'] =			$seg->get_qstr();
		$config['base_url'] =		RT_PATH.'/search'. $this->path .'/page/';
		$config['per_page'] =		15;
		$config['total_rows'] =		count($this->boards);
        $config['uri_segment'] =	$seg->get_order('page');
		
		$offset = ($page - 1) * $config['per_page'];
		$result = $this->M_search->list_result($sfl, $stx, $config['per_page'], $offset, $this->boards, $opt_where);
		
		$this->pagination->initialize($config);
		
		$list = array();
		if(is_array($result['qry']))
		{
			foreach($result['qry'] as $i=> $row) {
				// 비밀글 통과
				if (strpos($row['wr_option'], 'secret') !== FALSE)
					continue;
					
				//$href = RT_PATH.'/board/'.$row['bid'].'/view/wr_id/'.$row['wr_id'].'/sfl/wr_subject.wr_content/stx/'.$ori_stx;
				$href = RT_PATH.'/board/'.$row['bid'].'/view/wr_id/'.$row['wr_id'];
				if ($row['wr_is_comment']) {
					$href .= '#c_'.$row['wr_id'];
					$row['wr_content'] = get_text($row['wr_content']);
				}
				else
					$row['wr_content'] = preg_replace("/\s+&nbsp;+/", '', get_text(strip_tags(htmlspecialchars_decode($row['wr_content']))));
				
				// 답변 여백
				$reply = strlen($row['wr_reply']);
				$row['ico_reply'] = '';
				if ($reply > 1) {
					for ($k=1; $k<$reply; $k++)
						$row['ico_reply'] .= ' &nbsp;&nbsp; ';
				}
				
				// 아이콘들
				if ($reply > 0)
					$row['ico_reply'] .= "<img src='".BO_IMG_PATH."/ico_reply.gif' title='답변' alt='답변'/>\n";
				
				$row['ico_secret'] = '';
				if (strpos($row['wr_option'], 'secret') !== FALSE)
					$row['ico_secret'] = "<img src='".BO_IMG_PATH."/ico_secret.gif' title='비밀' alt='비밀'/>\n";
				
				// 가변 파일 - 첨부파일이 0개 이상일 경우에만 실행
				$row['ico_file'] = $row['ico_image'] = $row['ico_movie'] = '';
				
				if ($row['uf_count_file'] > 0)
					$row['ico_file'] = "<img src='".BO_IMG_PATH."/ico_file.gif' title='파일' alt='파일'/>\n";
				
				if ($row['uf_count_image'] > 0)
					$row['ico_image'] = "<img src='".BO_IMG_PATH."/ico_image.gif' title='이미지' alt='이미지'/>\n";
				
				if (stripos($row['wr_content'], '&lt;embed'))
					$row['ico_movie'] = "<img src='".BO_IMG_PATH."/ico_movie.gif' title='동영상' alt='동영상'/>\n";
				
				// 댓글수
				$row['comment_cnt'] = '';
				if ($row['wr_comment'])
					$row['comment_cnt'] = '('.$row['wr_comment'].')';
				
				//$list[$row['bid']][$i] = $row;	// 게시판 별로 그루핑
				$list[$i] = $row;
				$list[$i]['href'] = $href;
				$list[$i]['wr_subject'] = cut_str(search_font(get_text($row['wr_subject']), $stx), 50);
				$list[$i]['wr_content'] = ($levels[$row['bid']] <= $member['mb_level']) ? search_font(cut_str($row['wr_content'], 300), $stx) : '';
				$list[$i]['wr_datetime'] = preg_replace('/-/', '.', substr($row['wr_datetime'], 0, 10));
			}
		}
		
		//uksort($list, 'board_sort');
		
		$vars = array(			
			'stx'			=> $ori_stx,
			'text_stx'		=> get_text(stripslashes($stx)),
			'total_count'	=> number_format($result['total_cnt']),
			'list'			=> $list,
			'bo_count'		=> count($list),
			'paging'		=> $this->pagination->create_links(),
			'board_select'	=> board_select($this->M_basic->get_board(), $bid, '', TRUE),
			
			'pageNum'		=> '0',
			'subNum'		=> '0',
			'mainTitle'		=> '검색',
			'subTitle'		=> get_text(stripslashes($stx))
		);
		
		$this->load->view(setValue(LAYOUT_PATH.'/layout_sub', $this->layout), $vars);
	}
}
?>
