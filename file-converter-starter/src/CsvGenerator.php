<?php

declare(strict_types=1);

namespace FileConverter;


use phpDocumentor\Reflection\Types\Resource_;

class CsvGenerator implements Generator
{

    public function generate(array $source): string
    {
        $csvString='';
        foreach ($source as $row){
            $csvString.=implode(',',$row)."\n";
        }
        return $csvString;
    }
}