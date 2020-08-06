<?
	include_once  '../_init.php';
	include_once($GP -> CLS."/class.reserve.php");	
  include_once($GP -> CLS."/class.doctor.php");
  $C_Reserve 	= new Reserve;
	$C_Doctor 	= new Doctor;
  
   if($_GET['date']!= '') {
		$date = $_GET['date'];
	}else{
		$date = date('Y-m-d');
	}	  
   
  $now_day = date('Y-m-d');
  
  $dr_idx = $_GET['dr_idx'];	
  
  $x=explode("-",$date); // 들어온 날짜를 년,월,일로 분할해 변수로 저장합니다.
  $s_Y=$x[0]; // 지정된 년도
  $s_m=$x[1]; // 지정된 월
  $s_d=$x[2]; // 지정된 요일

  $s_t=date("t",mktime(0,0,0,$s_m,$s_d,$s_Y)); // 지정된 달은 몇일까지 있을까요?
  $s_n=date("N",mktime(0,0,0,$s_m,1,$s_Y)); // 지정된 달의 첫날은 무슨요일일까요?
  $l=$s_n%7; // 지정된 달 1일 앞의 공백 숫자.
  $ra=($s_t+$l)/7; $ra=ceil($ra); $ra=$ra-1; // 지정된 달은 총 몇주로 라인을 그어야 하나?

  $n_d= date("Y-m-d",mktime(0,0,0,$s_m,$s_d+1,$s_Y)); // 다음날
  $p_d= date("Y-m-d",mktime(0,0,0,$s_m,$s_d-1,$s_Y)); // 이전날
  $n_Y= date("Y-m-d",mktime(0,0,0,$s_m,$s_d,$s_Y+1)); // 내년
  $p_Y= date("Y-m-d",mktime(0,0,0,$s_m,$s_d,$s_Y-1)); // 작년
  $p_m= date("Y-m-d",mktime(0,0,0,$s_m-1,$s_d,$s_Y)); // 저번달
  $n_m= date("Y-m-d",mktime(0,0,0,$s_m+1,$s_d,$s_Y)); // 다음달
  
  
  //휴무일 배열 만들기 월별
  $args = '';
  $args['th_date'] = substr($date, 0, 7);
  $Holiday_data = $C_Reserve->HOliday_RS_LIST($args);
  
  $holi_arr = array();  
  if(is_array($Holiday_data) ) {
  	for($k=0; $k<count($Holiday_data); $k++) {
    	$holi_arr[$k] = $Holiday_data[$k]['th_date'];
    }
  }  
  
  $one_day = $C_Func->request_add_day($now_day , 1);	//오늘 + 1일
  $ck_week = $C_Func->DayWeekChange($now_day);  			//오늘의 주일
  
  
?>
<script type="text/javascript">	
	function ch_cal(date, dr_idx) {
		location.href = "?date=" + date + "&dr_idx=" + dr_idx;
		return false;
	}
	
	function day_list(dr_idx, date, obj) {		
	
		if(dr_idx == '') {
			alert("의료진을 먼저 선택하세요");
			return false;
		}
		
		$('#d_time').attr('src','d_time_list.php?dr_idx=' + dr_idx + '&cp_date=' + date);
		
		$('.calBody td').removeClass('cho');			
		addClass(obj.parentNode, 'cho');
		return false;
	}
	
	function addClass(element, className) {		
			element.className += " " + className;
	};

</script>
<div class="calWrap">
  <div class="calHead">
    <button type="button" class="prev" onClick="ch_cal('<?=$p_m?>','<?=$dr_idx?>')"><span>이전달</span></button>
    <strong><?=$s_Y?>년 <?=$s_m?>월</strong>
    <button type="button" class="next" onClick="ch_cal('<?=$n_m?>','<?=$dr_idx?>')"><span>다음달</span></button>
  </div>
  <div class="calBody">
    <table>
      <caption>진료 일정 선택 달력</caption>
      <colgroup>
        <col style="width:14%" />
        <col style="width:14%" />
        <col style="width:14%" />
        <col style="width:14%" />
        <col style="width:14%" />
        <col style="width:14%" />
        <col style="width:14%" />
      </colgroup>
      <thead>
        <tr>
          <th scope="col" class="sun">일</th>
          <th scope="col">월</th>
          <th scope="col">화</th>
          <th scope="col">수</th>
          <th scope="col">목</th>
          <th scope="col">금</th>
          <th scope="col">토</th>
        </tr>
      </thead>
      <tbody>
      <?
      for($r=0;$r<=$ra;$r++){
        echo "<tr>\n";
          for($z=1;$z<=7;$z++){									
              $rv=7*$r+$z; $ru=$rv-$l; // 칸에 번호를 매겨줍니다. 1일이 되기전 공백들 부터 마이너스 값으로 채운 뒤 ~
              
              if($ru<=0 || $ru>$s_t) { // 딱 그달에 맞는 숫자가 아님 표시하지 말자
                echo "<td>&nbsp;</td>";											
              }else{
                $s = date("Y-m-d",mktime(0,0,0,$s_m,$ru,$s_Y)); // 현재칸의 날짜
    
                $dayweek = $C_Func->DayWeekChange($s);                    
                $now_mon = date("m");                              
                $ch_mday = $s_m.$s_d;
                
                $ru_d = "";
                if(strlen($ru) < 2) {
                  $ru_d = "0".$ru;
                }else{
                  $ru_d = $ru;
                }
                $ch_mday1 = $now_mon.$ru_d;		                    
                
                $cho_date = $s_Y. "-" . $s_m . "-" . $ru_d; 
                
                //일정가져오기
                $args = '';
                $args['dr_idx'] = $dr_idx;
                $args['cho_date'] = $cho_date;
                $data = $C_Reserve->Doctor_Schedule_Chk($args);								
								
                if($data['cnt'] > 0) {									
									if($ch_mday == $ch_mday1) {
										echo "<td class='today' title=''><button type='button' onClick=\"day_list('" . $dr_idx. "','" . $cho_date. "', this)\">$ru</button></td>";	
									}else if($dayweek == "일") { //일요일은 방문접수만
										echo "<td class='sun' title=''><button type='button' onClick=\"day_list('" . $dr_idx. "','" . $cho_date. "', this)\">$ru</button></td>";	
									}else {
										echo "<td class='on' title=''><button type='button' onClick=\"day_list('" . $dr_idx. "','" . $cho_date. "', this)\">$ru</button></td>";	
									}
                		
                }else{
                 echo "<td title='일정없음'><button type='button' onClick=\"day_list('" . $dr_idx. "','" . $cho_date. "', this)\">$ru</button></td>";
                }				
              } //end if													
          }
          echo "</tr>\n";
          }
        ?>
      </tbody>
    </table>
  </div>
  <p class="calInfo">
		<span><img src="/admin/images/icon_cho.gif" alt="" /> 선택</span>
		<span><img src="/admin/images/icon_on.gif" alt="" /> 진료가능날짜</span>
	</p>
	<p>* 진료가능 날짜를 선택하시려면 해당 날짜를 클릭하세요.</p>
</div>
