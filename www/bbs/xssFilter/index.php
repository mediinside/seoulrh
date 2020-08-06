<?php
	/* $input의 html 태그를 필터링해 출력하는 예제 */

	require_once 'HTML/Safe.php'; // xss filter을 include
    $input    = '<div id="yournedat_flash"><object data="http://youareanidiot.org/youare.swf" type="application/x-shockwave-flash" width="640" height="480"><param name="movie" value="http://youareanidiot.org/youare.swf" /><param name="width" value="640" /><param name="height" value="480" /><param name="allowscriptaccess" value="always" /><embed src="http://youareanidiot.org/youare.swf" type="application/x-shockwave-flash" width="640" height="480" allowscriptaccess="always">This browser cannot support Adobe Flash Plugin</embed></object></div><p>You are an idiot!!</p>';  // html 태그
	$safe = new HTML_Safe; // xss filter 객체 생성
	$safe->setAllowTags(array('object', 'embed')); // 플래시를 위해 object 및 embed 태그 설정
	$output = $safe->parse($input); // html 태그를 필터링 하여 $output에 대입
	//echo $output;
	
	echo "<br>";
	echo htmlspecialchars($output); // 필터링 된 html 태그를 브라우저에 직접 출력
