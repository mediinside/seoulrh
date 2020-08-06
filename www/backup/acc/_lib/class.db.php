<?

//On Error Resume Next

//** DB ���� Ȯ��
If IsEmpty(DB_HOST) Or IsEmpty(DB_NAME) Or IsEmpty(DB_USER) Or IsEmpty(DB_PASS) Then
	Response.Write "<div>�����ͺ��̽� ���� ȯ���� ������ �ּ���.</div>"
	Response.End
End If


Class DbManager
	Private DefaultDb
	Private strDbConnect
	Private isTransUse

	Private Sub Class_Initialize()
		Set DefaultDb = Nothing
		isTransUse = False

		strDbConnect =	"provider=SQLOLEDB;Network Library=DBMSSOCN;"& _
									"server="& DB_HOST &";"& _
									"database="& DB_NAME &";"& _
									"uid="& DB_USER &";"& _
									"pwd="& DB_PASS &";"
	End Sub

	Private Sub Class_Terminate
		Call close
	End Sub

	//** Database ����
	Public Function connect(ByRef Db)
		If Not IsObject(Db) Then Set Db = Nothing
		If Db Is Nothing Then
			If DefaultDb Is Nothing Then
				Set DefaultDb = Server.CreateObject("ADODB.Connection")
				DefaultDb.Open strDbConnect
			End If
			Set Db = DefaultDb
		End If
	End Function

	//** Database �ݱ�
    Public Sub close
		If isTransUse Then
			If Err.Number = 0 Then
				Call commitTrans(DefaultDb)
			Else
				Call rollbackTrans(DefaultDb)
				Call errorMsg
			End If
			isTransUse = False
		End If

		If Not DefaultDb Is Nothing Then
			If DefaultDb.State = adStateOpen Then DefaultDb.Close
			Set DefaultDb = Nothing
		End If
   End Sub


	//** Ʈ����� ����
	Public Sub beginTrans(ByRef Db)
		Call connect(Db)
		Db.BeginTrans
		isTransUse = True
	End Sub

	//** Ʈ����� ��
	Public Function finishTrans(ByRef Db)
		If Not IsObject(Db) Then Set Db = Nothing
		If Db Is Nothing Then Set Db = DefaultDb

		finishTrans = True

		If isTransUse Then
			If Err.Number = 0 Then
				Db.CommitTrans()
			Else
				Call rollbackTrans(Db)
				Call errorMsg
				finishTrans = False
			End If

			isTransUse = False
		End If
	End Function

	//** Ȱ��ȭ Ʈ����� Ŀ��
	Public Sub commitTrans(ByRef Db)
		If Not IsObject(Db) Then Set Db = Nothing
		If Db Is Nothing Then Set Db = DefaultDb

		If Db.Errors.Count <> 0 Then
			Db.RollbackTrans()
		Else
			Db.CommitTrans()
		End If
	End Sub

	//** Ȱ��ȭ Ʈ����� �ѹ�
	Public Sub rollbackTrans(ByRef Db)
		If Not IsObject(Db) Then Set Db = Nothing
		If Db Is Nothing Then Set Db = DefaultDb

		Db.RollbackTrans()
	End Sub


	//** Sql Query ���� (Connection ��ü Execute)
	Public Function execute(ByVal sql, ByRef Db)
		Dim Rs

		Call connect(Db)

		Set Rs = Db.Execute(sql)

		Set execute = Rs
		Set Rs = Nothing
	End Function

	//** Sql Query(SP) ���� (RecordSet ��ȯ)
	Public Function execRs(ByVal sql, ByVal cmdType, ByRef arrParams, ByRef Db)
		Dim f
		Dim Cmd, Rs

		Call connect(Db)

	    Set Cmd = Server.CreateObject("ADODB.Command")
	    Set Rs = Server.CreateObject("ADODB.RecordSet")

	    Cmd.ActiveConnection = Db
	    Cmd.CommandText = sql
	    Cmd.CommandType = cmdType
	    Set Cmd = collectParams(Cmd, arrParams)


	    Rs.CursorLocation = adUseClient
		Set Rs = Cmd.Execute

		If cmdType = adCmdStoredProc Then
			For f = 0 To Cmd.Parameters.Count - 1
				If Cmd.Parameters(f).Direction = adParamOutput Or Cmd.Parameters(f).Direction = adParamInputOutput Or Cmd.Parameters(f).Direction = adParamReturnValue Then
					If IsArray(arrParams) Then
						arrParams(f)(4) = Cmd.Parameters(f).Value
					Else
						Exit For
					End If
				End If
			Next
		End If

	    Set Cmd.ActiveConnection = Nothing
	    Set Cmd = Nothing

	    If Rs.State = adStateClosed Then Set Rs.Source = Nothing

		Set execRs = Rs
		Set Rs = Nothing
	End Function

	//** Sql Query(SP) ���� (���� Data ��ȯ)
	Public Function execRsData(ByVal sql, ByVal cmdType, ByRef arrParams, ByRef Db)
		Dim Rs
		Dim data

		data = Null

		Call connect(Db)

		Set Rs = execRs(sql, cmdType, arrParams, Db)

		If Not Rs.Bof And Not Rs.Eof Then
			data = Rs(0)
		End If

		If Rs.State = adStateOpen Then Rs.close
		Set Rs = Nothing

		execRsData = data
	End Function


	//** Sql Query(SP) ����
	Public Sub exec(ByVal sql, ByVal cmdType, ByRef arrParams, ByRef Db)
		Dim f
		Dim Cmd

		Call connect(Db)

	    Set Cmd = Server.CreateObject("ADODB.Command")

	    Cmd.ActiveConnection = Db
		Cmd.CommandText = sql
		Cmd.CommandType = cmdType
	    Set Cmd = collectParams(Cmd, arrParams)

	    Cmd.Execute , , adExecuteNoRecords

		For f = 0 To Cmd.Parameters.Count - 1
			If Cmd.Parameters(f).Direction = adParamOutput Or Cmd.Parameters(f).Direction = adParamInputOutput Or Cmd.Parameters(f).Direction = adParamReturnValue Then
				If IsArray(arrParams) Then
					arrParams(f)(4) = Cmd.Parameters(f).Value
				Else
					Exit For
				End If
			End If
		Next

	    Set Cmd.ActiveConnection = Nothing
	    Set Cmd = Nothing
	End Sub

	//** �Ű�����(Array) ����
	Public Function makeParam(ByVal pName, ByVal pType, ByVal pDirection, ByVal pSize, ByVal pValue)
		makeParam = Array(pName, pType, pDirection, pSize, pValue)
	End Function

	//** �Ű�����(Array)�� ���� �̸��� �Ű����� �� ��ȯ
	Public Function getValue(ByRef params, ByRef paramName)
		Dim param

		For Each param In params
			If param(0) = paramName Then
				getValue = param(4)
				Exit Function
			End If
		Next
	End Function

	//** �Ű����� Parsing �� Parameter ��ü �����Ͽ� Command ��ü�� �߰�
	Private Function collectParams(ByRef Cmd, ByRef arrParams)
		Dim f
		Dim startPos, endPos
		Dim value

		If IsArray(arrParams) Then
			startPos = LBound(arrParams)
			endPos = UBound(arrParams)

			For f = startPos To endPos
				If IsArray(arrParams(f)) Then
					If UBound(arrParams(f)) = 4 Then
						If VarType(arrParams(f)(4)) = vbString Then
							If arrParams(f)(4) = "" Then
								value = Null
							Else
								value = Replace(Replace(Replace(Replace(Replace(arrParams(f)(4) _
									, "&#39;"		, "'") _
									, "&quot;"		, """") _
									, "&gt;"			, ">") _
									, "&lt;"			, "<") _
									, "&amp;"		, "&")
							End If
						Else
							value = arrParams(f)(4)
						End If

						If Not IsNull(value) Then
							Select Case arrParams(f)(1)
								Case adChar, adVarchar
									If Len(value) > arrParams(f)(3) Then value = Left(value, arrParams(f)(3))
								Case adInteger
									If CDbl(value) > 2147483647 Then value = 0
								Case adBigInt
									If CDbl(value) > 9223372036854775807 Then value = 0
							End Select
						End If

						//Response.write "0=>"& arrParams(f)(0) &":1=>"& arrParams(f)(1) &":2=>"& arrParams(f)(2) &":3=>"& arrParams(f)(3) &":value=>"& value &"<br>"
						Cmd.Parameters.Append Cmd.CreateParameter(arrParams(f)(0), arrParams(f)(1), arrParams(f)(2), arrParams(f)(3), value)
					End If
			    End If
		    Next

		    Set collectParams = Cmd
		    Exit Function
	    Else
		    Set collectParams = Cmd
	    End If
    End Function

	//** Database Error Message
	Private Sub errorMsg
		Dim msg

		msg = "<table cellpadding='0' cellspacing='0' border='0' align='center' width='500'>"& _
					"<tr height='50'><td></td></tr>"& _
					"<tr><td align='left' style='font-size:9pt;'><b>������ ó�� �� ������ �߻� �߽��ϴ�.</b></td></tr>"& _
					"<tr height='2'><td bgcolor='#CCCCCC'></td></tr>"& _
					"<tr height='20'><td></td></tr>"& _
					"<tr height='20'><td align='left' style='font-size:9pt; color:red;'><b>���� ����:</b></td></tr>"& _
					"<tr><td align='left' style='font-size:9pt; color:red;'>"& Err.Description &"</td></tr>"& _
					"<tr height='20'><td></td></tr>"& _
					"<tr><td align='left' style='font-size:9pt;'>���� ���� ������ ����Ʈ �����ڿ��� ������ �ֽñ� �ٶ��ϴ�.</td></tr>"& _
					"</table>"
		Response.write msg

	End Sub

End Class





//** DbManager ��ü ����
Sub closeDb
	If Not Db Is Nothing Then Set Db = Nothing
End Sub

//** RecordSet ��ü ����
Sub closeRs(ByRef Rs)
	If Rs.State = adStateOpen Then Rs.close
	Set Rs = Nothing
End Sub
?>