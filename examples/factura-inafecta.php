<?php

use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require dirname(__DIR__).'/vendor/autoload.php';

$util = Util::getInstance();

$invoice = new Invoice();
$invoice
    ->setUblVersion('2.1')
    ->setTipoOperacion('0101')
    ->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('124')
    ->setFechaEmision(new \DateTime())
    ->setTipoMoneda('PEN')
    ->setCompany($util->shared->getCompany())
    ->setClient($util->shared->getClient())
    ->setMtoOperInafectas(100)
    ->setMtoIGV(0)
    ->setTotalImpuestos(0)
    ->setValorVenta(100)
    ->setMtoImpVenta(100);

$item = new SaleDetail();
$item->setCodProducto('P001')
    ->setUnidad('KG')
    ->setDescripcion('PROD 1')
    ->setCantidad(2)
    ->setMtoValorUnitario(50)
    ->setMtoValorVenta(100)
    ->setMtoBaseIgv(100)
    ->setPorcentajeIgv(0)
    ->setIgv(0)
    ->setTipAfeIgv('30')
    ->setTotalImpuestos(0)
    ->setMtoPrecioUnitario(50)
;

$invoice->setDetails([$item])
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue('SON CIEN CON OO/100 SOLES')
    ]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($invoice);
$util->writeXml($invoice, $see->getFactory()->getLastXml());

if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());

    return;
}

/**@var $res \Greenter\Model\Response\BillResult*/
$cdr = $res->getCdrResponse();
$util->writeCdr($invoice, $res->getCdrZip());

$util->showResponse($invoice, $cdr);