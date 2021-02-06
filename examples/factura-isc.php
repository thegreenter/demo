<?php

declare(strict_types=1);

use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$invoice = new Invoice();
$invoice
    ->setUblVersion('2.1')
    ->setFecVencimiento(new DateTime())
    ->setTipoOperacion('0101')
    ->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('127')
    ->setFechaEmision(new DateTime())
    ->setFormaPago(new FormaPagoContado())
    ->setTipoMoneda('PEN')
    ->setCompany($util->shared->getCompany())
    ->setClient($util->shared->getClient())
    ->setMtoOperGravadas(200)
    ->setMtoIGV(42.12)
    ->setMtoBaseIsc(200) // Sumatoria MtoBaseISC detalles
    ->setMtoISC(34)
    ->setTotalImpuestos(76.12)
    ->setValorVenta(200)
    ->setSubTotal(276.12)
    ->setMtoImpVenta(276.12)
;

// Detalle con ISC
$item = new SaleDetail();
$item->setCodProducto('P001')
    ->setUnidad('NIU')
    ->setDescripcion('PROD 1')
    ->setCantidad(2)
    ->setMtoValorUnitario(100)
    ->setMtoValorVenta(200)
    ->setMtoBaseIsc(200)
    ->setTipSisIsc('01') // Catalog 08: Sistema al Valor
    ->setPorcentajeIsc(17.00) // 17%
    ->setIsc(34) // 200 * 0.17 (17%)
    ->setMtoBaseIgv(234) // ValorVenta + ISC
    ->setPorcentajeIgv(18)
    ->setIgv(42.12) // MtoBaseIGV * 18%
    ->setTipAfeIgv('10') // Catalog: 07
    ->setTotalImpuestos(76.12)
    ->setMtoPrecioUnitario(138.06) // (ValorVenta + TotalImpuestos) / Cantidad
;

$invoice->setDetails([$item])
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue('SON DOSCIENTOS SETENTA Y SEIS CON 12/100 SOLES')
    ]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

/** Si solo desea enviar un XML ya generado utilice esta función**/
//$res = $see->sendXml(get_class($invoice), $invoice->getName(), file_get_contents($ruta_XML));

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
