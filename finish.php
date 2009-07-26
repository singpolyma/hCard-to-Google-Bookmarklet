<?php

header('Content-Type: text/plain');

ob_start();
echo "<?xml version='1.0' encoding='UTF-8'?>\n";
?>
<feed xmlns='http://www.w3.org/2005/Atom'
      xmlns:gContact='http://schemas.google.com/contact/2008'
      xmlns:gd='http://schemas.google.com/g/2005'
      xmlns:batch='http://schemas.google.com/gdata/batch'>
  <category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2008#contact' />
<?php
foreach($_POST['do'] as $id => $item) {
?>
<entry>
    <category scheme='http://schemas.google.com/g/2005#kind' term='http://schemas.google.com/g/2008#contact'/>
<?php
	$item = json_decode($item, true);
	if($_POST['dupe'][$id]) {
		echo "\t<id>" . htmlspecialchars($_POST['dupe'][$id]) . "</id>\n";
		echo "\t<batch:operation type='update' />\n";
	} else {
    	echo "\t<batch:id>".htmlspecialchars($id)."</batch:id>\n";
		echo "\t<batch:operation type='insert' />\n";
	}
?>
	<title><?php echo htmlspecialchars($item['FN']); ?></title>
	<gd:name>
		<gd:fullName><?php echo htmlspecialchars($item['FN']); ?></gd:fullName>
	</gd:name>
	<?php if($item['EMAIL']) : ?>
	<gd:email rel='http://schemas.google.com/g/2005#home' address='<?php echo htmlspecialchars($item['EMAIL']); ?>' />
	<?php endif; ?>
	<?php if($item['TEL']) : ?>
	<gd:phoneNumber><?php echo htmlspecialchars($item['TEL']); ?></gd:phoneNumber>
	<?php endif; ?>
</entry>
<?php
}
?>
</feed>
<?php

$c = ob_get_contents();
ob_clean();

$c = escapeshellarg($c);
$token = escapeshellarg($_POST['token']);
$secret = escapeshellarg($_POST['secret']);
echo "Contacts saved!\n\n\n";
var_dump(shell_exec("ruby post.rb $token $secret $c 2>&1"));

?>
