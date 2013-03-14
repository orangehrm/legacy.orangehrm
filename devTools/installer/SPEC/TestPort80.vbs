Dim WshShell, oExec, key
Set WshShell = CreateObject("WScript.Shell")
Set oExec = WshShell.Exec("netstat -ano")
key = "0.0:80"

Dim values
values = checkPortStatus(oExec, key)
portInUse = values(0)
input = values(1)

If portInUse Then
  x = InStrRev(input, " ")

  ProcessID = Mid(input, x+1)
  
  commandTxt = "tasklist /FI " & Chr(34) & "PID eq " & ProcessID & Chr(34)
  
	Dim oExec2, key2
	Set oExec2 = WshShell.Exec(commandTxt)
	key2 = ProcessID

	Dim values2
	values2	= checkPortStatus(oExec2, key2)
	Found = values2(0)
	input2 = values2(1)

	If Found Then
		y = InStr(input2, " ")
		ExeName = Left(input2, y-1)
			WScript.StdOut.WriteLine "Quit " & ExeName & " and restart OrangeHRM installation. Once OrangeHRM is installed, you can start using "  & ExeName & " again. Visit www.orangehrm.com/exe-faq.shtml for more details." 
	End If
End If
'## If we explicitly set a Success code then we can avoid this.
	WScript.Quit 512

Function checkPortStatus(oExec, key)
	portInUse = false
	input = ""
	Do While True

		 If Not oExec.StdOut.AtEndOfStream Then
			  input = oExec.StdOut.ReadLine()
			  If InStr(input, key) <> 0 Then 
			' Found Port 80
					portInUse = true
					Exit DO
			  End If
		 Else
			Exit DO
		 End If
		 WScript.Sleep 100
	Loop
	
	Do While oExec.Status <> 1
     WScript.Sleep 100
	Loop
	Dim values(1)
	values(0) = portInUse
	values(1) = input
	
	checkPortStatus = values

End Function
