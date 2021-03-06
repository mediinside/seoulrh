<?

//**********************************************************************
// function 초기화
//**********************************************************************

//** 중복 포함 체크
Dim LibFunctionBaseIncluded
If LibFunctionBaseIncluded = True Then
	Response.Clear
	Response.Write "function.base.asp 파일이 두번 포함되었습니다."
	Response.End
End if
LibFunctionBaseIncluded = True



//**********************************************************************
// 문자열 처리 관련
//**********************************************************************


	//** 문자열 출력 프로시저
	Public Sub Print(ByVal value)
		//** 비주얼베이직에서의 Print문 사용
		Response.Write value
		Response.End
	End Sub

	//** 문자 공백 또는 Null 체크
	Public Function chkNull(value)
		If value = "" OR isNull(value) OR IsEmpty(value) Then chkNull = True Else chkNull = False
	End Function

	//** 정규식을 이용하여 문자열을 치환하는 함수
	Public Function eregi_replace(ByVal pattern, ByVal replacestr, ByVal text)
		Dim eregObj

		//** Create regular expression
		Set eregObj = New RegExp

		eregObj.Pattern = pattern //** Set Pattern(패턴 설정)
		eregObj.IgnoreCase = True //** Set Case Insensitivity(대소문자 구분 여부)
		eregObj.Global = True //** Set All Replace(전체 문서에서 검색)

		eregi_replace = eregObj.Replace(text, replacestr) //** Replace String
	End Function

	//** 정규식을 이용하여 문자열을 검색하는 함수(대소문자 구분 없음)
	Function eregi(ptrn, str)
		Dim eregEx, retVal
		Set eregEx = New RegExp
		eregEx.Pattern = ptrn
		eregEx.IgnoreCase = True

		retVal = eregEx.Test(str)

		If retVal Then
			eregi = True
		Else
			eregi = False
		End If
	End Function

	//** 정규식을 이용하여 문자열을 검색하는 함수(대소문자 구분)
	Function ereg(ptrn, str)
		Dim eregEx, retVal
		Set eregEx = New RegExp
		eregEx.Pattern = ptrn
		eregEx.IgnoreCase = False

		retVal = eregEx.Test(str)

		If retVal Then
			ereg = True
		Else
			ereg = False
		End If

		//** Usage
		//"[^가-힣]" //** 한글만
		//"[^-0-9 ]" //** 숫자만
		//"[^-a-zA-Z]" //** 알파벳만
		//"[^-가-힣a-zA-Z0-9/ ]" //** 숫자 알파벳 한글만
		//"<[^>]*>" //** <>태그만
		//"[^-a-zA-Z0-9/ ]" //** 영어 숫자만
	End Function

	//** <>태그를 벗겨내는 함수
	Function StripTags(ByVal htmlDoc)
		Set rex = New Regexp
		rex.Pattern= "<[^>]+>"
		rex.Global = True
		StripTags =rex.Replace(htmlDoc,"")
	End Function

	//** 문자열을 지정한 길이로 잘라내는 함수
	Public Function CutString(ByVal str, ByVal strlen)
		Dim rValue
		Dim nLength

		nLength = 0.00
		rValue = ""

		If Not str = "" Then
		For f=1 To Len(str) Step 1
			tmpStr = MID(str,f,1)
			tmpLen = ASC(tmpStr)
			If tmpLen < 0 Then
				//** 한글
				nLength = nLength + 1.4 //** 한글일때 길이값 설정
				rValue = rValue & tmpStr
			ElseIf tmpLen >= 97 And tmpLen <= 122 Then
				//** 영문 소문자
				nLength = nLength + 0.75 //** 영문소문자 길이값 설정
				rValue = rValue & tmpStr
			ElseIf tmpLen >= 65 And tmpLen <= 90 Then
				//** 영문 대문자
				nLength = nLength + 1 //** 영문대문자 길이값 설정
				rValue = rValue & tmpStr
			Else
				//** 그외 키값
				nLength = nLength + 0.6 //** 특수문자 기호값...
				rValue = rValue & tmpStr
			End If

			If nLength > strlen Then
				rValue = rValue & "..."
				Exit For
			End If
		Next
		End If

		CutString = rValue
	End Function

	//** 한글 1byte를 알파벳 2byte로 글자수를 늘리는 함수
	Public Function StrLenReturn(ByVal str)
		Dim i, strLen, tmpStr
		strLen = 0

		For i=1 To Len(str)
			tmpStr = Int(Asc(Mid(str,i,1)))
			If tmpStr < 0 Then //** 한글
				strLen = strLen + 2
			ElseIf tmpStr >= 33 And tmpStr <= 126 Then //** 아스키
				strLen = strLen + 1
			Else //** 그외 문자
				strLen = strLen + 2
			End If
		Next

		StrLenReturn = strLen
	End Function

	//** 영문/한글 문자열 길이를 같아보이게 줄이는 함수
	Public Function StrAbridge(ByVal str, ByVal MaxLength)
		If InStr(1, str, "<") Then
			strArrayTag = Split(str, "<")
			strArrayTlt = strArrayTag
			strArrayTag(0) = ""

			For i = 1 To UBound(strArrayTag)
				If Len(strArrayTag(i)) > 0 Then
					pos = InStr(1, strArrayTag(i), ">")
					If pos > 0 Then
						strArrayTag(i) = "<" & Left(strArrayTag(i), pos)
						strArrayTlt(i) = Right(strArrayTlt(i), Len(strArrayTlt(i)) - pos)
					Else
						strArrayTag(i) = "<" & strArrayTag(i)
						strArrayTlt(i) = ""
					End If
				End If
			Next

			For i = 0 To UBound(strArrayTlt)
				LenCount = 0
				For j = 1 To Len(strArrayTlt(i))
					If Asc(Mid(strArrayTlt(i),j,1)) < 0 Then
						LenCount = LenCount + 2
					Else
						LenCount = LenCount + 1
					End If
				Next

				TltCount = TltCount + LenCount

				If MaxLength < TltCount Then
					Overflow = TltCount - MaxLength
					If Overflow > 0 Then
						LenCount = 0
						For j = Len(strArrayTlt(i)) To 1 Step -1
							If Asc(Mid(strArrayTlt(i),j,1)) < 0 Then
								LenCount = LenCount + 2
							Else
								LenCount = LenCount + 1
							End If
							If Overflow < LenCount Then Exit For
						Next
						strArrayTlt(i) = Left(strArrayTlt(i), j)
					Else
						strArrayTlt(i) = ""
					End If
				End If
			Next

			For i = 0 To UBound(strArrayTag)
				strAbridge = strAbridge & strArrayTag(i) & strArrayTlt(i)
			Next

			If strAbridge <> str Then strAbridge = strAbridge & ".."
		Else
			LenCount = 0

			For j = 1 To Len(str)
				If Asc(Mid(str,j,1)) < 0 Then
					LenCount = LenCount + 2
				Else
					LenCount = LenCount + 1
				End If
				If MaxLength < LenCount Then Exit For
			Next

			If j < Len(str) Then
				strAbridge = Left(str,j) & ".."
			Else
				strAbridge = str
			End If
		End If
	End Function


	//** HTML 실행 방지 함수
	Public Function SpecialHtmlChars(ByVal str, ByVal dtype, ByVal tags)
		Dim contents: contents = str
		Dim doctype: doctype = LCase(dtype)
		Dim allowTagList: allowTagList = Replace(tags,",","|")

		Select Case doctype
			Case "text"
				//contents = Replace(contents,"&","&amp;") //** 유니코드 깨짐현상으로 사용안함
				contents = Replace(contents,"#","&#35;")
				contents = Replace(contents,"&","&#38;")
				contents = Replace(contents,"<","&lt;")
				contents = Replace(contents,">","&gt;")
				contents = Replace(contents,"  ","&nbsp; ")
				contents = Replace(contents,"	","&nbsp; &nbsp; ")
				contents = Replace(contents,Chr(13),"<br />") //** 줄바꿈을 <br />태그로 변환

			Case "normal"
				contents = Replace(contents,"<","&lt;")
				contents = Replace(contents,">","&gt;")
				contents = Replace(contents,"  ","&nbsp; ")
				contents = Replace(contents,"	","&nbsp; &nbsp; ")
				contents = Replace(contents,Chr(13),"<br />") //** 줄바꿈을 <br />태그로 변환

			Case "html"
				contents = eregi_replace("<(\/?)(?!\/|" & allowTagList & ")([^<>]*)?>", "&lt;$1$2&gt;", contents) //** 허용한 태그 이외의 모든 태그를 변환
				contents = eregi_replace("(javascript\:|vbscript\:)+","$1** ",contents) //** 클라이언트 스크립트 실행 방지
				contents = eregi_replace("(\.location|location\.|onload=|\.cookie|alert\(|window\.open\(|onmouse|onkey|onclick|view\-source\:)+", "** ", contents) //** 스크립트 이벤트 실행 방지
				contents = Replace(contents,"<" & "%","&lt;%") //** ASP태그 방지
				contents = Replace(contents,"%" & ">","&lt;%") //** ASP태그 방지

			Case "br"
				contents = eregi_replace("<(\/?)(?!\/|" & allowTagList & ")([^<>]*)?>", "&lt;$1$2&gt;", contents) //** 허용태그외의 모든 태그 실행 방지
				contents = eregi_replace("(javascript\:|vbscript\:)+","$1** ",contents) //** 클라이언트 스크립트 실행방지
				contents = eregi_replace("(\.location|location\.|onload=|\.cookie|alert\(|window\.open\(|onmouse|onkey|onclick|view\-source\:)+","** ",contents) //** 자바스크립트 실행방지
				contents = Replace(contents,"<" & "%","&lt;%") //** ASP태그 방지
				contents = Replace(contents,"%" & ">","&lt;%") //** ASP태그 방지

				contents = Replace(contents,Chr(13),"<br />") //** 줄바꿈을 <br />태그로 바꿈

			Case "none"
				contents = getTextString(contents) //** 모든 <>태그 실행 방지

			Case Else
				contents = eregi_replace("<(\/?)(?!\/|)([^<>]*)?>","&lt;$1$2&gt;", contents) //** 모든 <>태그 실행 방지
				contents = Replace(contents,Chr(13),"<br />") //** 줄바꿈을 <br />태그로 바꿈
		End Select

		SpecialHtmlChars = contents
	End Function

	//** 태그완전제거
	Function getTextString(str)
			Dim nLen
			Dim st
			Dim ed
			Dim ds
			Dim sf
			nLen        = Len(str)
			sf = str
			for i = 1 to nLen
					st = InStr(i,str,"<")
					ed = Instr(st+1,str,">")
					If st > 0 And ed > 0 Then
							ds = mid(str,st,(ed+1)-st)
							sf = Replace(sf,ds,"")
							i = ed
					End If
			next
			getTextString = sf
	End Function

	//** 랜덤 문자 출력 함수
	Public Function RndStr(ByRef Lenth)
		Dim RanNum
		Dim Chk
		Dim RanStr: RanStr = Null
		Dim i

		Randomize

		For i=1 To Lenth
			Do
				RanNum = Round(Rnd * 1000, 0)

				If RanNum>=48 And RanNum<=122 And (RanNum<=90 Or RanNum>=97) And (RanNum<=57 Or RanNum>=65) Then
					Exit Do
				End If
			Loop

			RanStr = RanStr & Chr(RanNum)
		Next

		RndStr = RanStr
	End Function

	//** 숫자를 문자열로 변환
	Const sBASE_64_CHARACTERS = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"
	Function Base64decode(ByVal asContents)
		Dim lsResult
		Dim lnPosition
		Dim lsGroup64, lsGroupBinary
		Dim Char1, Char2, Char3, Char4
		Dim Byte1, Byte2, Byte3
		If Len(asContents) Mod 4 > 0 Then asContents = asContents & String(4 - (Len(asContents) Mod 4), " ")
		lsResult = ""

		For lnPosition = 1 To Len(asContents) Step 4
		lsGroupBinary = ""
		lsGroup64 = Mid(asContents, lnPosition, 4)
		Char1 = INSTR(sBASE_64_CHARACTERS, Mid(lsGroup64, 1, 1)) - 1
		Char2 = INSTR(sBASE_64_CHARACTERS, Mid(lsGroup64, 2, 1)) - 1
		Char3 = INSTR(sBASE_64_CHARACTERS, Mid(lsGroup64, 3, 1)) - 1
		Char4 = INSTR(sBASE_64_CHARACTERS, Mid(lsGroup64, 4, 1)) - 1
		Byte1 = Chr(((Char2 And 48) \ 16) Or (Char1 * 4) And &HFF)
		Byte2 = lsGroupBinary & Chr(((Char3 And 60) \ 4) Or (Char2 * 16) And &HFF)
		Byte3 = Chr((((Char3 And 3) * 64) And &HFF) Or (Char4 And 63))
		lsGroupBinary = Byte1 & Byte2 & Byte3
		lsResult = lsResult + lsGroupBinary
		Next
		Base64decode = lsResult
	End Function

	Function Base64encode(ByVal asContents)
		Dim lnPosition
		Dim lsResult
		Dim Char1
		Dim Char2
		Dim Char3
		Dim Char4
		Dim Byte1
		Dim Byte2
		Dim Byte3
		Dim SaveBits1
		Dim SaveBits2
		Dim lsGroupBinary
		Dim lsGroup64

		If Len(asContents) Mod 3 > 0 Then asContents = asContents & String(3 - (Len(asContents) Mod 3), " ")
		lsResult = ""

		For lnPosition = 1 To Len(asContents) Step 3
		lsGroup64 = ""
		lsGroupBinary = Mid(asContents, lnPosition, 3)

		Byte1 = Asc(Mid(lsGroupBinary, 1, 1)): SaveBits1 = Byte1 And 3
		Byte2 = Asc(Mid(lsGroupBinary, 2, 1)): SaveBits2 = Byte2 And 15
		Byte3 = Asc(Mid(lsGroupBinary, 3, 1))

		Char1 = Mid(sBASE_64_CHARACTERS, ((Byte1 And 252) \ 4) + 1, 1)
		Char2 = Mid(sBASE_64_CHARACTERS, (((Byte2 And 240) \ 16) Or (SaveBits1 * 16) And &HFF) + 1, 1)
		Char3 = Mid(sBASE_64_CHARACTERS, (((Byte3 And 192) \ 64) Or (SaveBits2 * 4) And &HFF) + 1, 1)
		Char4 = Mid(sBASE_64_CHARACTERS, (Byte3 And 63) + 1, 1)
		lsGroup64 = Char1 & Char2 & Char3 & Char4
		lsResult = lsResult + lsGroup64
		Next

		Base64encode = lsResult
	End Function




