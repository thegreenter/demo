<?php

use Greenter\Data\Generator\InvoiceIcbperStore;

require __DIR__ . '/../../vendor/autoload.php';

$util = Util::getInstance();

$invoice =  $util->getGenerator(InvoiceIcbperStore::class)->create();

try {
    $pdf = $util->getPdf($invoice);
    $util->showPdf($pdf, $invoice->getName().'.pdf');
} catch (Exception $e) {
    var_dump($e);
}