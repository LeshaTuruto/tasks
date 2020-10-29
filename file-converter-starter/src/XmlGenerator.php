<?php

declare(strict_types=1);

namespace FileConverter;

use SimpleXMLElement;

class XmlGenerator implements Generator
{

    public function generate(array $source): string
    {
        $xmlData=new SimpleXMLElement('<?xml version="1.0"?><data></data>');
        $this->array_to_xml($source,$xmlData);
        return $xmlData->asXML();
    }
    private function array_to_xml(array $data, SimpleXMLElement $xml_data ) {
        foreach( $data as $key => $value ) {
            if( is_array($value) ) {
                if( is_numeric($key) ){
                    $key = 'item'.$key;
                }
                $subnode = $xml_data->addChild($key);
                $this->array_to_xml($value, $subnode);
            } else {
                $xml_data->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
}