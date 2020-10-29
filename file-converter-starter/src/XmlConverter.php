<?php

declare(strict_types=1);

namespace FileConverter;


class XmlConverter implements ArrayConverter
{

    public function convertToArray(string $source): array
    {
        $xml = simplexml_load_string($source, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        return $array;
    }
}