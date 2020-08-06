<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function videoThumbnail($source) {
	$hosts = array('youtube.com');	
	preg_match('/(http(s)?:\/\/(www\.)?(youtube.com\/embed\/[0-z]+))+/i', $source, $host);
	if($host) {
		if(preg_match('/'.$hosts[0].'/i', addslashes($host[0]))) {
			$url = explode('/', $host[0]);
			$img = 'http://img.youtube.com/vi/'.$url[count($url)-1].'/2.jpg';
			return $img;
		}
	}
}

function video($src, $id='video', $width='', $height='', $autoplay=FALSE, $poster=array()) {
	if(!is_array($src)) {
		$src = array($src);
	}
	if($poster && !is_array($poster)) {
		$poster = array($poster);
	}
	
	$wh = ($width && $height) ? "width='$width' height='$height'" : '';
	$autoplay = $autoplay ? "autoplay='autoplay'" : "";
		
	$play_list = array();
	$src = !is_array($src) ? array($src) : $src;
	foreach($src AS $key => $val) {
		$po = isset($poster[$key]) ? $poster[$key] : '';
		$play_list[] = "{
					0:{src:'$val', type: 'video/mp4'},
		            config:{poster:'$po'}
				}";
	}

	$html = "<video id='$id' class='projekktor' $wh controls='controls' preload='metadata' $autoplay></video>
	<script type='text/javascript'>
	$(document).ready(function() {
		projekktor('#$id', {
			playerFlashMP4: '/src/js/projekktor/jarisplayer.swf',
			playlist: [ ". implode(',', $play_list) ."]
		});
	});
	</script>";
	
	return $html;
}
?>
