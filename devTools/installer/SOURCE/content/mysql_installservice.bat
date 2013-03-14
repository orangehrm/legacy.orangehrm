@echo off 
REM Changes done to ensure service path is in lower case and matches
REM What is expected by the XAMPP control panel so that it doesn't complain about
REM Service path not matching expected path
REM Check if command line argument is given
if [%1] == [] GOTO SetDir

REM If arg given, set base dir to that value
SET BASE_DIR=%1
GOTO DirSet

:SetDir
REM if arg not given, set to current directory
SET BASE_DIR=%cd%

:DirSet


if "%OS%" == "Windows_NT" goto WinNT 

:Win9X 
echo Don't be stupid! Win9x don't know Services 
echo Please use mysql_start.bat instead 
goto exit 

:WinNT 
if exist %windir%\my.ini GOTO CopyINI 
if exist c:\my.cnf GOTO CopyCNF 
if not exist %windir%\my.ini GOTO MainNT 
if not exist c:\my.cnf GOTO MainNT 

:CopyINI 
echo Safe the %windir%\my.ini as %windir%\my.ini.old! 
copy %windir%\my.ini /-y %windir%\my.ini.old 
del %windir%\my.ini 
GOTO WinNT 

:CopyCNF 
echo Safe the c:\my.cnf as c:\my.cnf.old! 
copy c:\my.cnf /-y c:\my.cnf.old 
del c:\my.cnf 
GOTO WinNT 

:MainNT 
echo Installing MySQL as an Service 
rem copy "%BASE_DIR%\bin\my.cnf" /-y %windir%\my.ini
"%BASE_DIR%\bin\mysqld.exe" --install mysql --defaults-file="%BASE_DIR%\bin\my.ini"
echo Try to start the MySQL deamon as service ... 
net start MySQL 

:exit 
pause
