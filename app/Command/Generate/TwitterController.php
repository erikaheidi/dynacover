<?php

namespace App\Command\Generate;

use App\ImageSource;
use App\Service\TwitterServiceProvider;
use App\Storage;
use App\Template;
use Minicli\Command\CommandController;

class TwitterController extends CommandController
{
    public function handle(): int
    {
        $template = Storage::root() . 'app/Resources/templates/cover_basic.json';

        if ($this->hasParam('template')) {

            if (!is_file($template)) {
                $this->getPrinter()->error("Template not found.");
                return 1;
            }

            $template = $this->getParam('template');
        }

        $save_path = Storage::root() . 'latest_header.png';
        $template = Template::create($template);

        $featured = [];
        foreach ($template->sources as $key => $params) {
            /** @var ImageSource $source */
            $source = new $params['class'];
            $featured = array_merge($featured, $source->getImageList($this->getApp()));
        }

        $template->build($featured);
        $template->write($save_path);
        $this->getPrinter()->info("Finished generating cover at $save_path.");

        return 0;
    }
}