<?php

declare(strict_types=1);

use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
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
    ->setCorrelativo('129')
    ->setCompany($util->shared->getCompany())
    ->setFechaEmision(new DateTime())
    ->setFormaPago(new FormaPagoContado())
    ->setTipoMoneda('PEN')
    ->setClient($util->shared->getClient())
    ->setMtoOperGravadas(200.20)
    ->setMtoIGV(36.04)
    ->setIcbper(0.80)
    ->setTotalImpuestos(36.84)
    ->setValorVenta(200.20)
    ->setSubTotal(237.04)
    ->setRedondeo(0.04)
    ->setMtoImpVenta(237);

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
    ->setIgv(0.04)
    ->setIcbper(0.80) // (cantidad)*(factor ICBPER)
    ->setFactorIcbper(0.20)
    ->setTotalImpuestos(0.84)
;

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON DOSCIENTOS TREINTA Y SIETE CON 00/100 SOLES');

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

/**@var $res BillResult*/
$cdr = $res->getCdrResponse();
$util->writeCdr($invoice, $res->getCdrZip());

$util->showResponse($invoice, $cdr);
