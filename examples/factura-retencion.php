<?php

declare(strict_types=1);

use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Charge;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SalePerception;
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
    ->setCorrelativo('168')
    ->setFechaEmision(new DateTime())
    ->setFormaPago(new FormaPagoContado())
    ->setTipoMoneda('PEN')
    ->setClient($util->shared->getClient())
    ->setCompany($util->shared->getCompany())
    ->setDescuentos([
        (new Charge())
            ->setCodTipo('62') // Catalog. 53
            ->setMontoBase(236)
            ->setFactor(0.03) // 3%
            ->setMonto(7.08)
    ])
    ->setMtoOperGravadas(200)
    ->setMtoIGV(36)
    ->setTotalImpuestos(36)
    ->setValorVenta(200)
    ->setSubTotal(236)
    ->setMtoImpVenta(236);

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
            ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON OO/100 SOLES'),
    ]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($invoice);
$util->writeXml($invoice, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res BillResult*/
    $cdr = $res->getCdrResponse();
    $util->writeCdr($invoice, $res->getCdrZip());

    $util->showResponse($invoice, $cdr);
} else {
    echo $util->getErrorResponse($res->getError());
}

