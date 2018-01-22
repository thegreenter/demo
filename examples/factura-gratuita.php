<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

// Cliente
$client = new Client();
$client->setTipoDoc('6')
    ->setNumDoc('20000000001')
    ->setRznSocial('EMPRESA 1');

// Venta
$invoice = new Invoice();
$invoice
    ->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('124')
    ->setFechaEmision(new DateTime())
    ->setTipoMoneda('PEN')
    ->setClient($client)
    ->setMtoOperGravadas(0)
    ->setMtoOperExoneradas(0)
    ->setMtoOperInafectas(0)
    ->setMtoIGV(0)
    ->setMtoImpVenta(0)
    ->setCompany(Util::getCompany());

$item = new SaleDetail();
$item->setCodProducto('C023')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PROD REGALO')
    ->setIgv(0)
    ->setTipAfeIgv('11')
    ->setMtoValorVenta(0)
    ->setMtoValorUnitario(0)
    ->setMtoValorGratuito(20)
    ->setMtoPrecioUnitario(0);

$invoice->setMtoOperGratuitas(40.00);

$legend = new Legend();
$legend->setCode('1002')
    ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE');

$invoice->setDetails([$item])
    ->setLegends([$legend]);

// Envio a SUNAT.
$see = Util::getSee(SunatEndpoints::FE_BETA);

$res = $see->send($invoice);
Util::writeXml($invoice, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\BillResult*/
    $cdr = $res->getCdrResponse();
    Util::writeCdr($invoice, $res->getCdrZip());

    echo Util::getResponseFromCdr($cdr);
} else {
    var_dump($res->getError());
}

