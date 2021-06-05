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
        $template_file = 'app/Resources/templates/cover_basic.json';

        if ($this->hasParam('template')) {
            $template_file = $this->getParam('template');
        }

        if (!is_file(Storage::root() . $template_file)) {
            $this->getPrinter()->error("Template not found.");
            return 1;
        }

        $save_path = Storage::root() . 'latest_header.png';
        $template = Template::create(Storage::root() . $template_file);

        $featured = [];
        foreach ($template->sources as $key => $params) {
            /** @var ImageSource $source */
            $source = new $params['class'];
            $featured = array_merge($featured, $source->getImageList($this->getApp(), $params['count']));
        }

        $template->build($featured);
        $template->write($save_path);
        $this->getPrinter()->info("Finished generating cover at $save_path.");

        return 0;
    }
}