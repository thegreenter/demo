<?php

declare(strict_types=1);

use Greenter\Data\Generator\InvoiceStore;

require __DIR__ . '/../../vendor/autoload.php';

$util = Util::getInstance();

$invoice =  $util->getGenerator(InvoiceStore::class)->create();

try {
    $pdf = $util->getPdf($invoice);
    $util->showPdf($pdf, $invoice->getName().'.pdf');
} catch (Exception $e) {
    var_dump($e);
}