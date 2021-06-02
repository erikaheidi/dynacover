# minicli

[Minicli](https://github.com/minicli/minicli) is an experimental dependency-free toolkit for building CLI-only applications in PHP created by @erikaheidi.
This repository is a template you can use to create a new application that has a single dependency: `minicli/minicli`.

### Why minicli

The current trend in software development is basing your project on a big pile of unknowns. There is nothing wrong in using third party software, but if more than 80% of your application is out of your control, things can get messy.
What usually happens is that you don't even know what packages you're depending on, when using the most popular frameworks.

Minicli was created as [an educational experiment](https://dev.to/erikaheidi/bootstrapping-a-cli-php-application-in-vanilla-php-4ee) and a way to go dependency-free when building simple command-line applications in PHP. It can be used for microservices, personal dev tools, bots and little fun things.


## Getting Started

You'll need `php-cli` and [Composer](https://getcomposer.org/) to get started.

Create a new project with:

```
composer create-project --prefer-dist minicli/application myapp
```

Once the installation is finished, you can run `minicli` it with:

```
cd myapp
./minicli
```

This will show you the default app signature:

```
usage: ./minicli help
```

The default `help` command that comes with minicli (`app/Command/Help/DefaultController.php`) auto-generates a tree of available commands:

```
./minicli help
```

```
Available Commands

help
└──test

```

The `help test` command, defined in `app/Command/Help/TestController.php`, shows an echo test of parameters:

```
./minicli help test user=erika name=value
```

```
Hello, erika!

Array
(
    [user] => erika
    [name] => value
)
```

### The simplest app

The simplest minicli script doesn't require using Command Controllers at all. You can delete the `app` folder and use `registerCommand` with an anonymous function, like this:

```
#!/usr/bin/php
<?php

if (php_sapi_name() !== 'cli') {
    exit;
}

require __DIR__ . '/vendor/autoload.php';

use Minicli\App;
use Minicli\Command\CommandCall;

$app = new App();
$app->setSignature('./minicli mycommand');

$app->registerCommand('mycommand', function(CommandCall $input) {
    echo "My Command!";

    var_dump($input);
});

$app->runCommand($argv);
```

## Created with Minicli

- [Dolphin](https://github.com/do-community/dolphin) - a CLI tool for managing DigitalOcean servers with Ansible.
