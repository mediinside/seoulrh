<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['cf_use_jumin'] = FALSE; // 주민등록번호 사용

$config['cf_register_level'] = 2;   // 회원가입시 레벨
$config['cf_register_point'] = 0;   // 회원가입시 포인트
$config['cf_nick_modify']    = 7;   // 별명 수정 딜레이
$config['cf_open_modify']    = 7;   // 정보 수정 딜레이

$config['cf_email_mb_member'] = FALSE; // 가입시 축하메일 발송 여부
$config['cf_email_mb_admin']  = FALSE; // 가입시 관리자에게 메일 발송 여부

$config['cf_prohibit_id'] = "admin,administrator,관리자,운영자,어드민,주인장,webmaster,웹마스터,sysop,시삽,시샵,manager,매니저,메니저,root,루트,su,guest,방문객";
