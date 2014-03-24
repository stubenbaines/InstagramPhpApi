instagramPhpApi
===============

A PHP Library to make calls to the Instagram API.

Requires
-----
- php version >= 5.3
- cURL extension

Usage
-----
See Instagram API docs for an overview of how to use the Instagram API and the available endpoints:
http://instagram.com/developer/authentication/

The library supports both authenticated calls via an access token and non-auth calls using just a client id.

## Non-authenticated Calls ##
Many calls to Instagram can be made without an authenticated user. For these calls, just a client id is required.
Go to http://instagram.com/developer/clients/manage/ to generate a new client id.

Once you have a client id, you can create an instance of the Instaram api object passing in just a client id.

```php
$client_id = '[YOUR CLIENT ID]';
$instagram = new Instagram($client_id);
// Now execute an api call to grab popular media and store the response.        
$res = $instagram->get('/media/popular');
var_dump($res);
```

## Authenticated Calls ##
Making calls as an authenticated user is more complicated in that you need to have the user authorize your application which then gives you an access code which is then sent to Instagram to generate an access code used for all api requests.
```php
$client_id = '[YOUR CLIENT ID]';
$redirect = '[URL OF YOUR APP THAT HANDLES ACCESS CODES]';

$instagram = new Instagram($client_id, $client_secret, $redirect);

// Get an authorize URL. User will be asked to give your app permission to their Instagram account and redirect back with access code.
$instagram->getAuthUrl();

// Now take the access code and request an access token.
$instagram->getAccessToken($code);

// Once you have the access token, you can request user info.
$user = $instagram->getUser();

// Or make authenticated api requests.
$res = $instagram->get('/media/popular');
```

Samples
-----
In the repo there is a file named demo.php which demonstrates some non-authenticated calls.
demo_auth.php shows some authenticated calls. Replace the client_id with your client ID before running.

License
-----
Released under the MIT license.
