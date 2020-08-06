$(document).ready(function() {									
		$('#face_content').rssfeed('https://www.facebook.com/feeds/page.php?format=rss20&id=457925320931754', {												
			limit: 3,
			dateformat : "date"
		});
		
		/*
		$('#blog_content').rssfeed('http://hyundaehosp.blog.me/rss', {												
			limit: 1,
			dateformat : "date"
		});
		*/
		//fn_twitterTimeline();
});

function fn_twitterTimeline(){		
	var twitterPrm = {
		api: 'https://api.twitter.com/1.1/statuses/user_timeline.json',
		count: 1, //불러올 타임라인 수
		callback: "fn_makedocTwitter",//함수를 호출하여JSON데이터를 넘겨줍니다.
		//트위터개발자페이지에서 생성한 자신의 어플리케이션 정보를 입력하시기 바랍니다.
		consumerKey: "x4wrUpwjNcmPEm6oNmKaXQ",  
		consumerSecret: "azFHixBXdwyiMdAyr2z4GbUMfyqL8WVw8Dmf88KWu0",
		accessToken: "171406625-vGQGxrKsqoUqLeLjjJ6XGbUfmnioiEGoJbzEcL4x",
		tokenSecret: "Jp7sZuMOWP1B5UqTL1CxdigEcmoEWhnZ2Nzg259ig"	
	}

	var oauthMessage ={
		method: "GET",
		action: twitterPrm.api,
		parameters: {
			count: twitterPrm.count,
			callback: twitterPrm.callback,
			oauth_version: "1.0",
			oauth_signature_method: "HMAC-SHA1",
			oauth_consumer_key: twitterPrm.consumerKey,
			oauth_token: twitterPrm.accessToken
		}
	};
	//Outh인증관련
	OAuth.setTimestampAndNonce(oauthMessage);
	OAuth.SignatureMethod.sign(oauthMessage, {
		consumerSecret: twitterPrm.consumerSecret,
		tokenSecret: twitterPrm.tokenSecret
	});
	//Outh인증끝

	//Outh인증하여 URL리턴(jsonType)
	var twJsonPath = OAuth.addToURL(oauthMessage.action, oauthMessage.parameters);
	//alert(twJsonPath);
	$.ajax({
		type: oauthMessage.method,
		url: twJsonPath,
		dataType: "jsonp",
		jsonp: false,
		cache: true
	});
}

function fn_makedocTwitter(data){
	var timeLine='';
	var text;
	var date;
	var url;
	for(var i=0, len=data.length;i<len;i++){	
		date = data[i].created_at;				
		text = data[i].text;	
		
		var arr_tmp ="";
		var month = "";
		var day = "";
		var year = "";
		
		arr_tmp = date.split(' ');				
		month = _getMonthName(arr_tmp[1]);
		day = arr_tmp[2];
		year = arr_tmp[5];
		
		url = text.match(/(s?https?:\/\/[-_.!~*'()a-zA-Z0-9;\/?:@&=+$,%#]+)/gi,'<a href="$1" title="Permalink to this product" target="_blank">$1</a>');
		text = text.replace(/(s?https?:\/\/[-_.!~*'()a-zA-Z0-9;\/?:@&=+$,%#]+)/gi,'');				
		text = text.substr(0, 70) + "...";
		
		timeLine += "<a href='" + url +"' target='_blank'><strong>" + text +"</strong>" + year +"년 " + month +"월 " + day + "일</a>";
	}
	
	$('#tw_content').html(timeLine);
}

var _getMonthName = function(month) {			
	var num = "";
	switch (month) {
		case "Jan"  : num = "01";  break;
		case "Feb"  : num = "02";  break;
		case "Mar"  : num = "03";  break;
		case "Apr"  : num = "04";  break;
		case "May"  : num = "05";  break;
		case "Jun"  : num = "06";  break;
		case "Jul"  : num = "07";  break;
		case "Aug"  : num = "08";  break;
		case "Sep"  : num = "09";  break;
		case "Oct"  : num = "10";  break;
		case "Nov"  : num = "11";  break;
		case "Dec"  : num ="12";  break;
	}						
	return num;
}