<?php

include("../config.php");
include(STORE_DIR.'/config/config.php');
include("../include/my_functions.php");
include("../include/common.php") ;
include("../include/openssl_functions.php");

$stage = gpvar('stage');

switch($stage) {

case 'dl_root':
	upload("$config[cacert_pem]", "$config[ca_prefix]cacert.crt", 'application/x-x509-ca-cert');
	break;

case 'dl_crl':
	upload("$config[cacrl_der]", "$config[ca_prefix]cacrl.crl", 'application/pkix-crl');
	break;

case 'gen_crl':
        list($ret,$errtxt) = CA_generate_crl();

        printHeader(false);

        if ($ret) {
                ?>
                <center><h2>Certificate Revocation List Updated</h2></center>
                <p>
                <form action=<?=$PHP_SELF?> method=post>
                <input type=submit name=submit value="Back to Menu">
                </form>
                <?
                print '<pre>'.CA_crl_text().'</pre>';
        }
        else {
                ?>
                <font color=#ff0000>
                <h2>There was an error updating the Certificate Revocation List.</h2></font><br>
                <blockquote>
                <h3>Debug Info:</h3>
                <pre><?=$errtxt?></pre>
                </blockquote>
                <form action=<?=$PHP_SELF?> method=post>
                <p>
                <input type=submit name=submit value="Back to Menu">
                <p>
                </form>
                <?
        }
        break;

default:
	printHeader('ca');
	?>
	<br>
	<br>
	<center>
	<table class=menu width=600><th class=menu colspan=2><big>CERTIFICATE MANAGEMENT MENU</big></th>

	<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;" width=33%>
	<a href=request_cert.php>Create a New Certificate</a></td>
	<td>Use the <strong><cite>Certificate Request Form</cite></strong> to create and download new digital certificates.  
	You may create certificates in succession without re-entering the entire form 
	by clicking the "<strong>Go Back</strong>" button after each certificate is created.</td></tr>

	<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
	<a href=manage_certs.php>Manage Certificates</a></td>
	<td>Conveniently view, download, revoke, and renew your existing certificates using the
	<strong><cite>Certificate Management Control Panel</cite></strong>.</td></tr>

	<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
	<a href=<?=$PHP_SELF?>?stage=gen_crl>Update & View the Certificate Revocation List</a></td>
	<td>Some applications automagically reference the Certificate Revocation List to determine
	certificate validity.  It is not necessary to perform this update function, as the CRL is 
	updated when certificates are revoked.  However, doing so is harmless.
	<a href=../help.php target=_help>Read the online help</a> to learn more about this.</td></tr>

	<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
	<a href=<?=$PHP_SELF?>?stage=dl_root>Download the Root Certificate</a></td>
	<td>The "Root" certificate must be installed before using any of the 
	certificates issued here. <a href=../help.php target=_help>Read the online help</a> 
	to learn more about this.</td></tr>

	<tr><td style="text-align: center; vertical-align: middle; font-weight: bold;">
	<a href=<?=$PHP_SELF?>?stage=dl_crl>Download the Certificate Revocation List</a></td>
	<td>This is the official list of revoked certificates.  Using this list with your e-mail or
	browser application is optional.  Some applications will automagically reference this list. </td></tr>

	</table>
	</center>
	<br><br>
	<?
	printFooter();
}

?>
