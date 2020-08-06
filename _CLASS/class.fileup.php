<?
/******************************************************************************************
                                                        Sample Source
/******************************************************************************************
$get_name = 'upfile';
if($HTTP_POST_FILES[$get_name]['name'])
{
	include_once $GL_class_path . 'FileUpload_single.lib';
	$args['forder'] 				= $GL_home_path . "_/updata/";
	$args['files'] 					= $_FILES[$get_name];
	$args['max_file_size'] 	= 1024 * 25;// 25kb 이하
	$args['max_x'] 					= 0;
	$args['max_y'] 					= 0;
	$args['thumb'] 					= 1;
	$args['thumb_w'] 					= 0;
	$args['thumb_h'] 					= 0;
	$args['able_file'] 			=  array("jpg");

	$folder_web	= '/updata/mms_image/';

	$FileUpload = new FileUpload_single($args);
	$updata = $FileUpload -> fileUpload();
	if ($updata['error']) {
		echo '<script>alert("' . $updata['error'] . '")</script>';
		exit;
	}

	$updata['new_file_name'];
}
*/

class Fileup {

  private $debug_status = "N";                // 내부 디버그 하기
  private $upload_file;
  private $upload_forder;
  private $upload_max_size;
  private $upload_maxX , $upload_maxY;
  private $upload_thumb_w , $upload_thumb_h;
  private $upload_image_w , $upload_image_h;
  private $upload_able_file;
  private $upload_not_able_file;
  private $upload_thumb, $upload_thumb_type;
  private static $rowCount = 0;
  private static $returnArr = array();

  function __construct($args)
  {
  	  extract($args);
	    $this->upload_file 					= $files;
	    $this->upload_forder 				= $forder;				//업로드폴더
	    $this->upload_forder_some 	= $forder_some;		//썸네일폴더
	    $this->upload_max_size 			= $max_file_size;		//맥스파일사이즈
	    $this->upload_maxX 					= $max_x;				//이미지크기 width 제한
	    $this->upload_maxY 					= $max_y;				//이미지크기 height 제한
	    $this->upload_able_file 			= $able_file;			//확장자 제한
	    $this->upload_not_able_file 		= $not_able_file;		//
	    $this->upload_thumb 				= $thumb;				//썸네일 만들지 여부
	    $this->upload_thumb_w 				= $thumb_w;			//썸네일 이미지 width
	    $this->upload_thumb_h 				= $thumb_h;			//썸네일 이미지 height
	    $this->upload_thumb_w2 				= $thumb_w2;
	    $this->upload_thumb_h2 				= $thumb_h2;
	    $this->upload_thumb_type 			= $thumb_type;		//썸네일 타입 out
	    $this->upload_image_w 				= $image_w;			//업로드 이미지 수정될 width
	    $this->upload_image_h				= $image_h;			//업로드 이미지 수정될 height
	    $this->upload_real_image_del		= $real_image_del;
  }

