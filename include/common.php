<?php

umask(0007);

if ($_SERVER['PHP_AUTH_USER'])
	$PHPki_user = md5($_SERVER['PHP_AUTH_USER']);
else
	$PHPki_user = md5('default');

$PHP_SELF = $_SERVER['PHP_SELF'];


function printHeader($withmenu="default") {
	global $config;
	$title = ($config['header_title']?$config['header_title']:'PHPki Certificate Authority');

	switch ($withmenu) {
	case 'public':
	case 'about':
	case 'setup':
		$style_css = './css/style.css';
		break;
	case 'ca':
	case 'admin':
	default:
		$style_css = '../css/style.css';
		break;
	}

	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Expires: -1");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

	?>
	<html>
	<head>
	<title>PHPki: <?=$title?> </title>
	<link rel="stylesheet" type="text/css" href="<?=$style_css?>">
	</head>
	<body>
	<?

	if (isKonq()) { 
		$logoclass  = 'logo-konq';
		$titleclass = 'title-konq';
		$menuclass  = 'headermenu-konq';
	}
	else {
		$logoclass  = 'logo-ie';
		$titleclass = 'title-ie';
		$menuclass  = 'headermenu-ie';
	}

	?>
	<div class=<?=$logoclass?>>PHPki</div>
	<div class=<?=$titleclass?>><?=$title?></div>
	<?

	switch ($withmenu) {
	case false:
	case 'about':
		break;
	case 'setup':
		?>
		<div class=<?=$menuclass?>>
		<a class=<?=$menuclass?> href=readme.php>ReadMe</a>
		<a class=<?=$menuclass?> href=setup.php>Setup</a>
		<a class=<?=$menuclass?> href=about.php target=_about>About</a>
		</div>
		<?
		break;
	case 'public':
		print "<div class=$menuclass>";

		if (DEMO)  {
			print "<a class=$menuclass href=index.php>Public</a>";
			print "<a class=$menuclass href=ca/ >Manage</a>";
		}
		else {
			print "<a class=$menuclass href=index.php>Menu</a>";
		}

		if (file_exists('policy.html')) {
			print '<a class='.$menuclass.' style="color: red" href=policy.html target=help>Policy</a>';
		}
		?>
		<a class=<?=$menuclass?> href=help.php target=_help>Help</a>
		<a class=<?=$menuclass?> href=about.php target=_about>About</a>
		</div>
		<?
		break;
	case 'ca':
	default:
		print "<div class=$menuclass>";

		if (DEMO)  {
			print "<a class=$menuclass href=../index.php>Public</a>";
			print "<a class=$menuclass href=../ca/index.php>Manage</a>";
		}
		else {
			print "<a class=$menuclass href=index.php>Menu</a>";
		}

		if (file_exists('../policy.html')) {
			print '<a class='.$menuclass.' style="color: red" href=../policy.html target=help>Policy</a>';
		}
		?>
		<a class=<?=$menuclass?> href=../help.php target=_help>Help</a>
		<a class=<?=$menuclass?> href=../about.php target=_about>About</a>
		</div>
		<?
	}

	?><hr width=99% align=left color=#99caff><?
}


function printFooter() {
	?>
	<br>
	<hr width=99% align=left color=#99caff>
	<center style='margin-top: -5px; font-size: 8pt'>PHPki v<?=PHPKI_VERSION?> - Copyright 2003 - William E. Roadcap</center><br>
	</body>
	</html>
	<?
}

?>
