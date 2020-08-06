<?
function getInfo($mAll, $index) {
	if ( $mAll == null ) return null;
	$temp = explode("|", $mAll);
	if ( $index == 0 and count($temp) >= 1 ) return $temp[0];
	else if ( $index == 1 and count($temp) >= 2 ) return $temp[1];
	else if ( $index == 2 and count($temp) >= 3 ) return $temp[2];
	else return "";
}

$siteName = "서울재활병원";

/* 새 창 */
$newWin = "onclick='window.open(this.href); return false;' target='_blank' title='새 창'";
$popUp = "onclick='window.open(this.href,\"\",\"width=400,height=500,scrollbars=yes,left=20,top=20\"); return false;' target='_blank' title='새 창'";
//$mAll[1][1][0][0] = "메뉴명"."|"."링크주소"."|".$newWin;


//1depth
$mAll[1][0][0][0] = "진료예약"."|"."/acc/sub/01_01.php";

$mAll[1][1][0][0] = "입원예약 "."|"."/acc/sub/01_01.php";
	$mAll[1][1][1][0] = "입원안내"."|"."/acc/sub/01_01_01.php";
	$mAll[1][1][2][0] = "병동소개"."|"."/acc/sub/01_01_02.php";
$mAll[1][2][0][0] = "낮병동예약"."|"."/acc/sub/01_02.php";
	$mAll[1][2][1][0] = "낮병동예약"."|"."/acc/sub/01_01_01.php";
	$mAll[1][2][2][0] = "낮병동소개"."|"."/acc/sub/01_01_02.php";
$mAll[1][3][0][0] = "외래예약"."|"."/acc/sub/01_03.php";
	$mAll[1][3][1][0] = "외래예약"."|"."/acc/sub/01_01_01.php";
	$mAll[1][3][2][0] = "외래소개"."|"."/acc/sub/01_01_02.php";
$mAll[1][4][0][0] = "제증명발급"."|"."/acc/sub/01_03.php";
	$mAll[1][4][1][0] = "증명서 발급안내"."|"."/acc/sub/01_04_01.php";
	$mAll[1][4][2][0] = "의무기록 발급절차안내"."|"."/acc/sub/01_04_02.php";
	$mAll[1][4][3][0] = "영상CD복사 발급절차안내"."|"."/acc/sub/01_04_03.php";
	$mAll[1][4][4][0] = "입원"."|"."/acc/sub/01_04_04.php";
	$mAll[1][4][5][0] = "발급에 필요한 구비서류"."|"."/acc/sub/01_04_05.php";
	$mAll[1][4][6][0] = "서식다운로드"."|"."/acc/sub/01_04_06.php";
	$mAll[1][4][7][0] = "장애인등록절차"."|"."/acc/sub/01_04_07.php";
	$mAll[1][4][8][0] = "보장구 처방전 발급 및 검수확인서안내"."|"."/acc/sub/01_04_08.php";
$mAll[1][5][0][0] = "비급여항목"."|"."/acc/sub/01_03.php";

//1depth
$mAll[2][0][0][0] = "진료시간표 / 진료시간"."|"."/acc/sub/02_01.php";

$mAll[2][1][0][0] = "진료시간표 "."|"."/acc/sub/02_01.php";
$mAll[2][2][0][0] = "진료시간 "."|"."/acc/sub/02_02.php";

//1depth
$mAll[3][0][0][0] = "성인"."|"."/acc/sub/03_01.php";

$mAll[3][1][0][0] = "가상치료체험"."|"."/acc/sub/03_01.php";
	$mAll[3][1][1][0] = "뇌졸중환자재활시스템"."|"."/acc/sub/03_01_01.php";
	$mAll[3][1][2][0] = "가상1일 치료체험"."|"."/acc/sub/03_01_02.php";
	$mAll[3][1][3][0] = "가정복귀/사회복귀 프로그램"."|"."/acc/sub/03_01_03.php";
