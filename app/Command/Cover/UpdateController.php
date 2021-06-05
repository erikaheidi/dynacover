<?php

namespace App\Command\Cover;

use App\Storage;
use Minicli\Command\CommandController;

class UpdateController extends CommandController
{
    public function handle()
    {
        $this->getPrinter()->info("Generating new cover...");
        $this->getApp()->runCommand(['dynacover', 'generate', 'twitter']);
        $this->getApp()->runCommand(['dynacover', 'cover', 'upload']);
    }
}