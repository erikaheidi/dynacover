<?php

namespace App\Command\Cover;

use App\Storage;
use Minicli\Command\CommandController;

class UpdateController extends CommandController
{
    public function handle()
    {
        $template_file = $this->hasParam('template') ? $this->getParam('template') : $this->getApp()->config->default_template;

        $this->getPrinter()->info("Generating new cover...");
        $this->getApp()->runCommand(['dynacover', 'generate', 'twitter', "template=$template_file"]);
        $this->getApp()->runCommand(['dynacover', 'cover', 'upload']);
    }
}