$mAll[3][2][0][0] = "질환별클리닉"."|"."/acc/sub/03_02_01_01.php";
	$mAll[3][2][1][0] = "클리닉"."|"."/acc/sub/03_02_01_01.php";
			$mAll[3][2][1][1] = "뇌졸중"."|"."/acc/sub/03_02_01_01.php";
			$mAll[3][2][1][2] = "척수손상"."|"."/acc/sub/03_02_01_02.php";
			$mAll[3][2][1][3] = "뇌성마비"."|"."/acc/sub/03_02_01_03.php";
			$mAll[3][2][1][4] = "통증"."|"."/acc/sub/03_02_01_04.php";
			$mAll[3][2][1][5] = "류마티스"."|"."/acc/sub/03_02_01_05.php";
			$mAll[3][2][1][6] = "골다공증"."|"."/acc/sub/03_02_01_06.php";
			$mAll[3][2][1][7] = "근경직"."|"."/acc/sub/03_02_01_07.php";
			$mAll[3][2][1][8] = "족부"."|"."/acc/sub/03_02_01_08.php";
			$mAll[3][2][1][9] = "부정렬증후군"."|"."/acc/sub/03_02_01_09.php";
			$mAll[3][2][1][10] = "의지보조기"."|"."/acc/sub/03_02_01_10.php";
	$mAll[3][2][2][0] = "특수검사"."|"."/acc/sub/03_02_02_01.php";
			$mAll[3][2][2][1] = "뉴로피드백검사"."|"."/acc/sub/03_02_02_01.php";
			$mAll[3][2][2][2] = "신경근전도검사"."|"."/acc/sub/03_02_02_02.php";
			$mAll[3][2][2][3] = "요류역학검사"."|"."/acc/sub/03_02_02_03.php";
			$mAll[3][2][2][4] = "뇌졸중위험인자검사"."|"."/acc/sub/03_02_02_04.php";
			$mAll[3][2][2][5] = "심전도검사"."|"."/acc/sub/03_02_02_05.php";
			$mAll[3][2][2][6] = "호흡기능검사"."|"."/acc/sub/03_02_02_06.php";
			$mAll[3][2][2][7] = "비디오연하조영제검사"."|"."/acc/sub/03_02_02_07.php";
			$mAll[3][2][2][8] = "체온열검사"."|"."/acc/sub/03_02_02_08.php";
			$mAll[3][2][2][9] = "골다공증검사"."|"."/acc/sub/03_02_02_09.php";
$mAll[3][3][0][0] = "치료후기"."|"."/acc/sub/03_03.php";;
$mAll[3][4][0][0] = "의료상담"."|"."/acc/sub/03_04.php";

//1depth
$mAll[4][0][0][0] = "소아/청소년"."|"."/acc/sub/04_02.php";

$mAll[4][1][0][0] = "가상치료체험"."|"."/acc/sub/04_01_01.php";
	$mAll[4][1][1][0] = "뇌성마비환자 재활시스템"."|"."/acc/sub/04_01_01.php";
	$mAll[4][1][2][0] = "청소년대상 재활시스템"."|"."/acc/sub/04_01_02.php";
	$mAll[4][1][3][0] = "가상1일 치료체험"."|"."/acc/sub/04_01_03.php";
	$mAll[4][1][4][0] = "가정복귀/사회복귀 프로그램"."|"."/acc/sub/04_01_04.php";
$mAll[4][2][0][0] = "질환별클리닉"."|"."/acc/sub/04_02.php";
	$mAll[4][2][1][0] = "클리닉"."|"."/acc/sub/04_02_01_01.php";
			$mAll[4][2][1][1] = "뇌성마비"."|"."/acc/sub/04_02_01_01.php";
			$mAll[4][2][1][2] = "발달지연"."|"."/acc/sub/04_02_01_02.php";
			$mAll[4][2][1][3] = "운동장애"."|"."/acc/sub/04_02_01_03.php";
			$mAll[4][2][1][4] = "자폐장애"."|"."/acc/sub/04_02_01_04.php";
			$mAll[4][2][1][5] = "통증"."|"."/acc/sub/04_02_01_05.php";
			$mAll[4][2][1][6] = "근경직"."|"."/acc/sub/04_02_01_06.php";
			$mAll[4][2][1][7] = "족부"."|"."/acc/sub/04_02_01_07.php";
			$mAll[4][2][1][8] = "부정렬증후군"."|"."/acc/sub/04_02_01_08.php";
			$mAll[4][2][1][9] = "의지보조기"."|"."/acc/sub/04_02_01_09.php";
	$mAll[4][2][2][0] = "특수검사"."|"."/acc/sub/04_02_02_01.php";
			$mAll[4][2][2][1] = "신경근전도검사"."|"."/acc/sub/04_02_02_01.php";
			$mAll[4][2][2][2] = "비디오연하조영제검사"."|"."/acc/sub/04_02_02_01.php";
			$mAll[4][2][2][3] = "체온열검사"."|"."/acc/sub/04_02_02_03.php";
