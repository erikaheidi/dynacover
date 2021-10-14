<?php

return [
    //Twitter API Keys
    'twitter_consumer_key' => getenv('TW_CONSUMER_KEY') ?: 'APP_CONSUMER_KEY',
    'twitter_consumer_secret' => getenv('TW_CONSUMER_SECRET') ?: 'APP_CONSUMER_SECRET',
    'twitter_user_token' => getenv('TW_USER_TOKEN') ?: 'USER_ACCESS_TOKEN',
    'twitter_token_secret' => getenv('TW_USER_TOKEN_SECRET') ?: 'USER_ACCESS_TOKEN_SECRET',

    //GitHub Personal Token (for templates using GH Sponsors)
    'github_api_bearer' => getenv('GITHUB_TOKEN') ?: 'GITHUB_API_BEARER_TOKEN',

    //Default Template
    'default_template' => getenv('DEFAULT_TEMPLATE') ?: 'app/Resources/templates/cover_basic.json'
];