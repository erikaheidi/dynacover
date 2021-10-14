# dynacover

A PHP GD + TwitterOAuth CLI app to dynamically generate Twitter header images and optionally upload them via the API. This enables you to build cool little tricks, like showing your latest followers or sponsors, your latest content created, a qrcode to something, a progress bar for a goal, and whatever you can think of.

Other types of dynamic banners can also be generated. Dynacover uses [erikaheidi/gdaisy](https://github.com/erikaheidi/gdaisy) for image manipulation based on templates.

<p align="center">
<img src="https://user-images.githubusercontent.com/293241/120888813-b559f700-c5fa-11eb-901f-0dac22afd662.png" alt="dynamic twitter profile header"/>
</p>

This guide shows how Dynacover was built: [How to Dynamically Update Twitter Cover Image to Show Latest Followers Using PHP GD and TwitterOAuth](https://dev.to/erikaheidi/how-to-dynamically-update-twitter-cover-image-to-show-latest-followers-using-php-gd-and-twitteroauth-62n). Please notice that the version that is compatible with this tutorial is [0.1.0](https://github.com/erikaheidi/dynacover/releases/tag/0.1), the initial release. Newer versions use [erikaheidi/gdaisy](https://github.com/erikaheidi/gdaisy) as dependency to work with JSON templates.

## Requirements

You can run Dynacover in two different ways:

- **with Docker**: the simplest way to run Dynacover is by using the public [erikaheidi/dynacover](https://hub.docker.com/repository/docker/erikaheidi/dynacover) Docker image.
  - to further customize your cover, you can clone the [dynacover repo](https://hub.docker.com/repository/docker/erikaheidi/dynacover) to customize banner resources (JSON template and header images, both located at `app/Resources`), then build a local copy of the Dynacover Docker image to use your custom changes.
- **with a local PHP environment**: this will require `php-cli` 7.4+, Composer, and a few extensions, such as `php-gd`, `php-mbstring`, `php-curl`, and `php-json`.

### Obtaining Required Tokens

To upload your header images, you'll need to register an application within the [Twitter Developers Portal](https://dev.twitter.com) and obtain **4 tokens**:

- Consumer / App Token
- Consumer / App Secret
- User / Access Token
- User / Access Token Secret

Additionally, you can set up a [Personal GitHub API key](https://github.com/settings/tokens) to fetch your sponsors, in case you are enrolled in the GitHub Sponsors program. In this case, you should use the included `cover_sponsors.json` template. This is optional.

## Running Dynacover with Docker (Recommended)
Dynacover has a self-contained Docker image that you can use to run Dynacover with a single command. The container receives API credentials via a `.env` file provided at run time.

### Using the default Docker image

First, you'll need to set up your API credentials. [Check this blog post](https://dev.to/erikaheidi/how-to-dynamically-update-twitter-cover-image-to-show-latest-followers-using-php-gd-and-twitteroauth-62n) for detailed instructions on how to obtain these.

Create an environment file using [this template](https://github.com/erikaheidi/dynacover/blob/main/.env.example). In this example, we'll name the file `.dynacover`.

```bash
cd ~
nano .dynacover
```

```ini
# Credentials
TW_CONSUMER_KEY=YOUR_APP_KEY
TW_CONSUMER_SECRET=YOUR_APP_SECRET
TW_USER_TOKEN=USER_ACCESS_TOKEN
TW_USER_TOKEN_SECRET=USER_ACCESS_TOKEN_SECRET
GITHUB_TOKEN=GITHUB_PERSONAL_TOKEN

# Default Template
DEFAULT_TEMPLATE=app/Resources/templates/cover_basic.json
```

Save the file once you're done adding your API credentials there. You can also use this file to set up your default template of choice.

To test that your credentials are valid, you can list your latest followers with the following command, which will spin up a temporary container and inject the environment variables contained in `.dynacover`:

```shell
docker container run --env-file .dynacover --rm -v $(pwd) erikaheidi/dynacover php dynacover fetch followers
```
The following command will generate a dynamic cover based on the default template specified in `.dynacover`, and upload the resulting image as your Twitter header.

```bash
docker container run --env-file .dynacover --rm -v $(pwd) erikaheidi/dynacover php dynacover cover update
```

### Building a custom header

To customize your header with your own JSON template file and PNG images, you'll need to build a local Docker image with your changes. You don't need to have PHP installed locally. You need only Docker for that.

Start by cloning this repository to your local development machine:

```bash
git clone https://github.com/erikaheidi/dynacover.git
```

The Dockerfile included within the application will copy the files in the current directory into the image. When you are finished with any changes you made to customize your banner, you can run:

```bash
docker build . -t myuser/dynacover
```

Then, you can run dynacover using your custom image with:

```bash
docker container run --env-file .dynacover --rm -v $(pwd) myuser/dynacover php dynacover cover update
```

## Running Dynacover with a local PHP environment

It is also possible to run Dynacover in a local PHP environment. This will require:

- PHP (cli only) 7.4+
- GD / PHP-GD
- Curl
- Composer

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
This will install a few dependencies and generate a `config.php` file in the root folder of your application.

### 3. Set Up Twitter Credentials

Open the generated `config.php` file and set up your credentials:

```php
#config.php
<?php

return [
    //Twitter API Keys
    'twitter_consumer_key' => getenv('TW_CONSUMER_KEY') ?: 'APP_CONSUMER_KEY',
    'twitter_consumer_secret' => getenv('TW_CONSUMER_SECRET') ?: 'APP_CONSUMER_SECRET',
    'twitter_user_token' => getenv('TW_USER_TOKEN') ?: 'USER_ACCESS_TOKEN',
    'twitter_token_secret' => getenv('TW_USER_TOKEN_SECRET') ?: 'USER_ACCESS_TOKEN_SECRET',

    //GitHub Personal Token (for templates using GH Sponsors)
    'github_api_bearer' => getenv('GITHUB_TOKEN') ?: 'GITHUB_API_BEARER_TOKEN',

    //Default Template
    'default_template' => getenv('DEFAULT_TEMPLATE') ?: 'app/Resources/templates/cover_basic.json'
];
```
_The `github_api_bearer` token is optional, only in case you want to use the GitHub API to fetch Sponsors._

You need to replace the following strings with the corresponding tokens:

- `APP_CONSUMER_KEY`
- `APP_CONSUMER_SECRET`
- `USER_ACCESS_TOKEN`
- `USER_ACCESS_TOKEN_SECRET`
- `GITHUB_API_BEARER_TOKEN`

### 4. Test Twitter Connection

To test that your credentials are valid, you can list your latest followers with:

```shell
php dynacover fetch followers
```

If everything is set up correctly, you will see a list with your 10 latest followers.

### 5. Choose a Template and Preview your Cover

To preview your cover without uploading it to Twitter, run:

```shell
php dynacover generate twitter
```

This will use the default `cover_basic.json` template. You can specify a template with the `template=template_path` parameter:

```shell
php dynacover generate twitter template=app/Resources/templates/cover_neon.json
```

Built-in templates are located in the `app/Resources/templates` directory. You can also create your own templates in any preferred location and pass the template json path as parameter, relative to the application root folder. Check the included templates to see how it works. Ideally, you should choose a default template and set it up within the `dynacover` script configuration.

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

For this to be completely dynamic and update frequently, you'll need to include the script to your Crontab or equivalent. First make sure you set up the `default_template` config parameter in the `dynacover` script to your preferred template. By default, this is `app/Resources/templates/cover_basic.json`.

To open the current user's crontab, run:

```shell
crontab -e
```

This will open up a text editor. You should include the full paths to both the `php` executable and the `dynacover` script, like in this example which will update the cover every 5 minutes:

```
*/5 * * * * /usr/bin/php /home/erika/dynacover/dynacover cover update > /dev/null 2>&1
```
### Available Templates

### `cover_basic.json`
The default template shows latest 5 Twitter followers.

<p align="center">
<img src="https://user-images.githubusercontent.com/293241/120888813-b559f700-c5fa-11eb-901f-0dac22afd662.png" alt="cover basic"/>
</p>

### `cover_colorful.json`
Similar to the basic cover, but with a more colorful background. Shows latest 5 Twitter followers.

<p align="center">
<img src="https://user-images.githubusercontent.com/293241/120889018-8abc6e00-c5fb-11eb-85ee-ba85d95851b7.png" alt="cover colorful"/>
</p>


### `cover_neon.json`
This template shows your latest 5 Twitter followers in smaller size, in a blue-neon style header.

<p align="center">
<img src="https://user-images.githubusercontent.com/293241/120889083-d53dea80-c5fb-11eb-86c6-e08420de124e.png" alt="cover neon"/>
</p>

### `cover_sponsors.json`
This template uses the Github image source to obtain sponsors and include them in the banner. Make sure you have set up your GH token on the `credentials.php` file.

<p align="center">
<img src="https://user-images.githubusercontent.com/293241/120888781-8c396680-c5fa-11eb-8d1d-f3889fdd06e7.png" alt="cover with github sponsors"/>
</p>

## Recent Interactions Banner

The "interactions banner" is generated based on your recent interactions and can be limited to only include mutuals (people that follows you and you follow them back).

```shell
php dynacover generate interactions
```

<p align="center">
<img src="https://user-images.githubusercontent.com/293241/124271726-1a433700-db3e-11eb-851b-2812d9df923b.png" alt="recent interactions twitter banner"/>
</p>


For mutuals only, include the `--mutuals` flag:

```shell
php dynacover generate interactions --mutuals
```

_Please notice that the "mutuals" version may have a limited set of results after filtering your latest interactions (~200 mentions)._

