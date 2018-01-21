<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

require __DIR__ . '/../../vendor/autoload.php';

// Cliente
$client = new Client();
$client->setTipoDoc('6')
    ->setNumDoc('20000000001')
    ->setRznSocial('EMPRESA 1');

// Venta
$invoice = new Invoice();
$invoice->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('123')
    ->setFechaEmision(new DateTime())
    ->setTipoMoneda('PEN')
    ->setClient($client)
    ->setMtoOperGravadas(200)
    ->setMtoOperExoneradas(0)
    ->setMtoOperInafectas(0)
    ->setMtoIGV(36)
    ->setMtoImpVenta(1300.43)
    ->setCompany(Util::getCompany());

$item1 = new SaleDetail();
$item1->setCodProducto('C023')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PROD 1')
    ->setDescuento(1)
    ->setIgv(18)
    ->setTipAfeIgv('10')
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(56);

$items = Util::generator($item1, 14);

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON MIL TRESCIENTOS CON 43/100');

$invoice->setDetails($items)
    ->setLegends([$legend]);

try {
    $pdf = Util::getPdf($invoice);
    Util::showPdf($pdf, 'factura.pdf');
} catch (Exception $e) {
    var_dump($e);
}