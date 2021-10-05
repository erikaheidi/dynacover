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

        $featured = [];

        shuffle($sponsors);

        /** @var ApiModel $sponsor */
        foreach ($sponsors as $index => $sponsor) {
            $avatar = Storage::downloadImage($sponsor->avatarUrl);
            $featured[sprintf('%s%d', self::$prefix, $index + 1)] = [
                'screen_name' => $sponsor->login,
                'avatar' => $avatar,
                'image_file' => $avatar
            ];
        }

        return $featured;
    }
}
