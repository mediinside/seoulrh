<?php
	include_once  '../../_init.php';

	$title = "주소 찾기";
	include_once ($GP -> INC_WWW."/inc.addrheader.php");
	include_once $GP -> CLS . 'class.zipcode.php';
	$C_Zipcode = new Zipcode();


	if($_POST['search_key'] != "")
	{
		$args['zc_dong'] = $_POST['search_key'];
		$rst = 	$C_Zipcode->searchDong($args);
	}
	
if($rst) { 
?>
<p class="TxtInfo">아래의 주소중에서 해당되는 주소를 클릭 해주세요.</p>
<div class="pop1_cont2">
	<ul>
<?
		$args_cnt = count($rst);
		for($i=0; $i<$args_cnt; $i++){
				$optionValue = $rst[$i]['zc_post']."^".$rst[$i]['zc_sido']. " " .$rst[$i]['zc_gugun']. " " .$rst[$i]['zc_dong'];
?>
	<li>
		<a href="javascript:;" onclick="parent_addr('<?=$optionValue?>','B')" >
		[<?=$rst[$i]['zc_post']?>]	<?=$rst[$i]['zc_sido']?>	<?=$rst[$i]['zc_gugun']?> 	<?=$rst[$i]['zc_dong']?>
		</a>
	</li>	
<? 
		}
?>
	</ul>
</div>
<?
}else{ ?>
<p class="no_data">데이터가 없습니다.</p>
<? 
} 
?>