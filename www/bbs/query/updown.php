<?php
//등록관련 정보 확인
if(!$jb_code || !$cl_id || !$ch_id) {
	$C_Func->put_msg_and_back("등록관련정보가 부족합니다. 등록정보를 확인해 주세요.");
	die;
}


$args = "";		
$args['jb_code']	= $jb_code;
$args['cl_id']		= $cl_id;
$args['ch_id']		= $ch_id;
$rst = $C_JHBoard->DP_AUTO_CHAGE($args);

echo "true";
exit();
?>