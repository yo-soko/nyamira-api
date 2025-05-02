@echo off
::step 1 start the apache & mysql
cscript //nologo "C:\JavaPA\start_apache.vbs"
cscript //nologo "C:\JavaPA\start_mysql.vbs"

::wait for the services to start
timeout /t 2/nobreak >nul

::step 2 navigate to project
cd /d C:\JavaPA

:: step3 start laravel server
cscript //nologo "C:\JavaPA\employee_site.vbs"




start http://127.0.0.1:8000