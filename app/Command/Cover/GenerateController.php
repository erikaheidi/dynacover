<?php

namespace App\Command\Cover;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Service\TwitterServiceProvider;
use App\Template;
use Minicli\Command\CommandController;
use App\Storage;

class GenerateController extends CommandController
{
    public function handle()
    {
        /** @var TwitterServiceProvider $twitter */
        $twitter = $this->getApp()->twitter;

        $followers = $twitter->client->get('/followers/list', [
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
            $avatar = Storage::downloadImage($follower->profile_image_url_https);
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


}