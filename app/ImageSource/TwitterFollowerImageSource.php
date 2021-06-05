<?php

namespace App\ImageSource;

use App\ImageSource;
use App\Service\TwitterServiceProvider;
use App\Storage;
use Minicli\App;

class TwitterFollowerImageSource implements ImageSource
{
    static string $prefix = "tw";

    public function getImageList(App $app, $limit = 5): array
    {
        /** @var TwitterServiceProvider $twitter */
        $twitter = $app->twitter;

        $followers = $twitter->client->get('/followers/list', [
            'skip_status' => true,
            'count' => $limit
        ]);

        if (!isset($followers->users)) {
            return [];
        }

        $count = 1;
        $featured = [];

        foreach ($followers->users as $follower) {
            //get bigger thumbnail
            $avatar_path = str_replace('normal', 'bigger', $follower->profile_image_url_https);
            $avatar = Storage::downloadImage($avatar_path);
            $featured[self::$prefix . "$count"] = [
                'screen_name' => $follower->screen_name,
                'avatar' => $avatar
            ];

            $count++;
        }

        return $featured;
    }
}