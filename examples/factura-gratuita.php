<?php

use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

// Venta
$invoice = new Invoice();
$invoice
    ->setUblVersion('2.1')
    ->setTipoOperacion('0101')
    ->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('123')
    ->setFechaEmision(new \DateTime())
    ->setTipoMoneda('PEN')
    ->setClient($util->shared->getClient())
    ->setCompany($util->shared->getCompany())
    ->setMtoOperGratuitas(200)
    ->setMtoIGV(36)
    ->setTotalImpuestos(0)
    ->setValorVenta(0)
    ->setMtoImpVenta(0);

$detail = new SaleDetail();
$detail->setCodProducto('P001')
    ->setUnidad('NIU')
    ->setDescripcion('PROD 1')
    ->setCantidad(2)
    ->setMtoValorUnitario(0)
    ->setMtoValorGratuito(100)
    ->setMtoValorVenta(0)
    ->setMtoBaseIgv(200)
    ->setPorcentajeIgv(18)
    ->setIgv(36)
    ->setTipAfeIgv('11')
    ->setTotalImpuestos(36)
    ->setMtoPrecioUnitario(0)
;

$invoice->setDetails([$detail])
    ->setLegends([
        (new Legend())
            ->setCode('1002')
            ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE')
    ]);

// Envio a SUNAT.
$see =$util->getSee(SunatEndpoints::FE_BETA);

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

