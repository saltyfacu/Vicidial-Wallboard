<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_wallboard = "localhost";
$database_wallboard = "asterisk";
$username_wallboard = "cron";
$password_wallboard = "1234";
$wallboard = mysql_pconnect($hostname_wallboard, $username_wallboard, $password_wallboard) or trigger_error(mysql_error(),E_USER_ERROR); 
?>