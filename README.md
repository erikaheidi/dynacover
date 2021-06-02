# dynacover

A PHP GD + TwitterOAuth demo to dynamically generate Twitter header images and upload them via the API. This enables you to build cool little tricks, like showing your latest followers or sponsors, latest content creted, a qrcode to something, a progress bar for some goal, and whathever you can think of.

## Requirements

- PHP (cli only) 7.4+
- GD / PHP-GD
- Curl
- Composer

You also need to register an application within the [Twitter Developers Portal](https://dev.twitter.com) and obtain **4 tokens**:

- Consumer / App Token
- Consumer / App Secret
- User / Access Token
- User / Access Token Secret

## Installation

1. Clone this repository
2. Run `composer install`
3. Create a file named `credentials.php` in the root folder containing your keys as follows:

```php
#credentials.php
<?php

return [
    'twitter_consumer_key' => 'APP_CONSUMER_KEY',
    'twitter_consumer_secret' => 'APP_CONSUMER_SECRET',
    'twitter_user_token' => 'USER_ACCESS_TOKEN',
    'twitter_token_secret' => 'USER_ACCESS_TOKEN_SECRET',
];
```

4. Run `dynacover cover generate` to preview your cover
5. Run `dynacover cover update` to generate and upload to Twitter

