<?

//**********************************************************************
// function ÃÊ±âÈ­
//**********************************************************************

//** Áßº¹ Æ÷ÇÔ Ã¼Å©
Dim LibFunctionBaseIncluded
If LibFunctionBaseIncluded = True Then
	Response.Clear
	Response.Write "function.base.asp ÆÄÀÏÀÌ µÎ¹ø Æ÷ÇÔµÇ¾ú½À´Ï´Ù."
	Response.End
End if
LibFunctionBaseIncluded = True



//**********************************************************************
// ¹®ÀÚ¿­ Ã³¸® °ü·Ã
//**********************************************************************


	//** ¹®ÀÚ¿­ Ãâ·Â ÇÁ·Î½ÃÀú
	Public Sub Print(ByVal value)
		//** ºñÁÖ¾óº£ÀÌÁ÷¿¡¼­ÀÇ Print¹® »ç¿ë
		Response.Write value
		Response.End
	End Sub

	//** ¹®ÀÚ °ø¹é ¶Ç´Â Null Ã¼Å©
	Public Function chkNull(value)
		If value = "" OR isNull(value) OR IsEmpty(value) Then chkNull = True Else chkNull = False
	End Function

	//** Á¤±Ô½ÄÀ» ÀÌ¿ëÇÏ¿© ¹®ÀÚ¿­À» Ä¡È¯ÇÏ´Â ÇÔ¼ö
	Public Function eregi_replace(ByVal pattern, ByVal replacestr, ByVal text)
		Dim eregObj

		//** Create regular expression
		Set eregObj = New RegExp

		eregObj.Pattern = pattern //** Set Pattern(ÆÐÅÏ ¼³Á¤)
		eregObj.IgnoreCase = True //** Set Case Insensitivity(´ë¼Ò¹®ÀÚ ±¸ºÐ ¿©ºÎ)
		eregObj.Global = True //** Set All Replace(ÀüÃ¼ ¹®¼­¿¡¼­ °Ë»ö)

		eregi_replace = eregObj.Replace(text, replacestr) //** Replace String
	End Function

	//** Á¤±Ô½ÄÀ» ÀÌ¿ëÇÏ¿© ¹®ÀÚ¿­À» °Ë»öÇÏ´Â ÇÔ¼ö(´ë¼Ò¹®ÀÚ ±¸ºÐ ¾øÀ½)
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

	//** Á¤±Ô½ÄÀ» ÀÌ¿ëÇÏ¿© ¹®ÀÚ¿­À» °Ë»öÇÏ´Â ÇÔ¼ö(´ë¼Ò¹®ÀÚ ±¸ºÐ)
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
		//"[^°¡-ÆR]" //** ÇÑ±Û¸¸
		//"[^-0-9 ]" //** ¼ýÀÚ¸¸
		//"[^-a-zA-Z]" //** ¾ËÆÄºª¸¸
		//"[^-°¡-ÆRa-zA-Z0-9/ ]" //** ¼ýÀÚ ¾ËÆÄºª ÇÑ±Û¸¸
		//"<[^>]*>" //** <>ÅÂ±×¸¸
		//"[^-a-zA-Z0-9/ ]" //** ¿µ¾î ¼ýÀÚ¸¸
	End Function

	//** <>ÅÂ±×¸¦ ¹þ°Ü³»´Â ÇÔ¼ö
	Function StripTags(ByVal htmlDoc)
		Set rex = New Regexp
		rex.Pattern= "<[^>]+>"
		rex.Global = True
		StripTags =rex.Replace(htmlDoc,"")
	End Function

	//** ¹®ÀÚ¿­À» ÁöÁ¤ÇÑ ±æÀÌ·Î Àß¶ó³»´Â ÇÔ¼ö
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
				//** ÇÑ±Û
				nLength = nLength + 1.4 //** ÇÑ±ÛÀÏ¶§ ±æÀÌ°ª ¼³Á¤
				rValue = rValue & tmpStr
			ElseIf tmpLen >= 97 And tmpLen <= 122 Then
				//** ¿µ¹® ¼Ò¹®ÀÚ
				nLength = nLength + 0.75 //** ¿µ¹®¼Ò¹®ÀÚ ±æÀÌ°ª ¼³Á¤
				rValue = rValue & tmpStr
			ElseIf tmpLen >= 65 And tmpLen <= 90 Then
				//** ¿µ¹® ´ë¹®ÀÚ
				nLength = nLength + 1 //** ¿µ¹®´ë¹®ÀÚ ±æÀÌ°ª ¼³Á¤
				rValue = rValue & tmpStr
			Else
				//** ±×¿Ü Å°°ª
				nLength = nLength + 0.6 //** Æ¯¼ö¹®ÀÚ ±âÈ£°ª...
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

	//** ÇÑ±Û 1byte¸¦ ¾ËÆÄºª 2byte·Î ±ÛÀÚ¼ö¸¦ ´Ã¸®´Â ÇÔ¼ö
	Public Function StrLenReturn(ByVal str)
		Dim i, strLen, tmpStr
		strLen = 0

		For i=1 To Len(str)
			tmpStr = Int(Asc(Mid(str,i,1)))
			If tmpStr < 0 Then //** ÇÑ±Û
				strLen = strLen + 2
			ElseIf tmpStr >= 33 And tmpStr <= 126 Then //** ¾Æ½ºÅ°
				strLen = strLen + 1
			Else //** ±×¿Ü ¹®ÀÚ
				strLen = strLen + 2
			End If
		Next

		StrLenReturn = strLen
	End Function

	//** ¿µ¹®/ÇÑ±Û ¹®ÀÚ¿­ ±æÀÌ¸¦ °°¾Æº¸ÀÌ°Ô ÁÙÀÌ´Â ÇÔ¼ö
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


	//** HTML ½ÇÇà ¹æÁö ÇÔ¼ö
	Public Function SpecialHtmlChars(ByVal str, ByVal dtype, ByVal tags)
		Dim contents: contents = str
		Dim doctype: doctype = LCase(dtype)
		Dim allowTagList: allowTagList = Replace(tags,",","|")

		Select Case doctype
			Case "text"
				//contents = Replace(contents,"&","&amp;") //** À¯´ÏÄÚµå ±úÁüÇö»óÀ¸·Î »ç¿ë¾ÈÇÔ
				contents = Replace(contents,"#","&#35;")
				contents = Replace(contents,"&","&#38;")
				contents = Replace(contents,"<","&lt;")
				contents = Replace(contents,">","&gt;")
				contents = Replace(contents,"  ","&nbsp; ")
				contents = Replace(contents,"	","&nbsp; &nbsp; ")
				contents = Replace(contents,Chr(13),"<br />") //** ÁÙ¹Ù²ÞÀ» <br />ÅÂ±×·Î º¯È¯

			Case "normal"
				contents = Replace(contents,"<","&lt;")
				contents = Replace(contents,">","&gt;")
				contents = Replace(contents,"  ","&nbsp; ")
				contents = Replace(contents,"	","&nbsp; &nbsp; ")
				contents = Replace(contents,Chr(13),"<br />") //** ÁÙ¹Ù²ÞÀ» <br />ÅÂ±×·Î º¯È¯

			Case "html"
				contents = eregi_replace("<(\/?)(?!\/|" & allowTagList & ")([^<>]*)?>", "&lt;$1$2&gt;", contents) //** Çã¿ëÇÑ ÅÂ±× ÀÌ¿ÜÀÇ ¸ðµç ÅÂ±×¸¦ º¯È¯
				contents = eregi_replace("(javascript\:|vbscript\:)+","$1** ",contents) //** Å¬¶óÀÌ¾ðÆ® ½ºÅ©¸³Æ® ½ÇÇà ¹æÁö
				contents = eregi_replace("(\.location|location\.|onload=|\.cookie|alert\(|window\.open\(|onmouse|onkey|onclick|view\-source\:)+", "** ", contents) //** ½ºÅ©¸³Æ® ÀÌº¥Æ® ½ÇÇà ¹æÁö
				contents = Replace(contents,"<" & "%","&lt;%") //** ASPÅÂ±× ¹æÁö
				contents = Replace(contents,"%" & ">","&lt;%") //** ASPÅÂ±× ¹æÁö

			Case "br"
				contents = eregi_replace("<(\/?)(?!\/|" & allowTagList & ")([^<>]*)?>", "&lt;$1$2&gt;", contents) //** Çã¿ëÅÂ±×¿ÜÀÇ ¸ðµç ÅÂ±× ½ÇÇà ¹æÁö
				contents = eregi_replace("(javascript\:|vbscript\:)+","$1** ",contents) //** Å¬¶óÀÌ¾ðÆ® ½ºÅ©¸³Æ® ½ÇÇà¹æÁö
				contents = eregi_replace("(\.location|location\.|onload=|\.cookie|alert\(|window\.open\(|onmouse|onkey|onclick|view\-source\:)+","** ",contents) //** ÀÚ¹Ù½ºÅ©¸³Æ® ½ÇÇà¹æÁö
				contents = Replace(contents,"<" & "%","&lt;%") //** ASPÅÂ±× ¹æÁö
				contents = Replace(contents,"%" & ">","&lt;%") //** ASPÅÂ±× ¹æÁö

				contents = Replace(contents,Chr(13),"<br />") //** ÁÙ¹Ù²ÞÀ» <br />ÅÂ±×·Î ¹Ù²Þ

			Case "none"
				contents = getTextString(contents) //** ¸ðµç <>ÅÂ±× ½ÇÇà ¹æÁö

			Case Else
				contents = eregi_replace("<(\/?)(?!\/|)([^<>]*)?>","&lt;$1$2&gt;", contents) //** ¸ðµç <>ÅÂ±× ½ÇÇà ¹æÁö
				contents = Replace(contents,Chr(13),"<br />") //** ÁÙ¹Ù²ÞÀ» <br />ÅÂ±×·Î ¹Ù²Þ
		End Select

		SpecialHtmlChars = contents
	End Function

	//** ÅÂ±×¿ÏÀüÁ¦°Å
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

	//** ·£´ý ¹®ÀÚ Ãâ·Â ÇÔ¼ö
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

	//** ¼ýÀÚ¸¦ ¹®ÀÚ¿­·Î º¯È¯
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
// ÆûÃ³¸® °ü·Ã
//**********************************************************************

	//** ÁÖ¹Îµî·Ï¹øÈ£ Ã¼Å© ÇÔ¼ö
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


	//**  ¼ýÀÚ Ã¼Å© ÇÔ¼ö
	Public Function ChkAvailableNum(ByVal num)
		If num="" Or Not IsNumeric(num) Then
			ChkAvailableNum = False
		Else
			ChkAvailableNum = True
		End If
	End Function


	//** ¾ÆÀÌµð À¯È¿¹®ÀÚ Ã¼Å© ÇÔ¼ö
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

		ChkAvailableChr = chkrs //** À¯È¿ ±ÛÀÚ¸¸ Æ÷ÇÔ½Ã True, ±×¿Ü¿¡´Â False¸¦ ¹ÝÈ¯
	End Function


	//** URLÁÖ¼Ò À¯È¿ °Ë»ç
	Public Function IsUrl(ByVal url)
		If Not eregi("(http|https|ftp|mms)(:** [^ \n\r<>""°¡-ÆR]+)", url) Then
			IsUrl = False
		Else
			IsUrl = True
		End If
	End Function


	//** ÀÌ¸ÞÀÏÁÖ¼Ò À¯È¿ °Ë»ç
	Public Function IsEmail(ByVal email)
		If Not eregi("(^[_0-9a-zA-Z-]+(\.[_0-9a-zA-Z-]+)*@[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*$)", email) Then
			IsEmail = False
		Else
			IsEmail = True
		End If
	End Function


	//** ÇÚµåÆù À¯È¿ °Ë»ç
	Public Function IsPhoneNumber(ByVal phone)
		If Not eregi("(\d{2,4}-\d{2,4}-\d{4})", phone) Then
			IsPhoneNumber = False
		Else
			IsPhoneNumber = True
		End If
	End Function


	//** ÀÎÁ§¼Ç °ø°Ý¼º ¹®ÀÚ Á¦°Å
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


	//** µû¿ÈÇ¥ º¯È¯
	Public Function stripQuotationMarks (ByVal str)
		str = eregi_replace("'", "&#39;", str)
		str = eregi_replace("""", "&quot;", str)
		str = eregi_replace("--", "", str)
		str = eregi_replace("javascript", "", str)
		str = eregi_replace("script", "", str)
		stripQuotationMarks  = str
	End Function

	//** µû¿ÈÇ¥ º¯È¯µÈ°Í º¹±¸
	Public Function stripQuotationMarksRev (ByVal str)
		str = eregi_replace("&#39;", "'", str)
		str = eregi_replace("&quot;", """", str)
		stripQuotationMarksRev  = str
	End Function

	//** ÀÎÁ§¼Ç °ø°Ý¼º ¹®ÀÚ °Ë»ç
	Public Function IjStr(ByVal str)
		If eregi("(select|delete|insert|update|drop|shutdown|exec|;|--|')", str) Then
			IjStr = True
		Else
			IjStr = False
		End If
	End Function

	//** Referer À¯È¿¼º °Ë»ç
	Public Function AvailableUrl(ByVal url)
		If INSTR(Request("HTTP_REFERER"), url ) > 0 Then
			AvailableUrl = True
		Else
			AvailableUrl = False
		End If
	End Function

	//** Requestr°ª, º¯¼ö¼±¾ð, Request½ºÅ©¸³Æ®
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
// ÀÌ¸ÞÀÏ°ü·Ã ÇÔ¼ö
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
				.Item("http://schemas.microsoft.com/cdo/configuration/sendusing") = 1 ' 1: ·ÎÄÃ(SMTP), 2 : ¿ÜºÎ(SMTP)
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverport") = CdoSMTPPort
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpserver") = "127.0.0.1"
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpserverpickupdirectory") = "c:\Inetpub\mailroot\Pickup" ' Pickup µð·ºÅä¸® ¼³Á¤
				.Item("http://schemas.microsoft.com/cdo/configuration/smtpconnectiontimeout") = 30 ' ¿¬°á ½Ã°£
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
			 Response.Write "<a href='#' class='direction prev'><span></span><span></span> Ã³À½</a>"
		Else
			 Response.Write "<a href='?page=1"& search &"' class='direction prev'><span></span><span></span> Ã³À½</a>"
		End If

		If intTemp = 1 Then
			 Response.Write "<a href='#' class='direction prev'><span></span> ÀÌÀü</a>"
		Else
			 Response.Write "<a href='?page="& intTemp - 1 & search &"' class='direction prev'><span></span> ÀÌÀü</a>"
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
			 Response.Write "<a href='#' class='direction next'>´ÙÀ½ <span></span></a>"
		Else
			 Response.Write "<a href='?page="& intTemp & search &"' class='direction next'>´ÙÀ½ <span></span></a>"
		End If

		If intTemp > totalPage Then
			 Response.Write "<a href='#' class='direction next'>³¡ <span></span><span></span></a>"
		Else
			 Response.Write "<a href='?page="& totalPage & search &"' class='direction next'>³¡ <span></span><span></span></a>"
		End If

	End Sub




	Function levString(lv)

		Select Case lv
			Case "2"
				levString = "ÇÃ·¡Æ¼´½"
			Case "3"
				levString = "°ñµå"
			Case "4"
				levString = "½Ç¹ö"
			Case "5"
				levString = "Á¦ÈÞ"
			Case "6"
				levString = "¹ýÀÎ"
			Case "7"
				levString = "°¡Á·"
			Case Else
				levString = "ÀÏ¹Ý"
		End Select

	End Function



	Function poString(cd)

		Select Case cd
			Case "AA00"
				poString = "Áß¾ÓÁöºÎ"
			Case "AA01"
				poString = "¼­ÃÊÁöºÎ"
			Case "AA02"
				poString = "¼®¼ºÆ¯º°ÁöºÎ"
			Case Else
				poString = ""
		End Select

	End Function
?>