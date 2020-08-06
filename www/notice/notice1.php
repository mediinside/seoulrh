<?PHP
	include_once $_SERVER['DOCUMENT_ROOT'] . '/_init.php';
	include_once $GP -> INC_WWW . '/head.php';
	$locArr = array(5,5);

    $index_page = "notice1.php";
	$query_page = "query.php";

	if(!$jb_code) {
  		$jb_code="10";
	}
?>
</head>
<body id="main" onLoad="init()">
	<div id="wrap">
	<?php include_once $GP -> INC_WWW . '/header_sub.php'; ?>
		<div id="container" tabindex="0">
			<!-- main section 01 start -->
			<section class="contents">			
				<?php include $GP -> INC_PATH ."/board_inc.php"; ?>
			</div>
		</section>
				
		<?php include_once $GP -> INC_WWW . '/bottomBnnr.php';?>
		<?php include_once $GP -> INC_WWW . '/sitemap.php';?>
		</div>
		
		<?php include_once $GP -> INC_WWW . '/footer.php';?>
	</div>
</body>
</html>

			

