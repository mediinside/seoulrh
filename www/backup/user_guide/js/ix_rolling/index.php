<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>IX</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" href="./ix_rolling.css" type="text/css">
		<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js'></script>
		<script type="text/javascript" src="./ix_rolling.js" charset="utf-8"></script>
	</head>
	<body>
		<div id="wrap">
			<div id="container">
				<table>
					<tr>
						<td valign="top"><div id="twitter" class="ix-rolling"></div></td>
					</tr>
				</table>
				<script type="text/javascript">
					jQuery(function() {
						jQuery("#twitter").ix_rolling({
							template : "twitter",
							keyword : "leesanghoC",
							width : "300",
							height : "276",
							use_profile_img : true,
							ix_path : '/js/ix_rolling'
						});
						/*
						jQuery("#ix-rolling").ix_rolling({
							template : "twitter",
							keyword : "KPOP,KARA,Tara",
							width : "300",
							height : "276",
							use_profile_img : true,
							ix_path : '<?php echo $g4['path'];?>/ix_rolling'
						}, {
							template : "facebook",
							keyword : "KPOP",
							ix_path : '<?php echo $g4['path'];?>/ix_rolling',
							cut_text : 140
						});
						jQuery("#ix-rolling1").ix_rolling({
							template : "facebook",
							keyword : "KPOP",
							width : "300",
							height : "276",
							use_profile_img : true,
							ix_path : '<?php echo $g4['path'];?>/ix_rolling',
							cut_text : 140
						});
						jQuery("#ix-rolling2").ix_rolling({
							template : "gnu4",
							keyword : "KPOP",
							width : "300",
							height : "276",
							bo_table : 'test',
							ix_path : '<?php echo $g4['path'];?>/ix_rolling'
						});
						*/
					});

				</script>
			</div>
		</div>
	</body>
</html>
