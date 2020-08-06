<?
define( NAVER_OAUTH_URL, "https://nid.naver.com/oauth2.0/" );
define( NAVER_SESSION_NAME, "NHN_SESSION" );
@session_start();
class Naver{
	private $tokenDatas	=	array();
	private $access_token			= '';			// oauth 엑세스 토큰
	private $refresh_token			= '';			// oauth 갱신 토큰
	private $access_token_type		= '';			// oauth 토큰 타입
	private $access_token_expire	= '';			// oauth 토큰 만료
	private $client_id		= '';			// 네이버에서 발급받은 클라이언트 아이디
	private $client_secret	= '';			// 네이버에서 발급받은 클라이언트 시크릿키
	private $returnURL		= '';			// 콜백 받을 URL ( 네이버에 등록된 콜백 URI가 우선됨)
	private $state			= '';			// 네이버 명세에 필요한 검증 키 (현재 버전 라이브러리에서 미검증)
	private $loginMode		= 'request';	// 라이브러리 작동 상태
	private $returnCode		= '';			// 네이버에서 리턴 받은 승인 코드
	private $returnState	 = '';			// 네이버에서 리턴 받은 검증 코드
	private $nhnConnectState	= false;
	// action options
	private $autoClose		= true;
	private $showLogout		= true;
	private $curl = NULL;
	private $refreshCount = 1;  // 토큰 만료시 갱신시도 횟수
	private $drawOptions = array( "type" => "normal", "width" => "200" );
	function __construct($argv = array()) {
		if  ( ! in_array  ('curl', get_loaded_extensions())) {
			echo 'curl required';
			return false;
		}
		if($argv['CLIENT_ID']){
			$this->client_id = trim($argv['CLIENT_ID']);
		}
		if($argv['CLIENT_SECRET']){
			$this->client_secret = trim($argv['CLIENT_SECRET']);
		}
		if($argv['RETURN_URL']){
			$this->returnURL = trim(urlencode($argv['RETURN_URL']));
		}
		if($argv['AUTO_CLOSE'] == false){
			$this->autoClose = false;
		}
		if($argv['SHOW_LOGOUT'] == false){
			$this->showLogout = false;
		}
		$this->loadSession();
		if(isset($_GET['nhnMode']) && $_GET['nhnMode'] != ''){
			$this->loginMode = 'logout';
			$this->logout();
		}
		if($this->getConnectState() == false){
			$this->generate_state();
			if($_GET['state'] && $_GET['code']){
				$this->loginMode = 'request_token';
				$this->returnCode = $_GET['code'];
				$this->returnState = $_GET['state'];
				$this->_getAccessToken();
			}
		}
	}
	function login($options = array()){
		if(isset($options['type'])){
			$this->drawOptions['type'] = $options['type'];
		}
		if(isset($options['width'])){
			$this->drawOptions['width'] = $options['width'];
		}
		
		if($this->loginMode == 'request' && (!$this->getConnectState()) || !$this->showLogout){
			echo '<li><a href="javascript:loginNaver();" class="btn sign-naver">
								<i class="ip-icon-naver-ci"></i><span>네이버<br>로그인</span></a></li>';
			echo '
			<script>
			function loginNaver(){
				var win = window.open(\''.NAVER_OAUTH_URL.'authorize?client_id='.$this->client_id.'&response_type=code&redirect_uri='.$this->returnURL.'&state='.$this->state.'\', \'네이버 아이디로 로그인\',\'width=320, height=480, toolbar=no, location=no\');
				var timer = setInterval(function() {
					if(win.closed) {
						window.location.reload();
						clearInterval(timer);
					}
				}, 5000);
			}
			</script>
			';
		}else if($this->getConnectState()){
			if($this->showLogout){
				echo '<a href="http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"].'?nhnMode=logout"><img src="https://www.eventmaker.kr/open/idn/naver_logout.png" width="'.$this->drawOptions['width'].'" alt="네이버 아이디 로그아웃"/></a>';
			}
		}
		if($this->loginMode == 'request_token'){
			$this->_getAccessToken();
		}
	}
	function logout(){
		$this->refreshCount = 1;
		$data = array();
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL, NAVER_OAUTH_URL.'token?client_id='.$this->client_id.'&client_secret='.$this->client_secret.'&grant_type=delete&refresh_token='.$this->refresh_token.'&sercive_provider=NAVER');
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);
		$retVar = curl_exec($this->curl);
		curl_close($this->curl);
		$this->deleteSession();
		echo "<script>window.location.href = 'http://".$_SERVER["HTTP_HOST"] . $_SERVER['PHP_SELF']."';</script>";
	}
	function getUserProfile($retType = "JSON"){
		if($this->getConnectState()){
			$data = array();
			$data['Authorization'] = $this->access_token_type.' '.$this->access_token;
			$this->curl = curl_init();
			curl_setopt($this->curl, CURLOPT_URL, 'https://apis.naver.com/nidlogin/nid/getUserProfile.xml');
			curl_setopt($this->curl, CURLOPT_POST, 1);
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($this->curl, CURLOPT_HTTPHEADER, array(
				'Authorization: '.$data['Authorization']
			));
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);
			$retVar = curl_exec($this->curl);
			curl_close($this->curl);
			$xml = new SimpleXMLElement($retVar);
			$responseState = (string) $xml->result[0]->resultcode[0];
			if($responseState == "024"){
				if($this->refreshCount > 0){
					$this->refreshCount--;
					$this->_refreshAccessToken();
					$this->getUserProfile();
					return;
				}else{
					$this->logout();
					return false;
				}
			}
			if($retType == "JSON"){
				$xmlJSON = array();
				$xmlJSON['result']['resultcode'] = (string) $xml->result[0]->resultcode[0];
				$xmlJSON['result']['message'] = (string) $xml->result[0]->message[0];
				if($xml->result[0]->resultcode == '00'){
					foreach($xml->response->children() as $response => $k){
						$xmlJSON['response'][(string)$response] = (string) $k;
					}
				}
				return json_encode($xmlJSON);
			}else{
				return $retVar;
			}
		}else{
			return false;
		}
	}
	/**
	*	Get AccessToken
	*	발급된 엑세스 토큰을 반환합니다. 엑세스 토큰 발급은 로그인 후 자동으로 이루어집니다.
	*/
	function getAccess_token(){
		if($this->access_token){
			return $this->access_token;
		}
	}
	/**
	*	 네이버 연결상태를 반환합니다.
	*    엑세스 토큰 발급/저장이 이루어진 후 connected 상태가 됩니다.
	*/
	function getConnectState(){
		return $this->nhnConnectState;
	}
	private function updateConnectState($strState = ''){
		$this->nhnConnectState = $strState;
	}
	/**
	*	토근을 세션에 기록합니다.
	*/
	private function saveSession(){
		if(isset($_SESSION) && is_array($_SESSION)){
			$_saveSession = array();
			$_saveSession['access_token']		=	$this->access_token;
			$_saveSession['access_token_type']	=	$this->access_token_type;
			$_saveSession['refresh_token']		=	$this->refresh_token;
			$_saveSession['access_token_expire']	=	$this->access_token_expire;
			$this->tokenDatas = $_saveSession;
			foreach($_saveSession as $k=>$v){
				$_SESSION[NAVER_SESSION_NAME][$k] = $v;
			}
		}
	}
	private function deleteSession(){
		if(isset($_SESSION) && is_array($_SESSION) && $_SESSION[NAVER_SESSION_NAME]){
			$_loadSession = array();
			$this->tokenDatas = $_loadSession;
			unset($_SESSION[NAVER_SESSION_NAME]);
			$this->access_token			= '';
			$this->access_token_type	= '';
			$this->refresh_token		= '';
			$this->access_token_expire	= '';
			$this->updateConnectState(false);
		}
	}
	/**
	*	저장된 토큰을 복원합니다.
	*/
	private function loadSession(){
		if(isset($_SESSION) && is_array($_SESSION) && $_SESSION[NAVER_SESSION_NAME]){
			$_loadSession = array();
			$_loadSession['access_token']		=	$_SESSION[NAVER_SESSION_NAME]['access_token'] ? $_SESSION[NAVER_SESSION_NAME]['access_token'] : '';
			$_loadSession['access_token_type']	=	$_SESSION[NAVER_SESSION_NAME]['access_token_type'] ? $_SESSION[NAVER_SESSION_NAME]['access_token_type'] : '';
			$_loadSession['refresh_token']		=	$_SESSION[NAVER_SESSION_NAME]['refresh_token'] ? $_SESSION[NAVER_SESSION_NAME]['refresh_token'] : '';
			$_loadSession['access_token_expire']	=	$_SESSION[NAVER_SESSION_NAME]['access_token_expire'] ? $_SESSION[NAVER_SESSION_NAME]['access_token_expire']:'';
			$this->tokenDatas = $_loadSession;
			$this->access_token			= $this->tokenDatas['access_token'];
			$this->access_token_type	= $this->tokenDatas['access_token_type'];
			$this->refresh_token		= $this->tokenDatas['refresh_token'];
			$this->access_token_expire	= $this->tokenDatas['access_token_expire'];
			$this->updateConnectState(true);
			$this->saveSession();
		}
	}
	private function _getAccessToken(){
		$data = array();
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL, NAVER_OAUTH_URL.'token?client_id='.$this->client_id.'&client_secret='.$this->client_secret.'&grant_type=authorization_code&code='.$this->returnCode.'&state='.$this->returnState);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);
		$retVar = curl_exec($this->curl);
		curl_close($this->curl);
		$NHNreturns = json_decode($retVar);
		if(isset($NHNreturns->access_token)){
			$this->access_token			= $NHNreturns->access_token;
			$this->access_token_type	= $NHNreturns->token_type;
			$this->refresh_token		= $NHNreturns->refresh_token;
			$this->access_token_expire	= $NHNreturns->expires_in;
			$this->updateConnectState(true);
			$this->saveSession();
			if($this->autoClose){
				echo "<script>window.close();</script>";
			}
		}
	}
	private function _refreshAccessToken(){
		$data = array();
		$this->curl = curl_init();
		curl_setopt($this->curl, CURLOPT_URL, NAVER_OAUTH_URL.'token?client_id='.$this->client_id.'&client_secret='.$this->client_secret.'&grant_type=refresh_token&refresh_token='.$this->refresh_token);
		curl_setopt($this->curl, CURLOPT_POST, 1);
		curl_setopt($this->curl, CURLOPT_POSTFIELDS, $data);
		curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);
		$retVar = curl_exec($this->curl);
		curl_close($this->curl);
		$NHNreturns = json_decode($retVar);
		if(isset($NHNreturns->access_token)){
			$this->access_token			= $NHNreturns->access_token;
			$this->access_token_type	= $NHNreturns->token_type;
			$this->access_token_expire	= $NHNreturns->expires_in;
			$this->updateConnectState(true);
			$this->saveSession();
		}
	}
	private function generate_state() {
    $mt = microtime();
		$rand = mt_rand();
		$this->state = md5( $mt . $rand );
  }
}
?>