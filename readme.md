# Kirby InstagramAPI – Instagram Endpoints access

![GitHub release](https://img.shields.io/github/release/bnomei/kirby-instagram-api.svg?maxAge=1800) ![Beta](https://img.shields.io/badge/Stage-beta-blue.svg) ![License](https://img.shields.io/badge/license-commercial-green.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-2.3%2B-red.svg)

Kirby CMS User-Field, Tag and Page Method to access Instagram API Endpoints. This is a boilerplate to create your own Instagram embeds.

Use this plugin to simplify the secured api authorisation to a few clicks and show your Instagram images and metadata without any [fixed styling embed codes](https://help.instagram.com/513918941996087).

This is not an [Embed Code Plugin](https://www.instagram.com/developer/embedding/) but grants you access to all [API Endpoints](https://www.instagram.com/developer/endpoints/). It works without implicit OAuth and can use [Signed Requests](https://www.instagram.com/developer/secure-api-requests/). 

You will have to parse the result of the endpoint to generate your html-elements using `snippets` – [see example](https://github.com/bnomei/kirby-instagram-api/blob/master/snippets/ia-example-media.php).

Be aware that using the Instagram API has [rate limits](https://www.instagram.com/developer/limits/).

**NOTE:** This is not a free plugin. In order to use it on a production server, you need to buy a license. For details on Kirby InstagramAPI's license model, scroll down to the License section of this document.

## Key Features

- access [Instagram API Endpoints](https://www.instagram.com/developer/endpoints/)
- use `snippets` to customize the output
- simply extend existing Kirby CMS User-Accounts with [Kirby User Roles](https://getkirby.com/docs/panel/roles)
- or use existing token
- responses can be cached
- includes helper to cache images

## Requirements

- [**Kirby**](https://getkirby.com/) 2.3+
- [Instagram Client ID and Secret](https://www.instagram.com/developer/clients/manage/)
- [PHP Extension PECL hash >= 1.1](http://php.net/manual/en/function.hash-hmac.php)


## Quick Tour
Once installed, configured and authorized you can output [your images](https://www.instagram.com/developer/endpoints/users/#get_users_media_recent_self) like this:

use the Kirby-Tag
```
(instagramapi: mykirbyusername_or_token endpoint: users/self/media/recent snippet: ia-example-media)
```

or plain php
```php
// snippet
snippet('ia-example-media', ['user'=>'mykirbyusername_or_token', 'endpoint'=>'users/self/media/recent']);

// page or site methods
$result = $page->instagramapi('mykirbyusername_or_token', 'users/self/media/recent');
foreach($result['data'] as $data) { /*...*/ }
```

## Installation

### [Kirby CLI](https://github.com/getkirby/cli)

```
kirby plugin:install bnomei/kirby-instagram-api
```

### Git Submodule

```
$ git submodule add https://github.com/bnomei/kirby-instagram-api.git site/plugins/kirby-instagram-api
```

### Copy and Paste

1. [Download](https://github.com/bnomei/kirby-instagram-api/archive/master.zip) the contents of this repository as ZIP-file.
2. Rename the extracted folder to `kirby-instagram-api` and copy it into the `site/plugins/` directory in your Kirby project.

## Usage

### Create Client ID and Secret

Create a new Client and get [Instagram Client ID and Secret](https://www.instagram.com/developer/clients/manage/) and set them in your `/site/config/config.php`.

```php
c::set('plugin.instagram-api.client-id', 'YOUR_CLIENT_ID_HERE');
c::set('plugin.instagram-api.client-secret', 'YOUR_CLIENT_SECRET_HERE');
```

### Setup Instagram Client Security

Setup Client **Security** with *Valid redirect URIs, Disabling Implicit OAuth and Enforced Signed Requests* at the [Instagram Developer Site](https://www.instagram.com/developer/clients/manage/). Now you have an *Instagram App*.

Make sure to attach **kirby-instagram-api/redirect** to your redirect URI or it will not work. Also verify protocol.

```
HTTP?
http://YOUR_REDIRECT_URI_HERE/kirby-instagram-api/redirect

Or HTTPS?
https://YOUR_REDIRECT_URI_HERE/kirby-instagram-api/redirect
```

If you are using `https` consider setting the [option to enforce this in the Panel](https://getkirby.com/docs/cheatsheet/options/ssl) as well as in Kirby.

### Setup Instagram Sandbox

Unless you intend to go live with your *Instagram App* right away you need to add up to 10 users to the [Sandbox](https://www.instagram.com/developer/sandbox/). You can do that at the [Instagram Developer Site](https://www.instagram.com/developer/clients/manage/).

### Add Field to Role Blueprint

Add the `instagramapi` field by [extending a role blueprint](https://getkirby.com/docs/panel/users#custom-user-form-fields) to existing user accounts.

```yaml
# site/blueprints/users/admin.yml
fields:
  instagramapi: instagramapi
```

### Send the Authorize Link

1. Before triggering any emails make sure you sending email is correct by setting either `plugin.instagram-api.email.from` or `email.from`. Sending emails might not work on `localhost` since [Kirby's email() helper](https://getkirby.com/docs/cheatsheet/helpers/email) is used.
2. Then send the email with the Authorization Link using the Button at the Users Panel View. 
3. Open the email and authorize. The data will be stored at the user account and the user will get a second verification email containing a backup of that data.

You can define your own subject and body-snippet for both emails using settings and `snippets` – see settings below.

### Example: Using a Tag with Snippet show to Most-Recent-Media

```
(instagramapi: mykirbyusername_or_token endpoint: users/self/media/recent snippet: ia-example-media)
```

If you use the same snippet or endpoint everytime consider setting a default in your `site/config/config.php`.

```php
c::set('plugin.instagram-api.tag.endpoint', 'users/self/media/recent');
c::set('plugin.instagram-api.tag.snippet', 'ia-example-media');
```

This simplifies the Kirby-Tag call to:

```
(instagramapi: mykirbyusername_or_token)
```

### Example: Using a Snippet show to Most-Recent-Media

See `snippet` [ia-example-media](https://github.com/bnomei/kirby-instagram-api/blob/master/snippets/ia-example-media.php).

```php
snippet('ia-example-media');

// or if you want to override the defaults
snippet('ia-example-media', ['user'=>'mykirbyusername_or_token', 'endpoint'=>'users/self/media/recent']);
```

### Example Page and Site Method

Of course you can use the Page or Site Methods directly. Take a look at the [example](https://github.com/bnomei/kirby-instagram-api/blob/master/snippets/ia-example-media.php) how to setup user, endpoint and parse the result.


### Use without extending a Kirby User (v0.7+)

If you already have a `token` you can use this plugin without its Kirby User Field. Like it was suggested by @olach in [Issue #1](https://github.com/bnomei/kirby-instagram-api/issues/1). Just use your `token` instead of the username.

```
(instagramapi: TOKEN endpoint: users/self/media/recent snippet: ia-example-media)
```

```php
$result = $page->instagramapi('TOKEN', 'users/self/media/recent');
foreach($result['data'] as $data) { /*...*/ }
```

If you set the token in you `site/config/config.php`. It will be used unless you provide a username or other token.

```php
c::set('plugin.instagram-api.default-token', 'TOKEN');
```

```
(instagramapi: endpoint: users/self/media/recent snippet: ia-example-media)
```

```php
// suggested
$result = $page->instagramapi(c::get('plugin.instagram-api.default-token'), 'users/self/media/recent');
// but this will also work
$result = $page->instagramapi(null, 'users/self/media/recent');
foreach($result['data'] as $data) { /*...*/ }
```

## Other Setting

You can set these in your `site/config/config.php`.

### plugin.instagram-api.license
- default: ''
- add your license here and the widget reminding you to buy one will disappear from the Panel.

### plugin.instagram-api.cache (since v0.8+)
- default: `0`
- time in seconds to cache json responses to `/site/cache/`.

### plugin.instagram-api.client-id
- get it from [Instagram Deverloper](https://www.instagram.com/developer/clients/manage/)

### plugin.instagram-api.client-secret
- get it from [Instagram Deverloper](https://www.instagram.com/developer/clients/manage/)

### plugin.instagram-api.scope (v0.9+)
- default: `basic`
- set this if you want to request a [different permission scope](https://www.instagram.com/developer/authorization/). Might need an *approved instgram app* not just a *sandbox app*.

```php
c::set('plugin.instagram-api.scope', 'public_content+likes');
```

### plugin.instagram-api.default-token
- default: ''
- set this if you want to ommit specifying the token.

### plugin.instagram-api.tag.endpoint
- default: ''
- set this if you want to ommit specifying the endpoint in the tag.

```php
c::set('plugin.instagram-api.tag.endpoint', 'users/self/media/recent');
```

### plugin.instagram-api.tag.snippet
- default: ''
- set this if you want to ommit specifying the snippet in the tag.

```php
c::set('plugin.instagram-api.tag.snippet', 'ia-example-media');
```

### plugin.instagram-api.email.from
- default: email.from or **it might fail**

### plugin.instagram-api.email-request.subject
- default: email.subject or 'Kirby CMS InstagramAPI Plugin: Authorization Email'

### plugin.instagram-api.email-request.body-snippet
- default: instagramapi-email-request-body

### plugin.instagram-api.email-success.subject
- default: email.subject or 'Kirby CMS InstagramAPI Plugin: Authorization Email'

### plugin.instagram-api.email-success.body-snippet
- default: instagramapi-email-success-body

### plugin.instagram-api.service
- default: email.service or `instagramapi-html`
- use the html email service provided by this plugin or set your own [email service](https://getkirby.com/docs/developer-guide/advanced/emails)

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby-instagram-api/issues/new).

I am in no way affiliated with the google map places referenced in this readme and the examples. I just liked their names.

## License

Kirby InstagramAPI can be evaluated as long as you want on how many private servers you want. To deploy Kirby InstagramAPI on any public server, you need to buy a license. You need one unique license per public server (just like Kirby does). See `license.md` for terms and conditions.

[<img src="https://img.shields.io/badge/%E2%80%BA-Buy%20a%20license-green.svg" alt="Buy a license">](https://bnomei.onfastspring.com/kirby-instagramapi)

However, even with a valid license code, it is discouraged to use it in any project, that promotes racism, sexism, homophobia, animal abuse or any other form of hate-speech.

## Technical Support

Technical support is provided on GitHub only. No representations or guarantees are made regarding the response time in which support questions are answered. But you can also join the discussions in the [Kirby Forum](https://forum.getkirby.com/search?q=kirby-instagram-api).

## Credits

Kirby InstagramAPI is developed and maintained by Bruno Meilick, a game designer & web developer from Germany.
I want to thank [Fabian Michael](https://github.com/fabianmichael) for inspiring me a great deal and [Julian Kraan](http://juliankraan.com) for telling me about Kirby in the first place.