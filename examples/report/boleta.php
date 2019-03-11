<?php

use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
require __DIR__ . '/../../vendor/autoload.php';

$util = Util::getInstance();

// Venta
$invoice = new Invoice();
$invoice->setTipoDoc('03')
    ->setSerie('B001')
    ->setCorrelativo('1')
    ->setFechaEmision(new DateTime())
    ->setTipoMoneda('PEN')
    ->setClient($util->shared->getClientPerson())
    ->setMtoOperGravadas(200)
    ->setMtoOperExoneradas(0)
    ->setMtoOperInafectas(0)
    ->setMtoIGV(36)
    ->setMtoImpVenta(100)
    ->setCompany($util->shared->getCompany());

$item1 = new SaleDetail();
$item1->setCodProducto('C023')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PROD 1')
    ->setIgv(18)
    ->setTipAfeIgv('10')
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(56);

$items = $util->generator($item1, 10);

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON CIEN CON 00/100 SOLES');

$invoice->setDetails($items)
    ->setLegends([$legend]);

try {
    $pdf = $util->getPdf($invoice);
    $util->showPdf($pdf, $invoice->getName().'.pdf');
} catch (Exception $e) {
    var_dump($e);
}