<?php

declare(strict_types=1);

use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Detraction;
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
    ->setTipoOperacion('1001') // Catalogo 51: Detracción
    ->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('127')
    ->setFechaEmision(new DateTime())
    ->setFormaPago(new FormaPagoContado())
    ->setTipoMoneda('PEN')
    ->setCompany($util->shared->getCompany())
    ->setClient($util->shared->getClient())
    ->setMtoOperGravadas(800)
    ->setMtoIGV(144)
    ->setTotalImpuestos(144)
    ->setValorVenta(800)
    ->setSubTotal(944)
    ->setMtoImpVenta(944)
    ->setDetraccion(
    // MONEDA SIEMPRE EN SOLES
        (new Detraction())
            // Carnes y despojos comestibles
            ->setCodBienDetraccion('014') // catalog. 54
            // Deposito en cuenta
            ->setCodMedioPago('001') // catalog. 59
            ->setCtaBanco('0004-3342343243')
            ->setPercent(4.00)
            ->setMount(37.76)
    );

$detail = new SaleDetail();
$detail->setCodProducto('P001')
    ->setUnidad('ZZ')
    ->setDescripcion('PROD 1')
    ->setCantidad(4)
    ->setMtoValorUnitario(200)
    ->setMtoValorVenta(800)
    ->setMtoBaseIgv(800)
    ->setPorcentajeIgv(18)
    ->setIgv(144)
    ->setTipAfeIgv('10') // Catalogo 07 - Gravado
    ->setTotalImpuestos(144)
    ->setMtoPrecioUnitario(236)
;

$invoice->setDetails([$detail])
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue('SON NOVECIENTOS CUARENTA Y CUATRO CON OO/100 SOLES'),
        (new Legend())
            ->setCode('2006')
            ->setValue('Operación sujeta a detracción')
    ]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

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
