Set WshShell = CreateObject("WScript.Shell")
WshShell.Run "C:\xampp\mysql_start.bat", 0, False
Set WshShell = Nothing