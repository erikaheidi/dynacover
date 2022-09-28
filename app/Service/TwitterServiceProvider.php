<?php


namespace App\Service;


use Abraham\TwitterOAuth\TwitterOAuth;
use Minicli\App;
use Minicli\ServiceInterface;

class TwitterServiceProvider implements ServiceInterface
{
    public TwitterOAuth $client;

    public function load(App $app): void
    {
        $api_token = $app->config->twitter_consumer_key;
        $api_secret = $app->config->twitter_consumer_secret;
        $access_token = $app->config->twitter_user_token;
        $token_secret = $app->config->twitter_token_secret;

        $this->client = new TwitterOAuth($api_token, $api_secret, $access_token, $token_secret);
    }
}