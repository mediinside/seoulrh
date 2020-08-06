<?php
	include_once  '../_init.php';	
	include_once($GP -> CLS."/class.popup.php");
	$C_Popup 	= new Popup;
	
	

	if( !$_GET['pop_idx']) {
		echo"<script>self.close();</script>";
		die;
	} else {
		
		$args = "";
		$args['pop_idx'] = $_GET['pop_idx'];
		$rst = $C_Popup->PopUp_Info($args);
		
		if($rst) {
			extract($rst);
			
			$css_img_height = $pop_height-21;
			
			//Text OR Image
			if ($pop_type=="T") {
					$pop_str= $C_Func->dec_contents_view($pop_contents);
			}else{
				$url = $GP -> UP_POPUP_URL .$pop_file_name;	
				//이미지 클릭시 이동할 페이지
				if($pop_link_url != "") {
					$pop_str="<a href='javascript:move_page()'><img src='${url}' class='pop_img_" . $pop_idx . "'></a>";
				} else {
					$pop_str="<img src='${url}' class='pop_img_" . $pop_idx . "'>";
				}
			}
		
			//닫기설정
			switch ($pop_cookie) {
				case "1" :		$cookie_text="하루동안";		break;
				case "7" :		$cookie_text="일주일동안";	break;
				case "30" :	$cookie_text="한달동안";		break;
				case "90" :	$cookie_text="영원히";			break;
				default :		$cookie_text="영원히";			break;
			
			}
		}
	}
?>
<html>
<head>
    <title><?=$pop_title;?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
		<style type="text/css">
    	.pop_img_<?=$pop_idx?> { width:100%; }
   </style>
	<style type="text/css">
	<!--
	body, table {
		font-family: "굴림", "굴림체", "돋움";
		font-size: 9pt;
		color: #333333;
	}
	
	A:link {
		color:#0066FF;
		text-decoration:none;
		font-family: "굴림", "굴림체", "돋움";
		font-size: 9pt;
	} 
	A:visited {
		color:#0066FF;
		text-decoration:none;
		font-family: "굴림", "굴림체", "돋움";
		font-size: 9pt;
	} 
	A:active {
		color:#0066FF;
		text-decoration:none;
		font-family: "굴림", "굴림체", "돋움";
		font-size: 9pt;
	} 
	A:hover {
		color:#0000FF;
		text-decoration:underline;
		font-family: "굴림", "굴림체", "돋움";
		font-size: 9pt;
	}	
	-->
	</style>
	
	<script language="JavaScript">
    <!--//		
		function setCookie( name, value, expiredays )
		{
						var todayDate = new Date();
						todayDate.setDate( todayDate.getDate() + expiredays );
						document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
		}

		function closeWin() {
				if ( document.ReOpen.Notice.checked )
						setCookie( "popup_<?=$pop_idx;?>", "done" , <?=$pop_cookie;?>);
				else
						setCookie( "popup_<?=$pop_idx;?>" );
		
				self.close();
		}

		function move_page()
		{
			opener.location.href="<?=$pop_link_url;?>";
			this.close();	
		}
		
        document.focus();
    //-->
    </script>
</head>
<body bgcolor="#FFFFFF" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table width="100%" height="100%" cellpadding="0" cellspacing="0" border="0">
	<tr>
    	<td valign="top"><?=$pop_str?></td>
    </tr>
	<form name="ReOpen">
    <tr>        		
        <td height="25" align="right" bgcolor="#000000" style="padding-right:10px; font-size:9pt; color:#FFFFFF;">
        <input type="checkbox" name="Notice" checked>이 창을 <?=$cookie_text;?> 열지 않습니다.&nbsp;&nbsp;&nbsp;<a href="javascript:closeWin();"><font color="#FFFFFF">[창닫기]</font></a>
      	</td>
    </tr>
	</form>
</table>
</body>
</html>