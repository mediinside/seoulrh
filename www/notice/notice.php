<?PHP
	include_once $_SERVER['DOCUMENT_ROOT'] . '/www/_init.php';
	include_once $GP -> INC_WWW . '/head.php';
	$locArr = array(5,5);

    $index_page = "notice.php";
	$query_page = "query.php";

    $jb_code = $_GET["jb_code"];
    
    if($jb_code == "10"){
        $dep1 = "6";
        $dep2 = "6-1";
        $dep3 = "6-1-1";
        $jb_mode = "";
    }
    elseif($jb_code == "20"){
        $dep1 = "6";
        $dep2 = "6-1";
        $dep3 = "6-1-2";
        $jb_mode = "";
    }
    elseif($jb_code == "30"){
        $dep1 = "6";
        $dep2 = "6-1";
        $dep3 = "6-1-3";
        $jb_mode = "";
    }
    elseif($jb_code == "40"){
        $dep1 = "6";
        $dep2 = "6-1";
        $dep3 = "6-1-4";
        $jb_mode = "";
    }
    elseif ($jb_code == "50"){
        $dep1 = "6";
        $dep2 = "6-1";
        $dep3 = "6-1-5";
        $jb_mode = "twrite";
    }
    elseif($jb_code == "60"){
        $dep1 = "6";
        $dep2 = "6-1";
        $dep3 = "6-1-6";
        $jb_mode = "";
    }
    elseif($jb_code == "70"){
        $dep1 = "6";
        $dep2 = "6-1";
        $dep3 = "6-1-7";
        $jb_mode = "";
    }
    elseif($jb_code == "80"){
        $dep1 = "6";
        $dep2 = "6-1";
        $dep3 = "6-1-8";
        $jb_mode = "";
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

			

