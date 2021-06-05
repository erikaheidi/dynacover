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

Additionally, you can set up a [Personal GitHub API key](https://github.com/settings/tokens) to fetch your sponsors, in case you are enrolled in the GitHub Sponsors program ,and you want to use the included `cover_sponsors.json` template. This is optional.

## Installation

Installation is done in a few steps: clone, install dependencies, set up credentials, and you are ready to go.

### 1. Clone this repository

Start by cloning this repository to your local PHP dev environment or remote PHP server:

```shell
git clone https://github.com/erikaheidi/dynacover.git
cd dynacover
```

### 2. Install Composer Dependencies

```shell
composer install
```
This will install a few dependencies and generate a `credentials.php` file in the root folder of your application.

### 3. Set Up Twitter Credentials

Open the generated `credentials.php` file and set up your credentials:

```php
#credentials.php
<?php
return [
    'twitter_consumer_key' => 'APP_CONSUMER_KEY',
    'twitter_consumer_secret' => 'APP_CONSUMER_SECRET',
    'twitter_user_token' => 'USER_ACCESS_TOKEN',
    'twitter_token_secret' => 'USER_ACCESS_TOKEN_SECRET',

    'github_api_bearer' => 'GITHUB_API_BEARER_TOKEN'
];
```
_The `github_api_bearer` token is optional, only in case you want to use the GitHub API to fetch Sponsors._

Replace the keys accordingly and save the file.

### 4. Test Twitter Connection

To test that your credentials are valid, you can list your latest followers with:

```shell
php dynacover fetch followers
```

If everything is set up correctly, you will see a list with your 10 latest followers.

### 5. Preview your Cover

To preview your cover without uploading it to Twitter, run:

```shell
php dynacover twitter generate
```

This will use the default `cover_basic.json` template. You can specify a template with the `template=template_path` parameter:

```shell
php dynacover twitter generate template=app/Resources/templates/cover_neon.json
```

Built-in templates are located in the `app/Resources/templates` directory.

You can also create your own templates in any preferred location and pass the template json path as parameter, relative to the application root folder. Check the included templates to see how it works.

Covers are generated in the root of the application folder, with the name `latest_header.png`. Check the generated image before uploading it to Twitter to confirm it has your latest followers, and it looks like you expect.

### 6. Upload to Twitter

To upload the latest generated cover to twitter, run:

```shell
php dynacover cover upload
```
To generate **and** update your cover using the template that is configured as default in the app config, run:

```shell
php dynacover cover update
```

### 7. Set Up Crontab (Optional)

For this to be completely dynamic and update frequently, you'll need to include the script to your Crontab or equivalent.

To open the current user's crontab, run:

```shell
crontab -e
```

This will open up a text editor. You should include the full paths to both the `php` executable and the `dynacover` script, like in this example which will update the cover every 5 minutes:

```
*/5 * * * * /usr/bin/php /home/erika/dynacover/dynacover cover update > /dev/null 2>&1
```

