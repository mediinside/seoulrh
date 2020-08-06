<?
CLASS Dbconn
{
	public $host;
	private $dbuser;
	private $dbpass;
	private $dbname;	
	private $affected_row;	
	private $connect;

	private $loggingUsed= "N";
	private $logFolder = "/home/mysql_log";
	private $logFileName = "";
	
	function __construct($DB = '') {
		global $GP;
		
		if (!$DB) {
			// 기본DB
			$DB = $GP -> DB;
		} 
		
		if (is_array($DB)) {
			$this -> host 		= $DB['host'];
			$this -> dbuser  	= $DB['dbuser'];
			$this -> dbpass 	= $DB['dbpass'];
			$this -> dbname 	= $DB['dbname'];
			
			$this -> db_connect();
		} else {
			echo "Undefined connect information";
			exit;
		}
	}
	
	// Destruct class
	function __destruct()	
	{
		$this -> db_close ();
	}
	
	// Set database name
	function set_dbname($dbname)		
	{
		$this -> dbname = $dbname;
	}
	
	// Set database name
	function get_dbname()		
	{
		return $this -> dbname;
	}	
	
	// Database connect
	public function db_connect()
	{
		if (!is_resource($this -> connect)) {
			//echo $this->host .'/'. $this->dbuser .'/'. $this->dbpass ;
			//echo 'host=>:'. $this->host .'/'. $this->dbuser. '<br>';exit;
			$this -> connect = @mysql_connect( $this->host, $this->dbuser, $this->dbpass ,true) or die( "현재 접속이 원활하지 않습니다. 잠시 후 다시 시도해 주시기 바랍니다. " ); 
			@mysql_query("SET NAMES utf8");
			@mysql_query("set character_set_results=utf-8");
			if( !@@mysql_select_db( $this->dbname, $this -> connect ) ) {
				//echo "$this->dbname select ERROR1 " ;
				echo "현재 접속이 원활하지 않습니다. 잠시 후 다시 시도해 주시기 바랍니다. " ;
			}
		}
	}	
	
	// Connection close
	protected function db_close ()
	{
		if (is_resource($this -> connect)) {
			$this -> connect = "";
			@mysql_close();
		}
	}	
	
	// Get microtime
	public function getmicrotime ()	
	{ 
		list($usec, $sec) = explode(" ",microtime()); 
		return ((float)$usec + (float)$sec); 
	} 
	
	// Log write to text file
	public function log_write ($args)
	{
		@extract($args);
		
/*		if($this -> loggingUsed != "Y")
			return;*/
	
		if(! $this -> logFileName)
		{
			$today = date("Ymd");
			$this -> logFileName = $logFolder."/" . $today . ".data";
		}

		$fp = @fopen($this -> logFileName , "a+") or die("Can't open ".$this -> logFileName);
		$filename = $this -> logFileName;
		
		@fwrite($fp, "#$msg\n");
		@fclose($fp);
	}
	
	public function log_write_free ($args) {
		extract($args);
		if (!$file) {
			$file = '/home/zena/tmp/see_qry.data';
		}
		if ($file && $msg) {
			$fp = @fopen($file , "a+");
			
			$msg = $this -> getmicrotime() . $msg;
			@fwrite($fp, "#$msg\n");
			@fclose($fp);
		}
	}
	
	// Print error massage
	private function sql_error ($query, $location) 
	{
		//if (mysql_errno() == '2006') {
			//$this -> db_connect();
		//} else {
		echo $location . " ::::::::::::::::::> " . $query . "<br>".mysql_errno($this -> connect)." : ".mysql_error($this -> connect);
		exit();
		//}
	}
	
	// Get result rows count
	public function  execSqlNumRow ($query) 
	{
		
		$result = $this -> execQry ($query, 'select');
	
		if (!$result){
			$this->sql_error($query , 'execSqlNumRow');
		}
		else {
			if (mysql_num_rows($result)) 
				return mysql_num_rows($result);
			else
				return 0;
		}
	}
		
	// Get one colume data
	public function  execSqlOneCol ($query) 
	{
		$result = $this -> execQry ($query, 'select');
	
		if (!$result){
			$this->sql_error($query , 'execSqlOneCol');
		} else {
			if (@mysql_num_rows($result)) 
				$rst = mysql_result($result, 0, 0);
			else
				$rst = 0;
				
			mysql_free_result($result);
		}
		
		return $rst;
	}
	
	// Get one row data
	public function execSqlOneRow($query)
	{
		$result = $this -> execQry ($query, 'select');

		if (!$result){
			$this->sql_error($query , 'execSqlOneRow');
		}
		else {
			$rst = mysql_fetch_array($result);
		}
		mysql_free_result($result);
		
		return $rst;
	}
	
	// Get list data
	public function execSqlList($query) {
		$result = $this -> execQry ($query,'select');
		
		if (!$result){
			$this->sql_error($query , 'execSqlList');
		}
		$count	= 0;
		$data	= array();
		while($row = mysql_fetch_array($result)){
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($result);
		return $data;
	}
	
	// Get list data
	public function execSqlAssoc($query) {
		$result = $this -> execQry ($query,'select');
		
		if (!$result){
			$this->sql_error($query , 'execSqlList');
		}
		$count	= 0;
		$data	= array();
		while($row = mysql_fetch_assoc($result)){
			$data[$count] = $row;
			$count++;
		}
		mysql_free_result($result);
		return $data;
	}
		
	// execute Update, Delete
	public function execSqlUpdate ($query) 		
	{
		$result = $this -> execQry ($query,'update');
	
		if (!$result){
			$this->sql_error($query , 'execSqlUpdate');
		}
		else 
		{
			return $this -> affected_row;
		}
	}	
	
	// execute Insert
	public function execSqlInsert ($query) 
	{
		$result = $this -> execQry ($query,'insert');
		
		$key = mysql_insert_id($this->connect);
		
		if (!$result){
			$this->sql_error($query , 'execSqlInsert');
		}
		else return $key;
	}
	
	// execute Insert
	public function execSqlQry ($query) 
	{
		$result = $this -> execQry ($query,'create');
		
		if (!$result){
			$this->sql_error($query , 'execSqlCreate');
		}
		else return $result;
	}
	
	// execute query
	private function execQry ($query, $exec_type = '') {
		
		if (!$query) return false;
		
		$time_start = $this -> getmicrotime();
		$result = mysql_query($query, $this -> connect);
		$time_end = $this -> getmicrotime();
		$time = $time_end - $time_start;
		$this -> affected_row = 0;
		$this -> affected_row = mysql_affected_rows($this -> connect);
		$getNow = date("YmdHms");		
		$detail_rst = $getNow . "||" . $time . "||" . $query;
		$detail_rst2 = $query . ' ('. number_format($time,5) . ' Sec)';
		$add_detail = "";
		$exp_query = "";
		// except select log
		if ($exec_type == 'insert') {
			//$this->log_write($detail_rst);
		} 
		//$this->log_write($detail_rst);
			
		return $result;
	}	

	// 업데이트문 만들기
	function makeUpdate ($args) {
		if (is_array($args)) {
			foreach ($args as $v) {
				$tmp[] = $v . " = '$" . $v . "'";
			}
			
			$rst = implode(' , ', $tmp);
		}
		
		return $rst;
	}
}
?>
