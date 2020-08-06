<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php
include_once $GP -> CLS."/class.api2.php";
//API 객체생성
// sms.gabia.com 로그인 id와 로그인 후에 환경설정에서 생성한 api key
$api = new gabiaSmsApi('hnufovea','f840f087eba744ce1f01b2a126dc998e');
echo "==============".$_POST['sendNum'];
exit;
$oBuffer = array($_POST['sendNum']);	

$mms_count = $api->getSmsCount();
$ref_key = "allspineMMS";
$callback_arr= $api->getCallbackNum(); //type : array , 등록된 발신번호 리턴

$file_path = array('./Penguins.jpg');  //서버에 있는 이미지 경로
/************************************한건씩 보낼수 있는 SMS_API***************************************************************************/
foreach($oBuffer as $p)
{
	// 발송시에 _REF_KEY_는 나중에 개별적인 발송 결과를 확인하고자 할 때 사용되는 값입니다.
	// 고객 내부의 규칙에 따른 40byte 이내의 unique한 값을 넣어주시면 됩니다.
	
	$r = $api->mms_send($p, $callback_arr, $file_path, "윌스기념병원 수원 찾아 오시는 길 약도 입니다. 경기도 수원시 팔달구 경수대로 437(인계동 994-3) 전화: 1577-8382 지도: http://naver.me/GtPZvPvl", "찾아 오시는 길", $ref_key, "");		//이미지 전송
	if ($r == gabiaSmsApi::$RESULT_OK)
	{
		echo($p . " : " . $api->getResultMessage() . "<br>");
		echo("이전 : " . $api->getBefore() . "<br>");
		echo("이후 : " . $api->getAfter() . "<br>");
	}
	else echo("error : " . $p . " - " . $api->getResultCode() . " - " . $api->getResultMessage() . "<br>");
}
// 발송 결과 값을 알고자 하는 ref key 값 설정.
$result = $api->get_status_by_ref($ref_key);
echo "<br>";
echo "CODE : ".$result["CODE"]."\n<br>";
echo "MESG : ".$result["MESG"];
?>

