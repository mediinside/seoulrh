<?
class FileDownload {

	public function fDown($file,$name,$downview,$speed,$limit)// 경로, 원파일명, 다운 0/보임 1, 다운속도, 속도제한여부
	{
			//echo $downview;
		   //do something on download abort/finish
	    //register_shutdown_function( 'function_name'  );
	    if(!file_exists($file))
	        die('File not exist!');
	    $size = filesize($file);
	    $name = rawurldecode($name);

			//2013-02-28 파일다운시 한글 깨짐으로 인한 iconv 추가
			$name = iconv("utf-8", "euc-kr", $name);

	    if (ereg('Opera(/| )([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
	        $UserBrowser = "Opera";
	    elseif (ereg('MSIE ([0-9].[0-9]{1,2})', $_SERVER['HTTP_USER_AGENT']))
	        $UserBrowser = "IE";
	    else
	        $UserBrowser = '';

	    $downview = ($downview) ? "attachment" : "inline";
	

	    /// important for download im most browser
	    $mime_type = ($UserBrowser == 'IE' || $UserBrowser == 'Opera')? 'application/octetstream' : 'application/octet-stream';
	    @ob_end_clean(); /// decrease cpu usage extreme
	    Header('Content-Type: ' . $mime_type);
	    Header('Content-Disposition: '.$downview.'; filename="'.$name.'"');
	    Header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
	    Header('Accept-Ranges: bytes');
	    Header("Cache-control: private");
	    Header('Pragma: private');

	    /////  multipart-download and resume-download
	    if(isset($_SERVER['HTTP_RANGE']))
	    {
	        list($a, $range) = explode("=",$_SERVER['HTTP_RANGE']);
	        str_replace($range, "-", $range);
	        $size2 = $size-1;
	        $new_length = $size-$range;
	        Header("HTTP/1.1 206 Partial Content");
	        Header("Content-Length: $new_length");
	        Header("Content-Range: bytes $range$size2/$size");
	    } else {
	        $size2=$size-1;
	        Header("Content-Length: ".$size);
	    }

			$chunksize = 1*(1024*$speed);
	    $this->bytes_send = 0;
	    if ($file = fopen($file, 'rb'))
	    {
				
	        if(isset($_SERVER['HTTP_RANGE']))
	            fseek($file, $range);
	        while(!feof($file) and (connection_status()==0))
	        {
	            $buffer = fread($file, $chunksize);
	            print($buffer);//echo($buffer); // is also possible
	            flush();
	            $this->bytes_send += strlen($buffer);
	            if($limit) sleep(1); // 다운로드 속도제한
	        }
	        fclose($file);
	    } else
	        die('Error can not open file!!');
	    if(isset($new_length))
	        $size = $new_length;
	    die();
	    Header("Connection: close");
	}

}
?>
