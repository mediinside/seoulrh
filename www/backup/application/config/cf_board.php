<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['cf_delay_sec'] = 10; // 글쓰기 딜레이

$config['cf_email_wr_write'] 	   = FALSE;	// 답변메일 받기 사용 여부
$config['cf_email_wr_webmaster']   = TRUE;	// 글 등록시 웹마스터에게 메일 발송 여부
$config['cf_email_wr_group_admin'] = TRUE;	// 글 등록시 그룹 관리자에게 메일 발송 여부
$config['cf_email_wr_board_admin'] = TRUE;	// 글 등록시 게시판 관리자에게 메일 발송 여부

$config['cf_use_mvcp_log'] = FALSE;			// 복사, 이동시 로그 사용 여부
$config['cf_search_part']  = 100000;		// 검색 파트 개수 조절


$config['cf_block_ip'] = array(				// 글쓰기 아이피 차단
	'112.206.122.51',
	'112.198.130.127',
	'61.97.246.114'
);
$config['cf_block_keyword'] = array(		// 글쓰기 키워드 차단
	'카지노',
	'바카라',
	'강원랜드',
	'BOY821.COM',
	'주민등록증위조',
	'증명서위조',
	'GAO88.COM',
);
