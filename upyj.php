<?php    
putenv('LANG=C.UTF-8');    
$result = shell_exec('svn update --accept theirs-full E:\www\yzgjoa 2>&1');    
echo nl2br($result);    