	public function fileUpload()
	{
		$rst['error'] = $this -> _checkException ();

		if (!$rst['error']) {
		  $upload_file = $this->upload_file;
		  $fileName 			= $upload_file['name'];                // 실제 파일명
		  $fileTmpName 			= $upload_file['tmp_name'];         		// tmp 파일명
		  $fileSize 			= $upload_file['size'];                // 파일 사이즈
		  $fileError 			= $upload_file['error'];              	// 파일 에러코드
		  //print_r($upload_file); exit;

		  if (!$fileError) {
		    list($usec, $sec) = explode(" ",microtime());
		    $uploadFilename = (round(((float)$usec + (float)$sec))).rand(1,10000);		//  업로드 파일명 날짜에 따라 변환
		    $expFilename = explode(".", $fileName);										//  . 을 기준으로 확장명 분할
		    $extension = strtolower($expFilename[sizeof($expFilename)-1]);				//  확장명 소문자화
		    $new_file_name = $uploadFilename.".".$extension;							//  새로운 파일명 생성
		    $new_file_name_thumb = $uploadFilename."_s.".$extension;					//  새로운 파일명 생성
		    $new_file_name_thumb2 = $uploadFilename."_s2.".$extension;					//  새로운 파일명 생성
		    $dest = $this->upload_forder . $new_file_name;								//  파일 저장경로
/*
		    echo $fileTmpName;
		    echo 'dest=>' . $dest; exit;*/
		    // TMP 이미지 복사
				if(@move_uploaded_file($fileTmpName,$dest)) {
					$rst['file_name'] 			= $fileName;
					$rst['new_file_name'] 		= $new_file_name;
					$image_size				= getimagesize($dest);
					$tmp_img_x 				= $image_size[0];
					$tmp_img_y 				= $image_size[1];

					// 썸네일 만들기
					if ($this->upload_thumb) {

						// 원본 이미지를 삭제 하고 큰 이미지를 변경시킴
						if ($this->upload_image_w) {

							if ($tmp_img_x > $this->upload_image_w || $tmp_img_y > $this->upload_image_h) {

								if ($this->upload_thumb_type == 'out') {
									$re_size_data2 = $this -> get_re_size2 ($tmp_img_x, $tmp_img_y, $this->upload_image_w, $this->upload_image_h);		
								} else {
									$re_size_data2 = $this -> get_re_size ($tmp_img_x, $tmp_img_y, $this->upload_image_w, $this->upload_image_h);									
								}

								$rst['image_x'] = $re_size_data2['re_w'];
								$rst['image_y'] = $re_size_data2['re_h'];

								$this -> put_gdimage($this->upload_forder, $new_file_name, $rst['image_x'], $rst['image_y'], $new_file_name);
							} else {
								$rst['image_x'] = $tmp_img_x;
								$rst['image_y'] = $tmp_img_y;
							}
						}

						// 작은 썸네일 만들기
						if ($this->upload_thumb_w && $this->upload_thumb_h) {
							$re_size_data = $this -> get_re_size2 ($tmp_img_x, $tmp_img_y, $this->upload_thumb_w, $this->upload_thumb_h);
							$rst['image_thumbx'] = $re_width 		= $re_size_data['re_w'];
							$rst['image_thumby'] = $re_height 	= $re_size_data['re_h'];
							$thum_rst = $this -> put_gdimage($this->upload_forder, $new_file_name, $re_width, $re_height, $new_file_name_thumb);
							
							if($this->upload_forder_some) {
								$new_file_name_thumb_move = $this->upload_forder_some."/".$new_file_name_thumb;								
								$new_file_name_thumb_original = $this->upload_forder."/".$new_file_name_thumb;								
								$this -> file_move($new_file_name_thumb_original, $new_file_name_thumb_move);
							}
						}

						// 작은 썸네일 만들기2
						if ($this->upload_thumb_w2 && $this->upload_thumb_h2) {
							$re_size_data = "";
							$re_size_data = $this -> get_re_size2 ($tmp_img_x, $tmp_img_y, $this->upload_thumb_w2, $this->upload_thumb_h2);
							$re_width = "";
							$re_height = "";
							$re_width 		= $re_size_data['re_w'];
							$re_height 		= $re_size_data['re_h'];
							$this -> put_gdimage($this->upload_forder, $new_file_name, $re_width, $re_height, $new_file_name_thumb2);
							
							if($this->upload_forder_some) {
								$new_file_name_thumb_move = $this->upload_forder_some."/".$new_file_name_thumb2;								
								$new_file_name_thumb_original = $this->upload_forder."/".$new_file_name_thumb2;								
								$this -> file_move($new_file_name_thumb_original, $new_file_name_thumb_move);
							}
						}

						if ($thum_rst == '1') {
							$rst['new_file_name_thum'] = $new_file_name_thumb;
						} else {
							$rst['error'] = $thum_rst;
						}
					} else {

						$rst['image_x'] = $tmp_img_x;
						$rst['image_y'] = $tmp_img_y;
					}

					if ($this->upload_real_image_del) {
						$rst['new_file_name'] 	= "";
						@unlink($dest);
					}
				} else {
					$rst['error'] = 'TMP 복사실패!!';
				}
			} else {
				$rst['error'] 	.= $this -> _errorChk($fileError);
			}
        }       

	  return $rst;
	}

