<?php
include('./config.php');
include('./include/my_functions.php');
include('./include/common.php');

printHeader(about);
?>
<center><h1>PHPki HELP FILES</h1>
<a href=<?=BASE_URL?>help/PKI_basics.html><h3>PKI and E-mail Encryption - A Brief Explanation</h3></a>
<a href=<?=BASE_URL?>help/cacert_install_ie.html><h3>Installing Our Root Certificate For Use With Outlook and Outlook Express</h3></a>
<p><a href=<?=BASE_URL?>help/usercert_install_ie.html><h3>Installing Your Personal E-mail Certificate For Use With Outlook and Outlook Express</h3></a>
<p><a href=<?=BASE_URL?>help/glossary.html><h3>Glossary</h3></a>
</center>
<?
printFooter();
?>
