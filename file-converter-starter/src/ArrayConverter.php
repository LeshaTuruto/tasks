<?php

declare(strict_types=1);

namespace FileConverter;


interface ArrayConverter
{
    public function convertToArray(string $source):array;
}