<!DOCTYPE html>
<html lang="ko">
<head>
<?
//Check Mobile
$mAgent = array("iPhone","iPod","Android","Blackberry",
"Opera Mini", "Windows ce", "Nokia", "sony" );
$chkMobile = "PC";
for($i=0; $i<sizeof($mAgent); $i++){
if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
    $chkMobile = "mobile";
    break;
    }
}

?>
	<meta charset="utf-8">
	<!-- <meta id="viewport2" name="viewport" content="width=1400"> -->
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<!-- <meta name="mobile-web-app-capable" content="yes"> -->
	<!-- <meta name="apple-mobile-web-app-capable" content="yes"> -->
	<!-- <meta name="format-detection" content="telephone=no"> -->
	<title>서울재활병원</title>
	<link rel="apple-touch-icon" sizes="57x57" href="/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
	<link rel="manifest" href="/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" href="/resource/css/swiper.min.css">
	<link rel="stylesheet" href="/resource/css/common.css">
	<link rel="stylesheet" href="/resource/css/layout.css">
    <script type="text/javascript" src="/resource/js/jquery-1.12.2.min.js"></script>
	<script type="text/javascript" src="/resource/js/jquery-ui.min.js"></script>
	<script type="text/javascript" src="/resource/js/swiper.min.js"></script>
    <?
        if($chkMobile == "PC"){
    ?>
            <!-- pc -->
            <link rel="stylesheet" href="/resource/css/style.css">
            <meta id="viewport" name="viewport" content="width=device-width">
            <script type="text/javascript" src="/resource/js/function.js"></script>
    <?
        }
        else{
    ?>
            <!-- mobile -->
            <meta id="viewport" name="viewport" content="width=620">
			<script type="text/javascript" src="/resource/js/function_m.js"></script>
			<link rel="stylesheet" href="/resource/css/mobile.css">
    <?
        }

    ?>
	<link rel="stylesheet" href="/resource/css/board.css">

	<!-- <script type="text/javascript" src="/js/jquery.datepicker.js"></script> -->
	<!--[if lt IE 9]>
		<script type="text/javascript" src="/resource/js/jquery.bxslider.min.js"></script>
		<script type="text/javascript" src="/resource/js/html5shiv.min.js"></script>
	<![endif]-->
	<script type="text/javascript" src="/resource/js/ui.js"></script>
    <script>
    function blog_share() {
        var url = encodeURI(encodeURIComponent(myform.url.value));
        var title = encodeURI(myform.title.value);
         window.open( 'https://share.naver.com/web/shareView.nhn?url=' + url + "&title=" + title );
         }
    function facebook_share(){
        var linkUrl = window.location.href;
        window.open( 'http://www.facebook.com/sharer.php?u=' + encodeURIComponent(linkUrl) );
        }

	</script>


