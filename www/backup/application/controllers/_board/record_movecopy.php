<?php
class Record_movecopy extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model(array('M_board_mvcp', 'M_postlink', 'M_content'));
		$this->load->config('cf_board');
		define('IS_ADMIN', $this->input->post('is_admin'));
	}	
	
	function update() {
		// 게시판 관리자 이상 복사, 이동 가능
		if (!IS_ADMIN)
		     show_404();
		
  		$bid		= $this->input->post('bid'); // 원본 게시판
  		$bids		= $this->input->post('bids'); // 대상 게시판
		$wr_ids		= unserialize($this->input->post('wr_ids'));
		$sw			= $this->input->post('sw');
		$qstr		= $this->input->post('qstr');

		if (!$wr_ids) alert_close('잘못된 접근입니다.');
		switch ($sw) {
			case 'move' :
				if(count($bids) > 1) {
					alert('이동할 게시판이 많습니다.');
				}
				$act = '이동';
				break;
			case 'copy' :
				$act = '복사';
				break;
			default:
				alert_close('잘못된 접근입니다.');
				break;
		}
		
		$board = $this->M_basic->get_board($bid, 'bid, bo_db, bo_subject');
		$member = unserialize(MEMBER);
		$base_url = $this->config->item('base_url');
		
		// 원본 파일 디렉토리
		$ori_dir = DATA_PATH.'/file/'.$board['bo_db'];
		
		$save = array(); // 이동시 삭제를 위한
		$save_count_write = 0; // 수행한 전체 글 개수
		$save_count_comment = 0; // 수행한 전체 코멘트 개수
		
		$wr_nums = $this->M_board_mvcp->get_dist_num($board['bo_db'], $wr_ids);
		
		// 해당 게시물의 답글까지 모두 가져와
		$result =  $this->M_board_mvcp->get_write_num($board['bo_db'], $wr_nums);
		
		foreach ($bids as $move_bid) {
			$sub_dir = DATA_PATH.'/file/'.$move_bid;
			
			$count_write = 0;
	        $count_comment = 0;
			
			$next_wr_num = $this->M_board_mvcp->get_min_num($move_bid);
			$next_wr_num = (int)($next_wr_num - 1);
			
			$tmp_num = $wr_pars = FALSE;
			foreach ($result as $cnt => $wr) {
	            if (!$wr['wr_is_comment'] && $this->config->item('cf_use_mvcp_log'))
	                $wr['wr_content'] .= (strpos($wr['wr_option'], 'editor') !== FALSE ? '<br/>' : '\n').'[ 이 게시물은 '.$member['mb_nick'].'님에 의해 '.TIME_YMDHIS.' '.$board['bo_subject'].'에서 ' .$act.' 됨]';
          		
          		// 다음 wr_num 마다 -1 씩 증가
          		if ($tmp_num != $wr['wr_num'])
          			$next_wr_num = (int)($next_wr_num - 1);
          		
	  			$tmp_num = $wr['wr_num'];
	  			
				// 해당 게시판으로 글 Insert					
          		$insert_id = $this->M_board_mvcp->write_insert($move_bid, $next_wr_num, $wr);
          		
          		// 관련글 복사
          		$this->M_postlink->post_copy($board['bo_db'], $wr['wr_id'], $move_bid, $insert_id);
          		
              	// 코멘트가 아니라면
              	$save[$cnt]['uf_file'] = array();
	            if (!$wr['wr_is_comment']) {
	            	$save_parent = $insert_id;
	            	
	            	// 파일이 있다면
	            	if ($wr['uf_count_image'] > 0 || $wr['uf_count_file'] > 0) {
		            	// 해당 게시물의 파일 정보를 가져와
		            	$result3 = $this->M_board_mvcp->get_write_file($board['bo_db'], $wr['wr_id']);
						foreach ($result3 as $k => $uf) {
							if ($uf['uf_file']) {
		                        // 원본파일을 복사하고 퍼미션을 변경
		                        @copy($ori_dir.'/'.$uf['uf_file'], $sub_dir.'/'.$uf['uf_file']);
		                        @chmod($sub_dir.'/'.$uf['uf_file'], 0606);
		                    }
		                    
							// 해당 게시판으로 파일 Insert
							$this->M_board_mvcp->write_file_insert($move_bid, $insert_id, $uf);
							
							// 이동시 삭제를 위한 파일 정보 저장
		                    if ($sw == 'move' && $uf['uf_file'])
		                        $save[$cnt]['uf_file'][$k] = $ori_dir.'/'.$uf['uf_file'];
						}

						$this->M_board_mvcp->content_update($move_bid, $insert_id, str_replace(
							array($base_url.'/data/file/'.$board['bo_db'],
								  $base_url.'/board/'.$board['bo_db'].'/download/wr_id/'.$wr['wr_id']),
							array($base_url.'/data/file/'.$move_bid,
								  $base_url.'/board/'.$move_bid.'/download/wr_id/'.$insert_id),
						$wr['wr_content']));
					}
					
	                $count_write++;
	                $save_count_write++;
	            }
	            else {
	                $count_comment++;
	                $save_count_comment++;
          		}

				// 원글배열
				$wr_pars[$save_parent][] = $insert_id;
				$insert_ids[] = $insert_id;
				
				// 이동시 삭제를 위한 원글 저장
				// 처음 넘겨온 글배열과 다를 수 있음 (답글을 추가로 얻어오므로))
	            if ($sw == 'move')
	                $save[$cnt]['wr_id'] = $wr['wr_id'];
			}
			
			// 원글값 Update
			foreach($wr_pars as $save_par => $save_ids) {
				$this->M_board_mvcp->write_parent_update($move_bid, $save_ids, $save_par);	
			}
			
			// 카운트, wr_num
			$this->M_board_mvcp->bo_count_update($move_bid, $count_write, $count_comment, $next_wr_num, '+');
	    }
	    
		if($sw == 'move') {
			$wr_re_co_ids = array();
			foreach ($save as $row) {
				foreach ($row['uf_file'] as $uf_file) {
					@unlink($uf_file);
				}
				$wr_re_co_ids[] = $row['wr_id'];	// 처음 넘어온 wr_ids에 답글/댓글이 추가됨
			}
			
			// 이동된 글이 관리자 컨텐츠 페이지에 참조된 경우 테이블/인덱스 값 변경
			$this->M_dbvars->ref_move($board['bo_db'], $wr_re_co_ids, $move_bid, $insert_ids);
			
			// 이동된 글이 관련글로 되어있는 글들의 link_id값을 이동된 id값으로 변경
			$this->M_postlink->post_move($board['bo_db'], $wr_re_co_ids, $move_bid, $insert_ids);
			
			// 삭제될 원글의 관련글 삭제
			$this->M_postlink->post_delete($board['bo_db'], $wr_re_co_ids);
			
			// 원글 삭제
			$this->M_board_mvcp->write_delete($board['bo_db'], $wr_re_co_ids);
			
			$bo_min_wr_num = $this->M_board_mvcp->get_min_num($board['bo_db']);
			$this->M_board_mvcp->bo_count_update($board['bo_db'], $save_count_write, $save_count_comment, $bo_min_wr_num, '-');
		}
		
		$msg = '해당 게시물을 선택한 게시판으로 '.$act.' 하였습니다.';
		$opener_href = RT_PATH.'/board/'.$bid.'/lists'.$qstr;
		
		echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=".$this->config->item('charset')."\">";
		echo "<script type='text/javascript'>alert('".$msg."'); opener.document.location.href='".$opener_href."'; window.close();</script>";
	}
}
