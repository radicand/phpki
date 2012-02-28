<?php

include('./config.php');
include(STORE_DIR.'/config/config.php');
include('./include/common.php');
include('./include/my_functions.php');
include('./include/openssl_functions.php');

$stage        = gpvar('stage');
$submit       = gpvar('submit');
$search       = gpvar('search');
$serial       = gpvar('serial');
$show_valid   = gpvar('show_valid');
$show_revoked = gpvar('show_revoked');
$show_expired = gpvar('show_expired');

# Force stage back to search form if search string is empty.
if ($stage == "search" && ! $search) $stage = "";

# Force filter to (V)alid certs if no search status is selected.
if ( !($show_valid.$show_revoked.$show_expired) ) $show_valid = 'V';

switch ($stage) {
case display:
	printHeader('about');

	print '
	<center><h2>Certificate Details</h2></center>
	<center><font color=#0000AA><h3>(#'.htvar($serial).')<br>'.htvar(CA_cert_cname($serial).' <'.CA_cert_email($serial).'>').'</h3></font></center>';

	if ($revoke_date = CAdb_is_revoked($serial))
	print '<center><font color=red><h2>REVOKED '.htvar($revoke_date).'</h2></font></center>';

	print '<pre>'.htvar(CA_cert_text($serial)).'</pre>';
	break;

case 'download':
	$rec = CAdb_get_entry($serial);
	upload("$config[cert_dir]/$serial.der", "$rec[common_name] ($rec[email]).cer", 'application/pkix-cert');
        break;

case search:
	printHeader('public');

	$db = CAdb_to_array("^[${show_valid}${show_revoked}${show_expired}].*$search");

	print '<body onLoad="self.focus();document.form.submit.focus()">';
	if (sizeof($db) == 0) {
		?>
		<center>
		<h2>Nothing Found</h2>
		<form action=<?=$PHP_SELF?> method=post name=form>
		<input type=hidden name=search value="<?=htvar($search)?>">
		<input type=hidden name=show_valid value="<?=htvar($show_valid)?>">
		<input type=hidden name=show_revoked value="<?=htvar($show_revoked)?>">
		<input type=hidden name=show_expired value="<?=htvar($show_expired)?>">
		<input type=submit name=submit value="Go Back">
		</form>
		</center>
		<?
		printFooter();
		break;
	}

	print '<table>';
	print '<th colspan=9><big>CERTIFICATE SEARCH RESULTS</big></th>';

        $headings = array(
                status=>"Status", issued=>"Issued", expires=>"Expires",
                common_name=>"User's Name", email=>"E-mail",
                organization=>"Organization", unit=>"Department",
                locality=>"Locality", province=>"State"
        );

        print '<tr>';
        foreach($headings as $field=>$head) {
                print '<th>'.htvar($head). '</th>';
        }
        print '</tr>';

	foreach($db as $rec) {
		$stcolor = array(Valid=>'green',Revoked=>'red',Expired=>'orange');

		?>
		<tr style="font-size: 11px;">
		<td style="color: <?=$stcolor[$rec['status']]?>; font-weight: bold"><?=htvar($rec['status'])?></td>
		<td style="white-space: nowrap"><?=htvar($rec['issued'])?></td>
		<td style="white-space: nowrap"><?=htvar($rec['expires'])?></td>
		<td><?=htvar($rec[common_name])?></td>
		<td style="white-space: nowrap"><a href="mailto:<?=htvar($rec['common_name']).' <'.htvar($rec['email']).'>"'?>><?=htvar($rec['email'])?></a></td>
		<td><?=htvar($rec['organization'])?></td>
		<td><?=htvar($rec['unit'])?></td>
		<td><?=htvar($rec['locality'])?></td>
		<td><?=htvar($rec['province'])?></td>
		<td><a href=<?=$PHP_SELF?>?stage=display&serial=<?=htvar($rec['serial'])?> target=_certdisp><img src=images/display.png alt="Display" title="Display the certificate in excruciating detail"></a>
		<?
		if ($rec['status'] != 'Revoked') {
			?>
			<a href=<?=$PHP_SELF?>?stage=download&serial=<?=htvar($rec['serial'])?>><img src=images/download.png alt="Download" title="Download the certificate so that you may send encrypted e-mail"></a>
			<?
		}
		print '</td></tr>';
	}

	?>
	</table>

	<form action=<?=$PHP_SELF?> method=post name=form>
	<input type=submit name=submit value="Another Search">
	<input type=hidden name=search value="<?=htvar($search)?>">
	<input type=hidden name=show_valid value="<?=htvar($show_valid)?>">
	<input type=hidden name=show_revoked value="<?=htvar($show_revoked)?>">
	<input type=hidden name=show_expired value="<?=htvar($show_expired)?>">
	</form>
	<?

	printFooter();
	break;

default:
	printHeader('public');

	?>
	<body onLoad="self.focus();document.search.search.focus()">
	<center><h2>Certificate Search</h2>
	<form action=<?=$PHP_SELF?> method=post name=search>
	<input type=text name=search value="<?=htvar($search)?>" maxlength=60 size=40>
	<input type=submit name=submit value="Find It!"><br>
	<input type=checkbox name=show_valid value="V" <?=($show_valid?'checked':'')?>>Valid
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=show_revoked value="R" <?=($show_revoked?'checked':'')?>>Revoked
	&nbsp;&nbsp;&nbsp;&nbsp;<input type=checkbox name=show_expired value="E" <?=($show_expired?'checked':'')?>>Expired
	<input type=hidden name=stage value=search>
	</form></center>

	<br><br>
	<?
	printFooter();
}

?>
