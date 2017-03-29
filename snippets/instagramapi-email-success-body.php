<?php
	// SNIPPET: $user - Object, $account, $data

	$username = $user->firstname() .' '. $user->lastname();
    if(strlen(trim($username)) == 0) {
    	$username = $user->username();
    }
?>

<p>Hi <?= $username ?>,</p>

<p>your Instagram Account <em><?= $account ?></em> has been successfully authorized and added to the Kirby CMS. Here is a copy of the data stored.
</p>

<pre><?= $data ?></pre>

<p>Sincerly yours,<br>
<br>
Kirby CMS Instagram API Robot
</p>
