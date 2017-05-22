<?php

	/////////////////////////////////////
	//
	// if this snippet gets called by TAG:
	// $user and maybe $endpoint are set.
	// $snippetByTag will be 'true'.
	// 
	if(isset($snippetByTag) && $snippetByTag == true && isset($result)) {
		// $result is allready filled

	} 

	/////////////////////////////////////
	//
	// if this snippet gets called by your code:
	// you have to set $user and $endpoint
	// and get the results yourself.
	// $snippetByTag and $result is not set.
	// 
	else {
		if(!isset($user)) {
			// example: get any user you want
			$user = site()->users()->first();

			// or by username
			// $user = site()->user('somename');

			// or just a name as string
			//$user = 'myusername';
		}

		if(!isset($endpoint)) {
			$endpoint = 'users/self/media/recent';
		}

		// request endpoint
		$result = site()->instagramapi($user, $endpoint);

		// with params if you need them
		// $result = $site->instagramapi($user, 'media/recent', '', ['count' => 10]);
	}
	
	/////////////////////////////////////
	// sneak peak? uncomment the following line
	// a::show($result);

	// always check if request returned valid data 
	if(gettype($result) == 'array' && a::get($result, 'data')) {

		// do something with the data
		foreach($result['data'] as $data) {
			// https://getkirby.com/docs/toolkit/api/helpers/brick

			$imgurl = $data['images']['low_resolution']['url'];

			// if you want to cache the image you could
			// use this helper or write your own based on it
			/*
			if($imgMedia = site()->instagramapiCacheImageToThumbs($imgurl)) {
				$imgurl = $imgMedia->url();
			}
			*/

			$img = brick('img')
				->attr('src', $imgurl)
				->attr('alt', $data['caption']['text']);

			$a = brick('a', $img)
				->attr('href', $data['link']);

			echo $a;
		}
	} else {
		// show error message this plugin provides
		echo brick('code', $result);
	}
