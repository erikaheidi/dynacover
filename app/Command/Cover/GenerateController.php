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
        $template_file = $this->hasParam('template') ? $this->getParam('template') : $this->getApp()->config->default_template;

        $this->getApp()->runCommand(['dynacover', 'generate', 'twitter', "template=$template_file"]);
    }
}
