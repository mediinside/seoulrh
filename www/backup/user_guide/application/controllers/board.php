<?php
class Board extends CI_Controller {
	public $board;   	// 게시판 정보
	public $member;  	// 회원 정보
	public $write;   	// 게시물 정보
	public $seg;     	// uri 정보
	public $postlink;	// 관련글
	public $board_list;	// 게시판 리스트

	function __construct() {
		parent::__construct();
		$this->load->model(array('M_board', 'M_postlink'));
		$this->load->helper(array('board', 'textual', 'search'));
		$this->load->config('cf_board');
		
		$this->seg		= new search_seg();
	}
	
	function _remap($bid) {
		if($bid == 'index') {
			goto_url('/');
		}
		
		$bo_style_arr	= array('lists', 'view', 'write', 'rss', 'password', 'editor', 'download');
		$wr_id			= $this->seg->get_seg('wr_id');
		$bo_style		= $this->uri->segment(3, 'list');
		$sca			= $this->seg->get_seg('sca');
		$layout			= $this->seg->get_seg('layout');
		$qstr			= qstr_rep($this->seg->get_qstr(), 'wr_id,sca');
		
		if(FALSE === array_search($bo_style, $bo_style_arr)) {
			alert('잘못된 접근입니다.', '/');
		}
		
		// 회원정보
		$this->member	= unserialize(MEMBER);
		
		// 게시판 정보
		$this->board	= $bid ? $this->M_basic->get_board($bid) : FALSE;
		
		// 게시판 유효성
		if(!$this->board) alert('게시판 ID가 올바르지 않습니다.', '/');
		
		// 게시물 정보
		$this->write	= $wr_id ? $this->M_basic->get_write($this->board['bo_db'], $wr_id ) : FALSE;
		
		// 관련글
		$this->postlink	= ($this->board['bo_use_postlink'] && $this->write) ? $this->M_postlink->list_result($this->board['bo_db'], $wr_id, $qstr) : FALSE;
		
		// 관리자 확인
		$is_admin		= (IS_MEMBER && $this->board)? is_boAdmin($this->board, $this->member) : FALSE;
		
		
		
		// 하위 카테고리
		$category = FALSE;
		if($this->board['bo_use_category']) {
			$this->load->helper('category');
			$cate_disabled = ($this->seg->get_seg('w')=='r' || $this->write['wr_reply']) ? TRUE : FALSE;
			$category = make_category(array(
					'type'		=> 'bo_'.$this->board['bo_db'],
					'id'		=> 'ca_code',
					'code'		=> setValue($sca, $this->write['ca_code']),
					'lst'		=> ($bo_style == 'lists') ? TRUE : FALSE,
					'disabled'	=> $cate_disabled,
					'qstr'		=> $qstr,
			));
		}
		
		if($this->board['bo_use_postlink'] && $wr_id) {
			$this->postlink = $this->M_postlink->list_result($this->board['bid'], $wr_id, $qstr);
		}
		
		// 상수 정의
		define('BO_TABLE',		$this->board['bid']);
		define('BO_DB',			$this->board['bo_db']);
		define('BO_IMG_PATH',	IMG_DIR.'/board');
		define('IS_ADMIN',		$is_admin);
		define('INPUT_ADMIN',	($is_admin) ? "<input type='hidden' name='is_admin' value='".$is_admin."'/>": '');
		define('BO_CSS',		$this->board['bo_css']);
		
		$this->board_list = $this->M_basic->get_board();
		$this->load->vars();
		$this->load->vars( array_merge(array(
			'pTitle'		=> ($this->board['bo_show_gr'] ? $this->board['gr_subject'].' &gt; ' : '').'<strong>'.$this->board['bo_subject'].'</strong>',			
			'board'			=> $this->board,
			'wr_id'			=> $wr_id,
			'category'		=> $category,
			'postlink'		=> $this->postlink,
			'board_select'	=> ($this->board['bo_use_board_sel']) ? board_select($this->board_list, BO_TABLE, RT_PATH.'/board/"+$(this).val()+"/lists'.$qstr) : '',
			'sca'			=> ($sca) ? str_replace('.', '-', $sca) : '',
			'sca_str'		=> ($sca) ? '/sca/'.$sca : '',
			
			'mainTitle'		=> $this->board['gr_subject'],		// 한국아이국악협회 커스터마이징
			'subTitle'		=> $this->board['bo_subject']		// 한국아이국악협회 커스터마이징
		), param_decode($this->board['bo_parameter'])) );
		
		// 레이아웃
		$layout_name = LAYOUT_PATH.'/'.$this->board['ly_file'];
		if(array_search($layout, array('admin', 'blank')) !== FALSE) {	// 허가된 레이아웃만 사용 가능
			$layout_name = 'layout/layout_'.$layout;
			include APPPATH.'controllers/'.ADM_F.'/init.php';
		}
		$this->load->setLayout($layout_name);
		
		// 위젯 실행
		$widgetFile = ($bo_style == 'download') ? "inc/$bo_style" : "_board/$bo_style";
		widget::run($widgetFile);
	}
}
