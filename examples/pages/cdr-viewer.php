<?php

declare(strict_types=1);

use PhpZip\ZipFile;

require __DIR__ . '/../../vendor/autoload.php';


if (!isset($_GET['f'])) {
    echo 'Parametro f requerido';
    exit();
}

$file = $_GET['f'];
$baseFiles = __DIR__.'/../../files';

$info = pathinfo($file);
$zipPath = $info['basename'];
$xmlPath = $info['filename'].'.xml';

if (file_exists($baseFiles.DIRECTORY_SEPARATOR.$xmlPath)) {
    header('Content-Type: text/xml');
    readfile($baseFiles.DIRECTORY_SEPARATOR.$xmlPath);

    exit();
}

if (!file_exists($baseFiles.DIRECTORY_SEPARATOR.$zipPath)) {
    echo 'Archivo zip no existe';
    exit();
}

$content = file_get_contents($baseFiles.DIRECTORY_SEPARATOR.$zipPath);
if ($content === false) {
    exit(400);
}

$zipFile = new ZipFile();
$zipFile->openFromString($content);
$zipFile->extractTo($baseFiles);

header('Content-Type: text/xml');
readfile($baseFiles.DIRECTORY_SEPARATOR.$xmlPath);
