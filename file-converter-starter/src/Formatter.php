<?php

declare(strict_types=1);

namespace FileConverter;


class Formatter
{
    public array $formats = [];
    public function __construct(string $initFilePath)
    {
        $initFile=fopen($initFilePath,'r') or die("failed to open file");
        while(!feof($initFile))
        {
            $this->formats[]=trim(fgets($initFile));
        }
        fclose($initFile);
    }
    public function isSupported(string $format):bool{
        return in_array($format,$this->formats);
    }
    public function addNewFormat(string $format):bool{
        $this->formats[]=$format;
    }
}