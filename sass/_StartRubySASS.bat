@echo off
echo C:\Windows\System32\cmd.exe /E:ON /K C:\Ruby25-x64\bin\setrbvars.cmd
S:
cd /D S:\XAMPP\htdocs\Badminton
sass --watch sass:css
pause >nul