	// 비율에 맞게 사이즈 변경 시키기 (틀 안에 맞추기)
	private function get_re_size ($width, $height, $thum_w, $thum_h) {
		if ($width > $height) {
			$rate1 = round(1 / round($height / $width , 3),2);
			$rate2 = round($thum_w / $width,2);
			$tmp_height = $height * $rate2;

			if ($tmp_height > $thum_h) {
				$tmp_width 	= $thum_h * $rate1;
				$tmp_height = $thum_h;
			} else {
				$tmp_width = $thum_w;
			}
		} else if ($width < $height) {
			$rate1 = round(1 / round($width / $height , 3),2);
			$rate2 = round($thum_h / $height,2);
			$tmp_width = $width * $rate2;

			if ($tmp_width > $thum_w) {
				$tmp_height = $thum_w * $rate1;
				$tmp_width 	= $thum_w;
			}	else {
				$tmp_height = $thum_h;
			}
		} else {
			if ($thum_w > $thum_h) {
				$tmp_height = $thum_h;
				$tmp_width 	= $thum_h;
			} else {
				$tmp_height = $thum_w;
				$tmp_width 	= $thum_w;
			}
		}

		$rst['re_w'] = $tmp_width;
		$rst['re_h'] = $tmp_height;

		return $rst;
	}


	// 이미지 비율대로 맞추기 (틀 밖에 맞추기)
	function get_re_size2  ($width, $height, $re_w, $re_h) {

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

		$rst['re_w'] = round($tmp_width);
		$rst['re_h'] = round($tmp_height);

		return $rst;
	}
	
			
	function file_move( $from, $to ){	 
			if ( copy ($from, $to) ){	 
					unlink($from);	 
					return TRUE;	 
			}else{	 
					return FALSE;	 
			}	 
	}


	// 정상생성여부 확인시 : 정상생성시 1을 리턴, 이외는 실패

