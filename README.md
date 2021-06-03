# dynacover

A PHP GD + TwitterOAuth demo to dynamically generate Twitter header images and upload them via the API. This enables you to build cool little tricks, like showing your latest followers or sponsors, latest content creted, a qrcode to something, a progress bar for some goal, and whathever you can think of.

<p align="center">
<img src="https://user-images.githubusercontent.com/293241/120513746-dc32e600-c3cc-11eb-862e-e7058f78fbfe.png"/>
</p>

The demo is explained in detail in this guide: [How to Dynamically Update Twitter Cover Image to Show Latest Followers Using PHP GD and TwitterOAuth](https://dev.to/erikaheidi/how-to-dynamically-update-twitter-cover-image-to-show-latest-followers-using-php-gd-and-twitteroauth-62n).

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

Start by cloning this repository to your local PHP dev environment or remote PHP server:

```shell
git clone https://github.com/erikaheidi/dynacover.git
cd dynacover
```

2. Install Composer Dependencies

```shell
composer install
```

3. Set Up Twitter Credentials

Create a file named `credentials.php` in the root folder containing your keys as follows:

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

Replace the keys accordingly and save the file.

4. Test Twitter Connection

To test that your credentials are valid, you can list your latest followers with:

```shell
php dynacover fetch followers
```

If everything is set up correctly, you will see a list with your 10 latest followers.

5. Preview your Cover

To preview your cover without uploading it to Twitter, run:

```shell
php dynacover cover generate
```

Covers are generated in the root of the application folder, with the name `latest_header.png`. Check the generated image before uploading it to Twitter to confirm it has your latest followers and it looks like you expect.

6. Upload to Twitter

To generate **and** update your cover, run:

```shell
php dynacover cover update
```

7. Set Up Crontab (Optional)

For this to be completely dynamic and update frequently, you'll need to include the script to your Crontab or equivalent.

To open the current user's crontab, run:

```shell
crontab -e
```

This will open up a text editor. You should include the full paths to both the `php` executable and the `dynacover` script, like in this example which will update the cover every 5 minutes:

```
*/5 * * * * /usr/bin/php /home/erika/dynacover/dynacover cover update > /dev/null 2>&1
```

