<?php

namespace App\ImageSource;

use App\ApiModel;
use App\ImageSource;
use App\Service\GithubServiceProvider;
use App\Storage;
use Minicli\App;

class GhSponsorImageSource implements ImageSource
{
    static string $prefix = "sp";

    public function getImageList(App $app, int $limit = 5, array $params = []): array
    {
        /** @var GithubServiceProvider $github */
        $github = $app->github;

        $sponsors = $github->getSponsorsList();

        if (!isset($sponsors)) {
            return [];
        }

        $count = 1;
        $featured = [];

        shuffle($sponsors);

        /** @var ApiModel $sponsor */
        foreach ($sponsors as $sponsor) {
            $avatar = Storage::downloadImage($sponsor->avatarUrl);
            $featured[self::$prefix . "$count"] = [
                'screen_name' => $sponsor->login,
                'avatar' => $avatar,
                'image_file' => $avatar
            ];

            $count++;
        }

        return $featured;
    }
}