	// gd 이미지 생성
	// $img_name = 생성에 사용될 원본 파일명, $width = 생성이미지 가로크기, $height = 생성이미지 세로크기, $save_name = 저장될 파일명
	private function put_gdimage($img_upload_dir, $img_name, $width, $height, $save_name){

		// GD 버젼체크
		$gd = gd_info();
		$gdver = substr(preg_replace("/[^0-9]/", "", $gd['GD Version']), 0, 1);
		if(!$gdver) return "GD 버젼체크 실패거나 GD 버젼이 1 미만입니다.";

		$srcname = $img_upload_dir.$img_name;
		$timg = getimagesize($srcname);
		//if($timg[2] != 1 && $timg[2] != 2 && $timg[2] != 3) return "확장자가 jp(e)g/png/gif 가 아닙니다.";

		// jpg, jpeg
		if($timg[2] == 2){

			$cfile = imagecreatefromjpeg($srcname);
			//echo $width . '/' . $height; exit;
			// gd 버전별로
			if($gdver == 2){
			$dest = imagecreatetruecolor($width, $height);
			imagecopyresampled($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}else{
			$dest = imagecreate($width, $height);
			imagecopyresized($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}
			imagejpeg($dest, $img_upload_dir.$save_name, 90);

		// png
		}else if($timg[2] == 3){

			$cfile = imagecreatefrompng($srcname);
			if($gdver == 2){
			$dest = imagecreatetruecolor($width, $height);
			imagecopyresampled($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}else{
			$dest = imagecreate($width, $height);
			imagecopyresized($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}
			imagepng($dest, $img_upload_dir.$save_name, 90);

		// gif
		}else if($timg[2] == 1){

			$cfile = imagecreatefromgif($srcname);
			if($gdver == 2){
			$dest = imagecreatetruecolor($width, $height);
			imagecopyresampled($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}else{
			$dest = imagecreate($width, $height);
			imagecopyresized($dest, $cfile, 0, 0, 0, 0, $width, $height, $timg[0], $timg[1]);
			}
			imagegif($dest, $img_upload_dir.$save_name, 90);
		}
		// 메모리에 있는 그림 삭제
		imagedestroy($dest);
		return 1;
	}

	private function _checkException () {

		$file = $this->upload_file;
	    $image_size = getimagesize($file['tmp_name']);

	    if ($this->upload_max_size && $file['size'] > $this->upload_max_size) {
	    	$rst = '업로드 용량을 초과 하였습니다. ' . ($this->upload_max_size / 1024) .'Kb 이하로 올려주세요.\\n';
	    }
	    if ($this->upload_maxX && $image_size[0] > $this->upload_maxX){
	    	$rst .= '가로의 크기가 초과 하였습니다. 가로사이즈 ' . $this->upload_maxX .'px 이하로 올려주세요.\\n';
	    }
	    if ($this->upload_maxY && $image_size[1] > $this->upload_maxY){
	    	$rst .= '세로의 크기가 초과 하였습니다. 세로사이즈 ' . $this->upload_maxY .'px 이하로 올려주세요.\\n';
	    }
		if(!is_dir($this->upload_forder) && @mkdir($this->upload_forder, 0755) == false) {
			$rst .= $this->upload_forder.'폴더가 생성되어 있지 않습니다.\\n';
		}
		if (is_array($this->upload_able_file) && !empty($this->upload_able_file)) {
			foreach ($this->upload_able_file as $k => $v) {
				$file_type = strtoupper($v);

				if ($file_type == 'JPG' || $file_type == 'JPEG') {
					$img_check[] = 'image/jpeg';
					$img_check[] = 'image/pjpeg';
				}

				if ($file_type == 'CSV' || $file_type == 'XLS' || $file_type == 'EXE') {
					$img_check[] = 'application/vnd.ms-excel';
					$img_check[] = 'application/octet-stream';
					$img_check[] = 'application/haansoftxls';
				}

				if ($file_type == 'GIF') {
					$img_check[] = 'image/gif';
				}
				
				if ($file_type == 'PNG') {
					$img_check[] = 'image/x-png';
					$img_check[] = 'image/png';
				}

				if ($file_type == 'AI') {
					$img_check[] = 'application/pdf';
				}

				if ($file_type == 'SWF') {
					$img_check[] = 'application/x-shockwave-flash';
				}

				if ($file_type == 'ZIP') {
					$img_check[] = 'application/x-zip-compressed';
				}

				if ($file_type == 'PPT') {
					$img_check[] = 'application/vnd.ms-powerpoint';
				}

				$upload_able_file_array[] = $file_type;
			}

			if (!in_array($file['type'],$img_check)) {
				$rst .= $file['type'] . " 업로드 할 수 없는 파일형식 입니다. \\n" . implode(', ',$upload_able_file_array) . ' 형식만 업로드 할 수 있습니다.\\n';
			}
		}

		if(is_array($this->upload_not_able_file) && !empty($this->upload_not_able_file)){
			$fileName 			= $file['name'];                // 실제 파일명
		    $expFilename 		= explode(".", $fileName);                                   //  . 을 기준으로 확장명 분할
		    $extension 			= strtoupper($expFilename[sizeof($expFilename)-1]);            //  확장명 소문자화

		    if(in_array($extension, $this->upload_not_able_file)){
		    	$rst .= '"' . $extension . '"은 업로드 할수 없는 파일형식입니다.\\n';
		    }
		}

     return $rst;
	}

	// 에러확인
	private function _errorChk($errorCode)
	{
		switch ($errorCode)
		{
		case UPLOAD_ERR_INI_SIZE :
			$rst = "업로드 제한용량(".ini_get('upload_max_filesize').")을 초과한 파일입니다.";
			break;
		case UPLOAD_ERR_FORM_SIZE :
			$rst = "업로드한 파일이 HTML 에서 정의되어진 파일 업로드 제한용량을 초과하였습니다.";
			break;
		case UPLOAD_ERR_PARTIAL :
			$rst = "파일이 일부분만 전송되었습니다. ";
			break;
		default :
			$rst = "카피되지 않았습니다. 퍼미션을 확인해 주세요";
			break;
		}

		return $rst;
	}


}
?>