//**********************************************************************
// 폼처리 관련
//**********************************************************************

	//** 주민등록번호 체크 함수
	Function chkSidNum(ByVal idsn1, ByVal idsn2)
		Dim total,key,idn

		total = 0
		key ="234567892345"
		idn = idsn1 & idsn2

		For z=1 To 12 Step 1
			total = total + Mid(idn,z,1) * Mid(key,z,1)
		Next
		total = 11 - (total Mod 11)

		If Right(idn,1)=Right(total,1) Then
			chkSidNum = True
		Else
			chkSidNum = False
		End If
	End Function


	//**  숫자 체크 함수
	Public Function ChkAvailableNum(ByVal num)
		If num="" Or Not IsNumeric(num) Then
			ChkAvailableNum = False
		Else
			ChkAvailableNum = True
		End If
	End Function


	//** 아이디 유효문자 체크 함수
	Public Function ChkAvailableChr(ByVal str)
		Dim chkrs: chkrs = True
		Dim i, cr

		For i=1 To Len(str)
			cr = Asc(Mid(str,i,1))
			If cr < 48 Then
				chkrs = False
			ElseIf cr > 122 Then
				chkrs = False
			ElseIf (cr > 90 And cr < 97) And cr <> 95 Then
				chkrs = False
			ElseIf cr > 57 And cr < 65 Then
				chkrs = False
			End If
		Next

		ChkAvailableChr = chkrs //** 유효 글자만 포함시 True, 그외에는 False를 반환
	End Function


	//** URL주소 유효 검사
	Public Function IsUrl(ByVal url)
		If Not eregi("(http|https|ftp|mms)(:** [^ \n\r<>""가-힣]+)", url) Then
			IsUrl = False
		Else
			IsUrl = True
		End If
	End Function


	//** 이메일주소 유효 검사
	Public Function IsEmail(ByVal email)
		If Not eregi("(^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)", email) Then
			IsEmail = False
		Else
			IsEmail = True
		End If
	End Function


	//** 핸드폰 유효 검사
	Public Function IsPhoneNumber(ByVal phone)
		If Not eregi("(\d{2,4}-\d{2,4}-\d{4})", phone) Then
			IsPhoneNumber = False
		Else
			IsPhoneNumber = True
		End If
	End Function


	//** 인젝션 공격성 문자 제거
	Public Function InjectionDefender(ByVal str)
		str = eregi_replace("'", "''", str)
		str = eregi_replace("""", "&quot;", str)
		str = eregi_replace(";", "", str)
		str = eregi_replace("--", "", str)
		str = eregi_replace("\@variable ", "", str)
		str = eregi_replace("\@@variable ", "", str)
		str = eregi_replace("\+", "", str)
		str = eregi_replace("print ", "", str)
		str = eregi_replace("set ", "", str)
		str = eregi_replace("\%", "", str)
		'str = eregi_replace("Or", "", str)
		'str = eregi_replace("And", "", str)
		str = eregi_replace("union ", "", str)
		str = eregi_replace("insert ", "", str)
		str = eregi_replace("select ", "", str)
		str = eregi_replace("update ", "", str)
		str = eregi_replace("create ", "", str)
		str = eregi_replace("drop ", "", str)
		str = eregi_replace("openrowset ", "", str)
		str = eregi_replace("exec ", "", str)

		InjectionDefender  = str
	End Function


	//** 따옴표 변환
	Public Function stripQuotationMarks (ByVal str)
		str = eregi_replace("'", "&#39;", str)
		str = eregi_replace("""", "&quot;", str)
		str = eregi_replace("--", "", str)
		str = eregi_replace("javascript", "", str)
		str = eregi_replace("script", "", str)
		stripQuotationMarks  = str
	End Function

	//** 따옴표 변환된것 복구
	Public Function stripQuotationMarksRev (ByVal str)
		str = eregi_replace("&#39;", "'", str)
		str = eregi_replace("&quot;", """", str)
		stripQuotationMarksRev  = str
	End Function

	//** 인젝션 공격성 문자 검사
	Public Function IjStr(ByVal str)
		If eregi("(select|delete|insert|update|drop|shutdown|exec|;|--|')", str) Then
			IjStr = True
		Else
			IjStr = False
		End If
	End Function

	//** Referer 유효성 검사
	Public Function AvailableUrl(ByVal url)
		If INSTR(Request("HTTP_REFERER"), url ) > 0 Then
			AvailableUrl = True
		Else
			AvailableUrl = False
		End If
	End Function

	//** Requestr값, 변수선언, Request스크립트
	Public Function RequestStringAll()
		Dim obj,objStringAll,objRequestSet : objStringAll = "<br>Dim " : objRequestSet = ""
		For Each obj In Request.Form
			Response.Write obj &" : "& Request.Form(obj) &"<br>"
			objStringAll	= objStringAll & obj & ","
			objRequestSet	= objRequestSet & obj &" = stripQuotationMarks(.Form("""& obj &"""))<br>"
		Next
		For Each obj In Request.QueryString
			Response.Write obj &" : "& Request.QueryString(obj) &"<br>"
			objStringAll	= objStringAll & obj & ","
			objRequestSet	= objRequestSet & obj &" = stripQuotationMarks(.QueryString("""& obj &"""))<br>"
		Next
		Response.Write objStringAll & "<br><br>" & objRequestSet
	End Function


//**********************************************************************
// 이메일관련 함수
//**********************************************************************

	Function Email_Send(emailSendName, emailSendMail, emailGetName, emailGetMail, emailSubject, emailBody, fileName, ccMail)

		Const CdoSMTPPort = 25

		strFrom = emailSendMail
		If Not IsNull(emailSendName) And emailSendName <> "" Then strFrom = "<"& strFrom &">"& emailSendName
		strTo = emailGetMail
		If Not IsNull(emailGetName) And emailGetName <> "" Then strTo = "<"& strTo &">"& emailGetName

		If Request.ServerVariables("SERVER_SOFTWARE") = "Microsoft-IIS/6.0" Then
			Set objMessage = Server.CreateObject("CDO.Message")
			Set objConfig = objMessage.Configuration

			// Setting the SMTP Server
			With objConfig.Fields
				.Item("http://schemas.microsoft.com/cdo/configuration/sendusing") = 1 ' 1: 로컬(SMTP), 2 : 외부(SMTP)
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = CdoSMTPPort
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpserver") = "127.0.0.1"
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverpickupdirectory") = "c:\Inetpub\mailroot\Pickup" ' Pickup 디렉토리 설정
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = 30 ' 연결 시간
				.Update
			End With

			With objMessage
				.To			= strTo
				.From		= strFrom
				.Subject	= emailSubject
				.HTMLBody	= emailBody
				.BodyPart.Charset = "euc-kr"
				.HTMLBodyPart.Charset = "euc-kr"
				.Send
			End With

			Set objConfig = Nothing
			Set objMessage = Nothing

		Else 'Microsoft-IIS/5.0
			Set objMail = Server.CreateObject("CDONTS.NewMail")

			With objMail
				.To				= strTo
				.From			= strFrom
				.Subject		= emailSubject
				.BodyFormat		= 0
				.MailFormat		= 0
				.Body			= emailBody
				.Send
			End With

			Set objMail = Nothing

		End If

	End Function


	Sub PageListNum(ByVal page,ByVal blockPage,ByVal totalPage,ByVal search,ByVal inBgcolorOut,ByVal inBgcolorOver)

		Dim intTemp : intTemp = Int((page - 1) / blockPage) * blockPage + 1

		If intTemp < blockPage Then
			 Response.Write "<a href='#' class='direction prev'><span></span><span></span> 처음</a>"
		Else
			 Response.Write "<a href='?page=1"& search &"' class='direction prev'><span></span><span></span> 처음</a>"
		End If

		If intTemp = 1 Then
			 Response.Write "<a href='#' class='direction prev'><span></span> 이전</a>"
		Else
			 Response.Write "<a href='?page="& intTemp - 1 & search &"' class='direction prev'><span></span> 이전</a>"
		End If


		Dim intLoop : intLoop = 1

		Do Until intLoop > blockPage Or intTemp > totalPage
			 If intTemp = CInt(page) Then
				  Response.Write "<strong>" & intTemp &"</strong>"
			 Else
				  Response.Write "<a href='?page="& intTemp & search &"'>" & intTemp & "</a>"
			 End If
			 intTemp = intTemp + 1
			 intLoop = intLoop + 1
		Loop

		If intTemp > totalPage Then
			 Response.Write "<a href='#' class='direction next'>다음 <span></span></a>"
		Else
			 Response.Write "<a href='?page="& intTemp & search &"' class='direction next'>다음 <span></span></a>"
		End If

		If intTemp > totalPage Then
			 Response.Write "<a href='#' class='direction next'>끝 <span></span><span></span></a>"
		Else
			 Response.Write "<a href='?page="& totalPage & search &"' class='direction next'>끝 <span></span><span></span></a>"
		End If

	End Sub




	Function levString(lv)

		Select Case lv
			Case "2"
				levString = "플래티늄"
			Case "3"
				levString = "골드"
			Case "4"
				levString = "실버"
			Case "5"
				levString = "제휴"
			Case "6"
				levString = "법인"
			Case "7"
				levString = "가족"
			Case Else
				levString = "일반"
		End Select

	End Function



	Function poString(cd)

		Select Case cd
			Case "AA00"
				poString = "중앙지부"
			Case "AA01"
				poString = "서초지부"
			Case "AA02"
				poString = "석성특별지부"
			Case Else
				poString = ""
		End Select

	End Function
?>