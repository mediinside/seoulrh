<?php
if(!SU_ADMIN) {
	exit;
}

$this->load->model(ADM_F.'/M_a_menus');
$this->load->helper('admin');

$admin['path']		= RT_PATH.'/'.ADM_F;
$admin['img_path']	= IMG_DIR.'/'.ADM_F;
$admin['use_point']	= $this->config->item('cf_use_point');
$admin['use_popup']	= $this->config->item('cf_use_popup');

$member = IS_MEMBER ? unserialize(MEMBER) : '';
$menus = $this->M_a_menus->list_result();

$this->load->vars( array(
	'_TITLE_'		=> '관리자 페이지',
	'_JS_'			=> array('layerDlg'),
	'_MENUS_'		=> $menus,

	'member'		=> $member,
	'path'			=> RT_PATH.'/'.ADM_F,
	'img_path'		=> IMG_DIR.'/'.ADM_F		
) );

?>
