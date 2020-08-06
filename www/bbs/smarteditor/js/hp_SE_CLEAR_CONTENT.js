var ck = 0;
nhn.husky.SE_CLEAR_CONTENT = jindo.$Class({ 
       name: "SE_LineCheck", 

       $init : function(){},        

		$ON_MSG_APP_READY : function(){ 
			//this.oApp.exec("EVENT_EDITING_AREA_CLICK"); 
		}, 
				
		$ON_EVENT_EDITING_AREA_CLICK : function(e){ 
				console.log(e);
				var sHTML = this.oApp.getIR();
				console.log(sHTML);
				 if(sHTML == "고객의 소리에 등록하신 글은 마이페이지에서 확인가능합니다.<br>") {
					this.oApp.exec("SET_CONTENTS", [""]);
				}
				ck++;

		} 
}); 