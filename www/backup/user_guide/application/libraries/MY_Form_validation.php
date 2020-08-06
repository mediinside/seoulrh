<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Form_validation extends CI_Form_validation {
	function __construct() {
		parent::__construct();
	}
	
	public function alpha_numeric_under($str)
	{
		return ( ! preg_match("/^([-a-z0-9_])+$/i", $str)) ? FALSE : TRUE;
	}
	
	public function alpha_numeric_under_dash_slash($str)
	{
		return ( ! preg_match("/^([-a-z0-9_-]|\/)+$/i", $str)) ? FALSE : TRUE;
	}
}
?>