$mAll[4][3][0][0] = "치료후기"."|"."/acc/sub/04_03.php";
$mAll[4][4][0][0] = "의료상담"."|"."/acc/sub/04_04.php";

//1depth
$mAll[5][0][0][0] = "병원소개"."|"."/acc/sub/05_03.php";

$mAll[5][1][0][0] = "오시는 길"."|"."/acc/sub/05_01.php";
$mAll[5][2][0][0] = "전화번호"."|"."/acc/sub/05_02_01.php";
	$mAll[5][2][1][0] = "주요전화번호"."|"."/acc/sub/05_02_01.php";
	$mAll[5][2][2][0] = "층별전화번호"."|"."/acc/sub/05_02_02.php";
$mAll[5][3][0][0] = "병원소개"."|"."/acc/sub/05_03.php";
	$mAll[5][3][1][0] = "비전하우스"."|"."/acc/sub/05_03_01.php";
	$mAll[5][3][2][0] = "병원장인사말"."|"."/acc/sub/05_03_02.php";
	$mAll[5][3][3][0] = "미션/비전/로고"."|"."/acc/sub/05_03_03.php";
	$mAll[5][3][4][0] = "연혁"."|"."/acc/sub/05_03_04.php";
	$mAll[5][3][5][0] = "조직도"."|"."/acc/sub/05_03_05.php";
	$mAll[5][3][6][0] = "인증내역"."|"."/acc/sub/05_03_06.php";
$mAll[5][4][0][0] = "병원소식"."|"."/acc/sub/05_04.php";
$mAll[5][5][0][0] = "언론보도"."|"."/acc/sub/05_05.php";
$mAll[5][6][0][0] = "협력기관"."|"."/acc/sub/05_06.php";

//1depth
$mAll[6][0][0][0] = "교육연구"."|"."/acc/sub/06_01.php";

$mAll[6][1][0][0] = "교육연구센터"."|"."/acc/sub/06_01.php";
$mAll[6][2][0][0] = "세미나 신청"."|"."/acc/sub/06_02_01.php";
	$mAll[6][2][1][0] = "작업치료세미나"."|"."/acc/sub/06_02_01.php";
	$mAll[6][2][2][0] = "신경과학세미나"."|"."/acc/sub/06_02_02.php";
	$mAll[6][2][3][0] = "소아물리작업치료세미나"."|"."/acc/sub/06_02_03.php";
	$mAll[6][2][4][0] = "보행훈련세미나"."|"."/acc/sub/06_02_04.php";
	$mAll[6][2][5][0] = "임상작업치료"."|"."/acc/sub/06_02_05.php";
	$mAll[6][2][6][0] = "청소년치료세미나"."|"."/acc/sub/06_02_06.php";
$mAll[6][3][0][0] = "전문가연수프로그램 신청"."|"."/acc/sub/06_03_01.php";
	$mAll[6][3][1][0] = "프로그램신청"."|"."/acc/sub/06_03_01.php";
	$mAll[6][3][2][0] = "참가자후기"."|"."/acc/sub/06_03_01.php";
$mAll[6][4][0][0] = "연구현황"."|"."/acc/sub/06_04.php";

//1depth
$mAll[7][0][0][0] = "공공재활의료사업"."|"."/acc/sub/07_02.php";

$mAll[7][1][0][0] = "연보"."|"."?"."|".$newWin;
$mAll[7][2][0][0] = "후원안내"."|"."/acc/sub/07_02.php";
	$mAll[7][2][1][0] = "후원안내"."|"."/acc/sub/07_02_01.php";
	$mAll[7][2][2][0] = "후원내역"."|"."/acc/sub/07_02_02.php";
    $mAll[7][2][3][0] = "후원내역"."|"."/acc/sub/07_02_03.php";
$mAll[7][3][0][0] = "공공재활의료사업활동"."|"."/acc/sub/07_03.php";
	$mAll[7][3][1][0] = "사회복귀프로그램"."|"."/acc/sub/07_03_01.php";
	$mAll[7][3][2][0] = "보조공학"."|"."/acc/sub/07_03_02.php";
$mAll[7][4][0][0] = "자원봉사신청"."|"."/acc/sub/07_04.php";

