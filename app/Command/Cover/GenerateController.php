<?php

namespace App\Command\Cover;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Template;
use Minicli\Command\CommandController;

class GenerateController extends CommandController
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
            'count' => 5
        ]);

        if (!isset($followers->users)) {
            $this->getPrinter()->error("An error occurred.");
            return 1;
        }

        $count = 1;
        $featured = [];
        foreach ($followers->users as $follower) {
            $avatar = $this->downloadAvatar($follower->profile_image_url_https);
            $featured["tw$count"] = [
                'screen_name' => $follower->screen_name,
                'avatar' => $avatar
            ];

            $count++;
        }

        $save_path = __DIR__ . '/../../../latest_header.png';
        $template = Template::create(__DIR__ . '/../../Resources/templates/cover_basic.json');
        $template->build($featured);
        $template->write($save_path);
        $this->getPrinter()->info("Finished generating cover at $save_path.");

        return 0;
    }

    public function downloadAvatar($url): string
    {
        $file_contents = file_get_contents($url);

        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($url);

        $image = fopen($file_path, "w+");
        fwrite($image, $file_contents);
        fclose($image);

        return $file_path;
    }
}
