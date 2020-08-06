<?php
	include_once  '../../_init.php';

	$title = "주소 찾기";
	include_once ($GP -> INC_WWW."/inc.addrheader.php");
	include_once $GP -> CLS . 'class.zipcode.php';
	$C_Zipcode = new Zipcode();
	
	if (is_array($_POST)) foreach ($_POST as $k => $v) ${$k} = $v;
	
	
	function objectToArray($d) {
		
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
		
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
	
	/*
	Array ( 
				 [rdtype] => D 
				 [sido] => 인천 
				 [gugun] => 부평구 
				 [txtDongName] => 부평동 
				 [txtDongNameJibunNo] => 13-25 
				 [txtRoadName] => 도로명 
				 [txtRoadNameBldgNo] => 건물번호 
				 [txtRoadNameBldgNm] => 건물명(아파트명)
	*/
	//보낼 데이터
	$data = array(
		'rdtype' => $rdtype,
		'sido' => urlencode($sido),    
		'gugun' => urlencode($gugun),		
		'txtDongName' => urlencode($txtDongName),								
		'txtDongNameJibunNo' => $txtDongNameJibunNo,	
		'txtRoadName' => urlencode($txtRoadName),								
		'txtRoadNameBldgNo' => $txtRoadNameBldgNo,					
		'txtRoadNameBldgNm' => urlencode($txtRoadNameBldgNm),
		'return_type' => 'json'
	);		
	
	// Send a request to example.com 
	$result = $C_Func->post_request('http://mediinside.co.kr/api/addr_new.php', $data); 
	
	if ($result['status'] == 'ok'){ 
		$re_msg = $result['content'];			
		$obj_result = json_decode($re_msg); 	
		
		if($obj_result->msg == "true") {
			$rst = objectToArray($obj_result->result);		
		}			
	}else{
		exit();	
	}


	if($rst) { 
?>
<p class="TxtInfo">아래의 주소중에서 해당되는 주소를 클릭 해주세요.</p>
<div class="pop1_cont2">
	<ul>
<?
		$args_cnt = count($rst);
		for($i=0; $i<$args_cnt; $i++){
				$optionValue = 
					$rst[$i]['zc_post']."^".$rst[$i]['zc_sido']. " " .$rst[$i]['zi_gugun']. " " .$rst[$i]['zi_doro']. " " .$rst[$i]['zi_build_num1']. "(" . $rst[$i]['zi_law'] . ")"
					."^". $rst[$i]['zc_sido']. " " .$rst[$i]['zi_gugun']. " " .$rst[$i]['zi_law']. " " .$rst[$i]['zi_zibun'] ."-". $rst[$i]['zi_zibun_bu'];
?>
	<li>
		<a href="javascript:;" onclick="parent_addr('<?=$optionValue?>','N')" >
		<label>[<?=$rst[$i]['zc_post']?>]	<?=$rst[$i]['zc_sido']?>	<?=$rst[$i]['zi_gugun']?>	 <?=$rst[$i]['zi_doro']?> 	<?=$rst[$i]['zi_build_num1']?>	(<?=$rst[$i]['zi_law']?>)</label><br />
		<label style="padding-left:52px;"><?=$rst[$i]['zc_sido']?>	<?=$rst[$i]['zi_gugun']?> <?=$rst[$i]['zi_law']?> <?=$rst[$i]['zi_zibun']?>-<?=$rst[$i]['zi_zibun_bu']?></label><br />
		</a>
	</li>	
<? 
		}
?>
	</ul>
</div>
<?
	}else{ 
?>
		<p class="no_data">데이터가 없습니다.</p>
<? 
	} 
?>


