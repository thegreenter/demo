<?php

use Greenter\Model\Sale\Charge;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Prepayment;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();
$invoice = new Invoice();
$invoice
    ->setUblVersion('2.1')
    ->setTipoOperacion('0101')
    ->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('143')
    ->setFechaEmision(new \DateTime())
    ->setTipoMoneda('PEN')
    ->setClient($util->shared->getClient())
    ->setCompany($util->shared->getCompany())
    ->setDescuentos([(
        new Charge())
        ->setCodTipo('04')
        ->setFactor(1)
        ->setMonto(100) // anticipo
        ->setMontoBase(100)
    ])
    ->setMtoOperGravadas(100)
    ->setMtoIGV(18)
    ->setValorVenta(200)
    ->setTotalImpuestos(18)
    ->setMtoImpVenta(118)
    ->setAnticipos([
        (new Prepayment())
            ->setTipoDocRel('02') // catalog. 12
            ->setNroDocRel('F001-111')
            ->setTotal(100)
    ])
    ->setTotalAnticipos(100);

$detail = new SaleDetail();
$detail->setCodProducto('P001')
    ->setUnidad('NIU')
    ->setDescripcion('PROD 1')
    ->setCantidad(2)
    ->setMtoValorUnitario(100)
    ->setMtoValorVenta(200)
    ->setMtoBaseIgv(200)
    ->setPorcentajeIgv(18)
    ->setIgv(36)
    ->setTipAfeIgv('10')
    ->setTotalImpuestos(36)
    ->setMtoPrecioUnitario(118)
;

$invoice->setDetails([$detail])
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON OO/100 SOLES')
    ]);

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