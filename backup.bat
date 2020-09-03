@echo off  
set "Ymd=%date:~,4%%date:~5,2%%date:~8,2%"  
E:/phpstuy1/MySQL/bin/mysqldump --opt -u root --password=yzgj1988 yzoa > E:/www/yzgjoa/upload/db_backup/yzoa_%Ymd%.sql  
@echo on  