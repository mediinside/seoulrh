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

function video($src, $id='video', $width='', $height='', $autoplay=FALSE) {
	$wh = ($width && $height) ? "width='$width' height='$height'" : '';
	$autoplay = $autoplay ? "autoplay='autoplay'" : "";
	
	$html = "<video id='$id' class='projekktor' src='$src' $wh controls='controls' preload='metadata' $autoplay></video>";
	
	$html .= "
		<script type='text/javascript'>
		$(document).ready(function() {
			projekktor('#$id', {
				playerFlashMP4: '/js/projekktor/jarisplayer.swf'
			});
		});
		</script>
	";
	
	return $html;
}
?>
