<?php

include('./config.php');
include('./include/my_functions.php');
include('./include/common.php');

printHeader('setup');
print '<center><font color=red><h1>READ ME</h1></font></center>';
print '<pre>';
readfile('./README');
print '</pre>';
printFooter();
?>
