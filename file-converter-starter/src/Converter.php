<?php

declare(strict_types=1);

namespace FileConverter;

use SimpleXMLElement;

class Converter
{
    public function __construct(/* ??? */)
    {
    }

    public function convert(\SplFileObject $file, string $outputFormat, string $outputFilePath):void
    {
        if($file->getExtension()==$outputFormat){
            $this->convertSameExtensions($file,$outputFilePath);
        }
        else{
            switch ($outputFormat){
                case 'json':
                    $this->convertToJson($file, $outputFilePath);
                    break;
                case 'csv':
                    $this->convertToCsv($file, $outputFilePath);
                    break;
                case 'xml':
                    $this->convertToXml($file, $outputFilePath);
                    break;
                default:
                    $fileContent = $file->fread($file->getSize());
                    echo $fileContent;
                    $this->writeToFile($fileContent,$outputFilePath);

            }
        }
    }
    private function convertSameExtensions(\SplFileObject $file, string $outputFilePath):void{
        $fileContent = $file->fread($file->getSize());
        $this->writeToFile($fileContent, $outputFilePath);
    }
    private function convertToJson(\SplFileObject $file, string $outputFilePath):void{
        $fileContent = $file->fread($file->getSize());
        $finalContent = '';
        switch($file->getExtension()){

            case 'csv':
                $array = array_map("str_getcsv",explode("\n",$fileContent));
                $finalContent = json_encode($array);
                break;
            case 'xml':
                $xml_content = simplexml_load_string($fileContent);
                $finalContent = json_encode($xml_content);
                break;
            default:
                $finalContent = $fileContent;
        }
        $this->writeToFile($finalContent, $outputFilePath);
    }
    private function convertToCsv(\SplFileObject $file, string $outputFilePath):void{
        $fileContent = $file->fread($file->getSize());
        $finalContent = '';
        switch($file->getExtension()){

            case 'json':
                if($fileContent[0]!='['){
                    $fileContent='['.$fileContent.']';
                }
                $contentDecoded=json_decode($fileContent,true);
                $outputFile=fopen($outputFilePath,'w');
                $header=false;
                foreach ($contentDecoded as $row){
                    if (empty($header))
                    {
                        $header = array_keys($row);
                        fputcsv($outputFile, $header);
                        $header = array_flip($header);
                    }
                    fputcsv($outputFile,array_merge($header,$row));
                }
                fclose($outputFile);
                break;
            default:
                $finalContent = $fileContent;
                $this->writeToFile($finalContent, $outputFilePath);
        }
    }
    private function convertToXml(\SplFileObject $file, string $outputFilePath):void{
        $fileContent = $file->fread($file->getSize());
        $finalContent = '';
        $outputFile=fopen($outputFilePath,'w');
        switch($file->getExtension()){

            case 'json':
                $array = json_decode($fileContent,true);
                $xml = $this->convertJsonToXMl($array);
                fwrite($outputFile,$xml->asXML());
                break;
            case 'csv':
                $xml=$this->convertCsvToXml($fileContent);
                fwrite($outputFile,$xml);
                break;
            default:
                fwrite($outputFile,$fileContent);
        }
        fclose($outputFile);
    }
    private function convertCsvToXml(string $csvstring):string{
        $tmpstring=nl2br($csvstring);
        $array = explode('<br />',$tmpstring);
        foreach (array_keys($array) as $item){
            $array[$item]=trim($array[$item]);
        }
        $xmlstring='<result>';
        $header=explode(',',$array[0]);
        for($i = 1; $i < sizeof($array); $i++){
            $xmlstring.='<row>';
            $tmpArray=explode(',',$array[$i]);
            for($j = 0; $j < sizeof($tmpArray); $j++){
                $xmlstring.='<'.$header[$j].'>'.$tmpArray[$j].'</'.$header[$j].'>';
            }
            $xmlstring.='</row>';
        }
        $xmlstring.='</result>';
        return $xmlstring;
    }
    private function convertJsonToXMl($array,$xml=false):SimpleXMLElement{
        if($xml === false){
            $xml = new SimpleXMLElement('<result/>');
        }

        foreach($array as $key => $value){
            if(is_array($value)){
                $this->convertXMl($value, $xml->addChild($key));
            } else {
                $xml->addChild($key, strval($value));
            }
        }

        return $xml;
    }
    private function writeToFile(string $content, string $outputFilePath):void{
        $outputFile = fopen($outputFilePath,'w') or die('Error');
        fwrite($outputFile, $content);
        fclose($outputFile);
    }
}
