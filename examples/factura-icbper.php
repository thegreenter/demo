<?php

use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$invoice = new Invoice();
$invoice
    ->setUblVersion('2.1')
    ->setTipoOperacion('0101')
    ->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('124')
    ->setCompany($util->shared->getCompany())
    ->setFechaEmision(new DateTime())
    ->setTipoMoneda('PEN')
    ->setClient($util->shared->getClient())
    ->setMtoOperGravadas(200.20)
    ->setMtoIGV(36.24)
    ->setIcbper(0.40)
    ->setTotalImpuestos(36.64)
    ->setMtoImpVenta(236.64);

$detail = new SaleDetail();
$detail
    ->setCodProducto('P001')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PRODUCTO 1')
    ->setMtoBaseIgv(200.00)
    ->setPorcentajeIgv(18.0)
    ->setIgv(36)
    ->setTotalImpuestos(36)
    ->setTipAfeIgv('10')
    ->setMtoValorVenta(200)
    ->setMtoValorUnitario(100)
    ->setMtoPrecioUnitario(118);

$detailBolsa = new SaleDetail();
$detailBolsa
    ->setCodProducto('P002')
    ->setUnidad('NIU')
    ->setCantidad(4)
    ->setDescripcion('BOLSA DE PLASTICO')
    ->setMtoValorUnitario(0.05)
    ->setMtoPrecioUnitario(0.059)
    ->setMtoValorVenta(0.20)
    ->setTipAfeIgv('10')
    ->setMtoBaseIgv(0.20)
    ->setPorcentajeIgv(18.0)
    ->setIgv(0.24)
    ->setTotalImpuestos(0.64)
    ->setIcbper(0.40) // (cantidad)*(factor ICBPER)
    ->setFactorIcbper(0.10)
;

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 64/100 SOLES');

$invoice->setDetails([$detail, $detailBolsa])
    ->setLegends([$legend]);


// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($invoice);
$util->writeXml($invoice, $see->getFactory()->getLastXml());

if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());

    exit();
}

/**@var $res \Greenter\Model\Response\BillResult*/
$cdr = $res->getCdrResponse();
$util->writeCdr($invoice, $res->getCdrZip());

$util->showResponse($invoice, $cdr);
