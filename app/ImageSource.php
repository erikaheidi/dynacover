<?php

namespace App;

use Minicli\App;

interface ImageSource
{
    public function getImageList(App $app, int $limit = 5, array $params = []): array;
}