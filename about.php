<?php


include('./config.php');
include('./include/my_functions.php');
include('./include/common.php');

printHeader('about');

?>
<p>
PHPki is an <a href=http://www.opensource.org target=_blank>Open Source</a>
Web application for managing a <a href=<?=BASE_URL?>help/glossary.html#PKI target=help/glossary>
Public Key Infrastructure</a> within a small organizations. PHPki acts as a
mechanism for the centralized creation and management of digital certificates.
PHPki is capable of managing certificates for multiple organizations or user
accounts.

<p>
PHPki requires the Apache Web Server, the <href=http://www.php.net target=_blank>PHP</a> Scripting Language, and <href=http://www.openssl.org target=_blank>
OpenSSL</a>, all of which are included with any major
<a href=http://www.linux.org target=_blank> Linux Operating System</a>
<a href=http://www.redhat.com target=_blank>distribution</a>.

<p>
This software may be freely redistributed under the terms of the 
<a href=http://www.gnu.org target=_blank>GNU</a> Public
License provided this page and all copyright notices remain completely intact.
<p>
<center><h4>Copyright: 2003, William E. Roadcap</h4>
<form>
<textarea name=gpl cols=80 rows=15 readonly>
<?php
readfile("./LICENSE.TXT");
?>
</textarea>
</form>
</center>
<p>

<?php
printFooter();
?>

