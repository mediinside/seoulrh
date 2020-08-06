<?php
// Dedc : admin 메뉴 Array
// Writer :
$GP -> MENU_ADMIN = array(
		array("tab"=>"1",			"folder"=>"bbs", 			"name"=>"게시판관리",			"link"=> "/admin/ru/bbs/bbs_list.php?m_tab=1"),
		array("tab"=>"2",			"folder"=>"doctor", 		"name"=>"의료진관리",			"link"=> "/admin/ru/doctor/dr_list.php?m_tab=2"),
		array("tab"=>"3",			"folder"=>"slide", 			"name"=>"슬라이드관리",			"link"=> "/admin/ru/slide/slide_list.php?m_tab=3"),
		array("tab"=>"4",			"folder"=>"phone", 			"name"=>"상담신청관리",			"link"=> "/admin/ru/cs/cs_list.php?m_tab=4"),
		array("tab"=>"5",			"folder"=>"popup", 			"name"=>"팝업관리",				"link"=> "/admin/ru/popup/popup_list.php?m_tab=5"),
		array("tab"=>"6",			"folder"=>"analytics", 		"name"=>"통계관리",				"link"=> "/admin/ru/analytics/day_visit.php?m_tab=6")		
);


$GP -> MENU_SUB_ADMIN = array();

$GP -> MENU_SUB_ADMIN['main'] = array(
	"시스템관리"   => array(
		array("tab"=>"1",		"title"=>"main1",		"name"=>"관리자 정보",		"link"=>  "/admin/ru/main/adm_info.php"),
		array("tab"=>"2",		"title"=>"main2",		"name"=>"권한 정보",			"link"=>  "/admin/ru/main/adm_auth.php"),
	)
);

$GP -> MENU_SUB_ADMIN['bbs'] = array(
	"게시판관리"   => array(
		array("tab"=>"1",		"title"=>"bbs1",		"name"=>"게시판 리스트",				"link"=>  "/admin/ru/bbs/bbs_list.php")
	)
);

$GP -> MENU_SUB_ADMIN['doctor'] = array(
	"의료진관리"   => array(
		array("tab"=>"1",	"title"=>"doctor1", "name"=>"의료진 정보",			"link"=>  "/admin/ru/doctor/dr_list.php"),
	)
);

$GP -> MENU_SUB_ADMIN['slide'] = array(
	"슬라이드관리"   => array(
		array("tab"=>"1",	"title"=>"slide1",		"name"=>"슬라이드 정보",			"link"=>  "/admin/ru/slide/slide_list.php"),
	)
);

$GP -> MENU_SUB_ADMIN['cs'] = array(
	"상담신청관리"   => array(
		array("tab"=>"1",	"title"=>"phone1",	"name"=>"상담신청관리",			"link"=>  "/admin/ru/cs/cs_list.php"),
	)
);

$GP -> MENU_SUB_ADMIN['popup'] = array(
	"팝업관리"   => array(
		array("tab"=>"1",	"title"=>"popup1",		"name"=>"팝업 리스트",				"link"=>  "/admin/ru/popup/popup_list.php"),
	)
);

$GP -> MENU_SUB_ADMIN['analytics'] = array(
	"통계관리"   => array(	
		array("tab"=>"1",	"title"=>"analytics1",	"name"=>"일별 통계",				"link"=>  "/admin/ru/analytics/day_visit.php"),
		array("tab"=>"2",	"title"=>"analytics2",	"name"=>"월별 통계",				"link"=>  "/admin/ru/analytics/month_visit.php"),
		array("tab"=>"3",	"title"=>"analytics3",	"name"=>"년별 통계",				"link"=>  "/admin/ru/analytics/year_visit.php"),		
		array("tab"=>"4",	"title"=>"analytics4",	"name"=>"Agent 통계",				"link"=>  "/admin/ru/analytics/Agent.php"),
		array("tab"=>"5",	"title"=>"analytics5",	"name"=>"기타 통계",				"link"=>  "/admin/ru/analytics/OS.php"),
	)
);


?>