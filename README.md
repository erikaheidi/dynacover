## About

Dynacover is a PHP GD + TwitterOAuth CLI app to dynamically generate Twitter header images and upload them via the API. This enables you to build cool little tricks, like showing your latest followers or GitHub sponsors, your latest content created, a qrcode to something, a progress bar for a goal, and whatever you can think of.

## Installation

You can run Dynacover in three different ways:

- as a **GitHub action**: the easiest way to run Dynacover is by setting it up in a public repository with [GitHub Actions](https://docs.github.com/en/actions), using repository secrets for credentials. [Follow this step-by-step guide](https://github.com/erikaheidi/dynacover/wiki/Setting-Up-Dynacover-with-GitHub-Actions) to set this up - no coding required.
- with **Docker**: you can use the public [erikaheidi/dynacover](https://hub.docker.com/repository/docker/erikaheidi/dynacover) Docker image to run Dynacover with a single command, no PHP required. [Follow this guide](https://github.com/erikaheidi/dynacover/wiki/Running-Dynacover-with-Docker) to set this up.
  - to further customize your cover, you can clone the [dynacover repo](https://hub.docker.com/repository/docker/erikaheidi/dynacover) to customize banner resources (JSON template and header images, both located at `app/Resources`), then build a local copy of the Dynacover Docker image to use your custom changes.
- with a **PHP CLI environment**: this will require `php-cli` 7.4+, Composer, and a few extensions: `php-gd`, `php-mbstring`, `php-curl`, and `php-json`. [Follow this guide](https://github.com/erikaheidi/dynacover/wiki/Running-Dynacover-on-a-PHP-CLI-environment) to set it up.

### Obtaining Required Tokens

To upload your header images, you'll need to register an application within the [Twitter Developers Portal](https://dev.twitter.com) and obtain **4 tokens**:

- Consumer / App Token
- Consumer / App Secret
- User / Access Token
- User / Access Token Secret

Additionally, you can set up a [Personal GitHub API key](https://github.com/settings/tokens) to fetch your sponsors, in case you are enrolled in the GitHub Sponsors program. In this case, you should use the included `cover_sponsors.json` template. This is optional.


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


