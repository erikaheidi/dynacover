<?php

namespace App\Command\Fetch;

use Minicli\Command\CommandController;
use Abraham\TwitterOAuth\TwitterOAuth;

class FollowersController extends CommandController
{
    public function handle()
    {
        $api_token = $this->getApp()->config->twitter_consumer_key;
        $api_secret = $this->getApp()->config->twitter_consumer_secret;
        $access_token = $this->getApp()->config->twitter_user_token;
        $token_secret = $this->getApp()->config->twitter_token_secret;

        $client = new TwitterOAuth($api_token, $api_secret, $access_token, $token_secret);
        $followers = $client->get('/followers/list', [
            'skip_status' => true,
            'count' => 10
        ]);

        $this->getPrinter()->info("Latest Followers", true);

        foreach ($followers->users as $follower) {
            $this->getPrinter()->info($follower->screen_name);
        }

        $this->getPrinter()->info("Finished.");
        return 0;
    }
}