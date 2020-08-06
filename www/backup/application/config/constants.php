<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* 사용자 정의 */
//define('ADMIN', 'whois'); // 최고관리자
define('ADM_F', 'adm'); // 관리자폴더

/* (utf-8 or euc-kr) 변경시 php,html,css,js 파일 인코딩은 별도 변환 작업 해야함 일부파일은 utf-8로 고정해야만 할수도 있음. */
define('ENCODING', 'utf-8'); // 인코딩

define('DOC_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('RT_PATH', ''); // ex) /test
define('HTML_PATH', '/html');				// 절대경로 입력 불가
define('LAYOUT_PATH', '/html/layout');		// 절대경로 입력 불가
define('SKIN_PATH', APPPATH.'views/');

define('USER_JS_DIR',  RT_PATH.'/js');
define('USER_CSS_DIR', RT_PATH.'/css');
define('JS_DIR',  RT_PATH.'/src/js');
define('CSS_DIR', RT_PATH.'/src/css');
define('IMG_DIR', RT_PATH.'/src/imgs');
define('EDT_DIR', RT_PATH.'/src/editor');
define('PG_DIR',  RT_PATH.'/pg');
define('DATA_DIR', RT_PATH.'/data');
define('PC_DIR', '/pc');
define('MOBILE_DIR', '/mobile');
define('DATA_PATH', DOC_ROOT.DATA_DIR);

define('TIME', time());
define('TIME_YMD', date('Y-m-d', TIME));
define('TIME_HIS', date('H:i:s', TIME));
define('TIME_YMDHIS', date('Y-m-d H:i:s', TIME));


define('EDITOR_UPLOAD_SIZE', 5); // 게시판 이외 에디터 업로드 사이즈 (mb);

define('ADMIN_MIN_LEVEL', 9); // 관리자 최소 레벨
/* End of file constants.php */
/* Location: ./application/config/constants.php */
