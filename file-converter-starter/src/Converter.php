<?php

declare(strict_types=1);

namespace FileConverter;

use SimpleXMLElement;

class Converter
{
    private Generator $Gen;
    private ArrayConverter $Conv;
    private Formatter $Form;
    public function __construct($initFilePath)
    {
        $this->Form=new Formatter($initFilePath);
    }

    public function convert(\SplFileObject $file, string $outputFormat, string $outputFilePath):void
    {
        $inputFormat=$file->getExtension();
        $handle=fopen($outputFilePath,"w");
        if($this->Form->isSupported($outputFormat)&&$this->Form->isSupported($inputFormat)){
            $content=$file->fread($file->getSize());
            switch ($inputFormat){
                case 'csv':
                    $this->Conv=new CsvConverter();
                    break;
                case 'json':
                    $this->Conv=new JsonConverter();
                    break;
                case 'xml':
                    $this->Conv=new XmlConverter();
                    break;
            }
            switch ($outputFormat){
                case 'csv':
                    $this->Gen=new CsvGenerator();
                    break;
                case 'json':
                    $this->Gen=new JsonGenerator();
                    break;
                case 'xml':
                    $this->Gen=new XmlGenerator();
                    break;
            }
            $convertedString=$this->Gen->generate($this->Conv->convertToArray($content));
            fwrite($handle,$convertedString);
        }
        else{
            fwrite($handle,'Unsupported format!');
        }
        fclose($handle);
    }
}
