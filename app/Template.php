<?php

namespace App;

class Template
{
    public string $name;
    public int $width;
    public int $height;
    public array $placeholders = [];
    protected $resource;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    static function create(string $filename): Template
    {
        $template = new Template(basename($filename));
        $template->loadJson($filename);

        return $template;
    }

    public function loadJson(string $json_file)
    {
        $json_content = json_decode(file_get_contents($json_file), true);

        $this->width = $json_content['width'];
        $this->height = $json_content['height'];

        foreach ($json_content['elements'] as $key => $element) {
            $this->addPlaceholder($key, $element['width'], $element['height'], $element['pos_x'], $element['pos_y'], $element['image'] ?? null);
        }
    }

    public function addPlaceholder(string $key, int $width, int $height, int $pos_x = 0, int $pos_y = 0, string $image = null)
    {
        $this->placeholders[$key] = [
            'width' => $width,
            'height' => $height,
            'pos_x' => $pos_x,
            'pos_y' => $pos_y,
            'image' => $image
        ];
    }

    public function getPlaceholder(string $key)
    {
        return $this->placeholders[$key] ?? null;
    }

    public function build(array $images)
    {
        foreach ($this->placeholders as $key => $placeholder) {

            if ($placeholder['image']) {
                $this->apply($key, __DIR__ . '/../' . $placeholder['image']);
                continue;
            }

            $this->apply($key, $images[$key]['avatar']);
        }
    }

    public function write(string $path)
    {
        imagepng($this->getResource(), $path);
    }

    public function apply(string $key, string $image_file)
    {
        $placeholder = $this->getPlaceholder($key);

        if ($placeholder && is_file($image_file)) {
            $resource = $this->getResource();
            $stamp = $this->getStampResource($image_file);
            $info = getimagesize($image_file);

            imagecopyresized(
                $resource,
                $stamp,
                $placeholder['pos_x'],
                $placeholder['pos_y'],
                0,
                0,
                $placeholder['width'],
                $placeholder['height'],
                $info[0],
                $info[1]
            );
        }
    }

    public function getResource()
    {
        if (!$this->resource) {
            $this->resource = imagecreatetruecolor($this->width, $this->height);
        }

        return $this->resource;
    }

    public function getStampResource(string $image_file)
    {
        $info = getimagesize($image_file);
        $extension = image_type_to_extension($info[2]);

        if (strtolower($extension) == '.png') {
            return imagecreatefrompng($image_file);
        }

        if (strtolower($extension) == '.jpeg' OR strtolower($extension) == '.jpg') {
            return imagecreatefromjpeg($image_file);
        }

        return null;
    }
}