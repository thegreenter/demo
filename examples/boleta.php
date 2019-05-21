<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();
// Cliente
$client = new Client();
$client->setTipoDoc('1')
    ->setNumDoc('20203030')
    ->setRznSocial('PERSON 1');

// Venta
$invoice = new Invoice();
$invoice
    ->setUblVersion('2.1')
    ->setTipoOperacion('0101')
    ->setTipoDoc('03')
    ->setSerie('B001')
    ->setCorrelativo('1')
    ->setFechaEmision(new DateTime())
    ->setTipoMoneda('PEN')
    ->setCompany($util->shared->getCompany())
    ->setClient($client)
    ->setMtoOperGravadas(100)
    ->setMtoIGV(18)
    ->setTotalImpuestos(18)
    ->setValorVenta(100)
    ->setMtoImpVenta(118)
    ;

$item1 = new SaleDetail();
$item1->setCodProducto('C023')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PROD 1')
    ->setMtoBaseIgv(100)
    ->setPorcentajeIgv(18)
    ->setIgv(18)
    ->setTipAfeIgv('10')
    ->setTotalImpuestos(18)
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(59);

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON CIENTO DIECIOCHO CON 00/100 SOLES');

$invoice->setDetails([$item1])
    ->setLegends([$legend]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);
$res = $see->send($invoice);
$util->writeXml($invoice, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\BillResult*/
    $cdr = $res->getCdrResponse();
    $util->writeCdr($invoice, $res->getCdrZip());

    $util->showResponse($invoice, $cdr);
} else {
    echo $util->getErrorResponse($res->getError());
}

