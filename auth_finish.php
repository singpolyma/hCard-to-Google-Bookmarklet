<?php

$secret = file_get_contents(dirname(__FILE__).'/tokens/'.rawurlencode($_GET['oauth_token']));
unlink(dirname(__FILE__).'/tokens/'.rawurlencode($_GET['oauth_token']));

$secret = escapeshellcmd($secret);
$token = escapeshellcmd($_GET['oauth_token']);
$access = explode("\n",shell_exec("ruby oauth.rb $token $secret"));

?>
Bookmarklet: <a href="javascript:void((function(token,secret){window.open('http://singpolyma.net/contacts2google/contact.php?uri='+encodeURIComponent(window.location.href)+'&amp;token='+encodeURIComponent(token)+'&amp;secret='+encodeURIComponent(secret));})('<?php echo htmlspecialchars($access[0]); ?>','<?php echo htmlspecialchars($access[1]); ?>'));">Add hCards to Google Contacts</a>
