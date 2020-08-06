
<?

	$CI =& get_instance();
	$CI->load->model('M_schedule');
	$sche_result = $CI->M_schedule->list_result('sc_id', 'asc', '', '', 100, 0);
	$sche_list = $sche_result['qry'];
?>

<div class="fr fs11 fc_gr01">※ <strong>가로보기</strong>를 권장합니다.</div>
<!--
<table class="t5 w100" summary="의사별 전문분야 안내 및 주중 진료시간표를 안내하는 표입니다.">
<caption class="dpn">진료시간표</caption>
-->
<img src="/img/sub/time.jpg"/>

<br />
