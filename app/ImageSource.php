<?php

namespace App;

use Minicli\App;

interface ImageSource
{
    public function getImageList(App $app, $limit = 5): array;
}