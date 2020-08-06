<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function resize_thumb($filename, $bid, $width='', $height='', $crop=FALSE) {
	if (!$filename || !$width)
        return FALSE;

    if ($crop) {
        $w = explode(',', $width);
        $h = explode(',', $height);
        $config['x_axis'] = ($w[1] / 2) - ($w[0] / 2);
        $config['y_axis'] = ($h[1] / 2) - ($h[0] / 2);
        $width  = $w[0];
        $height = $h[0];
    }

	$source_file = DATA_PATH.'/file/'.$bid.'/'.$filename;
    $thumb_path  = '/file/'.$bid.'/thumb/'.$width.'px_'.$filename;    
	$img_html    = "<img src='".DATA_DIR.$thumb_path."' alt='이미지'/>";
	
	if (file_exists(DATA_PATH.$thumb_path))
		return $img_html;
    
    $config['source_image']   = $source_file;
    $config['new_image']	  = DATA_PATH.$thumb_path;
    $config['create_thumb']   = TRUE;
    $config['thumb_marker']   = FALSE;
    $config['maintain_ratio'] = (!$height) ? TRUE : FALSE;
    $config['width'] 		  = $width;
    $config['height']		  = (!$height) ? $width : $height;
            
	$CI =& get_instance();
	$CI->load->library('image_lib');
    $CI->image_lib->initialize($config); 
    if (($crop && $CI->image_lib->crop()) || $CI->image_lib->resize())
    	return $img_html;
   	else
   		return '이미지 생성에 실패 하였습니다. (jpg,gif,jpeg,png 파일이 아닙니다.)';
}

function resize($matches, $re_width) {
	if (is_array($matches))
		$filename = $matches[0];
	
	$table = defined('BO_DB') ? BO_DB : 'shop';
	
	preg_match("/src=[\"'](.*\/)(.*\.(jpg|gif|jpeg|png))[\"']/i", $filename, $files);
	preg_match("/width=[\"']([0-9]*)[\"']/i", $filename, $width);
	preg_match("/onclick=[\"'](.*)[\"']/i", $filename, $onclick);
	preg_match("/style=[\"'](.*)[\"']/i", $filename, $style);
		
	if (!isset($files[0]))
		return FALSE;
	
	$CI =& get_instance();
	$CI->load->model('M_upload_files');
	
	$filePath = $CI->M_upload_files->get_dir($table);
	$fileDir = $filePath['data_url'].'/';
	
	if (preg_replace('/www\./i','',$files[1]) == preg_replace('/www\./i','',$fileDir)) {
		$size = @getimagesize($filePath['data_path'] .'/'. $files[2]);
		if (isset($size) && ((!$width && $size[0] > $re_width) || (isset($width[1]) && $width[1] > $re_width))) {
			$src		= "src='". $files[1].$files[2] ."'";
			$width		= "width='". $re_width ."'";
			$onclick	= "onclick='resize(this, ".$size[0].", ".$size[1].");". $onclick ."'";
			$style		= "style='cursor:pointer;". $style ."'";
			$html		= preg_replace('/'.addcslashes($files[0],'/').'/', "$src $onclick $style $width", $filename);
			
			return $html;
		}
	}
	
	return $filename;
}

function resize_content($content, $width='') {
	if(!$width) {
		$width = RESIZE_WIDTH;
	}
	
	return preg_replace_callback('/\<img[^\<\>]*\>/i', create_function(
             '$matches',
             'return resize($matches, "'. $width .'");'
        	), $content);
}
?>
