<?
CLASS Button
{
	private $GP;

	function __construct() {
		global $GP;
		$this -> GP = $GP;
	}
	// $type,$name,$onoff,$click=false,$width=false
	function getButtonDesignNew () {
		$args = @func_get_args ();
		$paraArr = array( 'type','name','onoff','click','width','class','etc');
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];

		extract($args);

		if(!$width){
			$name_len = strlen($name);
			$name_len = 12 * $name_len;
		}else{
			$name_len = $width;
		}

		$click = ($click)? "onClick=\"$click\"" : "";
		$btn_onoff = (!$onoff)? 'off' : 'on' ;

		$class = ($class)? 'class="' . $class . '"' : '';
		$str = "<button type=\"button\" class=\"btn_type\" onfocus=\"this.blur();\" type_status='$onoff' $click>
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"$name_len\">
			<tr>
				<td style=\"border:0;padding:0\" width=\"1\"><img src=\"" . $this -> GP -> IMAGE . "auto_button/$type/btn_" . $btn_onoff . "_l.gif\"></td>
				<td style=\"border:0;\" background=\"" . $this -> GP -> IMAGE . "auto_button/$type/btn_" . $btn_onoff . "_c.gif\" style=\"text-align:center;\">
				<div style=\"padding-top:2px; $etc\" $class>$name</div></td>
				<td style=\"border:0;padding:0\" width=\"1\"><img src=\"" . $this -> GP -> IMAGE . "auto_button/$type/btn_" . $btn_onoff . "_r.gif\"></td>
			</tr>
		</table>
		</button>";

		return $str;
	}

	function getButtonDesign () {
		$args = @func_get_args ();
		$paraArr = array( 'type','name','onoff','click','width','class','etc','id');
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];
		@extract($args);
		$click = ($click)? "onClick=\"$click\"" : "";
		$width = (int)$width;
		$rst = "<span id='webForm'><input type='button' name='bs_del' id='$id' value='$name' $click $class class='webForm_$type' style='$etc' onfocus=\"this.blur();\" $click></span>";
		return $rst;
	}
	
	/*
	function getButtonDesign () {
		$args = @func_get_args ();
		$paraArr = array( 'type','name','onoff','click','width','class','etc','id');
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];
		@extract($args);
		$click = ($click)? "onClick=\"$click\"" : "";
		$width = (int)$width;
		$rst = "<span id='webForm'><input type='button' name='bs_del' id='$id' value='$name' $click $class class='webForm_$type' style='$etc' onfocus=\"this.blur();\" $click></span>";
		return $rst;
	}
	*/

	// desc	 : 버튼 id, name 값을 지정할수 있도록 변경(name_id_val 추가)
	// auth  : YYH 2012-12-28 금요일
	// param
	function getButtonDesign_Mod () {
		$args = @func_get_args ();
		$paraArr = array( 'type','name','onoff','click','width','class','etc','name_id_val');
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];

		extract($args);

		$click = ($click)? "onClick=\"$click\"" : "";
		$width = (int)$width;
		$rst = "
		<span id='webForm'>
			<input type='button' id='$name_id_val' name='$name_id_val' value='$name' $click class='webForm_$type' style='width:".$width."px; $etc' onfocus=\"this.blur();\" $click>
		</span>
		";
		return $rst;
	}


	function getButtonDesignHome () {
		$args = @func_get_args ();
		$paraArr = array( 'type','name','onoff','click','width','class');
		$argsCnt = count($args);
		for($i = 0; $i < $argsCnt ; $i++) ${$paraArr[$i]} = $args[$i];

		extract($args);

		if(!$width){
			$name_len = strlen($name);
			$name_len = 12 * $name_len;
		}else{
			$name_len = $width;
		}

		$click = ($click)? "onClick=\"$click\"" : "";

		$btn_onoff = (!$onoff)? 'off' : 'on' ;

		$class = ($class)? 'class="' . $class . '"' : '';
		$str = "<button type=\"button\" class=\"btn_type\" onfocus=\"this.blur();\" type_status='$onoff' $click>
		<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"$name_len\">
			<tr>
				<td style=\"border:0;padding:0\" width=\"1\"><img src=\"" . $this -> GP -> IMAGE_COM . "auto_button/$type/btn_" . $btn_onoff . "_l.gif\"></td>
				<td style=\"border:0;\" background=\"" . $this -> GP -> IMAGE_COM . "auto_button/$type/btn_" . $btn_onoff . "_c.gif\" style=\"text-align:center;\"><div style=\"padding-top:2px\" $class>$name</div></td>
				<td style=\"border:0;padding:0\" width=\"1\"><img src=\"" . $this -> GP -> IMAGE_COM . "auto_button/$type/btn_" . $btn_onoff . "_r.gif\"></td>
			</tr>
		</table>
		</button>";

		return $str;
	}
}

?>
