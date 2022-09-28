<?php

namespace App\Command\Generate;

use App\ImageSource;
use App\Storage;
use App\Template;
use GDaisy\Placeholder\ImagePlaceholder;
use GDaisy\PlaceholderInterface;
use Minicli\Command\CommandController;

class TwitterController extends CommandController
{
    /**
     * @throws \Exception
     */
    public function handle(): void
    {
        $template_dir = $this->getApp()->config->templates_dir;
        $template_file = $this->getApp()->config->default_template;
        $images_dir = $this->getApp()->config->images_dir;
        $output_dir = $this->getApp()->config->output_dir;

        if ($this->hasParam('template')) {
            $template_file = $this->getParam('template');
        }

        if (!is_file($template_file)) {
            $template_file = $template_dir . '/' . $template_file;
            if (!is_file($template_file)) {
                throw new \Exception("Template $template_file not found.");
            }
        }

        $save_path = $output_dir. '/latest_header.png';
        $template = Template::create($template_file);

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
                $resource_image = $placeholder->image;

                if (!is_file($resource_image)) {
                    $resource_image = $images_dir . '/' . $placeholder->image;
                }

                $placeholder->apply($template->getResource(), ['image_file' => $resource_image]);
                continue;
            }

            $placeholder->apply($template->getResource(), $featured[$key]);
        }

        $template->write($save_path);
        $this->getPrinter()->info("Finished generating cover at $save_path, using $template_file as template.");
    }
}