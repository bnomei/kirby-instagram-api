<?php
	// SNIPPET: $user - Object, $link

	$username = $user->firstname() .' '. $user->lastname();
    if(strlen(trim($username)) == 0) {
    	$username = $user->username();
    }
?>

<p>Hi <?= $username ?>,</p>

<p>click the following URL to authentificate Kirby CMS to link to your Instagram Account.
</p>

<p><a href="<?= $link?>"><?= $link ?></a></p>

<p>Sincerly yours,<br>
<br>
Kirby CMS Instagram API Robot
</p>
