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
        $this->getApp()->runCommand(['dynacover', 'generate', 'twitter']);
    }
}
