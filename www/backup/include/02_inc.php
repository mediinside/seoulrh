<div class="cont_col2_01">
	
    <div class="col201_l"><img src="/img/sub/02_top_img01.jpg" width="197" height="139" alt="" /></div>	
    
	<div class="col201_r">
		<h2><img src="/img/sub/02_top_txt01.gif" alt="서울재활병원 진료시간표 및 진료시간을 안내입니다." /></h2>
		
		<div class="col201_menu">
		
			<div id="contenttabmenu2">
				<h2 class="bg">본문 메뉴</h2>
				<ul>
					<!-- <li id="ctabm01"><a href="/board/md_schedule/lists">진료시간표</a></li> -->
					<li id="ctabm02"><a href="/sub/02_02">진료시간</a></li>
				</ul>
			</div>
			
			<script type="text/javascript">initClickOn("contenttabmenu2","ctabm<?=$m2?>");</script>
		
		</div>
	
	</div>
</div>
	
<div class="clb"></div>

<?
	$CI =& get_instance();
	$CI->load->model('M_schedule');
	$sche_result = $CI->M_schedule->list_result('sc_id', 'asc', '', '', 100, 0);
	$sche_list = $sche_result['qry'];
?>
<br />

<h3 class="mgt_m50"><img src="/img/sub/02_01_h3_txt01.gif" alt="진료시간표" /></h3>

<table class="t5 w100" summary="의사별 전문분야 안내 및 주중 진료시간표를 안내하는 표입니다.">
<caption class="dpn">진료시간표</caption>
<col style="width: 15%;" span="2" /><col style="width: 7%;" span="10" />

<img src="/img/sub/time.jpg"/>



<br />
