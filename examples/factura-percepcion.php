<?php

use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SalePerception;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

// Venta
$invoice = new Invoice();
$invoice->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('125')
    ->setFechaEmision(new DateTime())
    ->setTipoMoneda('PEN')
    ->setClient($util->getClient())
    ->setMtoOperGravadas(200)
    ->setMtoOperExoneradas(0)
    ->setMtoOperInafectas(0)
    ->setMtoIGV(36)
    ->setMtoImpVenta(2000.00)
    ->setCompany($util->getCompany());

$invoice->setPerception((new SalePerception())
    ->setMto(40)
    ->setCodReg('01')
    ->setMtoBase(2000)
    ->setMtoTotal(2040)
);

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

$item2 = new SaleDetail();
$item2->setCodProducto('C02')
    ->setCodProdSunat('P21')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PROD 1')
    ->setIgv(18)
    ->setTipAfeIgv('10')
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(56);

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON DOS MIL CON 00/100');
$legend2 = new Legend();
$legend2->setCode('2000')
    ->setValue('COMPROBANTE DE PERCEPCIÓN');

$invoice->setDetails([$item1, $item2])
    ->setLegends([$legend]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($invoice);
Util::writeXml($invoice, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\BillResult*/
    $cdr = $res->getCdrResponse();
    Util::writeCdr($invoice, $res->getCdrZip());

    echo $util->getResponseFromCdr($cdr);
} else {
    var_dump($res->getError());
}

