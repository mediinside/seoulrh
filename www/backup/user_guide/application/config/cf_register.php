<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['cf_use_jumin'] = FALSE; // 주민등록번호 사용

$config['cf_register_level'] = 2;   // 회원가입시 레벨
$config['cf_register_point'] = 0;   // 회원가입시 포인트
$config['cf_nick_modify']    = 7;   // 별명 수정 딜레이
$config['cf_open_modify']    = 7;   // 정보 수정 딜레이

$config['cf_email_mb_member'] = FALSE; // 가입시 축하메일 발송 여부
$config['cf_email_mb_admin']  = FALSE; // 가입시 관리자에게 메일 발송 여부

$config['cf_prohibit_id'] = "admin,administrator,관리자,운영자,어드민,주인장,webmaster,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,root,루트,su,guest,방문객";

// 로그인 페이지 파라메터
$config['cf_page_login'] = array(
	'pageNum'		=> 7,
	'subNum'		=> 1,
	'mainTitle'		=> '사이트 정보',
	'subTitle'		=> '로그인'
);

// 회원가입 페이지 파라메터
$config['cf_page_join'] = array(
	'pageNum'		=> 7,
	'subNum'		=> 2,
	'mainTitle'		=> '사이트 정보',
	'subTitle'		=> '회원가입'
);

// 회원정보 수정 페이지 파라메터
$config['cf_page_modify'] = array(
	'pageNum'		=> 7,
	'subNum'		=> 5,
	'mainTitle'		=> '사이트 정보',
	'subTitle'		=> '회원정보수정'
);

// 아이디/비번 찾기 페이지 파라메터
$config['cf_page_idpwd'] = array(
	'pageNum'		=> 7,
	'subNum'		=> 3,
	'mainTitle'		=> '사이트 정보',
	'subTitle'		=> '아이디/패스워드 찾기'
);

// 회원 탈퇴 페이지 파라메터
$config['cf_page_leave'] = array(
	'pageNum'		=> 7,
	'subNum'		=> 6,
	'mainTitle'		=> '사이트 정보',
	'subTitle'		=> '회원탈퇴'
);

// 비밀번호 확인 페이지 파라메터 (정보수정/탈퇴 공통)
$config['cf_page_confirm'] = array(
	'pageNum'		=> 7,
	'subNum'		=> 5,
	'mainTitle'		=> '사이트 정보',
	'subTitle'		=> '비밀번호 확인'
);

// 포인트 조회 페이지 파라메터
$config['cf_page_point'] = array(
	'pageNum'		=> 7,
	'subNum'		=> 8,
	'mainTitle'		=> '사이트 정보',
	'subTitle'		=> '포인트 내역'
);
