<?php
	include_once  $_SERVER[DOCUMENT_ROOT].'./_init.php';
	include_once($GP -> CLS."/class.popup.php");
	$C_Popup 	= new Popup;
	
	$rst = $C_Popup->PopUp_Show();


	$mobile	= 0;
	$mobile_agent = '/(iPod|iPhone|Android|BlackBerry|SymbianOS|SCH-M\d+|Opera Mini|Windows CE|Nokia|SonyEricsson|webOS|PalmOS)/';		
	
	// preg_match() 함수를 이용해 모바일 기기로 접속하였는지 확인
	if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {	
		$mobile = 1;
	}

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
		window.open(page,"_self");
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
		
		$css_img_height = $pop_height-31;
	
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
				$pop_link_url = str_replace('http://hdhosp.co.kr','',$pop_link_url);
				$pop_link_url = str_replace('http://www.hdhosp.co.kr','',$pop_link_url);				
				//$pop_str="<a href=\"javascript:move_page('${pop_link_url}')\"><img src='${url}' border='0' class='pop_img'></a>";
				$pop_str="<a href='" . $pop_link_url . "'><img src='${url}' border='0' class='pop_img'></a>";
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
					setCookie( 'popup_<?=$pop_idx?>', 'done' , <?=$pop_idx?>);
			else
					setCookie( 'popup_<?=$pop_idx?>' );
					
			document.getElementById('divpop_<?=$pop_idx?>').style.visibility = 'hidden'; 
	}
	</script>
	<?
		if($mobile == 0) {
	?>
	<div id="divpop_<?=$pop_idx?>" style="width:<?=$pop_width?>px; height:<?=$pop_height?>px; position:absolute; left:<?=$pop_x_position?>px; top:<?=$pop_y_position?>px; z-index:9999; visibility: hidden; overflow:<?=$scrollbars?>;">
	<?
		}else{ //모바일
	?>
	<div id="divpop_<?=$pop_idx?>" style="position:fixed;top:50px;left:5%;right:5%; z-index:9999; visibility: hidden; overflow:<?=$scrollbars?>;">
	<?
		}
	?>
  <form name="Pop_form_<?=$pop_idx?>">
    <?=$pop_str?>
    <div style="height:30px;background:#000;font-size:14px; color:#FFFFFF;">
      <label style="float:left;padding:5px;"><input name="chkbox" type="checkbox" value="1" checked /> 이 창을 <?=$cookie_text?> 열지 않습니다.</label>
      <a href="javascript:closeWin_<?=$pop_idx?>();" style="float:right;padding:5px;color:#fff;font-weight:bold;">[닫기]</a> 
    </div> 
  </form>     
  </div>  
	<style type="text/css">
		<?
			if($mobile == 0) {
		?>
    	.pop_img { width:<?=$pop_width?>px; height:<?=$css_img_height?>px;}
		<? }else{?>
		.pop_img { width:100%;}
		<? } ?>
    </style>
	<script type="text/javascript">
		if ( getCookie('popup_<?=$pop_idx?>') != 'done') {		
			document.getElementById('divpop_<?=$pop_idx?>').style.visibility = 'visible';
		}
			
		$(function() {    
			$("#divpop_<?=$pop_idx?>").draggable();  
		});  
    </script>
	<?php
	}
	?>	

