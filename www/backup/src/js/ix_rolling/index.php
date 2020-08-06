<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>IX Rolling</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="Description" name="Description" content="twitter api rolling ix rolling facebook rss">
		<link rel="stylesheet" href="./ix_rolling.min.css" type="text/css">
		<script src="http://code.jquery.com/jquery-1.7.1.min.js" charset="utf-8"></script>
		<script type="text/javascript" src="./ix_rolling-latest.min.js?ver=0.6" charset="utf-8"></script>
	</head>
	<body>
		<div id="wrap">
			<div id="container">
				IX rolling - Remote providers data scrolling using jQuery
				<table>
					<tr>
						<td valign="top"><div id="ix-rolling" class="ix-rolling"></div></td>
						<td valign="top"><div id="ix-rolling11" class="ix-rolling"></div></td>
						<td valign="top"><div id="ix-rolling1" class="ix-rolling"></div></td>
					</tr>
				</table>
				<script type="text/javascript">
					var ix_path = ".";
					jQuery(function() {
						jQuery("#ix-rolling").ix_rolling({
							template : "twitter",
							keyword : "KARA",
							width : 300,
							height : 300,
							use_profile_img : true,
							ix_path : ix_path
						});
						jQuery("#ix-rolling11").ix_rolling({
							template : "xml",
							url : "http://www.zend.com/en/company/news/press/feed",
							width : 300,
							height : 300,
							use_profile_img : true,
							ix_path : ix_path
						});
						jQuery("#ix-rolling1").ix_rolling({
							template : 'facebook',
							where:"page",
							where_id : "165106760172502",
							width : 300,
							height : 300,
							ix_path : ix_path,
							cut_text : 140
						});
					});

				</script>
			</div>
		</div>
	</body>
</html>
