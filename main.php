<?php

include('./config.php');
include(STORE_DIR.'/config/config.php');
include('./include/common.php');
include('./include/my_functions.php');
include('./include/openssl_functions.php');

$stage = gpvar('stage');

switch($stage) {

case 'dl_root':
	upload("$config[cacert_pem]", "$config[ca_prefix]cacert.crt", 'application/x-x509-ca-cert');
	break;

case 'display_root':
	printHeader('public');

	?>
	<center><h2>Root Certificate (PEM Encoded)</h2></center>
	<p>
	<pre><?= CA_get_root_pem() ?></pre>
	<p>
	<form action=<?=$PHP_SELF?> method=post>
		<input type=submit name=submit value="Back to Menu">
	</form>
	<?
	break;

case 'dl_crl':
	upload("$config[cacrl_der]", "$config[ca_prefix]cacrl.crl", 'application/pkix-crl');
	break;

default:
	printHeader('public');

	?>
	<br>
	<br>
	<center>
	<table class=menu width=500><th class=menu colspan=2><big>PUBLIC CONTENT MENU<big></th>
	
	<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;" width=35%>
	<a href=search.php>Search for a Certificate</a></td>
	<td>Find a digital certificate to download and install in your e-mail or browser application.</td></tr>
	
	<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
	<a href=<?=$PHP_SELF?>?stage=dl_root>Download Our Root Certificate</a></td>
	<td>You must install our "Root" certificate before you can use any of the 
	certificates issued here. <a href=help.php target=_help>Read the online help</a> 
	to learn more about this.</td></tr>
	
	<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
	<a href=<?=$PHP_SELF?>?stage=display_root>Display Our Root Certificate (PEM Encoded)</a></td>
	<td>This option provides the "Root" certificate PEM encoded text for advanced users 
	to manually install via copy and paste. <a href=help.php target=_help>Read the online help</a> 
	to learn more about this.</td></tr>
	
	<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
	<a href=<?=$PHP_SELF?>?stage=dl_crl>Download Our Certificate Revocation List</a></td>
	<td>The official list of certificates revoked by this site.  Installation and use of 
	this list is optional. Some e-mail programs will reference this list automagically. </td></tr>
	
	</table>
	</center>
	<br><br>
	<?

	printFooter();
}

?>
