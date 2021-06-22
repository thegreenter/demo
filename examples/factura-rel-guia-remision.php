<?php

declare(strict_types=1);

use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$guiaRemision = (new Document())
    ->setTipoDoc('09') // Guia de Remision remitente: 09, catalogo 01
    ->setNroDoc('T001-2'); // Serie y correlativo de la guia de remision

$invoice = new Invoice();
$invoice
    ->setUblVersion('2.1')
    ->setFecVencimiento(new DateTime())
    ->setTipoOperacion('0101')
    ->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('223')
    ->setFechaEmision(new DateTime())
    ->setFormaPago(new FormaPagoContado())
    ->setTipoMoneda('PEN')
    ->setGuias([
        $guiaRemision // Incluir guia remision.
    ])
    ->setCompany($util->shared->getCompany())
    ->setClient($util->shared->getClient())
    ->setMtoOperGravadas(200)
    ->setMtoIGV(36)
    ->setTotalImpuestos(36)
    ->setValorVenta(200)
    ->setSubTotal(236)
    ->setMtoImpVenta(236);

$item = new SaleDetail();
$item->setCodProducto('P001')
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
    ->setMtoPrecioUnitario(118);

$invoice->setDetails([$item])
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue('SON TRESCIENTOS TREINTA Y SEIS CON OO/100 SOLES')
    ]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($invoice);
$util->writeXml($invoice, $see->getFactory()->getLastXml());

if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());

    exit();
}

/**@var $res BillResult */
$cdr = $res->getCdrResponse();
$util->writeCdr($invoice, $res->getCdrZip());

$util->showResponse($invoice, $cdr);
