<?php

use App\Command\UploadCommand;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$app = new Application();
$app->add(new UploadCommand());
$app->run();

