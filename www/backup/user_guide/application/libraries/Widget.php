<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

define('WIDGET_PI', TRUE);

class Widget {
    function Widget() {
        $this->_assign_libraries();
    }

    function run($name, $data=array()) {
        $args = func_get_args();

        require_once APPPATH.'controllers/'.$name.EXT;

		if (strpos($name, '/')) {
			$temp = explode('/', $name);
			$name = ucfirst($temp[1]);
		} else
	        $name = ucfirst($name);

        $widget = new $name();
		$widget =& $widget;
		
        return call_user_func_array(array(&$widget, 'index'), array_slice($args, 1));
    }

    function _assign_libraries() {
        $CI =& get_instance();
        foreach (get_object_vars($CI) as $key => $object) {
            $this->$key =& $CI->$key;
        }
    }
}
?>
