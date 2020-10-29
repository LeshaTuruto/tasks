<?php

declare(strict_types=1);

namespace FileConverter;


interface Generator
{
    public function generate(array $source):string;
}