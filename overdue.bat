@echo off 
start http://oa.yuazen.cn/index.php?a=getoverdue&m=login
ping -n 5 127.1 >nul 5>nul 
taskkill /f /im IEXPLORE.exe 
exit