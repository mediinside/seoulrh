<?
//관리자 권한체크
if (!$C_Func -> Admin_Check()) {
	$C_Func -> put_msg_and_back ('접근권한이 없습니다.');
}
?>
