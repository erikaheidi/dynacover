<?php

namespace App;

use Minicli\App;

interface ImageSource
{
    public function getImageList(App $app): array;
}