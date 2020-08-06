<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . "/www/_init.php";

	//기본 리다이렉트
	//echo $_REQUEST["htImageInfo"];
    $foldername =  $_REQUEST["floderId"];
    echo "echo:" . $GP -> UP_IMG_SMARTEDITOR . "<br>" ;
    exit;

// default redirection
$url = $_REQUEST["callback"].'?callback_func='.$_REQUEST["callback_func"];
$bSuccessUpload = is_uploaded_file($_FILES['Filedata']['tmp_name']);

// SUCCESSFUL
if(bSuccessUpload) {
	$tmp_name = $_FILES['Filedata']['tmp_name'];
	$name = $_FILES['Filedata']['name'];
	
	$filename_ext = strtolower(array_pop(explode('.',$name)));
	$allow_file = array("jpg", "png", "bmp", "gif");
	
	if(!in_array($filename_ext, $allow_file)) {
		$url .= '&errstr='.$name;
	} else {
		$uploadDir = $GP -> UP_IMG_SMARTEDITOR . $foldername . "/";
		$uploadURL = $GP -> UP_IMG_SMARTEDITOR_URL . $foldername . "/";

		if(!is_dir($uploadDir)){
			@mkdir($uploadDir, 0777);
		}

		//파일명 새로 지정
		$file_name_md5=md5($name);
		$rand_key=rand_make();
		$file_name_md5=substr($file_name_md5, 5, 10);
		$file_name_md5=$file_name_md5 . $rand_key;
		$new_file_name=$file_name_md5 . "." . $filename_ext;
		
		$newPath = $uploadDir.urlencode($new_file_name);
		
		@move_uploaded_file($tmp_name, $newPath);
		
		$url .= "&bNewLine=true";
		$url .= "&sFileName=".urlencode(urlencode($new_file_name));
		$url .= "&sFileURL=". $uploadURL . urlencode(urlencode($new_file_name));
	}
}
// FAILED
else {
	$url .= '&errstr=error';
}

//난수생성함수
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
	
header('Location: '. $url);
?>