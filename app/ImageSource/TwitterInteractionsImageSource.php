<?php

namespace App\ImageSource;

use App\ImageSource;
use App\Service\TwitterServiceProvider;
use App\Storage;
use Minicli\App;

class TwitterInteractionsImageSource implements ImageSource
{
    static string $prefix = "int";

    public function getImageList(App $app, int $limit = 5, array $params = []): array
    {
        /** @var TwitterServiceProvider $twitter */
        $twitter = $app->twitter;
        $mutualsOnly = $params['mutuals'] ?? false;

        return $this->getFeaturedByMentions($twitter, $limit, self::$prefix, $mutualsOnly);
    }

    public function getFeaturedByMentions(TwitterServiceProvider $twitter, $limit, $prefix = "int", $mutualsOnly = false): array
    {
        $response = $twitter->client->get('/statuses/mentions_timeline', [
            'count' => 200,
            'include_entities' => false
        ]);

        $interactions = [];
        $users = [];

        //sort interactions by user id + count
        foreach ($response as $tweet) {
            $users[$tweet->user->id] = $tweet->user;
            $int_count = $interactions[$tweet->user->id] ?? 0;
            $interactions[$tweet->user->id] = $int_count+1;
        }
        arsort($interactions);

        if ($mutualsOnly) {
            $users_ids = array_keys($users);
            $query_chunks = array_chunk($users_ids, 100);
            $validation_queries = [];

            foreach ($query_chunks as $chunk) {
                $users_ids_list = implode(',', $chunk);
                $validation_queries[] = $this->getRelationships($twitter, $users_ids_list);
            }

            $validated = [];
            foreach ($validation_queries as $relationships) {
                foreach ($relationships as $relationship) {
                    if (isset($relationship->connections) && (count($relationship->connections) > 1)) {
                        $validated[$relationship->id_str] = $users[$relationship->id_str];
                    }
                }
            }

            $users = $validated;
        }

        $featured = [];
        $count = 1;

        //build an array with user info
        foreach ($interactions as $user_id => $interaction) {
            $user = $users[$user_id] ?? null;
            if ($user) {
                if (!$user->profile_image_url_https) {
                    continue;
                }

                $avatar_path = str_replace('normal', 'bigger', $user->profile_image_url_https);
                $avatar = Storage::downloadImage($avatar_path);

                if ($avatar) {
                    $featured[$prefix . "$count"] = [
                        'screen_name' => $user->screen_name,
                        'avatar' => $avatar,
                        'image_file' => $avatar
                    ];

                    $count++;
                }
            }

            if ($count > $limit) {
                break;
            }
        }

        return $featured;
    }

    public function getRelationships(TwitterServiceProvider $twitter, string $lookup)
    {
        return $twitter->client->get("friendships/lookup", [
            "user_id" => $lookup
        ]);
    }

}