<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function fsk_open($host, $geturl, $parm='', $port=80, $retime=10)	{
	$method = ($parm) ? 'POST' : 'GET';

	$fp	= fsockopen	($host,	$port, $errno, $errstr,	$retime);
	if (!$fp)
		echo $errstr ."(".$errno.")<br/>\n";
	else {
		fputs ($fp, $method." ".$geturl." HTTP/1.0\r\n");
		fputs ($fp, "Host: ".$host."\r\n");
		fputs ($fp, "User-Agent: Mozilla/4.0\r\n");
		
		if ($method == 'POST') {
			fputs ($fp, "Content-Type: application/x-www-form-urlencoded\r\n");
			fputs ($fp, "Content-Length: ".strlen($parm)."\r\n");
			fputs ($fp, "Connection: close\r\n\r\n");
			if (strlen($parm) > 0)
				fputs ($fp, $parm."\r\n");
		}
		else
			fputs($fp, "Content-Type: text/html\r\n\r\n");

		// HEADER 제거
		while(trim(fgets($fp,128)) != '') {}

		$str = '';
		while (!feof($fp)) {
			$str .=	fgets($fp,128);
		}
		fclose($fp);
	}

	return $str;
}
?>
