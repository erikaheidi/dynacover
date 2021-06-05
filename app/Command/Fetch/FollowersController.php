<?php

namespace App\Command\Fetch;

use App\Service\TwitterServiceProvider;
use Minicli\Command\CommandController;
use Abraham\TwitterOAuth\TwitterOAuth;

class FollowersController extends CommandController
{
    public function handle()
    {
        /** @var TwitterServiceProvider $twitter */
        $twitter = $this->getApp()->twitter;
        $followers = $twitter->client->get('/followers/list', [
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