//1depth
$mAll[8][0][0][0] = "채용정보"."|"."/acc/sub/08_01.php";

$mAll[8][1][0][0] = "인재상"."|"."/acc/sub/08_01.php";
$mAll[8][2][0][0] = "채용정보"."|"."/acc/sub/08_02.php";

//1depth
$mAll[9][0][0][0] = "그린플러스(e-book)"."|"."?"."|".$newWin;

$mAll[9][1][0][0] = "그린플러스(e-book)"."|"."?"."|".$newWin;

//1depth
$mAll[10][0][0][0] = "Foreign Language"."|"."/acc/sub/10_01.php";

$mAll[10][1][0][0] = "English"."|"."?";
$mAll[10][2][0][0] = "Chinese"."|"."?";
$mAll[10][3][0][0] = "Japanese"."|"."?";
$mAll[10][4][0][0] = "Russinan"."|"."?";

//1depth
$mAll[11][0][0][0] = "이용안내"."|"."/acc/sub/sitemap.php";

$mAll[11][1][0][0] = "사이트맵"."|"."/acc/sub/sitemap.php";
$mAll[11][2][0][0] = "개인정보취급방침"."|"."/acc/sub/privacy.php";
$mAll[11][3][0][0] = "이용약관"."|"."/acc/sub/stipulation.php";
$mAll[11][4][0][0] = "이메일무단수집거부"."|"."/acc/sub/nophishing.php";

//1depth
$mAll[12][0][0][0] = "마이페이지"."|"."/member/login.php";

//if ( MyMemberCheck(1) ) { // 회원일 경우
	$mAll[12][1][0][0] = "로그아웃"."|"."/member/login/out.php";
	$mAll[12][2][0][0] = "회원정보수정"."/member/confirm/qry/member.modify.php";
//}else{				      // 비회원일 경우
	$mAll[12][1][0][0] = "로그인"."|"."/member/login.php";
	$mAll[12][2][0][0] = "회원가입"."|"."/member/join.php";
//}

$mAll[12][3][0][0] = "아이디/비밀번호찾기"."|"."/member/forget_idpwd.php";
$mAll[12][4][0][0] = "회원정보수정"."|"."/member/confirm/qry/member.modify.php";



//공통메뉴
$mAll[0][0][0][0] = "홈"."|"."/acc/main/";

//[상]사이트 메뉴
$mAll[0][1][1][0] = $mAll[0][0][0][0];//Home
$mAll[0][1][2][0] = $mAll[12][2][0][0];//회원가입
$mAll[0][1][3][0] = $mAll[12][1][0][0];//로그인
$mAll[0][1][4][0] = $mAll[11][1][0][0];//사이트맵
$mAll[0][1][5][0] = $mAll[10][1][0][0];//English
$mAll[0][1][6][0] = $mAll[10][2][0][0];//Chinese
$mAll[0][1][7][0] = $mAll[10][3][0][0];//Japanese
$mAll[0][1][8][0] = $mAll[10][4][0][0];//Russinan

//[하]사이트 안내 및 정책
$mAll[0][2][1][0] = $mAll[5][3][0][0];//병원소개
$mAll[0][2][2][0] = $mAll[1][0][0][0];//진료안내
$mAll[0][2][3][0] = $mAll[5][1][0][0];//오시는 길
$mAll[0][2][4][0] = $mAll[11][2][0][0];//개인정보취급방침
$mAll[0][2][5][0] = $mAll[11][3][0][0];//이용약관
$mAll[0][2][6][0] = $mAll[11][4][0][0];//이메일무단수집거부


//[우]내부링크면여기서지정 - 우측 퀵메뉴(좌측 공통메뉴)
$mAll[0][3][1][0] = $mAll[5][1][0][0];//오시는 길
$mAll[0][3][2][0] = $mAll[5][2][0][0];//전화번호
$mAll[0][3][3][0] = $mAll[2][1][0][0];//진료시간표
$mAll[0][3][4][0] = $mAll[8][2][0][0];//채용정보


