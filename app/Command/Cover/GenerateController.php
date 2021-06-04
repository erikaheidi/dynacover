<?php

namespace App\Command\Cover;

use Abraham\TwitterOAuth\TwitterOAuth;
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

        $users = $followers->users;
        $placeholders = $this->getPlaceholders();

        $cover_final = imagecreatetruecolor(1500, 500);

        foreach ($placeholders as $key => $placeholder) {
            $follower = $users[$key];
            $this->getPrinter()->info("Adding user to banner: $follower->screen_name");

            $path = $this->downloadAvatar($follower->profile_image_url);
            $resource = $this->getResource($path);
            $info = getimagesize($path);

            if ($resource) {
                imagecopyresampled(
                    $cover_final,
                    $resource,
                    $placeholder['pos_x'],
                    $placeholder['pos_y'],
                    0,
                    0,
                    $placeholder['width'],
                    $placeholder['height'],
                    $info[0],
                    $info[1]
                );
            }
        }

        //now finish with the cover image on top
        $cover = imagecreatefrompng(__DIR__ . '/../../Resources/cover_template.png');

        if ($cover) {
            imagecopyresampled(
                $cover_final,
                $cover,
                0,
                0,
                0,
                0,
                1500,
                500,
                1500,
                500
            );
        }

        $save_path = __DIR__ . '/../../../latest_header.png';
        imagepng($cover_final, $save_path);
        $this->getPrinter()->info("Finished generating cover at $save_path.");

        return 0;
    }

    public function getPlaceholders(): array
    {
        return [
            [
                'pos_x' => 486,
                'pos_y' => 272,
                'width' => 130,
                'height' => 130
            ],[
                'pos_x' => 670,
                'pos_y' => 272,
                'width' => 130,
                'height' => 130
            ],[
                'pos_x' => 859,
                'pos_y' => 272,
                'width' => 130,
                'height' => 130
            ],[
                'pos_x' => 1049,
                'pos_y' => 272,
                'width' => 130,
                'height' => 130
            ],[
                'pos_x' => 1236,
                'pos_y' => 272,
                'width' => 130,
                'height' => 130
            ]
        ];
    }

    public function getResource($path)
    {
        $info = getimagesize($path);
        $extension = image_type_to_extension($info[2]);

        if (strtolower($extension) == '.png') {
            return imagecreatefrompng($path);
        }

        if (strtolower($extension) == '.jpeg' OR strtolower($extension) == '.jpg') {
            return imagecreatefromjpeg($path);
        }

        return null;
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
