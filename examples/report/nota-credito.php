<?php
use Greenter\Model\Client\Client;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

require __DIR__ . '/../../vendor/autoload.php';

// Cliente
$client = new Client();
$client->setTipoDoc('6')
    ->setNumDoc('20000000001')
    ->setRznSocial('EMPRESA 1');

$note = new Note();
$note
    ->setTipDocAfectado('01')
    ->setNumDocfectado('F001-111')
    ->setCodMotivo('07')
    ->setDesMotivo('DEVOLUCION POR ITEM')
    ->setTipoDoc('07')
    ->setSerie('FF01')
    ->setFechaEmision(new DateTime())
    ->setCorrelativo('123')
    ->setTipoMoneda('PEN')
    ->setClient($client)
    ->setMtoOperGravadas(200)
    ->setMtoOperExoneradas(0)
    ->setMtoOperInafectas(0)
    ->setMtoIGV(36)
    ->setMtoImpVenta(236)
    ->setCompany(Util::getCompany());

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

$items = Util::generator($item1, 14);

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON CIEN CON 00/100 SOLES');

$note->setDetails($items)
    ->setLegends([$legend]);

$pdf = Util::getPdf($note);

Util::showPdf($pdf, 'nota-credito.pdf');