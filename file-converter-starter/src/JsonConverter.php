<?php

declare(strict_types=1);

namespace FileConverter;


class JsonConverter implements ArrayConverter
{

    public function convertToArray(string $source): array
    {
        $array=json_decode($source,true);
        return $array;
    }
}