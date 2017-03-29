<?php

$input = explode(',', $field->val());
$isAuthorized = count($input) == 2;

$username = $field->model()->username();

$apiURL = implode('/',[ 
		site()->url(),
		'kirby-instagram-api/email',
		$username,
		sha1(kirby()->roots()->index().date('YmdH').$username),
		// acount by js
	]);

$buttonText = $field->text(); // todo: localize
?>

<div class="instagramapidata-wrapper">
	
	<div class="instagramapidata-field field-is-readonly">
		<input 
			class="input input-is-readonly <?php echo $field->type(); ?>"
			readonly
			tabindex="-1"
			id="<?php echo $field->id(); ?>" 
			name="<?php echo $field->name(); ?>" 
			value="<?php echo $field->val() ?>"/>
		<div class="field-icon"><i class="icon fa fa-instagram"></i></div>
	</div>
	<div class="instagramapidata-button">
		<a 
			class="btn btn-rounded needs-init <?php echo $field->type(); ?>" 
			href="<?php echo $apiURL ?>"
			target="_blank"

		><i class="fa fa-spinner fa-spin fa-fw"></i><i class="fa fa-exclamation-circle fa-fw"></i><i class="fa fa-info-circle fa-fw"></i><i class="fa fa-check-circle fa-fw"></i><span name="<?php echo $field->name(); ?>"><?php echo $buttonText ?></span></a>
	</div>
</div>
