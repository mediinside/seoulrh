<?
CLASS Func
{
	// OBJECT 암호화
	function mySerialize( $obj ) {
   return base64_encode(gzcompress(serialize($obj)));
	}

	// OBJECT 복호화
	function myUnserialize( $txt ) {
	   return unserialize(gzuncompress(base64_decode($txt)));
	}
/*
$mail_type_arr = array(
								'news_def'=>'뉴스레터 기본',
								'exam_delay'=>'심사보류',
								'deposit'=>'입금확인'
								);
$mail_type_sel = $C_Func -> makeSelect('mail_type',$mail_type_arr,$mail_type,"onchange='mailSet(this.value)'", '::없슴::');
*/
	// SELECT MAKE
	function makeSelect()
	{
		$args = @func_get_args ();
		$paraArr = array(
					'selectName'
					,'selArr'
					,'vals'
					,'etc'
					,'basic'
					,'desc'
					);
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];

		$str = "<select name='$selectName' id='$selectName' $etc>";
		if($basic)
		{
			$str .= "<option value=''>$basic</option>";
		}

		if(count($selArr) && is_array($selArr))
		{
			if($desc==false) asort($selArr);
			foreach ($selArr as $key => $value)
			{
				$selValue = "";
				$selName = "";
				$selValue = $key;
				$selName = ($value)? $value : $key;

				$selected = "";
				if($vals != '' && $vals == $selValue) $selected = "selected";
				$str .= "<option value='$selValue' $selected>$selName</option>";
			}
		}
		$str .= "</select>";

		return $str;
	}

	// 셀렉트 박스 만들기 배열순서대로 정렬
	function makeSelect_Normal()
	{
		$args = @func_get_args ();
		$paraArr = array(
					'selectName'
					,'selArr'
					,'vals'
					,'etc'
					,'basic'
					);
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];

		$str = "<select name='$selectName' id='$selectName' $etc>";
		if($basic)
		{
			$str .= "<option value=''>$basic</option>";
		}

		if(count($selArr) && is_array($selArr))
		{
			foreach ($selArr as $key => $value)
			{
				$selValue = "";
				$selName = "";
				$selValue = $key;
				$selName = $value;

				$selected = "";
				if($vals && $vals == $selValue) $selected = "selected";
				$str .= "<option value='$selValue' $selected>$selName</option>";
			}
		}
		$str .= "</select>";

		return $str;
	}

	// 라디오버튼 생성
	//$use_value_arr = array('미사용' => 0, '사용' => 1);
	//makeRadio ($use_value_arr, 'bl_type', $bl_type);

	function makeRadio () {
		$args = @func_get_args ();
		$paraArr = array(
					'value_arr'
					,'name'
					,'value'
					,'etc'
					);
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];


		if (is_array($value_arr)) {
			$str = "<table cellpadding='0' cellspacing='0' border='0' class='border_clear'><tr>";
			foreach ($value_arr as $k => $v) {
				if (!$value) $value = 0;
				$checked = ($k == $value)? 'checked' : '';
				$str .= "<td><input style='WIDTH: 14px; HEIGHT: 14px' type='radio' name='$name' value='$k' $checked $etc></td><td> $v</td><td>&nbsp;</td>";
			}
			$str .= "</tr></table>";
		}

		return $str;
	}

	function makeCheckbox () {
		$args = @func_get_args ();
		$paraArr = array(
					'value_arr'
					,'name'
					,'value'
					,'etc'
					);
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];

		if (is_array($value_arr)) {
			foreach ($value_arr as $k => $v) {
				if (is_array($value)) {
					$checked_str = (in_array($k,$value))? 'checked' : '';
				}
				$str .= "<table cellpadding='0' cellspacing='0' border='0' class='border_clear'><tr><td><input type=\"checkbox\" name=\"$name\" value=\"$k\" $checked_str $etc></td><td>$v</td><td>&nbsp;</td></tr></table>";
			}
		}

		return $str;
	}
	
	function makeCheckbox_Normal () {
		$args = @func_get_args ();
		$paraArr = array(
					'value_arr'
					,'name'
					,'value'
					,'etc'
					,'width'
					);
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];

		if (is_array($value_arr)) {
			foreach ($value_arr as $k => $v) {
				if (is_array($value)) {
					$checked_str = (in_array($k,$value))? 'checked' : '';
				}
				$str .= "<div style='display:inline-block; line-height:20px; width:" . $width ."px;'><input type=\"checkbox\" name=\"$name\" value=\"$k\" $checked_str $etc> $v</div>";
			}
		}

		return $str;
	}

	// list 에서 make_select를 위한 데이터 형식으로 변환
	// $get_arr = array('ca_idx','ca_name');
	// reset_for_make_select (&$args, $get_arr);
	function reset_for_make_select (&$args, $get_arr) {
		$args_cnt = count($args);
		for ($i = 0; $i < $args_cnt; $i++) {
			$tmp_arr[$args[$i][$get_arr[0]]] = $args[$i][$get_arr[1]];
			echo $args[$i][$get_arr[0]] . '/' . $args[$i][$get_arr[1]];
		}

		$args = '';
		$args = $tmp_arr;
	}

	// colgroup 스트링 만들기
	function colgroupList ($args) {
		$args_cnt = count($args);
		$retunrStr = '<colgroup>';
		for($i = 0; $i < $args_cnt; $i++)
		{
			$retunrStr .= "<col width=\"".$args[$i]."\">\n";
		}
		$retunrStr .= '</colgroup>';
		return $retunrStr;
	}

	// 글 인코딩 하기
	function enc_contents ($contents) {
		$result = htmlspecialchars(addslashes($contents));

		return $result;
	}

	// 글 디코딩 하기
	function dec_contents ($contents) {
		$result = stripslashes($contents);

		return $result;
	}

	// 글 디코딩 하기
	function dec_contents_view ($contents) {
		$result = htmlspecialchars_decode(nl2br(stripslashes($contents)));

		return $result;
	}

	// 에디터용 글 인코딩 하기
	function enc_contents_edit ($contents) {
		$result = htmlspecialchars(addslashes($this->noScriptTags($contents)));

		return $result;
	}
	

	// 에디터용 글 디코딩 하기
	function dec_contents_edit ($contents) {
		$result = html_entity_decode(stripslashes($contents));

		return $result;
	}
	
	// 스크립트 태그 , 스타일 태그, 주석태그 제거
	function noScriptTags ($str) {
		// 모든태그 제거
		$pattern = array('/<!--(.*?)-->/s', '/<script[^>]*?>(.*?)<\/script>/is', '/<style[^>]*?>(.*?)<\/style>/is');
		return preg_replace($pattern, '', $str);
	}


	// alert 메세지 출력
	function put_msg($msg) {
		echo "<script>alert('$msg');</script>";
		exit;
	}

	// alert 메세지 출력
	function put_msg_top($msg) {
		echo "<script>alert('$msg'); top.location.reload();</script>";
		exit;
	}

	// alert 메세지 출력 후 보냄
	function put_msg_and_go($msg,$url) {
		$url = ($url)? $url : '/';
		echo "<script>alert('$msg'); location.replace('$url');</script>";
		exit;
	}

	// alert 메세지 출력 후 뒤로
	function put_msg_and_back($msg) {
		echo "<script>alert('$msg'); history.back();</script>";
		exit;
	}

	// 해당 url 로 보냄
	function go_url($url) {
		echo "<script>location.href='$url';</script>";
		exit;
	}

	function go_url2($url) {
		echo "<script>location.replace('$url');</script>";
		exit;
	}

	// 해당 url 로 보냄
	function top_go_url($url) {
		echo "<script>top.location.href='$url';</script>";
		exit;
	}

	// alert 후 해당 url 로 보냄
	function put_msg_top_go_url($msg , $url) {
		$url = ($url)? $url : '/';
		echo "<script>alert('$msg'); top.location.href='$url';</script>";
		exit;
	}

	// alert 메세지 출력 후 창 닫기
	function put_msg_and_stop($msg) {
		echo $msg;
		exit;
	}

	// alert 메세지 출력 후 창 닫기
	function put_msg_and_close($msg) {
		echo "<script>alert('$msg'); self.close();</script>";
		exit;
	}

	// alert 메세지 출력 후 부모창 리로드하고 창 닫기
	function put_msg_and_close2($msg) {
		echo "<script>alert('$msg'); opener.location.reload(); self.close();</script>";
		exit;
	}

	// alert 메세지 출력 후 부모창 특정위치로 보내고 창 닫기
	function put_msg_and_close3($msg, $url) {
		$url = ($url)? $url : '/';
		echo "<script>alert('$msg'); opener.location.href='$url'; self.close();</script>";
		exit;
	}

	// alert 메세지 출력 후 부모창의 최상위 레이어를 특정위치로 보내고 창 닫기
	function put_msg_and_close4($msg, $url) {
		$url = ($url)? $url : '/';
		echo "<script>alert('$msg'); opener.top.location.href='$url'; self.close();</script>";
		exit;
	}

	// alert 메세지 출력 후 부모창으로 보내기
	function put_msg_and_parent($msg, $url) {
		$url = ($url)? $url : '/';
		echo "<script>alert('$msg'); parent.location.href='$url';</script>";
		exit;
	}
	
	// alert 메세지 출력 후 RELOAD
	function put_msg_and_parentreload($msg) {
		echo "<script>alert('$msg'); parent.location.reload();</script>";
		exit;
	}

	// alert 메세지 출력 후 RELOAD
	function put_msg_and_modalclose($msg) {
		echo "<script>alert('$msg'); parent.window.location.reload(true); </script>";
		exit;
	}

	// 해당 url 로 보냄
	function go_replace($url) {
		$url = ($url)? $url : '/';
		echo "<script>location.replace('$url');</script>";
		exit;
	}
	// 해당 url 로 보냄
	function go_replace_opener($url) {
		$url = ($url)? $url : '/';
		echo "<script>opener.location.replace('$url');</script>";
		exit;
	}

	// 창 닫고 해당 url 로 보냄
	function go_replace_opener2($url) {
		$url = ($url)? $url : '/';
		echo "<script>opener.top.location.href='$url'; self.close();</script>";
		exit;
	}

	function date_reform1($dateForm) // 0000.00.00
	{
		$dateForm_arr = explode(" ",$dateForm);
		$dateForm_arr2 = explode("-",$dateForm_arr[0]);

		return $dateForm_arr2[0] . "." . $dateForm_arr2[1] . "." . $dateForm_arr2[2];
	}

	function date_reform2($dateForm) // 0000/00/00 00:00:00
	{
		$dateForm_arr = explode(" ",$dateForm);
		$dateForm_arr2 = explode("-",$dateForm_arr[0]);


		return $dateForm_arr2[0] . "/" . $dateForm_arr2[1] . "/" . $dateForm_arr2[2] . ' ' . $dateForm_arr[1];
	}

	function date_reform3($dateForm)
	{
		$dateForm1 = substr($dateForm,0,4);
		$dateForm2 = substr($dateForm,4,2);
		$dateForm3 = substr($dateForm,6,2);

		return $dateForm1 . "." . $dateForm2 . "." . $dateForm3;
	}

	function date_reform4($dateForm)
	{
		$dateForm = str_replace('-','/',$dateForm);
		$dateForm = substr($dateForm,0,16);

		return $dateForm;
	}

	// 한글문자 자름
	function fn_text_cut_kr($str,$start,$len) { 		// 한글 문자 자르기

		$str = trim($str);
		$backcnt= 0; // 시작첫글자에서 뒤로간 byte 수 (space나 영/숫자가 나올때 까지 또는 문장의 맨 처음시작까지)
		$cntcheck =0;

		if ($start>0 ) {
			if(ord($str[$start]) >= 128) { // 첫 시작글자가 한글이면
				for ($i=$start;$i>0;$i--) {
					if (ord($str[$i]) >= 128) {
						$backcnt++;
					}else{
						break;
					}
				}

				if( (ord($str[0]) < 128) || ($backcnt != $start)) {    //첫글자가 한글이 아니거나, 영숫자 없고, 띄어 쓰기 없는 한글로만 되어 있는 경우가 아니면
					$start= ($backcnt%2) ? $start : $start-1;    //첫글짜가 깨지면, 시작점 = (시작 byte -1byte)
				}

				if (($backcnt%2)==1) {
					$cntcheck = 0;    //문장 시작 첫글자 안짤림
				}else{
					$cntcheck = 1;        //문장 시작 첫글자 짤림
				}

			}
		}

		$backcnt2= 0; // 마지막글자에서 뒤로간 byte 수 (space나 영/숫자가 나올때 까지)

		for ($i=($len-1);$i>=0;$i--) {
			if (ord($str[$i+$start]) >= 128){
				$backcnt2++;
			}else{
				break;
			}
		}

		if (($backcnt2%2)==1) {
			$cntcheck2 = 1;    //문장 마지막 글자 짤림
		}else{
			$cntcheck2 = 0;        //문장 마지막 글자 안짤림
		}

		(int)$cnt=$len-abs($backcnt2%2);    //자를 문자열 길이 (byte)
		if(($cntcheck+$cntcheck2)==2) $cnt+=2;    //$cntcheck가 짤리고, $cntcheck2가 짤리는 경우

		$cutstr = substr($str,$start,$cnt);
		if(strlen($str) && strlen($str) > strlen($cutstr))
		$cutstr .= "..";
		return $cutstr;
	}

	// 이미지 비율대로 맞추기 (틀안에 들어가도록)
	function get_re_size ($img_abs_root, $re_w, $re_h) {
		$image_size		= @getimagesize($img_abs_root);
		$width 				= $image_size[0];
		$height				= $image_size[1];

		if ($width > $height) {
			$rate1 = round(1 / round($height / $width , 3),2);
			$rate2 = round($re_w / $width,2);
			$tmp_height = $height * $rate2;

			if ($tmp_height > $re_h) {
				$tmp_width 	= $re_h * $rate1;
				$tmp_height = $re_h;
			} else {
				$tmp_width = $re_w;
			}
		} else if ($width < $height) {
			$rate1 = round(1 / round($width / $height , 3),2);
			$rate2 = round($re_h / $height,2);
			$tmp_width = $width * $rate2;

			if ($tmp_width > $re_w) {
				$tmp_height = $re_w * $rate1;
				$tmp_width 	= $re_w;
			}	else {
				$tmp_height = $re_h;
			}
		} else {
			if ($re_w > $re_h) {
				$tmp_height = $re_h;
				$tmp_width 	= $re_h;
			} else {
				$tmp_height = $re_w;
				$tmp_width 	= $re_w;
			}
		}

		$rst[] = round($tmp_width);
		$rst[] = round($tmp_height);

		return $rst;
	}

	// 이미지 비율대로 맞추기 (틀 밖에 맞추기)
	function get_re_size2  ($img_abs_root, $re_w, $re_h) {
		$image_size		= @getimagesize($img_abs_root);
		$width 				= $image_size[0];
		$height				= $image_size[1];

		$rate_w = round(1 / round($width / $re_w , 3),2);
		$rate_h = round(1 / round($height / $re_h , 3),2);

		if ($rate_h > $rate_w) {
			$tmp_width = $width * $rate_h;
			$tmp_height = $height * $rate_h;
		} else if ($rate_h < $rate_w) {
			$tmp_width = $width * $rate_w;
			$tmp_height = $height * $rate_w;
		} else {
			$tmp_width = $width * $rate_w;
			$tmp_height = $height * $rate_h;
		}

		$rst[] = round($tmp_width);
		$rst[] = round($tmp_height);

		return $rst;
	}

	//Email 유효성 체크
	function check_email($email)
	{
		if (!eregi("^[^@ ]+@[a-zA-Z0-9\-\.]+\.+[a-zA-Z0-9\-\.]", $email))
		{
			return false;
		}else{
			return true;
		}
	}

	//2 Digit
	function make2digit($val)
	{
		if(strlen($val) < 2){
			$val = "0".$val;
		}

		return $val;
	}

	// 2의 자승 합산 풀기
	function untieDigit ($value,$rst = array()) {
		if ($value) {
			$cmp_value = 0;
			$i = 1;
			while ($cmp_value <= $value) {
				$cmp_value = pow(2,$i);
				$i++;
			}
			$digit = $i - 2;

			if ($digit >= 0) {
				$digit_value = pow(2,$digit);
				if ($digit) {
					$mod_value = $value % $digit_value;
				}

				$rst[] = $digit_value;
				if ($mod_value) {
					$cnt = $cnt + 1;
					$rst = $this -> untieDigit ($mod_value,$rst);

				}
			}
		}
		sort($rst);
		return $rst;
	}

	function tieDigit ($args) {
		$rst = '';
		if (is_array($args)) {
			foreach ($args as $v) {
				$rst += $v;
			}
		}

		return $rst;
	}

	// 2자승 의 자승값 구하기
	function untieDigitMakeLog ($value) {
		if (is_array($value)) {
			$rst = "";
			foreach ($value as $v) {
				$rst[] = log($v) / log(2);
			}
		}
		return $rst;
	}


	function isblank($str)
	{
		$p_arr	= array("　","\n","&nbsp;"," ");
		$temp	= str_replace($p_arr,"",$str);
		if(eregi("[^[:space:]]",$temp)) return 0;
		return 1;
	}

	// 영어와 숫자만 있는지 체크 [ 틀림0   ,  맞음1 ]
	function isEngNum($str)
	{
		if(eregi("[^a-zA-Z0-9]",$str)) return 0;
		return 1;
	}

	//숫자만 있는 체크 [ 맞음 1 , 틀림 0 ]
	function isNum($str)
	{
		if(eregi("[^0-9]",$str)) return 0;
		return 1;
	}

	# 특정 문자가 한글의 범위내(0xA1A1 - 0xFEFE)에 있는지 검사
	function isHan($char)
	{
		for($i = 0; $i < strlen($char); $i++) if(ord($char[$i]) < 0x80)  return 0;
		return 1;
	}

	// 이메일 체크 [ 맞음 이메일 , 틀림 빈값 ]
	function ismail( $str )
	{
		if( eregi("([a-z0-9\_\-\.]+)@([a-z0-9\_\-\.]+)", $str) ) return 1;
		return 0;
	}

	// 썬네일 이미지 명 가져오기
	function getThumbName ($name) {
		$nameArr = explode('.', $name);
		$nameCnt = count($nameArr);

		$nameArr[$nameCnt-2] = $nameArr[$nameCnt-2] . '_s';

		return implode('.',$nameArr);
	}

	//카드번호에 '-' 넣기
	function addDashForCardNumber($card_no)
	{
		$rVal = false;

		//if(strlen($card_no) == 16){
			$rVal = substr($card_no, 0, 4) . '-' . substr($card_no, 4, 4) . '-' . substr($card_no, 8, 4) . '-' . substr($card_no, 12, 4);
		//}

		return $rVal;
	}

	// 정보 숨김
	/*
	function blindInfo ($value,$view_cnt) {
		$value_len = strlen($value);
		if ($value_len && $view_cnt) {
			$rst = substr($value,0,$view_cnt);

			for ($i = 0; $i < $value_len - $view_cnt; $i++) {
				$addstar .= '*';
			}
		}
		return $rst . $addstar;
	}
	*/
	
	function blindInfo ($value,$view_cnt) {
		return substr($value, 0, $view_cnt).'**';
	}

	// , 를 삭제하고 인트형으로 변환해서 보냄
	function makeInt ($value) {
		return str_replace(',','',$value) * 1;
	}

	// 클릭로그를 잡기위한 LINK 변환
	function enc_log_link ($args) {
		extract($args);
		// pos 클릭한 섹션 위치, type 쇼핑몰 타입 , url 되돌려 보내야할 url
		$reurl = '&alli_idx=' . $alli_idx . '&pos=' . $pos . '&type=' . $type . '&target=' . $target;

		return $reurl;
	}

	// 엑셀 구간 설정
	function excel_callback($buffer)
	{
	  $buffer_arr = explode('<excel>',$buffer);
	  return $buffer_arr[1];
	}

	function replaceSP($val)
	{
		return str_replace("'", "''", $val);
	}

	// ex) makeExtract ('-', 'zipcode' , '158-525');
	// rst) zipcode1 => 158 , zipcode2 => 525
	function makeExtract ($bar,$name , $val) {
		$str_arr = explode($bar, $val);

		$i = 1;
		foreach ($str_arr as $v) {
			$rst[$name . $i] = $v;
			$i++;
		}
		return $rst;
	}

	function makeOrderNum () {
		$rand_num = rand(1,99999);
		$rand_num_len = strlen($rand_num);
		if (strlen($rand_num) < 5) {
			$feel_zero = 5 - $rand_num_len;
			for ($i = 0; $i < $feel_zero; $i++) {
				$add_zero .= '0';
			}
			$rand_num = $add_zero . $rand_num;
		}
		return $order_num = date('ymdHis') . '_' . $rand_num;
	}

	function getLastDay($year, $month)
	{
	 return date("Y-m-d", @mktime(0, 0, 0, $month+1, 1, $year) - 1 );
	}

	function getNextMonth($year, $month)
	{
	 return date("Y-m-d", @mktime(0, 0, 0, $month+1, 1, $year));
	}

	// 암호화
	function decrypt_md5($hex_buf, $key="password")
	{
	    $len = strlen($hex_buf);
	    for ($i=0; $i<$len; $i+=2)
	        $buf .= chr(hexdec(substr($hex_buf, $i, 2)));

	    $key1 = pack("H*", md5($key));
	    while($buf)
	    {
	       $m = substr($buf, 0, 16);
	       $buf = substr($buf, 16);

	       $c = "";
	       for($i=0;$i<16;$i++)
	       {
	           $c .= $m{$i}^$key1{$i};
	       }

	       $ret_buf .= $m = $c;
	       $key1 = pack("H*",md5($key.$key1.$m));
	    }

	    return($ret_buf);
	}

	// 복호화
	function encrypt_md5($buf, $key="password")
	{
	    $key1 = pack("H*",md5($key));
	    while($buf)
	    {
	        $m = substr($buf, 0, 16);
	        $buf = substr($buf, 16);

	        $c = "";
	        for($i=0;$i<16;$i++)
	        {
	            $c .= $m{$i}^$key1{$i};
	        }
	        $ret_buf .= $c;
	        $key1 = pack("H*",md5($key.$key1.$m));
	    }

	    $len = strlen($ret_buf);
	    for($i=0; $i<$len; $i++)
	        $hex_data .= sprintf("%02x", ord(substr($ret_buf, $i, 1)));
	    return($hex_data);
	}


	//비트연산 {배열로 넘어 온 값을 비트연산 값으로 변환}
	function bit_get($_fld) {
	    global ${"$_fld"};
	    $fld = ${"$_fld"};
	    if(!is_array($fld)) {
	        ${"$_fld"} = 0;
	        return 0;
	    }
	    $tmp = 0;
	    foreach($fld as $k=>$v) {
	        $tmp = $tmp | (0+$v);
	    }
	    ${"$_fld"} = $tmp;
	}
	//비트연산 값을 $sep 으로 구분 하여 String으로 반환
	function bit_str($bit,$arr,$sep="") {

	    if(!is_array($arr)) return NULL;

	    $cnt = count($arr);
	    $b = 1; $str = ""; $cnt_match =0;
	    for($n=0;$n<$cnt;$n++) {
	        if($b & $bit) {
	            if($cnt_match>0)
	                $str .= $sep;
	            $str .= $arr[$b];
	            $cnt_match ++;
	        }
	        $b <<= 1;
	    }
	    return $str;
	}

	// 로그쓰기
	function file_log_write ($args) {
		extract($args);
		$today = date("Ymd");
		$target_file = $target."/" . $today . ".data";

		$fp = @fopen($target_file , "a+") or die("Can't open ".$target_file);
		$filename = $target_file;
		$getNow = date("YmdHms");
		$msg = $getNow . ' : ' . $msg;

		@fwrite($fp, "#$msg\n");
		@fclose($fp);
	}


	function getHour () {
		for ($i = 0; $i < 24; $i++) {
			$data = (strlen($i) < 2)? '0' . $i : $i;
			$rst[$data] = $data;
		}

		return $rst;
	}

	function getMinute () {
		for ($i = 0; $i < 60; $i++) {
			$data = (strlen($i) < 2)? '0' . $i : $i;
			$rst[$data] = $data;
		}

		return $rst;
	}

	function getUseCnt ($str) {
		if($str<1){
			exit();
		}
		for ($i = 1; $i <= $str; $i++) {
			$data = (strlen($i) < 2)? ' ' . $i : $i;
			$rst[$data] = $data;
		}

		return $rst;
	}

	// 문자열마지막에 특수기호를 추가하여 마지막 배열 빼기
	function strCuts_replace($val1,$val2,$val3){
		$str = $val1.$val3;
		$strExt = str_replace($val2.$val3,' ',$str);
		return $strExt;
	}

	// 파일 삭제
	function fileDelete($dirs){
		$rs = @unlink($dirs);
		return $rs;
	}

	function enc_nl2br ($val) {
		if ($val) {
			$tmp_arr = explode('<br />', nl2br($val));

			if (is_array($tmp_arr)) {
				$rst = implode('`#', $tmp_arr);
			}
		}

		return $rst;
	}

	function dec_nl2br ($val) {
		if ($val) {
			$rst = str_replace('`#','', $val);

	/*		if (is_array($tmp_arr)) {
				$rst = implode('<br />', $tmp_arr);
			}		*/
		}

		return $rst;
	}

	function arr_nl2br ($val) {
		if ($val) {
			$rst = explode('`#', $val);
		}

		return $rst;
	}

	function make_ordernum ($type) {
			$rand_num = rand(1,9);
			$rand_num_len = strlen($rand_num);
			if (strlen($rand_num) < 5) {
				$feel_zero = 5 - $rand_num_len;
				for ($i = 0; $i < $feel_zero; $i++) {
					$add_zero .= '0';
				}
				$rand_num = $add_zero . $rand_num;
			}
			return $order_num = $type . date('ymdHis') . $rand_num;
	}

	// $addr1, $addr2를 받아 네이버 포지션 x,y축을 얻는다 2011.1.19 어해민
	function getNaverPoint ($args) {
		extract($args);


		$args = '';
		$args['address'] = $addr1 . $addr2;
	  $rst = getNaverPointAction ($args);
	  // 주소1,2 로 찾지 못하면 주소1로 다시 찾아본다
	  if ($rst['x'] == '') {
			$args = '';
			$args['address'] = $addr1;
		  $rst = getNaverPointAction ($args);
	  }

		return $rst;
	}

	function getNaverPointAction ($args) {
		global $GL_navermap_api_key;
		extract($args);

	  // 네이버 지도api 키값
	  $map_key = "key=" . $GL_navermap_api_key;
	  if (!$map_query) $map_query = $address;

	  // 여기부터 주소 검색 xml 파싱
	  $pquery = $map_key. "&query=". str_replace(" ","",$map_query);
	//echo $pquery;
	  $fp = fsockopen ("map.naver.com", 80, $errno, $errstr, 30);
	  if (!$fp) {
	      echo "$errstr ($errno)";
	      //exit;
	  } else {
	      fputs($fp, "GET /api/geocode.php?");
	      fputs($fp, $pquery);
	      fputs($fp, " HTTP/1.1\r\n");
	      fputs($fp, "Host: map.naver.com\r\n");
	      fputs($fp, "Connection: Close\r\n\r\n");

	      $header = "";
	      while (!feof($fp)) {
	          $out = fgets ($fp,512);
	          if (trim($out) == "") {
	              break;
	          }
	          $header .= $out;
	      }

	      $mapbody = "";
	      while (!feof($fp)) {
	          $out = fgets ($fp,512);
	          $mapbody .= $out;
	      }

	      $idx = strpos(strtolower($header), "transfer-encoding: chunked");

	      if ($idx > -1) { // chunk data
	          $temp = "";
	          $offset = 0;
	          do {
	              $idx1 = @strpos($mapbody, "\r\n", $offset);
	              $chunkLength = hexdec(substr($mapbody, $offset, $idx1 - $offset));

	              if ($chunkLength == 0) {
	                  break;
	              } else {
	                  $temp .= substr($mapbody, $idx1+2, $chunkLength);
	                  $offset = $idx1 + $chunkLength + 4;
	              }
	          } while(true);
	          $mapbody = $temp;
	      }
	      //header("Content-Type: text/xml; charset=utf-8");
	      fclose ($fp);
	  }

	  // 여기부터 좌표값 변수에 등록
	  $map_x_point_1=explode("<x>", $mapbody);
	  $map_x_point_2=explode("</x>", $map_x_point_1[1]);
	  $map_x_point=$map_x_point_2[0];
	  $map_y_point_1=explode("<y>", $mapbody);
	  $map_y_point_2=explode("</y>", $map_y_point_1[1]);
	  $map_y_point=$map_y_point_2[0];

		$rst['x'] 		= $map_x_point;
		$rst['y'] 		= $map_y_point;

	  return 	$rst;
	}

	function bigIntToStr ($a) {
		$a = number_format($a);
		return str_replace(",","",$a);
	}

	# 네이버 좌표 -> 구글 변환
	function naverChangeGoogle($args){
		//$TmX = "340751";
		//$TmY = "528629";
		extract($args);
		$apiKey = "44582f33bd00c4d0eac0d7048db6c7f85a1071ed";
		$pquery = "apikey=$apiKey&x=$TmX&y=$TmY&fromCoord=KTM&toCoord=WGS84&output=xml";

		$fp = fsockopen ("apis.daum.net", 80, $errno, $errstr, 30);
		if (!$fp) {
		  	echo "$errstr ($errno)";
		  	//exit;
		} else {
			fputs($fp, "GET /maps/transcoord?");
			fputs($fp, $pquery);
			fputs($fp, " HTTP/1.1\r\n");
			fputs($fp, "Host: apis.daum.net\r\n");
			fputs($fp, "Connection: Close\r\n\r\n");

			$header = "";
		      while (!feof($fp)) {
		          $out = fgets ($fp,512);
		          if (trim($out) == "") {
		              break;
		          }
		          $header .= $out;
		      }

		      $mapbody = "";
		      while (!feof($fp)) {
		          $out = fgets ($fp,512);
		          $mapbody .= $out;
		      }

		      /*
		      echo $mapbody;
		      exit();
		      */

		      $idx = strpos(strtolower($header), "transfer-encoding: chunked");

		      if ($idx > -1) { // chunk data
		          $temp = "";
		          $offset = 0;
		          do {
		              $idx1 = @strpos($mapbody, "\r\n", $offset);
		              $chunkLength = hexdec(substr($mapbody, $offset, $idx1 - $offset));

		              if ($chunkLength == 0) {
		                  break;
		              } else {
		                  $temp .= substr($mapbody, $idx1+2, $chunkLength);
		                  $offset = $idx1 + $chunkLength + 4;
		              }
		          } while(true);
		          $mapbody = $temp;
		      }
		      //header("Content-Type: text/xml; charset=utf-8");
		      fclose ($fp);

		      $mapPointLat1 = explode("x='",$mapbody);
		      $mapPointLat1 = explode("'", $mapPointLat1[1]);

		      $mapPointLat2 = explode("y='",$mapbody);
		      $mapPointLat2 = explode("'", $mapPointLat2[1]);

		      $rst['lat'] = $mapPointLat1[0];
		      $rst['lng'] = $mapPointLat2[0];

		      return $rst;
		}
	}


	# 구글 위도 경도 변환 함수.
	function googleMapPoint($addr){
		//$addr = "서울시강남구 대치동";
		$addr = iconv("EUC-KR","UTF-8",$addr);
		$addr = urlencode($addr);

		$pquery = "address=$addr&sensor=false";

		$fp = fsockopen ("maps.google.com", 80, $errno, $errstr, 30);
		if (!$fp) {
		  	echo "$errstr ($errno)";
		  	//exit;
		} else {
			fputs($fp, "GET /maps/api/geocode/xml?");
			fputs($fp, $pquery);
			fputs($fp, " HTTP/1.1\r\n");
			fputs($fp, "Host: maps.google.co.kr\r\n");
			fputs($fp, "Connection: Close\r\n\r\n");

			$header = "";
		      while (!feof($fp)) {
		          $out = fgets ($fp,512);
		          if (trim($out) == "") {
		              break;
		          }
		          $header .= $out;
		      }

		      $mapbody = "";
		      while (!feof($fp)) {
		          $out = fgets ($fp,512);
		          $mapbody .= $out;
		      }

		      /*
		      echo $mapbody;
		      exit();
		      */

		      $idx = strpos(strtolower($header), "transfer-encoding: chunked");

		      if ($idx > -1) { // chunk data
		          $temp = "";
		          $offset = 0;
		          do {
		              $idx1 = @strpos($mapbody, "\r\n", $offset);
		              $chunkLength = hexdec(substr($mapbody, $offset, $idx1 - $offset));

		              if ($chunkLength == 0) {
		                  break;
		              } else {
		                  $temp .= substr($mapbody, $idx1+2, $chunkLength);
		                  $offset = $idx1 + $chunkLength + 4;
		              }
		          } while(true);
		          $mapbody = $temp;
		      }
		     // header("Content-Type: text/xml; charset=utf-8");
		      fclose ($fp);



		      $mapPointLat1 = explode("<location>",$mapbody);
		      $mapPointLat2 = explode("</location>",$mapPointLat1[1]);
		      $rstMapPoint = $mapPointLat2[0];

		      $rstMapPoint1 = explode("<lat>",$rstMapPoint);
		      $rstMapPoint1 = explode("</lat>",$rstMapPoint1[1]);

		      $rstMapPoint2 = explode("<lng>",$rstMapPoint);
		      $rstMapPoint2 = explode("</lng>",$rstMapPoint2[1]);

		     $google_lat = $rstMapPoint1[0];
		     $google_lng = $rstMapPoint2[0];

		     $Arr_Google[0] = $google_lng;	//위도
		     $Arr_Google[1] = $google_lat;	//경도

		     return $Arr_Google;
		}
	}

	# 쿠폰 랜
	function randpass()
	 {
	  $chars = "abcdefghijkmnopqrstuvwxyz023456789";
	  srand((double)microtime()*1000000);

	  $pass = '' ;
	  $i = 0;
	  while ($i <= 15) {
	   $num = rand() % 33;
	   $tmp = substr($chars, $num, 1);
	   $pass = $pass . $tmp;
	   $i++;
	  }

	  $str1 = substr($pass,0,4);
	  $str2 = substr($pass,4,4);
	  $str3 = substr($pass,8,4);
	  $str4 = substr($pass,12,4);

	  $ext = $str1 ."-".$str2 ."-".$str3 ."-".$str4;
	  $ext = strtoupper($ext);

	  return $ext;
	 }

	// 앞부분에 빈자리만큼 0을 채운다
	function fillZeroDigit ($args) {
		$cnt = '';
		$str = '';

		extract($args);

		$str_len = strlen($str);
		$len_over_cnt = $cnt - $str_len;
		if ($len_over_cnt) {
			for ($i = 0; $i < $len_over_cnt ; $i++) {
				$make_fill .= '0';
			}
		}

		return $make_fill . $str;
	}


	function http_post ($url, $data)
	{
	  $data_url = http_build_query ($data);
	  $data_len = strlen ($data_url);

	  return array ('content'=> file_get_contents ($url, false, stream_context_create (array ('http'=>array ('method'=>'POST'
	          , 'header'=>"Connection: close\r\nContent-Length: $data_len\r\n"
	          , 'content'=>$data_url
	          ))))
	      , 'headers'=>$http_response_header
	      );
	}

	function UDP_Network_Sock_SEND($_IP, $_PORT, $_MSG)
	{
		$sock = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
		@socket_set_nonblock($sock);
		if($sock > 0){
			$sc = @socket_connect($sock, $_IP, $_PORT);
			if($sc > 0){
				@socket_write($sock, $_MSG, strlen($_MSG));
			}
			@socket_close($sock);
		}
	}

	function UDP_Network_Sock_RECV($_IP, $_PORT, $_MSG)
	{
			$result = "";
			$sock = @socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
			@socket_set_nonblock($sock);
			if($sock > 0) {
				$sc = @socket_connect($sock, $_IP, $_PORT);
				if($sc > 0) {
					@socket_write($sock, $_MSG, strlen($_MSG));
					usleep(10000);
					$result = socket_read($sock, 1024);
				}
				socket_close($sock);
			}
			return $result;
	}


  // 배열정보에서 해당 정보 받아오기
  function getStatusInfoFromArray ($args = '') {
  	if (is_array($args)) extract($args);
  	if (is_array($data)) {
  		foreach ($data as $v) {
  			if ($v['status'] == $status) {
  				return $v;
  				break;
  			}
  		}
  	}
  }

  function makeLink ($args = '') {
  	if (is_array($args)) extract($args);

  	$rst = $data;
  	if (!empty($data) && !empty($link)) {
  		$rst = "<a href='$link' $etc>" . $data . "</a>";
  	}
  	return $rst;
  }

  // 0 "0" 도 분별
	function is_blank($value) {
	    return empty($value) && !is_numeric($value);
	}

  // 상태 select array 받아오기
	function getStatusSelectArr ($args = '') {
  	if (is_array($args)) extract($args);
  	global $GP;
  	switch ($type) {
  		case 'site':
  			$data = $GP -> GL_Ads_status;
  			break;
  		case 'keyword':
  			$data = $GP -> GL_Key_status;
  			break;
  	}

  	foreach ($data as $v) {
  		$rst[$v['status']] = $v['name'];
  	}
  	return $rst;
	}

	
	function seller_is_login() {
		if (!$_SESSION['m_idx'] && $_SESSION['m_level'] != '10') {
			$rst = 0;
		} else {
			$rst = 1;
		}

		return $rst;
	}
	
	// 관리자. 로그인 확인하기
	function is_login_admin () {
		if ($_SESSION['suserlevel'] !='1') {
			$rst = 0;
		} else {
			$rst = 1;
		}

		return $rst;
	}
	
	//어드민 레벨 체크
	function Admin_Check() {
		if ($_SESSION['suserlevel'] != '9') {
			$rst = 0;
		} else {
			$rst = 1;
		}
		return $rst;
	}

	function getFiles ($folder_name) {
		if(is_dir($folder_name)) {
			$dir_obj = opendir ($folder_name);
			$i = 0;
			while(($file_str = readdir($dir_obj)) !== false) {
				if($file_str!="." && $file_str!=".."){
					$rst[$i]['name'] = $file_str;
					$rst[$i]['size'] =  filesize($folder_name."/".$file_str);
					$i++;
				}
			}
			closedir($dir_obj);
		}

		return $rst;
	}

	// 파일 리스트 노출
	function fileInfoView ($val) {
		$args = unserialize($val);

		if (is_array($args)) {
			$i = 0;
			foreach ($args as $v) {
				$rst[$i]['str'] = $v['name'] . ' [' . $this -> getConvKByte($v['size']) . ' kb]';
				$rst[$i]['name'] = $v['name'];
				$i++;
			}
		}

		return $rst;
	}

	// 킬로바이트로 변경
	function getConvKByte ($val) {
		return round(($val / 1024),2);
	}

	// 폴더 내용 압축
	function makeZipForder ($target,$loc = '') {
		global $GP;

		include_once $GP -> CLS . 'class.zip.php';
		$loc = ($loc)? $loc : $GP -> UP_WORK;

		$file_location = $loc . $target;
		$files = $this -> getFiles ($file_location);

		if (is_array($files) && count($files) > 1) {
			// 압축파일 만들기
			$ziper = new zipfile();
			$ziper -> loadFiles ($file_location, $files);
			$ziper -> output($loc . $target . ".zip");
		}

		return $files;
	}

	// GET POST 내용을 보여줌
	function submitPrint () {
		global $_POST,$_GET;
		foreach ($_POST as $k => $v) {
			echo 'POST:' . $k . ' =>' . $v . '<br>';
		}

		foreach ($_GET as $k => $v) {
			echo 'GET:' . $k . ' =>' . $v . '<br>';
		}

		foreach ($_FILES as $k => $v) {
			echo 'FILES:' . $k . ' =>' . $v . '<br>';
		}
	}

	function strcut_utf8($str, $len, $checkmb=false, $tail='') {
	    /**
	     * UTF-8 Format
	     * 0xxxxxxx = ASCII, 110xxxxx 10xxxxxx or 1110xxxx 10xxxxxx 10xxxxxx
	     * latin, greek, cyrillic, coptic, armenian, hebrew, arab characters consist of 2bytes
	     * BMP(Basic Mulitilingual Plane) including Hangul, Japanese consist of 3bytes
	     **/
	    preg_match_all('/[\xE0-\xFF][\x80-\xFF]{2}|./', $str, $match); // target for BMP

	    $m = $match[0];
	    $slen = strlen($str); // length of source string
	    $tlen = strlen($tail); // length of tail string
	    $mlen = count($m); // length of matched characters

	    if ($slen <= $len) return $str;
	    if (!$checkmb && $mlen <= $len) return $str;

	    $ret = array();
	    $count = 0;
	    for ($i=0; $i < $len; $i++) {
	        $count += ($checkmb && strlen($m[$i]) > 1)?2:1;
	        if ($count + $tlen > $len) break;
	        $ret[] = $m[$i];
	    }

	    return join('', $ret).$tail;
	}
	
	//문자열자르기 by shin
	
	function sub_string($string,$start,$length,$charset=NULL) {     
	if($charset==NULL) {
			$charset='UTF-8';
		  }

		   $str_len=mb_strlen($string,'UTF-8'); 
		   if($str_len>$length) {   

			$string=mb_substr($string,$start,$length,'UTF-8');   
			$string.="...";
			}         
			  return $string;             
    }	
	

	// desc	 : 셀렉트박스 값 체크
	// auth  : YYH 2012-12-12 수요일
	// param
	function GetChk($str, $value)
	{
		if (is_array($value)) {
			unset($ar);
			foreach ($value as $val) {
				if ($val) $ar[] = $val;
			}
			$chk = (@in_array($str, $ar)) ? ' checked="checked"' : '';
		} else {
			$chk = ($str == $value) ? ' checked="checked"' : '';
		}
		return $chk;
	}

	####### 로더앱 관련
	function request_minus_day($start_date, $add_day)
	{
		$date = date("Y-m-d", strtotime($start_date."-". $add_day." days"));
		return $date;
	}

	function request_add_day($start_date, $add_day)
	{
		$date = date("Y-m-d", strtotime($start_date."+". $add_day." days"));
		return $date;
	}

	
	function Iconv_euc_utf8($str){
		$str = iconv("EUC-KR","UTF-8",$str);
		return $str;
	}
	
	function Iconv_utf8_euc($str){
		$str = iconv("UTF-8","EUC-KR",$str);
		return $str;
	}
	
	
	// 관리자 로그인 확인하기
	function is_adm_login () {
		if (!$_SESSION['adminid']) {
			$rst = 0;
		} else {
			$rst = 1;
		}
		return $rst;
	}
	
	// 로그인 확인하기
	function is_login () {
		if (!$_SESSION['susercode']) {
			$rst = 0;
		} else {
			$rst = 1;
		}
		return $rst;
	}
	
	// 관리자 로그인 확인하기
	function adminTitleBar($title, $width='1030') {
			$html = "
				<div style='font-size:17px; font-weight:bold; height:25px; padding-top:10px; text-align:left'><img src='/admin/img/common/t_icon1.gif' />$title</div>
				<div style='width:".$width."px; height:3px; background:#ccc'></div><br />
			";
			return $html;
	}

	
	
	#++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
	# 게시판 관려ㄴ 함수
	#++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++#
	
	# 자동줄바꿈
	function auto_br($str, $br_use="Y")
	{
		if ($br_use != "N")
			$str=nl2br($str);
	
		return $str;
	}
	
	
	// 자동 링크
	function auto_link($str)
	{
		$regex[http] = "(http|https|ftp|telnet|news):\/\/([a-z0-9_\-]+\.[][a-zA-Z0-9:;&#@=_~%\?\/\.\,\+\-]+)";
		$regex[mail] = "([a-z0-9_\-]+)@([a-z0-9_\-]+\.[a-z0-9\._\-]+)";
		
		// < 로 열린 태그가 그 줄에서 닫히지 않을 경우 nl2br()에서 <BR> 태그가
		// 붙어 깨지는 문제를 막기 위해 다음 줄까지 검사하여 이어줌
		$str = eregi_replace("<([^<>\n]+)\n([^\n<>]+)>", "<\\1 \\2>", $str);
		
		// 특수 문자와 링크시 target 삭제
		$str = eregi_replace("&(quot|gt|lt)","!\\1",$str);
		$str = eregi_replace("([ ]*)target=[\"'_a-z,A-Z]+","", $str);
		$str = eregi_replace("([ ]+)on([a-z]+)=[\"'_a-z,A-Z\?\.\-_\/()]+","", $str);
		
		// html사용시 link 보호
		$str = eregi_replace("<a([ ]+)href=([\"']*)($regex[http])([\"']*)>","<a href=\"\\4_orig://\\5\" target=\"_blank\">", $str);
		$str = eregi_replace("<a([ ]+)href=([\"']*)mailto:($regex[mail])([\"']*)>","<a href=\"mailto:\\4#-#\\5\">", $str);
		$str = eregi_replace("<img([ ]*)src=([\"']*)($regex[http])([\"']*)","<img src=\"\\4_orig://\\5\"",$str);
		
		// 링크가 안된 url및 email address 자동링크
		$str = eregi_replace("($regex[http])","<a href=\"\\1\" target=\"_blank\">\\1</a>", $str);
		$str = eregi_replace("($regex[mail])","<a href=\"mailto:\\1\">\\1</a>", $str);
		
		// 보호를 위해 치환한 것들을 복구 
		$str = eregi_replace("!(quot|gt|lt)","&\\1",$str);
		$str = eregi_replace("http_orig","http", $str);
		$str = eregi_replace("#-#","@",$str);
		
		// link가 2개 겹쳤을때 이를 하나로 줄여줌
		$str = eregi_replace("(<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>)+<a href=([\"']*)($regex[http])([\"']*)+([^>]*)>","\\1", $str);
		$str = eregi_replace("(<a href=([\"']*)mailto:($regex[mail])([\"']*)>)+<a href=([\"']*)mailto:($regex[mail])([\"']*)>","\\1", $str);
		$str = eregi_replace("</a></a>","</a>",$str);
		
		return $str;
	}
	
	function url_auto_link($str)
	{
			// 140326 유창화님 제안코드로 수정
			// http://sir.co.kr/bbs/board.php?bo_table=pg_lecture&wr_id=461
			// http://sir.co.kr/bbs/board.php?bo_table=pg_lecture&wr_id=463
			$str = str_replace(array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;"), array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t"), $str);
			$str = preg_replace("`(?:(?:(?:href|src)\s*=\s*(?:\"|'|)){0})((http|https|ftp|telnet|news|mms)://[^\"'\s()]+)`", "<A HREF=\"\\1\" TARGET='_blank'>\\1</A>", $str);
			$str = preg_replace("/(^|[\"'\s(])(www\.[^\"'\s()]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='_blank'>\\2</A>", $str);
			$str = preg_replace("/[0-9a-z_-]+@[a-z0-9._-]{4,}/i", "<a href='mailto:\\0'>\\0</a>", $str);
			$str = str_replace(array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t"), array("&nbsp;", "&lt;", "&gt;"), $str);
	
			/*
			// 속도 향상 031011
			$str = preg_replace("/&lt;/", "\t_lt_\t", $str);
			$str = preg_replace("/&gt;/", "\t_gt_\t", $str);
			$str = preg_replace("/&amp;/", "&", $str);
			$str = preg_replace("/&quot;/", "\"", $str);
			$str = preg_replace("/&nbsp;/", "\t_nbsp_\t", $str);
			$str = preg_replace("/([^(http:\/\/)]|\(|^)(www\.[^[:space:]]+)/i", "\\1<A HREF=\"http://\\2\" TARGET='{$config['cf_link_target']}'>\\2</A>", $str);
			//$str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,]+)/i", "\\1<A HREF=\"\\2\" TARGET='$config['cf_link_target']'>\\2</A>", $str);
			// 100825 : () 추가
			// 120315 : CHARSET 에 따라 링크시 글자 잘림 현상이 있어 수정
			$str = preg_replace("/([^(HREF=\"?'?)|(SRC=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET='{$config['cf_link_target']}'>\\2</A>", $str);
	
			// 이메일 정규표현식 수정 061004
			//$str = preg_replace("/(([a-z0-9_]|\-|\.)+@([^[:space:]]*)([[:alnum:]-]))/i", "<a href='mailto:\\1'>\\1</a>", $str);
			$str = preg_replace("/([0-9a-z]([-_\.]?[0-9a-z])*@[0-9a-z]([-_\.]?[0-9a-z])*\.[a-z]{2,4})/i", "<a href='mailto:\\1'>\\1</a>", $str);
			$str = preg_replace("/\t_nbsp_\t/", "&nbsp;" , $str);
			$str = preg_replace("/\t_lt_\t/", "&lt;", $str);
			$str = preg_replace("/\t_gt_\t/", "&gt;", $str);
			*/
	
			return $str;
	}
	
	//답글 처리
	function reply_depth($step, $icon="")
	{	
		global $GP;		

		$depth_limit=5;	//답글 밀림 정도를 방지
		$depth="";
		
		if($step > 0) {	
			if($step < $depth_limit){
				for ($i=1; $i<=$step; $i++)
					$depth .= "&nbsp;&nbsp;";					
			} else {
				for ($i=1; $i<= $depth_limit; $i++)
					$depth .="&nbsp;&nbsp;";
			}
			
			if($icon == "")
				$depth = $depth . "<img src=\"" . $GP -> IMG_PATH. "/comm_img/ticon_reply.gif\" width=38 height=14 border=0 align=\"absmiddle\"> ";
			else
				$depth = $depth . $icon;
		}
			
		return $depth;
	}
	
	/*===========================================================================
	## 뉴 아이콘 적용
	
	$term			: 아이콘을 출력시켜줄 기간
	$reg_date		: 등록일
	$icon			: 적용시킬아이콘
	=============================================================================*/
	function new_icon($term, $reg_date, $icon)
	{
		$new_icon = "";	
		$reg_date = str_replace(" ","-",$reg_date);
		$reg_date = str_replace(":","-",$reg_date);
		$ex = explode("-",$reg_date);
		
		$reg_mktime = mktime($ex[3], $ex[4], $ex[5], $ex[1], $ex[2], $ex[0]); // 시 분 초 월 일 년
		$to_mktime = mktime(0, 0, 0, date(m), date(d), date(Y)) - ($term*86400);
		
		if($reg_mktime > $to_mktime)
			$new_icon .= $icon;	
		return $new_icon;
	}
	
	//number_format 의 약자	
	function num_f($val) 
	{
		return number_format($val);
	}
	
	
	//글자 두께
	function font_bold($str)
	{
		$str = "<b>" . $str . "</b>";
		
		return $str;
	}
	
	// 자동등록방지용 난수생성함수
	function rand_make()
	{
		srand((double)microtime()*1000000);
		$f_rand=rand();
	
		srand((double)microtime()*1000000);
		$s_rand=rand();
	
		$rand_key = $f_rand + $s_rand;
		$rand_key = substr($rand_key,0,5);
		
		return $rand_key;
	}
	
	//파일 업로드( 확장자 체크 )
	function file_ext_check($user_file_name, $user_file_size)
	{
		$file_ext = substr( strrchr($user_file_name, "."),1); 
		$file_ext = strtolower($file_ext);	//확장자를 소문자로...
		
		//확장자 체크
		if ($file_ext=="html" || $file_ext == "htm" || $file_ext=="php3" || $file_ext=="php" || $file_ext=="phtml" || $file_ext=="cgi" || $file_ext=="exe")
		{
			$this->put_msg_and_back('해당 파일은 보안 관계로 올릴 수 없습니다.');
			die;
		}	
	
		//업로드 허용용량체크 - MIN
		if ($user_file_size <= 1)
		{
			$this->put_msg_and_back('비정상적인 파일입니다.');
			die;
		}
		
		//업로드 허용용량체크 - MAX
		if ($user_file_size >= 52428800) {
			$this->put_msg_and_back("업로드 허용용량 초과!!");
			die;
		}
		
		return true;
	}
	
	//파일업로드("", "", "", 저장경로, 저장후 파일명, 저장전 파일명)
	function file_upload($f_tmp_name, $f_name, $f_size, $save_path, $old_file_name="")
	{
		//확장자 구분
		$file_ext = substr( strrchr($f_name, "."),1); 
		$file_ext = strtolower($file_ext);	//확장자를 소문자로...
		
		
		//파일명 새로 지정
		$file_name_md5=md5($f_name);	
		$rand_key= $this->rand_make();
		$file_name_md5=substr($file_name_md5, 5, 10);
		$file_name_md5=$file_name_md5 . $rand_key;
		$new_file_name=$file_name_md5 . "." . $file_ext;
		
		//이전파일 삭제
		if($old_file_name)
			@unlink("${save_path}/${old_file_name}");
		
		//이미지업로드
		$chk=@copy($f_tmp_name, "${save_path}/${new_file_name}");
			
		//이미업로드가 정상적으로 이루어 졌을 경우에만 썸네일 이미지 생성
		if(!$chk)
		{
			$new_file_name="";
		}
		else
		{	
			//gif, jpg, png의 경우 썸네일이미지생성
			if ($file_ext=="gif" || $file_ext=="jpg" || $file_ext=="png")
			{
				//이전 썸네일 이미지명 삭제
				if($old_file_name)
					$old_img_name="s_" . $old_file_name;
			
				//썸네일 이미지명 지정 
				$save_img_name="s_" . $file_name_md5 . $ex_tm . "." . $file_ext;
	
				//썸네일 이미지 생성
				$this->image_create_upload($f_tmp_name, $f_name, $f_size, $save_path, $save_img_name, $old_img_name, 130, 130);
			}
		} //end_of_if(!$chk)
	
	
		//임시파일 삭제
		@unlink($f_tmp_name);
		
		return $new_file_name;
		
	}//fuction image_upload
	
	
	//이미지 최적화후 업로드(파일명, 확장자, 이미지사이즈가 지정된형식이나 사이즈로 변경된후 저장...)
	function image_create_upload($img_tmp_name, $img_name, $img_size, $save_path, $save_img_name, $old_img_name="", $img_width, $img_height)
	{		
		//이전파일 삭제
		if($old_img_name)
			@unlink("$save_path/$old_img_name");
			
		
		//파일의 확장자
		$file_ext = substr( strrchr($img_name, "."),1); 
		$file_ext = strtolower($file_ext);	//확장자를 소문자로...
		
				
		#
		# 이하 썸네일
		#
		
		//비율에 맞게 이미지 조정...
		$sz = @getimagesize($img_tmp_name);
		if($sz[0]  > $img_width || $sz[1] > $img_height)
		{        
			if($sz[0]>$sz[1]) $per=$img_width/$sz[0]; 
			else $per=$img_height/$sz[1];
			$width=ceil($sz[0]*$per); 
			$height=ceil($sz[1]*$per); 
		}
		else
		{
			$width=ceil($sz[0]);//width 값 
			$height=ceil($sz[1]);//height 값 
		}				
				
		//파일의 확장자에 따른 썸네일 생성
		switch($file_ext)
		{
			case "jpg" :
				$image = ImageCreateFromJPEG($img_tmp_name); 
				$thumb_image = ImageCreateTrueColor($width,$height); 
				imagecopyresampled($thumb_image,$image,0,0,0,0,$width+1,$height+1,ImageSX($image),ImageSY($image)); 
				$thumbneil = $save_path."/".$save_img_name;
				ImageJPEG($thumb_image,$thumbneil,100);
				break;
				
			case "gif" :
				$image = ImageCreateFromGIF($img_tmp_name); 
				$thumb_image = ImageCreateTrueColor($width,$height); 
				imagecopyresampled($thumb_image,$image,0,0,0,0,$width+1,$height+1,ImageSX($image),ImageSY($image)); 
				$thumbneil = $save_path."/".$save_img_name;
				ImageJPEG($thumb_image,$thumbneil,100); 
				break;
				
			case "png" :
				$image = ImageCreateFromPNG($img_tmp_name); 
				$thumb_image = ImageCreateTrueColor($width,$height); 
				imagecopyresampled($thumb_image,$image,0,0,0,0,$width+1,$height+1,ImageSX($image),ImageSY($image)); 
				$thumbneil = $save_path."/".$save_img_name;
				ImageJPEG($thumb_image,$thumbneil,100); 
				break;
				
			default : break;
		}
					
		return true;
	}//fuction fileupload
	
	
	// 파일삭제
	function del_file($del_file_name, $del_path)
	{
			if($del_file_name )
			@unlink("${del_path}/${del_file_name}");
	}
	
	function escape_ereg($str) 
	{ 
		$str=eregi_replace("(\*|\(|\)|\+|\?|\\|\/|\"|\'|\[|\]|\^|\+)","",$str); 
		return $str; 
	}
	
	// 수정, 답변시 textField 내의 특수문자 처리 (', " 등)
	function replace_quot($str)
	{
		$str=stripslashes(eregi_replace("\"","&quot;", $str));
		
		return $str;
	}
	
	// 제목의 HTML 방지, 역슬래쉬 제거(제목, 문자열 길이제한)
	function replace_string($str, $len="", $tail="")
	{
		$str=htmlspecialchars($str);
		
		if($len)
			$str= $this->strcut_utf8($str, $len, $tail);
						
		return $str;				
	}
	
	/*
	###############################################
			 ::: 브라우저 체크함수 :::          
			사용방법 : ckBrowser();  
		ex) $browser = ckBrowser();
	###############################################
	*/
	
	function ckBrowser() {
		
		if(!$agent=getenv("HTTP_USER_AGENT")) return 'unknown';
			
		if(eregi( 'MSIE', $agent)) { 
			preg_match("/MSIE ([0-9][.][0-9]{0,2})/i",$agent,$match);
			return "MS-Explorer {$match[1]}"; 
		} 
		if(eregi( 'Netscape', $agent)) {
			$temp=substr($agent,strrpos($agent,'Netscape'));
			$temp = preg_replace("/[^0-9+.]/","",$temp);
			return "Netscape {$temp}"; 
		} 	
		if(eregi( 'Opera', $agent)) { 
			$temp=substr($agent,strrpos($agent,'Opera'));
			$temp = preg_replace("/[^0-9+.]/","",$temp);
			return "Opera {$temp}"; 
		}
		if(eregi( 'Firefox', $agent)) { 
			$temp=substr($agent,strrpos($agent,'Firefox'));
			$temp = preg_replace("/[^0-9+.]/","",$temp);
			return "Firefox {$temp}"; 
		}
		if(eregi( 'Mozilla', $agent)) { 
			if(eregi('rv',$agent)){
				preg_match_all("/rv:(.*)\)/i",$agent,$match,PREG_SET_ORDER);
				return "Mozilla {$match[0][1]}"; 
			}
		}
		
		if (eregi('Chrome', $agent)) return "Chrome";
		if (eregi('Safari', $agent)) return "Safari";
		if (eregi('Lynx', $agent)) return "Lynx";
		if (eregi('LibWWW', $agent)) return "LibWWW";
		if (eregi('Konqueror', $agent)) return "Konqueror";
		if (eregi('Internet Ninja', $agent)) return "Internet Ninja";
		if (eregi('Download Ninja', $agent)) return "Download Ninja";
		if (eregi('WebCapture', $agent)) return "WebCapture";
		if (eregi('LTH', $agent)) return "LTH";
		if (eregi('Gecko', $agent)) return "Gecko";
		if (eregi('wget', $agent)) return "Wget command";
	
		if (eregi('PSP', $agent)) return "PlayStation Portable";
		if (eregi('Symbian', $agent)) return "Symbian PDA";
		if (eregi('Nokia', $agent)) return "Nokia PDA";
		if (eregi('LGT', $agent)) return "LG Mobile";
		if (eregi('mobile', $agent)) return "ETC Mobile";
	
		return 'unknown';
	}
	
	

	/*
	###############################################
			 ::: OS 체크함수 :::          
			사용방법 : ckOs();  
		ex) $os = ckOs();
	###############################################
	*/
	
	function ckOs() {
				
		if(!$agent=getenv("HTTP_USER_AGENT")) return 'unknown';
		
			if (eregi('win95', $agent) || eregi('windows 95', $agent)) return "Windows 95";
			if (eregi('Windows 9x', $agent) || eregi('Win 9x 4.90', $agent) || eregi('Windows Me', $agent)) return "Windows ME";
		if (eregi('Win98', $agent) || eregi( 'Windows 98', $agent)) return "Windows 98";	
		if (eregi('Windows NT 5.1', $agent) || eregi('Windows XP', $agent)) return "Windows XP";	
		if (eregi('Windows NT 5.0', $agent) || eregi('Windows 2000', $agent)) return "Windows 2000";    
			if (eregi('windows NT 5.2', $agent) || eregi('Windows 2003', $agent)) return "Windows 2003";			
		if (eregi('Windows NT 6.1', $agent) || eregi('Windows XP', $agent)) return "Windows 7";
		if (eregi('windows NT 6', $agent)) return "Windows Vista";			
		if (eregi('Winnt', $agent) || eregi('Windows NT', $agent)) return "Windows NT";
		if (eregi('windows', $agent)) return "ETC Windows";
			if (eregi('Mac', $agent )) {
			if(eregi('PowerPC' , $agent)) return "Mac PowerPC";
			if(eregi('Macintosh' , $agent)) return "Mac Macintosh";
			if(eregi('PowerPC' , $agent)) return "Mac OS X";
			return "ETC Mac";
		}	
		if (eregi('Os2', $agent)) return "OS2";
		if (eregi('Linux', $agent) || eregi('Wget', $agent)) return "Linux";
		if (eregi('Unix', $agent)) return "Unix";
		if (eregi('Freebsd', $agent)) return "Freebsd";
	
		if (eregi('PSP', $agent)) return "PlayStation Portable";
		if (eregi('Symbian', $agent)) return "Symbian PDA";
		if (eregi('Nokia', $agent)) return "Nokia PDA";
		if (eregi('LGT', $agent)) return "LG Mobile";
		if (eregi('mobile', $agent)) return "ETC Mobile";
	
		if (eregi('Googlebot', $agent)) return "GoogleBot";
		if (eregi('OmniExplorer', $agent)) return "OmniExplorerBot";
		if (eregi('MJ12bot', $agent)) return "majestic12Bot";
		if (eregi('ia_archiver', $agent)) return "Alexa(IA Archiver)";
		if (eregi('Yandex', $agent)) return "Yandex bot";
		if (eregi('Inktomi', $agent)) return "Inktomi Slurp";
		if (eregi('Giga', $agent)) return "GigaBot";
		if (eregi('Jeeves', $agent)) return "Jeeves bot";
		if (eregi('Planetwide', $agent)) return "IBM Planetwide bot";
		if (eregi('bot', $agent) || eregi('Crawler', $agent) || eregi('library', $agent)) return "ETC Robot";
		
		return 'unknown';
	}
	
	/*
	###############################################
			 ::: OS 체크함수 :::          
			사용방법 : ckBot();  
		ex) $bot = ckBot();
	###############################################
	*/
	
	function ckBot() {
				
		if(!$agent=getenv("HTTP_USER_AGENT")) return 'unknown';
		
		if (eregi('Googlebot', $agent)) return "GoogleBot";
		if (eregi('OmniExplorer', $agent)) return "OmniExplorerBot";
		if (eregi('MJ12bot', $agent)) return "majestic12Bot";
		if (eregi('ia_archiver', $agent)) return "Alexa(IA Archiver)";
		if (eregi('Yandex', $agent)) return "Yandex bot";
		if (eregi('Inktomi', $agent)) return "Inktomi Slurp";
		if (eregi('Giga', $agent)) return "GigaBot";
		if (eregi('Jeeves', $agent)) return "Jeeves bot";
		if (eregi('cowbot', $agent)) return "NaverBot";
		if (eregi('MSNBot', $agent)) return "MSNBot";
		if (eregi('Slurp', $agent)) return "Yahoo! Slurp";
		if (eregi('EMPAS.ROBOT', $agent)) return "EMPAS.ROBOT";
		if (eregi('Daumoa', $agent)) return "DAUMOA";
		if (eregi('Twiceler', $agent)) return "Twiceler";
		if (eregi('Yeti', $agent)) return "Yeti";
		if (eregi('bot', $agent) || eregi('Crawler', $agent) || eregi('library', $agent)) return "Etc";
		
		return 'unknown';
	}
	
	function ckEngine() {
				
		if(!$agent=getenv("HTTP_USER_AGENT")) return 'unknown';
		
		if (eregi('naver', $agent)) return "naver";
		if (eregi('google', $agent)) return "google";
		if (eregi('paran', $agent)) return "paran";
		if (eregi('nate', $agent)) return "nate";
		if (eregi('daum', $agent)) return "daum";
		if (eregi('bing', $agent)) return "bing";
		if (eregi('yahoo', $agent)) return "yahoo";
		if (eregi('msn', $agent)) return "msn";
		if (eregi('simmani', $agent)) return "simmani";
		if (eregi('altavista', $agent)) return "altavista";		
		return 'unknown';
	}
	
	
	//소켓통신 포스트
	function post_request($url, $data='', $referer='') 
	{ 	
		$host_info = explode("/", $url);
    $host = $host_info[2];
    $path = $host_info[3]."/".$host_info[4];
		
		srand((double)microtime()*1000000);
		$boundary = "---------------------".substr(md5(rand(0,32000)),0,10);

		// 헤더 생성
		$header = "POST /".$path ." HTTP/1.0\r\n";
		$header .= "Host: ".$host."\r\n";
		$header .= "Content-type: multipart/form-data, boundary=".$boundary."\r\n";

		// 본문 생성
		foreach($data AS $index => $value){
				$send_data .="--$boundary\r\n";
				$send_data .= "Content-Disposition: form-data; name=\"".$index."\"\r\n";
				$send_data .= "\r\n".$value."\r\n";
				$send_data .="--$boundary\r\n";
		}
		$header .= "Content-length: " . strlen($send_data) . "\r\n\r\n";
		
		$fp = fsockopen($host, 80);

    if ($fp) { 
        fputs($fp, $header.$send_data);
        $rsp = '';
        while(!feof($fp)) { 
            $rsp .= fgets($fp,8192); 
        }
        fclose($fp);
        $result = explode("\r\n\r\n",trim($rsp));
				
				$header = ''; 
				if(isset($result[0])) { 
					$header = $result[0]; 
				} 
				$content = ''; 
				if(isset($result[1])) { 
					$content = $result[1]; 
				} 
				
				// return as structured array: 
				return array( 
					'status' => 'ok', 
					'header' => $header, 
					'content' => $content 
				);  
    }
    else {
        return array( 
					'status' => 'err', 
					'error' => "$errstr ($errno)" 
				); 
    }
	}
	
	//숫자를 요일로 변경
	function DayWeekChange($date)
	{
		$num = strftime("%w",strtotime(str_replace('-','',$date))); // 0:일 1:월	
		$M = "";
		switch ($num) 
		{ 
		  case ("0") : $M = "일"; break; 
		  case ("1") : $M = "월"; break; 
		  case ("2") : $M = "화"; break; 
		  case ("3") : $M = "수"; break; 
		  case ("4") : $M = "목"; break; 
		  case ("5") : $M = "금"; break; 
		  case ("6") : $M = "토"; break; 
		  default : 
		} 
		
		return $M;
	}	
	
	
	//숫자를 요일로 변경
	function MonthChangeEng($Month)
	{		
		$M = "";
		switch ($Month) 
		{ 
		  case ("01") : $M = "January"; break; 
		  case ("02") : $M = "Febrary"; break; 
		  case ("03") : $M = "March"; break; 
		  case ("04") : $M = "April"; break; 
		  case ("05") : $M = "May"; break; 
		  case ("06") : $M = "June"; break; 
		  case ("07") : $M = "July"; break; 
			case ("08") : $M = "August"; break; 
			case ("09") : $M = "September"; break; 
			case ("10") : $M = "October"; break; 
			case ("11") : $M = "November"; break; 
			case ("12") : $M = "December"; break; 
		  default : 
		} 
		
		return strtoupper($M);
	}	
	
	//특정 주차 시작일 구하기
	function week_start_date($inputYear, $inputMonth, $inputDay) {
			$tmp = date("D", mktime (0,0,0,date($inputMonth), date($inputDay), date($inputYear))); 
			
			$arr_tmp = '';
			Switch ($tmp) { 
				case "Sun" : 
					$startDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay), date($inputYear))); 
					$endDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)+6, date($inputYear))); 
				break; 
				case "Mon" : 
					$startDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)-1, date($inputYear))); 
					$endDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)+5, date($inputYear))); 
				break; 
				case "Tue" : 
					$startDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)-2, date($inputYear))); 
					$endDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)+4, date($inputYear))); 
				break; 
				case "Wed" : 
					$startDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)-3, date($inputYear))); 
					$endDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)+3, date($inputYear))); 
				break; 
				case "Thu" : 
					$startDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)-4, date($inputYear))); 
					$endDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)+2, date($inputYear))); 
				break; 
				case "Fri" : 
					$startDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)-5, date($inputYear))); 
					$endDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)+1, date($inputYear))); 
				break; 
				case "Sat" : 
					$startDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay)-6, date($inputYear))); 
					$endDate = date("Y-m-d", mktime (0,0,0,date($inputMonth), date($inputDay), date($inputYear))); 
				break; 
			} 
			$arr_tmp['s_date'] = $startDate;
			$arr_tmp['e_date'] = $endDate;
			return $arr_tmp;
	}
	
	//분 더하기
	function plus_minute($time,$minute) 
	{ 
		sscanf($time,'%2d:%2d',$hh,$mm); 
		$mm+= $minute; 
		if ( $mm>=60 ) { 
			$hh= $hh+1<24 ? $hh+1 : 0; 
			$mm-=60; 
		} 
		return sprintf('%02d:%02d',$hh,$mm); 
	} 
	
	
	//루프 카운터 구하기 
	// 시작시간, 끝나는시간, 타입(M:분 H:시간)
	// 08:00 , 18:00 , M
	function loop_cnt($s_time, $e_time, $type) {
		
		sscanf($s_time,'%2d:%2d',$s_h,$s_m); 
		sscanf($e_time,'%2d:%2d',$e_h,$e_m); 
		
		if($type == "60") {
			$h_cnt = $e_h - $s_h;
			$m_cnt = ($e_m - $s_m) / 30;
		}else if($type == "30") {
			$h_cnt = ($e_h - $s_h) * 2;			
			$m_cnt = ($e_m - $s_m) / 30;
		}else{
			$h_cnt = ($e_h - $s_h) * 4;			
			$m_cnt = ($e_m - $s_m) / 15;
		}
		
		$tot_cnt = $h_cnt + $m_cnt;
		return $tot_cnt;
	}
	
	//사양변경 신청시 이용기간중 남은 일수 구하기
	//시작년월일, 마감년월일
	function request_day($start_date, $end_date)
	{
		$now_time = strtotime("$start_date");
		$end_time = strtotime("$end_date");
		$tot_day=floor(($end_time - $now_time)/86400);
	
		return $tot_day;
	}
	
	// 시작일부터 몇일후 구하기
	// ( 시작년월일 , 계약 일수 )
	function request_term_day($start_date, $add_day)
	{
		$date = date("Y-m-d", strtotime($start_date."+". $add_day." days"));
		return $date;
	}
	
	//주차 구하기
	function toWeekNum($date) { 
		$timestamp = strtotime($date);
    $w = date('w', mktime(0,0,0, date('n',$timestamp), 1, date('Y',$timestamp))); 
    return ceil(($w + date('j',$timestamp) -1) / 7); 
	} 
	
	
	function xss_clean($data) {
		// Fix &entity\n;
		$data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
		$data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
		$data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
		$data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

		// Remove any attribute starting with "on" or xmlns
		$data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

		// Remove javascript: and vbscript: protocols
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
		$data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

		// Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
		$data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

		// Remove namespaced elements (we do not need them)
		$data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

		do
		{
				// Remove really unwanted tags
				$old_data = $data;
				$data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
		}
		while ($old_data !== $data);

		// we are done...
		return $data;
	}
	
	//테이블이 존재하는지 여부
	function TableExists($tablename, $db) {	
		
		// Get a list of tables contained within the database.
		$result = mysql_list_tables($db);
		$rcount = mysql_num_rows($result);
	
		// Check each in list for a match.
		for ($i=0;$i<$rcount;$i++) {
			if (mysql_tablename($result, $i)==$tablename) return true;
		}
		return false;		
	}
	
	// 페이지별 카운터 증가  (호스팅 종류 및 신청페이지 별 카운터 체크용 함수)
	function fnPageCounterUp($user_ip, $sess_id, $request_uri, $referer_url, $page_type)
	{
		$user_ip = substr($user_ip,0,11);		
		
		//if($user_ip != "112.169.102")
		//{				
			//동일한 아이피는 한번만 체크
			$sql="SELECT COUNT(*) FROM tblPageStatus WHERE tps_ip='$_SERVER[REMOTE_ADDR]' AND tps_type='$page_type' AND DATE_FORMAT(tps_dateitme, '%Y-%m-%d')=CURDATE()";
			$result = mysql_query($sql);
			$row=mysql_fetch_array($result);
			
			//로그인 했을경우 회원번호 입력용 변수
			if($_SESSION['suser_idx'])
				$cd_mb_idx=$_SESSION['suser_idx'];
			else
				$cd_mb_idx=0;		
			
			if($row[0]<=0)
			{	
				$sql="
					INSERT INTO 
						tblPageStatus 
					SET 
						tps_ip='$_SERVER[REMOTE_ADDR]', 
						mb_code=$cd_mb_idx, 	
						tps_sess='$sess_id', 
						tps_url='$request_uri', 
						tps_type='$page_type', 
						tps_refer='$referer_url', 
						tps_dateitme=NOW()
				";
				mysql_query($sql);
			}
		//} //end_of_if(eregi("^218.234.217", $user_ip))
	}
	
	

	function datetimediff($rtime, $ctime = null, $option = null){
		if ($ctime) $cur_time = strtotime($ctime);
		else $cur_time = time();
		$ref_time = strtotime($rtime);
	
		$cur_date = floor($cur_time / 86400);
		$ref_date = floor($ref_time / 86400);
	
		$datetimediff = $cur_time - $ref_time;
		$datedist = $cur_date - $ref_date;
		$datediff = floor($datetimediff / 86400);
		$weekdiff = floor($datediff / 7);
		$timediff = $datetimediff % 86400;
	
		$hour = floor($timediff / 3600);
		$min = floor($timediff % 3600 / 60);
		$sec = floor($timediff % 3600 % 60);
	
		$result = "";
		/*
		if ($datedist>34) {
					$result = date("Y년 n월 j일", $ref_time);
		} else if ($weekdiff>0) {
					$result = $weekdiff . "주 전";
		} else {
					if ($datediff>0) {
								$result = $datedist . "일 전";
					} else if ($timediff<=0) {
								$result = "1초 전";
					} else {
								if ($hour) $result = $hour . "시간";
								else if ($min) $result = $min . "분";
								else $result = $sec . "초";
								if ($result) $result .= " 전";
					}
		}
		*/
		if ($datedist>34) {
					$result = date("Y년 n월 j일", $ref_time);
		} else if ($weekdiff>0) {
					$result = $weekdiff . "주 전";
		} else {
					if ($datediff>0) {
								$result = $datedist . "일 전";
					} else if ($timediff<=0) {
								$result = "1초 전";
					} else {
								if ($hour) $result = $hour . "시간";
								else if ($min) $result = $min . "";
								else $result = $sec . "초";
								//if ($result) $result .= " 전";
								if ($result) $result .= "";
					}
		}
		
		if ($option=='ALL') {
					$result = "";
					//if ($datediff) $result .= ($result?" ":"") . $datediff."일";
					//if ($hour) $result .= ($result?" ":"") . $hour."시간";
					//if ($min) $result .= ($result?" ":"") . $min ."분";
					//if ($sec) $result .= ($result?" ":"") . $sec . "초";
					//$result .= " 전";
					if ($datediff) $result .= ($result?" ":"") . $datediff."";
					if ($hour) $result .= ($result?" ":"") . $hour."";
					if ($min) $result .= ($result?" ":"") . $min ."";
					if ($sec) $result .= ($result?" ":"") . $sec . "";
					$result .= "";
		}
		return $result;
	}
	

}
?>