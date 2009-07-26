<?php

function get($url) {
	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	return curl_exec($curl);
}

function vcard2hash($s) {
	$s = explode("\n", $s);
	$r = array();
	foreach($s as $line) {
		$v = explode(':', $line, 2);
		$v[0] = explode(';', $v[0], 2);
		$v[0] = $v[0][0];
		$r[$v[0]] = trim($v[1]);
	}
	return $r;
}

$token = escapeshellarg($_GET['token']);
$secret = escapeshellarg($_GET['secret']);
$contacts = shell_exec("ruby getcontacts.rb $token $secret");

$contacts = json_decode($contacts, true);
$contacts = $contacts['feed']['entry'];

$vcards = get('http://suda.co.uk/projects/microformats/hcard/get-contact.php?uri='.rawurlencode($_GET['uri']));
$vcards = explode('BEGIN:VCARD', $vcards);

echo '<form method="post" action="finish.php">';
echo '<input type="hidden" name="token" value="'.htmlspecialchars($_GET['token']).'" />';
echo '<input type="hidden" name="secret" value="'.htmlspecialchars($_GET['secret']).'" />';

echo '<ul>';
foreach($vcards as $vcard) {
	$vcard = vcard2hash($vcard);
	$fn = strtolower($vcard['FN']);
	if(!$fn) continue;
	$json = json_encode($vcard);
	$id = md5($json);
	$email = strtolower($vcard['EMAIL']);
	echo '<li>';
	echo '<label><input checked="checked" type="checkbox" name="do['.$id.']" value="'.htmlspecialchars($json).'" /> '.htmlspecialchars($vcard['FN']).'</label>';
	$d = array();
	foreach($contacts as $contact) {
		$iz = false;
		if(is_array($contact['gd$email'])) {
			foreach($contact['gd$email'] as $e) {
				if(strtolower($e['address']) == $email) {
					$iz = true;
					break;
				}
			}
		}
		if($iz || trim(strtolower($contact['title']['$t'])) == $fn) {
			$d[] = '<label><input checked="checked" type="checkbox" name="dupe['.$id.']" value="'.htmlspecialchars($contact['id']['$t']).'" /> '.htmlspecialchars($contact['title']['$t']).'</label>';
		}
	}
	if(count($d)) {
		echo '<h3>Possible duplicates</h3>';
		echo '<ul>';
		echo implode("\n", $d);
		echo '</ul>';
	}
	echo '</li>'."\n";
}
echo '</ul>';

echo '<input type="submit" value="Add Contacts" />';

echo '</from>';

?>
