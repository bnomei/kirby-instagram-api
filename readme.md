# Kirby InstagramAPI – Instagram Endpoints access

![GitHub release](https://img.shields.io/github/release/bnomei/kirby-instagram-api.svg?maxAge=1800) ![Beta](https://img.shields.io/badge/Stage-beta-blue.svg) ![License](https://img.shields.io/badge/license-commercial-green.svg) ![Kirby Version](https://img.shields.io/badge/Kirby-2.3%2B-red.svg)

Kirby CMS User-Field, Tag and Page Method to access Instagram API Endpoints. This is a boilerplate to create your own Instagram embeds.

Use this plugin to simplify secured api authorisation to a few clicks and show your Instagram images and their metadata within your awesome layout without any [embed codes](https://help.instagram.com/513918941996087).

**NOTE:** This is not a free plugin. In order to use it on a production server, you need to buy a license. For details on Kirby InstagramAPI's license model, scroll down to the License section of this document.

This is not an [Embed Code Plugin](https://www.instagram.com/developer/embedding/) but grants you access to all [API Endpoints](https://www.instagram.com/developer/endpoints/), forces you to disable implicit OAuth and use [Signed Requests](https://www.instagram.com/developer/secure-api-requests/). Why? Because it is saver and I like it that way. 

You will have to parse the result of the endpoint to generate your html-elements using `snippets` – [see example](https://github.com/bnomei/kirby-instagram-api/blob/master/snippets/ia-example-media.php).

Be aware that using the Instagram API has [rate limits](https://www.instagram.com/developer/limits/).

## Key Features

- access [Instagram API Endpoints](https://www.instagram.com/developer/endpoints/)
- use `snippets` to customize the output
- just a new field for Users with simple integration using [Kirby User Roles](https://getkirby.com/docs/panel/roles)

## Requirements

- [**Kirby**](https://getkirby.com/) 2.3+
- [Instagram Client ID and Secret](https://www.instagram.com/developer/clients/manage/)
- [PHP Extension PECL hash >= 1.1](http://php.net/manual/en/function.hash-hmac.php)

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

Create a new Client and get [Instagram Client ID and Secret](https://www.instagram.com/developer/clients/manage/) and set them in your `/site/config/config.php`. Keep them save!

```php
c::set('plugin.instagram-api.client-id', 'YOUR_CLIENT_ID_HERE');
c::set('plugin.instagram-api.client-secret', 'YOUR_CLIENT_SECRET_HERE');
```

### Setup Instagram Client Security

Setup Client **Security** with *Valid redirect URIs, Disabling Implicit OAuth and Enforced Signed Requests* at the [Instagram Developer Site](https://www.instagram.com/developer/clients/manage/). Now you have an *Instagram App*.

Make sure to attach **kirby-instagram-api/redirect** to your redirect URI or it will not work. Also verify protocol.

```
Still at HTTP?
http://YOUR_REDIRECT_URI_HERE/kirby-instagram-api/redirect

Or are you using HTTPS?
https://YOUR_REDIRECT_URI_HERE/kirby-instagram-api/redirect
```

If you are using https consider setting the [option to enforce this in the Panel](https://getkirby.com/docs/cheatsheet/options/ssl) as well as in Kirby.

### Setup Instagram Sandbox

Unless you intend to go live with your *Instagram App* right away you need to add up to 10 users to the [Sandbox](https://www.instagram.com/developer/sandbox/). You can do that at the [Instagram Developer Site](https://www.instagram.com/developer/clients/manage/).

### Add Field to Role Blueprint

Add the `instagramapi` field by [extending a role blueprint](https://getkirby.com/docs/panel/users#custom-user-form-fields).

```yaml
# site/blueprints/users/admin.yml
fields:
  instagramapi: instagramapi
```

### Send the Authorize Link

Before triggering any emails make sure you sender is correct by setting either `plugin.instagram-api.email.from` or `email.from`. Sending email might not work on `localhost` since only [Kirby's email() helper](https://getkirby.com/docs/cheatsheet/helpers/email) is used.

- Then send the email with the Authorization Link using the Button at the Users Panel View. 
- Open the email and authorize. The user will get a second verification email containing a backup of the data.

### Example: Using a Tag with Snippet show to Most-Recent-Media

See `snippet` [ia-example-media](https://github.com/bnomei/kirby-instagram-api/blob/master/snippets/ia-example-media.php).

```
(instagramapi: myusername endpoint: media/recent snippet:ia-example-media)
```

If you use the same snippet or endpoint everytime consider setting a default in your `site/config/config.php` instead.

```php
c::set('plugin.instagram-api.tag.endpoint', 'media/recent');
c::set('plugin.instagram-api.tag.snippet', 'ia-example-media');
```

```
using default snippet now...
(instagramapi: myusername)
```

### Example: Using a Snippet show to Most-Recent-Media

See `snippet` [ia-example-media](https://github.com/bnomei/kirby-instagram-api/blob/master/snippets/ia-example-media.php).

```php
snippet('ia-example-media', ['user' => site()->user()])
```

### Page and Site Method

Of course you can use the Page or Site Methods directly. But take a look at the [example](https://github.com/bnomei/kirby-instagram-api/blob/master/snippets/ia-example-media.php) how to parse the result.

## Other Setting

You can set these in your `site/config/config.php`.

### plugin.instagram-api.license
- default: ''
- add your license here and the widget reminding you to buy one will disappear from the Panel.

### plugin.instagram-api.client-id

### plugin.instagram-api.client-secret

### plugin.instagram-api.tag.endpoint
- default: ''
- set this if you want to ommit specifying the endpoint in the tag.

```php
c::set('plugin.instagram-api.tag.endpoint', 'media/recent');
```

### plugin.instagram-api.tag.snippet
- default: ''
- set this if you want to ommit specifying the snippet in the tag.

```php
c::set('plugin.instagram-api.tag.snippet', 'ia-example-media');
```

### plugin.instagram-api.email.from
- default: email.from or `site()->user()->email()`

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
- use simple html email service provided by this plugin or set your own [email service](https://getkirby.com/docs/developer-guide/advanced/emails)

## Disclaimer

This plugin is provided "as is" with no guarantee. Use it at your own risk and always test it yourself before using it in a production environment. If you find any issues, please [create a new issue](https://github.com/bnomei/kirby-instagram-api/issues/new).

I am in no way affiliated with the google map places referenced in this readme and the examples. I just liked their names.

## License

Kirby InstagramAPI can be evaluated as long as you want on how many private servers you want. To deploy Kirby InstagramAPI on any public server, you need to buy a license. You need one unique license per public server (just like Kirby does). See `license.md` for terms and conditions.

[<img src="https://img.shields.io/badge/%E2%80%BA-Buy%20a%20license-green.svg" alt="Buy a license">](https://bnomei.onfastspring.com/kirby-instagram-api)

However, even with a valid license code, it is discouraged to use it in any project, that promotes racism, sexism, homophobia, animal abuse or any other form of hate-speech.

## Technical Support

Technical support is provided on GitHub only. No representations or guarantees are made regarding the response time in which support questions are answered. But you can also join the discussions in the [Kirby Forum](https://forum.getkirby.com/search?q=kirby-instagram-api).

## Credits

Kirby InstagramAPI is developed and maintained by Bruno Meilick, a game designer & web developer from Germany.
I want to thank [Fabian Michael](https://github.com/fabianmichael) for inspiring me a great deal and [Julian Kraan](http://juliankraan.com) for telling me about Kirby in the first place.