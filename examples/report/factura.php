<?php

require __DIR__ . '/../../vendor/autoload.php';

$util = Util::getInstance();

$invoice =  $util->getGenerator(\Greenter\Data\Generator\InvoiceStore::class)->create();

try {
    $pdf = $util->getPdf($invoice);
    $util->showPdf($pdf, $invoice->getName().'.pdf');
} catch (Exception $e) {
    var_dump($e);
}