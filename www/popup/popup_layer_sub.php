<?php
	include_once  $_SERVER[DOCUMENT_ROOT].'./_init.php';
	include_once($GP -> CLS."/class.popup.php");
	$C_Popup 	= new Popup;
	
	$rst = $C_Popup->PopUp_Sub_Show();
?>
<script type="text/javascript" src="/js/popup/jquery-ui.js"></script>
<script type="text/javascript">
	var getCookie = function(name) 	{
		var nameOfCookie = name + "=";
		var x = 0;
		while ( x <= document.cookie.length ) {
			var y = (x+nameOfCookie.length);
			if ( document.cookie.substring( x, y ) == nameOfCookie ) {
				if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
					endOfCookie = document.cookie.length;
				return unescape( document.cookie.substring( y, endOfCookie ) );
			}
			x = document.cookie.indexOf( " ", x ) + 1;
			if ( x == 0 )
			break;
		}
		return;
	}
	
	var setCookie = function(name, value, expiredays) {
		var todayDate = new Date();
		todayDate.setDate( todayDate.getDate() + expiredays );
		document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
	}
	
	var move_page = function(page)	{
		window.open(page,"_blank");
	}
</script>	
	<?php    
	for($i=0; $i<count($rst); $i++) {
		$pop_idx				= $rst[$i]['pop_idx'];
		$pop_type				= $rst[$i]['pop_type'];
		$pop_contents		= $rst[$i]['pop_contents'];
		$pop_file_name	= $rst[$i]['pop_file_name'];
		$pop_width			= $rst[$i]['pop_width'];
		$pop_height			= $rst[$i]['pop_height'];
		$pop_cookie			= $rst[$i]['pop_cookie'];
		$pop_link_url		= $rst[$i]['pop_link_url'];	
		$pop_x_position	=$rst[$i]['pop_x_position'];
		$pop_y_position	=$rst[$i]['pop_y_position'];
		
		$css_img_height = $pop_height-41;
	
		if($rst[$i]['pop_scroll']=="Y")	{
			$scrollbars="scroll";
		}else{
			$scrollbars="hidden";
		}
		
		//Text OR Image
		if ($pop_type=="T") {
				$pop_str = $C_Func->dec_contents_view($pop_contents);
				$pop_str = "<div style='width:${pop_width}px; height:${css_img_height}px; background-color:#CCC;'>" . $pop_str ."</div>";
		}else{
			$url = $GP -> UP_POPUP_URL .$pop_file_name;	
			//이미지 클릭시 이동할 페이지
			if($pop_link_url != "") {
				$pop_str="<a href=\"javascript:move_page('${pop_link_url}')\"><img src='${url}' border='0' class='pop_img'></a>";
			} else {
				$pop_str="<img src='${url}' width='${pop_width}' height='${pop_height}' border=0>";		
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
	?>
	<script type="text/javascript">
	function closeWin_<?=$pop_idx?>() {
			if ( document.Pop_form_<?=$pop_idx?>.chkbox.checked )
					setCookie( 'popup_sub_<?=$pop_idx?>', 'done' , <?=$pop_idx?>);
			else
					setCookie( 'popup_sub_<?=$pop_idx?>' );
					
			document.getElementById('divpop_<?=$pop_idx?>').style.visibility = 'hidden'; 
	}
	</script>
	<div id="divpop_<?=$pop_idx?>" style="width:<?=$pop_width?>px; height:<?=$pop_height?>px; position:absolute; left:<?=$pop_x_position?>px; top:<?=$pop_y_position?>px; z-index:100; visibility: hidden; overflow:<?=$scrollbars?>;">
  <form name="Pop_form_<?=$pop_idx?>">
    <?=$pop_str?>
    <div style="background-color:#000; padding-right:10px; font-size:9pt; color:#FFFFFF; text-align:right; height:20px;">
      <input name="chkbox" type="checkbox" value="1" checked /> 이 창을 <?=$cookie_text?> 열지 않습니다.
      <a href="javascript:closeWin_<?=$pop_idx?>();"><font color="#754d21"><B>[닫기]</B></font></a> 
    </div> 
  </form>     
  </div>  
	<style type="text/css">
    	.pop_img { width:<?=$pop_width?>px; height:<?=$css_img_height?>px;}
    </style>
	<script type="text/javascript">
		if ( getCookie('popup_sub_<?=$pop_idx?>') != 'done') {		
			document.getElementById('divpop_<?=$pop_idx?>').style.visibility = 'visible';
		}
			
		$(function() {    
			$("#divpop_<?=$pop_idx?>").draggable();  
		});  
    </script>
	<?php
	}
	?>	

