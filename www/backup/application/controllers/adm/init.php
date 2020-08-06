<?php
if(!SU_ADMIN) {
	exit;
}

$this->load->model(ADM_F.'/M_a_menus');
$this->load->helper(array('admin', 'search'));

$menus = $this->M_a_menus->list_result();
$member = unserialize(MEMBER);

$this->M_config->getConfig();

$seg = new search_seg;

$this->load->vars( array(
	'_TITLE_'		=> '관리자 페이지',
	'_JS_'			=> array('layerDlg'),
	
	'_MENUS_'		=> $menus,
	'SITE_NAME'		=> $this->config->item('cf_title'),
	'IMG_PATH'		=> IMG_DIR.'/'.ADM_F,

	'path'			=> RT_PATH.'/'.ADM_F
) );

?>
