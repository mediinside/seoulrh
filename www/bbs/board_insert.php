<?php
include_once("../_init.php");

$key="";
$value="";
//magic_quotes_gpc 설정이 ON이 아닐경우만 addslashes 처리
//extract()는 반복적으로 변수를 가공하기에 적합하지 않아서 사용안함
if(__MAGIC_QUOTES_GPC__ != 1) {
	foreach($_GET as $key => $value) { $$key=stripslashes(trim($value)); }
	foreach($_POST as $key => $value)	{ $$key=stripslashes(trim($value));}
} else {
	foreach($_GET as $key => $value) { $$key=trim($value); }	
	foreach($_POST as $key => $value) {	$$key=trim($value);	}
}
unset($key, $value);



# 게시판설정정보...
include $GP -> INC_PATH . "/board_config.php";


# 검색키워드 설정
//$search_key['jb_name'] = $_GET['search_key']['jb_name'];
//$search_key['jb_title'] = $_GET['search_key']['jb_title'];
//$search_key['jb_content'] = $_GET['search_key']['jb_content'];
//$search_key="search_key[jb_name]=${search_key[jb_name]}&search_key[jb_title]=$search_key[jb_title]&search_key[jb_content]=$search_key[jb_content]";
$search_key = "search_key=" . $_GET['search_key'];

//쿼리
switch ($jb_mode)
{

	#입력
    case ("twrite") :
        
		include $GP -> INC_PATH . "/query/twrite.inc.php";

	break;

	
	#수정
	case ("tmodify") :
		include $GP -> INC_PATH . "/query/tmodify.inc.php";

	break;
	
	
	#답변
	case ("treply") :
		include $GP -> INC_PATH . "/query/treply.inc.php";

	break;


	#삭제
	case ("tdelete") :
		include $GP -> INC_PATH . "/query/tdelete.inc.php";			

	break;	


	#파일만 삭제
	case ("tfiledelete") :
		include $GP -> INC_PATH . "/query/tfiledelete.inc.php";			

	break;


	#다중 삭제
	case ("tmultidelete") :
		include $GP -> INC_PATH . "/query/tmultidelete.inc.php";			

	break;
	
	
	#다중예약완료처리
	case ("tmulti_copy") :
		include $GP -> INC_PATH . "/query/tmulti_copy.inc.php";			

	break;
	
	
	#댓글입력
	case ("comment_write") :
		include $GP -> INC_PATH . "/query/comment_write.inc.php";

	break;

	
	#댓글수정
	case ("comment_modify") :
		include $GP -> INC_PATH . "/query/comment_modify.inc.php";

	break;
	
	
	#댓글삭제
	case ("comment_delete") :
		include $GP -> INC_PATH . "/query/comment_delete.inc.php";			

	break;
	
	#댓글입력
	case ("comment_reply") :
		include $GP -> INC_PATH . "/query/comment_reply.inc.php";

	break;

	#순서변경
	case ("updown") :
		include $GP -> INC_PATH . "/query/updown.php";
	break;


	default :
		die("비정상적인 접근입니다.");

	break;

} //end_of_switch($board_mode)
?>