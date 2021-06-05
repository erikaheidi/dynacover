<?php

namespace App\Command\Cover;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Storage;
use Minicli\Command\CommandController;

class UpdateController extends CommandController
{
    public function handle()
    {
        $banner_path = Storage::root() . 'latest_header.png';
        $default_cover = $this->getApp()->config->default_template;
        $this->getPrinter()->info("Generating new cover...");
        $this->getApp()->runCommand(['dynacover', 'generate', 'twitter', "template=$default_cover"]);

        $api_token = $this->getApp()->config->twitter_consumer_key;
        $api_secret = $this->getApp()->config->twitter_consumer_secret;
        $access_token = $this->getApp()->config->twitter_user_token;
        $token_secret = $this->getApp()->config->twitter_token_secret;

        $client = new TwitterOAuth($api_token, $api_secret, $access_token, $token_secret);

        $post = [
            'width' => 1500,
            'height' => 500,
            'offset_top' => 0,
            'offset_left' => 0,
            'banner' => base64_encode(file_get_contents($banner_path))
        ];

        $this->getPrinter()->info("Uploading cover to Twitter...");
        $client->post('/account/update_profile_banner', $post);

        $this->getPrinter()->info("Finished uploading new banner.");
        return 0;
    }
}