//공용코드
//메뉴제목,링크주소,자바스크립트,title속성 할당
for ( $i = 0; $i <= count($mAll); $i++ ) {
	if ( $mAll[$i][0][0][0] == null ) continue;
	for ( $j = 0; $j <= count($mAll[$i]); $j++ ) {
		if ( $mAll[$i][$j][0][0] == null and $i <> 0 ) continue;
		for ( $k = 0; $k <= count($mAll[$i][$j]); $k++ ) {
			if ( $mAll[$i][$j][$k][0] == null and $i <> 0 ) continue;
			for ( $l = 0; $l <= count($mAll[$i][$j][$k]); $l++ ) {
				if ( $mAll[$i][$j][$k][$l] == null) continue;
				$mTitle[$i][$j][$k][$l] = getInfo($mAll[$i][$j][$k][$l], 0);
				$mLink[$i][$j][$k][$l] = getInfo($mAll[$i][$j][$k][$l], 1);
				$mClick[$i][$j][$k][$l] = getInfo($mAll[$i][$j][$k][$l], 2);
				$mMenu[$i][$j][$k][$l] = "<a href='" . $mLink[$i][$j][$k][$l] . "'" . $mClick[$i][$j][$k][$l] . ">" . $mTitle[$i][$j][$k][$l] . "</a>";
				$mAnchor[$i][$j][$k][$l] = "href='" . $mLink[$i][$j][$k][$l] . "'" . $mClick[$i][$j][$k][$l];
//				echo $mMenu[$i][$j][$k][$l] ."<br />";
			}
		}
	}
}

$d1menu = "";
$d2menu = "";
$d3menu = "";
$d4menu = "";

if($d1n!=0) $d1menu = $mTitle[$d1n][0][0][0];
if($d2n!=0) $d2menu = $mTitle[$d1n][$d2n][0][0];
if($d3n!=0) $d3menu = $mTitle[$d1n][$d2n][$d3n][0];
if($d4n!=0) $d4menu = $mTitle[$d1n][$d2n][$d3n][$d4n];

//title태그내용
$titleTag = $siteName;
if($d1menu && $d1menu!="") $titleTag = $titleTag . " - " . $d1menu;
if($d2menu && $d2menu!="") $titleTag = $titleTag . " - " . $d2menu;
if($d3menu && $d3menu!="") $titleTag = $titleTag . " - " . $d3menu;
if($d4menu && $d4menu!="") $titleTag = $titleTag . " - " . $d4menu;

//현재위치
$locationLink = "<a href='" . $mLink[0][0][0][0] . "'" . $mClick[0][0][0][0] . " class='home' title='홈'>" . $mTitle[0][0][0][0] . "</a>";
if($d1menu && $d1menu!="") $locationLink = $locationLink . " <span>&gt;</span> " . $mMenu[$d1n][0][0][0];
if($d2menu && $d2menu!="") $locationLink = $locationLink . " <span>&gt;</span> " . $mMenu[$d1n][$d2n][0][0];
if($d3menu && $d3menu!="") $locationLink = $locationLink . " <span>&gt;</span> " . $mMenu[$d1n][$d2n][$d3n][0];
if($d4menu && $d4menu!="") $locationLink = $locationLink . " <span>&gt;</span> " . $mMenu[$d1n][$d2n][$d3n][$d4n];

//본문제목이미지
$d1nn = ""; if($d1n<10) $d1nn="0" . $d1n; else $d1nn = $d1n;
$d2nn = ""; if($d2n<10) $d2nn="0" . $d2n; else $d2nn = $d2n;
$d3nn = ""; if($d3n<10) $d3nn="0" . $d3n; else $d3nn = $d3n;
$d4nn = ""; if($d4n<10) $d4nn="0" . $d4n; else $d4nn = $d4n;

$titleImgSrc = "/img/inc/h/h";
if($d1n!=0) $titleImgSrc = $titleImgSrc . $d1nn;
if($d2n!=0) $titleImgSrc = $titleImgSrc . "_" . $d2nn;
if($d3n!=0) $titleImgSrc = $titleImgSrc . "_" . $d3nn;
if($d4n!=0) $titleImgSrc = $titleImgSrc . "_" . $d4nn;
$titleImgSrc = $titleImgSrc . ".gif";

$titleImgAlt="";
if($d4menu && $d4menu!="") $titleImgAlt = $d4menu;
else if ($d3menu && $d3menu!="") $titleImgAlt = $d3menu;
else if ($d2menu && $d2menu!="") $titleImgAlt = $d2menu;
else $titleImgAlt = $d1menu;

$titleImg = "";
$titleImg = "<img src='" . $titleImgSrc . "' alt='" . $titleImgAlt . "' />";
?>