<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	<? 
	// 절대경로 알아내기 
	global $PHP_SELF; 

	$thisfilename=basename(__FILE__); 
	$temp_filename=realpath(__FILE__); 
	if(!$temp_filename) $temp_filename=__FILE__; 
	$osdir=eregi_replace($thisfilename,"",$temp_filename); 
	unset($temp_filename); 

	$virdir = eregi_replace($thisfilename,"",$PHP_SELF); 


	echo "현재 디렉토리의 절대경로 : ".$osdir."<br>"; 
	echo "현재 디렉토리의 상대 경로 주소 : ".$virdir."<br>"; 

	?>
</body>
</html>