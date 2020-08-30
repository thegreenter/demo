<?php

declare(strict_types=1);

use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$note = new Note();
$note
    ->setUblVersion('2.1')
    ->setTipoDoc('07') // Tipo Doc: Nota de Credito
    ->setSerie('BB01') // Serie NCR
    ->setCorrelativo('123') // Correlativo NCR
    ->setFechaEmision(new DateTime())
    ->setTipDocAfectado('03') // Tipo Doc: Boleta
    ->setNumDocfectado('B001-12') // Boleta: Serie-Correlativo
    ->setCodMotivo('01') // Catalogo. 09
    ->setDesMotivo('ANULACION DE LA OPERACION')
    ->setTipoMoneda('PEN')
    ->setCompany($util->shared->getCompany())
    ->setClient($util->shared->getClient())
    ->setMtoOperGravadas(200)
    ->setMtoIGV(36)
    ->setTotalImpuestos(36)
    ->setMtoImpVenta(236)
    ;

$detail1 = new SaleDetail();
$detail1
    ->setCodProducto('C023')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PROD 1')
    ->setMtoBaseIgv(100)
    ->setPorcentajeIgv(18.00)
    ->setIgv(18)
    ->setTipAfeIgv('10')
    ->setTotalImpuestos(18)
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(56);

$detail2 = new SaleDetail();
$detail2
    ->setCodProducto('C02')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PROD 2')
    ->setMtoBaseIgv(100)
    ->setPorcentajeIgv(18.00)
    ->setIgv(18)
    ->setTipAfeIgv('10')
    ->setTotalImpuestos(18)
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(56);

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

$note->setDetails([$detail1, $detail2])
    ->setLegends([$legend]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($note);
$util->writeXml($note, $see->getFactory()->getLastXml());

if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());
    exit();
}

/**@var $res BillResult*/
$cdr = $res->getCdrResponse();
$util->writeCdr($note, $res->getCdrZip());

$util->showResponse($note, $cdr);
