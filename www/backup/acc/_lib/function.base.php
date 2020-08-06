<?

//**********************************************************************
// function �ʱ�ȭ
//**********************************************************************

//** �ߺ� ���� üũ
Dim LibFunctionBaseIncluded
If LibFunctionBaseIncluded = True Then
	Response.Clear
	Response.Write "function.base.asp ������ �ι� ���ԵǾ����ϴ�."
	Response.End
End if
LibFunctionBaseIncluded = True



//**********************************************************************
// ���ڿ� ó�� ����
//**********************************************************************


	//** ���ڿ� ��� ���ν���
	Public Sub Print(ByVal value)
		//** ���־����������� Print�� ���
		Response.Write value
		Response.End
	End Sub

	//** ���� ���� �Ǵ� Null üũ
	Public Function chkNull(value)
		If value = "" OR isNull(value) OR IsEmpty(value) Then chkNull = True Else chkNull = False
	End Function

	//** ���Խ��� �̿��Ͽ� ���ڿ��� ġȯ�ϴ� �Լ�
	Public Function eregi_replace(ByVal pattern, ByVal replacestr, ByVal text)
		Dim eregObj

		//** Create regular expression
		Set eregObj = New RegExp

		eregObj.Pattern = pattern //** Set Pattern(���� ����)
		eregObj.IgnoreCase = True //** Set Case Insensitivity(��ҹ��� ���� ����)
		eregObj.Global = True //** Set All Replace(��ü �������� �˻�)

		eregi_replace = eregObj.Replace(text, replacestr) //** Replace String
	End Function

	//** ���Խ��� �̿��Ͽ� ���ڿ��� �˻��ϴ� �Լ�(��ҹ��� ���� ����)
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

	//** ���Խ��� �̿��Ͽ� ���ڿ��� �˻��ϴ� �Լ�(��ҹ��� ����)
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
		//"[^��-�R]" //** �ѱ۸�
		//"[^-0-9 ]" //** ���ڸ�
		//"[^-a-zA-Z]" //** ���ĺ���
		//"[^-��-�Ra-zA-Z0-9/ ]" //** ���� ���ĺ� �ѱ۸�
		//"<[^>]*>" //** <>�±׸�
		//"[^-a-zA-Z0-9/ ]" //** ���� ���ڸ�
	End Function

	//** <>�±׸� ���ܳ��� �Լ�
	Function StripTags(ByVal htmlDoc)
		Set rex = New Regexp
		rex.Pattern= "<[^>]+>"
		rex.Global = True
		StripTags =rex.Replace(htmlDoc,"")
	End Function

	//** ���ڿ��� ������ ���̷� �߶󳻴� �Լ�
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
				//** �ѱ�
				nLength = nLength + 1.4 //** �ѱ��϶� ���̰� ����
				rValue = rValue & tmpStr
			ElseIf tmpLen >= 97 And tmpLen <= 122 Then
				//** ���� �ҹ���
				nLength = nLength + 0.75 //** �����ҹ��� ���̰� ����
				rValue = rValue & tmpStr
			ElseIf tmpLen >= 65 And tmpLen <= 90 Then
				//** ���� �빮��
				nLength = nLength + 1 //** �����빮�� ���̰� ����
				rValue = rValue & tmpStr
			Else
				//** �׿� Ű��
				nLength = nLength + 0.6 //** Ư������ ��ȣ��...
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

	//** �ѱ� 1byte�� ���ĺ� 2byte�� ���ڼ��� �ø��� �Լ�
	Public Function StrLenReturn(ByVal str)
		Dim i, strLen, tmpStr
		strLen = 0

		For i=1 To Len(str)
			tmpStr = Int(Asc(Mid(str,i,1)))
			If tmpStr < 0 Then //** �ѱ�
				strLen = strLen + 2
			ElseIf tmpStr >= 33 And tmpStr <= 126 Then //** �ƽ�Ű
				strLen = strLen + 1
			Else //** �׿� ����
				strLen = strLen + 2
			End If
		Next

		StrLenReturn = strLen
	End Function

	//** ����/�ѱ� ���ڿ� ���̸� ���ƺ��̰� ���̴� �Լ�
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


	//** HTML ���� ���� �Լ�
	Public Function SpecialHtmlChars(ByVal str, ByVal dtype, ByVal tags)
		Dim contents: contents = str
		Dim doctype: doctype = LCase(dtype)
		Dim allowTagList: allowTagList = Replace(tags,",","|")

		Select Case doctype
			Case "text"
				//contents = Replace(contents,"&","&amp;") //** �����ڵ� ������������ ������
				contents = Replace(contents,"#","&#35;")
				contents = Replace(contents,"&","&#38;")
				contents = Replace(contents,"<","&lt;")
				contents = Replace(contents,">","&gt;")
				contents = Replace(contents,"  ","&nbsp; ")
				contents = Replace(contents,"	","&nbsp; &nbsp; ")
				contents = Replace(contents,Chr(13),"<br />") //** �ٹٲ��� <br />�±׷� ��ȯ

			Case "normal"
				contents = Replace(contents,"<","&lt;")
				contents = Replace(contents,">","&gt;")
				contents = Replace(contents,"  ","&nbsp; ")
				contents = Replace(contents,"	","&nbsp; &nbsp; ")
				contents = Replace(contents,Chr(13),"<br />") //** �ٹٲ��� <br />�±׷� ��ȯ

			Case "html"
				contents = eregi_replace("<(\/?)(?!\/|" & allowTagList & ")([^<>]*)?>", "&lt;$1$2&gt;", contents) //** ����� �±� �̿��� ��� �±׸� ��ȯ
				contents = eregi_replace("(javascript\:|vbscript\:)+","$1** ",contents) //** Ŭ���̾�Ʈ ��ũ��Ʈ ���� ����
				contents = eregi_replace("(\.location|location\.|onload=|\.cookie|alert\(|window\.open\(|onmouse|onkey|onclick|view\-source\:)+", "** ", contents) //** ��ũ��Ʈ �̺�Ʈ ���� ����
				contents = Replace(contents,"<" & "%","&lt;%") //** ASP�±� ����
				contents = Replace(contents,"%" & ">","&lt;%") //** ASP�±� ����

			Case "br"
				contents = eregi_replace("<(\/?)(?!\/|" & allowTagList & ")([^<>]*)?>", "&lt;$1$2&gt;", contents) //** ����±׿��� ��� �±� ���� ����
				contents = eregi_replace("(javascript\:|vbscript\:)+","$1** ",contents) //** Ŭ���̾�Ʈ ��ũ��Ʈ �������
				contents = eregi_replace("(\.location|location\.|onload=|\.cookie|alert\(|window\.open\(|onmouse|onkey|onclick|view\-source\:)+","** ",contents) //** �ڹٽ�ũ��Ʈ �������
				contents = Replace(contents,"<" & "%","&lt;%") //** ASP�±� ����
				contents = Replace(contents,"%" & ">","&lt;%") //** ASP�±� ����

				contents = Replace(contents,Chr(13),"<br />") //** �ٹٲ��� <br />�±׷� �ٲ�

			Case "none"
				contents = getTextString(contents) //** ��� <>�±� ���� ����

			Case Else
				contents = eregi_replace("<(\/?)(?!\/|)([^<>]*)?>","&lt;$1$2&gt;", contents) //** ��� <>�±� ���� ����
				contents = Replace(contents,Chr(13),"<br />") //** �ٹٲ��� <br />�±׷� �ٲ�
		End Select

		SpecialHtmlChars = contents
	End Function

	//** �±׿�������
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

	//** ���� ���� ��� �Լ�
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

	//** ���ڸ� ���ڿ��� ��ȯ
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
// ��ó�� ����
//**********************************************************************

	//** �ֹε�Ϲ�ȣ üũ �Լ�
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


	//**  ���� üũ �Լ�
	Public Function ChkAvailableNum(ByVal num)
		If num="" Or Not IsNumeric(num) Then
			ChkAvailableNum = False
		Else
			ChkAvailableNum = True
		End If
	End Function


	//** ���̵� ��ȿ���� üũ �Լ�
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

		ChkAvailableChr = chkrs //** ��ȿ ���ڸ� ���Խ� True, �׿ܿ��� False�� ��ȯ
	End Function


	//** URL�ּ� ��ȿ �˻�
	Public Function IsUrl(ByVal url)
		If Not eregi("(http|https|ftp|mms)(:** [^ \n\r<>""��-�R]+)", url) Then
			IsUrl = False
		Else
			IsUrl = True
		End If
	End Function


	//** �̸����ּ� ��ȿ �˻�
	Public Function IsEmail(ByVal email)
		If Not eregi("(^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)", email) Then
			IsEmail = False
		Else
			IsEmail = True
		End If
	End Function


	//** �ڵ��� ��ȿ �˻�
	Public Function IsPhoneNumber(ByVal phone)
		If Not eregi("(\d{2,4}-\d{2,4}-\d{4})", phone) Then
			IsPhoneNumber = False
		Else
			IsPhoneNumber = True
		End If
	End Function


	//** ������ ���ݼ� ���� ����
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


	//** ����ǥ ��ȯ
	Public Function stripQuotationMarks (ByVal str)
		str = eregi_replace("'", "&#39;", str)
		str = eregi_replace("""", "&quot;", str)
		str = eregi_replace("--", "", str)
		str = eregi_replace("javascript", "", str)
		str = eregi_replace("script", "", str)
		stripQuotationMarks  = str
	End Function

	//** ����ǥ ��ȯ�Ȱ� ����
	Public Function stripQuotationMarksRev (ByVal str)
		str = eregi_replace("&#39;", "'", str)
		str = eregi_replace("&quot;", """", str)
		stripQuotationMarksRev  = str
	End Function

	//** ������ ���ݼ� ���� �˻�
	Public Function IjStr(ByVal str)
		If eregi("(select|delete|insert|update|drop|shutdown|exec|;|--|')", str) Then
			IjStr = True
		Else
			IjStr = False
		End If
	End Function

	//** Referer ��ȿ�� �˻�
	Public Function AvailableUrl(ByVal url)
		If INSTR(Request("HTTP_REFERER"), url ) > 0 Then
			AvailableUrl = True
		Else
			AvailableUrl = False
		End If
	End Function

	//** Requestr��, ��������, Request��ũ��Ʈ
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
// �̸��ϰ��� �Լ�
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
				.Item("http://schemas.microsoft.com/cdo/configuration/sendusing") = 1 ' 1: ����(SMTP), 2 : �ܺ�(SMTP)
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = CdoSMTPPort
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpserver") = "127.0.0.1"
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverpickupdirectory") = "c:\Inetpub\mailroot\Pickup" ' Pickup ���丮 ����
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = 30 ' ���� �ð�
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
			 Response.Write "<a href='#' class='direction prev'><span></span><span></span> ó��</a>"
		Else
			 Response.Write "<a href='?page=1"& search &"' class='direction prev'><span></span><span></span> ó��</a>"
		End If

		If intTemp = 1 Then
			 Response.Write "<a href='#' class='direction prev'><span></span> ����</a>"
		Else
			 Response.Write "<a href='?page="& intTemp - 1 & search &"' class='direction prev'><span></span> ����</a>"
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
			 Response.Write "<a href='#' class='direction next'>���� <span></span></a>"
		Else
			 Response.Write "<a href='?page="& intTemp & search &"' class='direction next'>���� <span></span></a>"
		End If

		If intTemp > totalPage Then
			 Response.Write "<a href='#' class='direction next'>�� <span></span><span></span></a>"
		Else
			 Response.Write "<a href='?page="& totalPage & search &"' class='direction next'>�� <span></span><span></span></a>"
		End If

	End Sub




	Function levString(lv)

		Select Case lv
			Case "2"
				levString = "�÷�Ƽ��"
			Case "3"
				levString = "���"
			Case "4"
				levString = "�ǹ�"
			Case "5"
				levString = "����"
			Case "6"
				levString = "����"
			Case "7"
				levString = "����"
			Case Else
				levString = "�Ϲ�"
		End Select

	End Function



	Function poString(cd)

		Select Case cd
			Case "AA00"
				poString = "�߾�����"
			Case "AA01"
				poString = "��������"
			Case "AA02"
				poString = "����Ư������"
			Case Else
				poString = ""
		End Select

	End Function
?>