<?php

require 'vendor/autoload.php';

use Greenter\Ws\Reader\DomCdrReader;
use Greenter\Ws\Reader\XmlReader;
use Greenter\Zip\ZipDecompressDecorator;
use Greenter\Zip\ZipFly;

function getXmlFromZip(?string $cdrContent): string
{
    $decompressor = new ZipDecompressDecorator(new ZipFly());

    $filter = function ($filename) {
        $fileExtension = pathinfo($filename, PATHINFO_EXTENSION);

        return 'xml' === strtolower($fileExtension);
    };
    $files = $decompressor->decompress($cdrContent, $filter);

    return 0 === count($files) ? '' : $files[0]['content'];
}

$zipContent = file_get_contents('path/cdr/R-20000000001-01-F001-1.zip');
if ($zipContent === false) {
    echo 'Error abriendo archivo zip'.PHP_EOL;
    exit();
}

$cdrReader = new DomCdrReader(new XmlReader());

$xml = getXmlFromZip($zipContent);

$cdr = $cdrReader->getCdrResponse($xml);

var_dump($cdr);