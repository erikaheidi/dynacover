<?php

return [
    //Twitter API Keys
    'twitter_consumer_key' => getenv('DYNA_TWITTER_KEY') ?: 'APP_CONSUMER_KEY',
    'twitter_consumer_secret' => getenv('DYNA_TWITTER_SECRET') ?: 'APP_CONSUMER_SECRET',
    'twitter_user_token' => getenv('DYNA_TWITTER_TOKEN') ?: 'USER_ACCESS_TOKEN',
    'twitter_token_secret' => getenv('DYNA_TWITTER_TOKEN_SECRET') ?: 'USER_ACCESS_TOKEN_SECRET',

    //GitHub Personal Token (for templates using GH Sponsors)
    'github_api_bearer' => getenv('DYNA_GITHUB_TOKEN') ?: 'GITHUB_API_BEARER_TOKEN',

    //Paths
    'templates_dir' => getenv('DYNA_TEMPLATES_DIR') ?: __DIR__ . '/app/Resources/templates',
    'images_dir' => getenv('DYNA_IMAGES_DIR') ?: __DIR__ . '/app/Resources/images',
    'output_dir' => getenv('DYNA_OUTPUT_DIR') ?: __DIR__,

    //Default Template
    'default_template' => getenv('DYNA_DEFAULT_TEMPLATE') ?: 'cover_basic.json'
];