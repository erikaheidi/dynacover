<?php

namespace App;

use GDaisy\Template as GDaisy;

class Template extends GDaisy
{
    public array $sources;

    static function create(string $filename): GDaisy
    {
        $template = new Template(basename($filename));
        $template->loadJson($filename);

        return $template;
    }

    public function loadJson(string $json_file)
    {
        parent::loadJson($json_file);
        $json_content = json_decode(file_get_contents($json_file), true);
        $this->sources = $json_content['sources'];
    }
}