<?php

require __DIR__ . '/../../vendor/autoload.php';

$util = Util::getInstance();

$note =  $util->getGenerator(\Greenter\Data\Generator\NoteStore::class)->create();

try {
    $pdf = $util->getPdf($note);
    $util->showPdf($pdf, $note->getName().'.pdf');
} catch (Exception $e) {
    var_dump($e);
}