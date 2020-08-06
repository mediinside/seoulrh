<?php
	include_once("../../_init.php");
	include_once($GP -> INC_ADM_PATH."/head.php");	
	include_once($GP -> CLS."/class.reserve.php");
	$C_Reserve 	= new Reserve;

	
	$args = "";
	$args['idx'] 	= $_GET['idx'];
	$rst = $C_Reserve ->Reserve_Detail_Old($args);
	
	if($rst) {
		extract($rst);		
	}
?>
<body>
<div class="Wrap_layer"><!--// 전체를 감싸는 Wrap -->
	<div class="boxContent_layer">
		<div class="boxContentHeader">
			<span class="boxTopNav"><strong>예약 정보</strong></span>
		</div>
		<form name="base_form" id="base_form" method="POST" action="?" enctype="multipart/form-data">
		<input type="hidden" name="mode" id="mode" value="RS_MODI_OLD" />
		<input type="hidden" name="idx" id="idx" value="<?=$_GET['idx']?>" />
		<div class="boxContentBody">			
			<div class="boxMemberInfoTable_layer">
      	<div class="layerTable">				
          <table class="table table-bordered">
            <tbody>
              <tr>
                <th width="15%"><span>*</span>성명</th>
                <td width="85%">
                  <?=$MemName?>(<?=$MemID?>) 
                </td>
              </tr>                    
              <tr>
                <th><span>*</span>생년월일</th>
                <td>
                  <?=$BirthDate?>
                </td>
              </tr>	            
              <tr>
                <th><span>*</span>성별</th>
                <td>
                  <?=($MemMF == "Male") ? "남자":"여자";?>
                </td>
              </tr>	
              <tr>            
              <tr>
                <th><span>*</span>자택번호</th>
                <td>							
                  <?=$Phone?>
                </td>
              </tr>	            						
              <tr>
                <th><span>*</span>휴대폰번호</th>
                <td>
                  <?=$Mobile?>
                </td>
              </tr>	
              <tr>
                <th><span>*</span>이메일</th>
                <td>
                  <?=$Email?>
                </td>
              </tr>		
              <tr>
                <th><span>*</span>주소</th>
                <td>
                  [<?=$Zipcode?>] <?=$Addr1?> <?=$Addr2?>
                </td>
              </tr>
              <tr>
                <th><span>*</span>진료과</th>
                <td>
                  <?=$gr_name?>
                </td>
              </tr>
              <tr>
                <th><span>*</span>진료의사</th>
                <td>
                  <?=$doctorName?>
                </td>
              </tr>
              <tr>
                <th><span>*</span>신청일</th>
                <td>
                  <?=$aDate?>
                </td>
              </tr>		
              <tr>
                <th><span>*</span>예약날짜</th>
                <td>
                  <?=$Possible_Date?>
                </td>
              </tr>		
              <tr>
                <th><span>*</span>예약시간</th>
                <td>
                  <?=$Possible_Time?>
                </td>
              </tr>	
              <tr>
                <th><span>*</span>증상 및 내용</th>
                <td>
                  <?=$Reservation_Contents?>
                </td>
              </tr>	
               <tr>
                <th><span>*</span>예약상태</th>
                <td>
                	<select name="Reservation_Status" id="Reservation_Status">
                  	<option value="">:::선택:::</option>
                    <option value="1" <? if($Reservation_Status == "1"){ echo "selected";}?> >예약대기</option>
                    <option value="2" <? if($Reservation_Status == "2"){ echo "selected";}?> >예약완료</option>
                  </select>
                </td>
              </tr>											
            </tbody>
          </table>		
        </div>		
				<div class="btnWrap">
				<button id="img_submit" class="btnSearch ">수정</button>
				<button id="img_cancel" class="btnSearch " onClick="javascript:parent.modalclose();">취소</button>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.alphanumeric.js"></script>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.validate.js"></script>
<script type="text/javascript" src="<?=$GP -> JS_PATH?>/jquery.base64.js"></script>
<script type="text/javascript">

	$(document).ready(function(){							
		
		
		$('#img_submit').click(function(){
				
				if($('#Reservation_Status option:selected').val() == '') {
					alert('예약상태를 선택하세요');
					return false;
				}							
				
				$('#base_form').attr('action','./proc/reserve_proc.php');
				$('#base_form').submit();
				return false;							
		});
	});
</script>
