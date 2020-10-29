<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use FileConverter\Application;

(new Application())->run(__DIR__."/ini.txt",__DIR__.'/tests/'.$argv[1], $argv[2], $argv[3]);