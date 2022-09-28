<?php

namespace App\Command\Cover;

use App\Service\TwitterServiceProvider;
use App\Storage;
use Minicli\Command\CommandController;
use MongoDB\Driver\Exception\CommandException;

class UploadController extends CommandController
{
    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        /** @var TwitterServiceProvider $twitter */
        $twitter = $this->getApp()->twitter;
        $banner_path = Storage::root() . 'latest_header.png';

        if (!is_file($banner_path)) {
            throw new \Exception("Header not found at default location.");
        }

        $post = [
            'width' => 1500,
            'height' => 500,
            'offset_top' => 0,
            'offset_left' => 0,
            'banner' => base64_encode(file_get_contents($banner_path))
        ];

        $this->getPrinter()->info("Uploading cover to Twitter...");
        $twitter->client->post('/account/update_profile_banner', $post);

        $this->getPrinter()->info("Finished uploading new banner.");
    }

}