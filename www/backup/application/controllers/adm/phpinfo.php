<?php
class Phpinfo extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	function index() {
		include "init.php";
		
		phpinfo();
	}
}
?>
