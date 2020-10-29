<?php

declare(strict_types=1);

namespace FileConverter;


class JsonGenerator implements Generator
{

    public function generate(array $source): string
    {
        $jsonstring=json_encode($source);
        return $jsonstring;
    }
}