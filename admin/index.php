<?php

include('../config.php');
include(STORE_DIR.'/config/config.php');
include('../include/my_functions.php');
include('../include/common.php') ;

$stage   = gpvar('stage');
$login   = gpvar('login');
$passwd  = gpvar('passwd');
$passwdv = gpvar('passwdv');

switch($stage) {
case 'list_users':
	printHeader('admin');
        print '<p><h3>Contents of '.htvar($config['passwd_file']).' file:</h3><pre>';
        readfile($config['passwd_file'])

	?>
	</pre>
	<form action=<?=$PHP_SELF?> method=post>
	<input type=submit name=submit value="Back to Menu">
	</form>
	<?
	printFooter(false);
	break;

case 'add_user_form';
	printHeader('admin');
	?>
	<body onLoad="self.focus();document.form.login.focus()">
	<form action=<?=$PHP_SELF?> method=post name=form>
	<table>
	<th colspan=2><h3>Add User or Change Password</h3></th>
	<tr><td>User ID</td><td><input type=text name=login value="<?=htvar($login)?>" maxlength=15 size=15></td></tr>
	<tr><td>Password </td><td><input type=password name=passwd value=''  size=20></td></tr>
	<tr><td>Verify Password </td><td><input type=password name=passwdv value='' size=20></td></tr>
	</table>
	<input type=hidden name=stage value=add_user>
	<input type=submit name=submit value='Submit'>
	</form>
	<?
	break;

case 'add_user':
	printHeader('admin');
	if (! $passwd || ! $passwdv || $passwd != $passwdv || strlen($passwd) < 8) {
		print "<center><h2><font color=red>Missing or invalid password or password and password verification do not match.</font></h2></center>"

		?>
		<p><center>
		<form action=<?=$PHP_SELF?> method=post>
		<input type=hidden name=stage value=add_user_form>
		<input type=hidden name=login value="<?=htvar($login)?>">
		<input type=submit name=submit value=Back>
		</form></center>
		<?
	}
	else {
		$pwdfile = escapeshellarg($config['passwd_file']);
		$login = escapeshellarg($login);
		$passwd = escapeshellarg($passwd);

		print 'Results of htpasswd command:<br>';
		system("htpasswd -bm $pwdfile $login $passwd 2>&1")
		?>
		<p>
		<form action=<?=$PHP_SELF?> method=post>
		<input type=submit name=submit value="Back to Menu">
		</form>
		<?
	}
	printFooter();
	break;

case 'del_user_form';
	printHeader('admin');
	?>
	<body onLoad="self.focus();document.form.login.focus()">
	<form action=<?=$PHP_SELF?> method=post name=form>
	<table>
	<th colspan=2><h3>Remove User</h3></th>
	<tr><td>User ID</td><td><input type=text name=login value="<?=htvar($login)?>" maxlength=15 size=15></td></tr>
	</table>
	<input type=hidden name=stage value=del_user>
	<input type=submit name=submit value='Submit'>
	</form>
	<?
	printFooter();
	break;
case 'del_user':
	printHeader('admin');

	$pwdfile = escapeshellarg($config['passwd_file']);
	$login = escapeshellarg($login);

	print 'Results of htpasswd command:<br>';
	system("htpasswd -D $pwdfile $login 2>&1")
	?>
	<p>
	<form action=<?=$PHP_SELF?> method=post>
	<input type=submit name=submit value="Back to Menu">
	</form>
	<?
	printFooter();
	break;

default:
	printHeader('admin');
	?>
	<br>
	<br>
	<center>
	<table class=menu><th class=menu>SYSADMIN MENU</th>
	<tr><td class=menu style="padding-left: 1em;"><table>
	<tr><td class=menu-pad><a href=<?=$PHP_SELF?>?stage=add_user_form>Add User or Change Password</a></td></tr>
	<tr><td class=menu-pad><a href=<?=$PHP_SELF?>?stage=del_user_form>Remove User</a></td></tr>
	<tr><td class=menu-pad><a href=<?=$PHP_SELF?>?stage=list_users>List Password File Contents</a></td></tr>
	</table></td></tr>
	</table>
	</center>
	<br><br>
	<?
	printFooter();
}

?>
