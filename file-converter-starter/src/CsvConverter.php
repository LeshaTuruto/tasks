<?php

declare(strict_types=1);

namespace FileConverter;


class CsvConverter implements ArrayConverter
{

    public function convertToArray(string $csvString): array
    {
        $array = array_map("str_getcsv",explode("\n",$csvString));
        return $array;
    }
}