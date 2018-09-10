<?php
/**
 * Created by PhpStorm.
 * User: Giansalex
 * Date: 10/09/2018
 * Time: 12:46
 */

if (!isset($_GET['name'])) {
    die();
}
$name = $_GET['name'];
$name = preg_replace('/[^A-Za-z0-9_\.\-]/', '', $name);

$filename = __DIR__.'/../../files/'.$name;

if (!file_exists($filename)) {
    die();
}

header('Content-type: application/zip');
header('Content-Disposition: attachment; filename="'.$name.'"');
header("Pragma: no-cache");
header("Expires: 0");

readfile($filename);
unlink($filename);