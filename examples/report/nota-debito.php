<?php

use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;

require __DIR__ . '/../../vendor/autoload.php';

$util = Util::getInstance();

$note = new Note();
$note
    ->setTipDocAfectado('01')
    ->setNumDocfectado('F001-111')
    ->setCodMotivo('02')
    ->setDesMotivo('AUMENTO EN EL VALOR')
    ->setTipoDoc('08')
    ->setSerie('FF01')
    ->setFechaEmision(new DateTime())
    ->setCorrelativo('123')
    ->setTipoMoneda('PEN')
    ->setClient($util->shared->getClient())
    ->setMtoOperGravadas(200)
    ->setMtoOperExoneradas(0)
    ->setMtoOperInafectas(0)
    ->setMtoIGV(36)
    ->setMtoImpVenta(236)
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

$items = $util->generator($item1, 6);

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON CIEN CON 00/100 SOLES');

$note->setDetails($items)
    ->setLegends([$legend]);

try {
    $pdf = $util->getPdf($note);
    $util->showPdf($pdf, $note->getName().'.pdf');
} catch (Exception $e) {
    var_dump($e);
}