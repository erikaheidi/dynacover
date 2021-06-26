<?php

namespace App\Command\Generate;

use App\ImageSource;
use App\Storage;
use App\Template;
use GDaisy\ImagePlaceholder;
use GDaisy\PlaceholderInterface;
use Minicli\Command\CommandController;

class TwitterController extends CommandController
{
    public function handle(): int
    {
        $template_file = $this->getApp()->config->default_template;

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
        //build sources
        foreach ($template->sources as $key => $params) {
            /** @var ImageSource $source */
            $source = new $params['class'];
            $featured = array_merge($featured, $source->getImageList($this->getApp(), $params['count']));
        }

        //apply
        /**
         * @var string $key
         * @var PlaceholderInterface $placeholder
         */
        foreach ($template->placeholders as $key => $placeholder) {
            if ($placeholder instanceof ImagePlaceholder and $placeholder->image) {
                $placeholder->apply($template->getResource(), ['image_file' => $placeholder->image]);
                continue;
            }

            $placeholder->apply($template->getResource(), $featured[$key]);
        }

        $template->write($save_path);
        $this->getPrinter()->info("Finished generating cover at $save_path.");

        return 0;
    }
}