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
    ->setCorrelativo('126')
    ->setFechaEmision(new DateTime())
    ->setFormaPago(new FormaPagoContado())
    ->setTipoMoneda('PEN')
    ->setCompany($util->shared->getCompany())
    ->setClient($util->shared->getClient())
    ->setMtoOperGravadas(200)
    ->setMtoOperExoneradas(100)
    ->setMtoOperInafectas(200)
    ->setMtoOperGratuitas(300)
    ->setMtoIGV(36)
    ->setMtoIGVGratuitas(18)
    ->setTotalImpuestos(36) // IGV + ISC + OTH
    ->setValorVenta(500)
    ->setSubTotal(536)
    ->setMtoImpVenta(536);

// Gravado
$item1 = new SaleDetail();
$item1->setCodProducto('P001')
    ->setUnidad('NIU')
    ->setDescripcion('PROD 1')
    ->setCantidad(2)
    ->setMtoValorUnitario(100)
    ->setMtoValorVenta(200)
    ->setMtoBaseIgv(200)
    ->setPorcentajeIgv(18)
    ->setIgv(36)
    ->setTipAfeIgv('10') // Catalog 07: Gravado
    ->setTotalImpuestos(36)
    ->setMtoPrecioUnitario(118);

// Exonerado
$item2 = new SaleDetail();
$item2->setCodProducto('P002')
    ->setUnidad('KG')
    ->setDescripcion('PROD 2')
    ->setCantidad(2)
    ->setMtoValorUnitario(50)
    ->setMtoValorVenta(100)
    ->setMtoBaseIgv(100)
    ->setPorcentajeIgv(0)
    ->setIgv(0)
    ->setTipAfeIgv('20') // Catalog 07: Exonerado
    ->setTotalImpuestos(0)
    ->setMtoPrecioUnitario(50);

// Inafecto
$item3 = new SaleDetail();
$item3->setCodProducto('P003')
    ->setUnidad('NIU')
    ->setDescripcion('PROD 3')
    ->setCantidad(2)
    ->setMtoValorUnitario(100)
    ->setMtoValorVenta(200)
    ->setMtoBaseIgv(200)
    ->setPorcentajeIgv(0)
    ->setIgv(0)
    ->setTipAfeIgv('30') // Catalog 07: Inafecto
    ->setTotalImpuestos(0)
    ->setMtoPrecioUnitario(100);

// Gravado gratuito
$item4 = new SaleDetail();
$item4->setCodProducto('P004')
    ->setUnidad('NIU')
    ->setDescripcion('PROD 4')
    ->setCantidad(1)
    ->setMtoValorUnitario(0)
    ->setMtoValorGratuito(100)
    ->setMtoValorVenta(100)
    ->setMtoBaseIgv(100)
    ->setPorcentajeIgv(18)
    ->setIgv(18)
    ->setTipAfeIgv('13') // Catalog 07: Gravado - Retiro,
    ->setTotalImpuestos(18)
    ->setMtoPrecioUnitario(0)
;

// Inafecto gratuito
$item5 = new SaleDetail();
$item5->setCodProducto('P005')
    ->setUnidad('NIU')
    ->setDescripcion('PROD 5')
    ->setCantidad(2)
    ->setMtoValorUnitario(0)
    ->setMtoValorGratuito(100)
    ->setMtoValorVenta(200)
    ->setMtoBaseIgv(200)
    ->setPorcentajeIgv(0)
    ->setIgv(0)
    ->setTipAfeIgv('32') // Catalog 07: Inafecto - Retiro,
    ->setTotalImpuestos(0)
    ->setMtoPrecioUnitario(0)
;

$invoice->setDetails([$item1, $item2, $item3, $item4, $item5])
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue('SON QUINIENTOS TREINTA Y SEIS CON OO/100 SOLES'),
        (new Legend())
            ->setCode('1002')
            ->setValue('TRANSFERENCIA GRATUITA DE UN BIEN Y/O SERVICIO PRESTADO GRATUITAMENTE')
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
