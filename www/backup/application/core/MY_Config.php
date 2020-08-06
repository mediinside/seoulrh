<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Config extends CI_Config {
	function __construct() {
		parent::__construct();

		$this->set_item('base_url', preg_replace('/\/$/', '', $this->item('base_url')));
	}
}
?>
