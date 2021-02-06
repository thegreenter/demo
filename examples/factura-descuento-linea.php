<?php

declare(strict_types=1);

use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Charge;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

// Factura con descuento por linea (que afecta la base imponible).
$invoice = new Invoice();
$invoice
    ->setUblVersion('2.1')
    ->setTipoOperacion('0101') // Cat.51
    ->setTipoDoc('01')
    ->setSerie('FE01')
    ->setCorrelativo('1')
    ->setFechaEmision(new DateTime())
    ->setFormaPago(new FormaPagoContado())
    ->setTipoMoneda('PEN')
    ->setCompany($util->shared->getCompany())
    ->setClient($util->shared->getClient())
    ->setMtoOperGravadas(66) // suma de v. venta (items).
    ->setMtoIGV(11.88)
    ->setTotalImpuestos(11.88)
    ->setValorVenta(66)
    ->setSubTotal(77.88)
    ->setMtoImpVenta(77.88);

$itemDescuento = new SaleDetail();
$itemDescuento->setCodProducto('P001')
    ->setUnidad('NIU')
    ->setDescripcion('Cuadernos')
    ->setCantidad(10)
    ->setDescuentos([
        (new Charge())
            ->setCodTipo('00') // Catalog. 53 (00: Descuento que afecta la Base Imponible)
            ->setMontoBase(20) // cantidad * valor unitario
            ->setFactor(0.2) // 20% descuento
            ->setMonto(4)
    ])
    ->setMtoValorUnitario(2)
    ->setMtoValorVenta(16) // cantidad * valor unitario - descuento (que afecta la base)
    ->setMtoBaseIgv(16)
    ->setPorcentajeIgv(18)
    ->setIgv(2.88)
    ->setTipAfeIgv('10')
    ->setTotalImpuestos(2.88)
    ->setMtoPrecioUnitario(1.89); // (Valor venta + Total Impuestos) / Cantidad

$item2 = new SaleDetail();
$item2->setCodProducto('P002')
    ->setUnidad('NIU')
    ->setDescripcion('Radio')
    ->setCantidad(1)
    ->setMtoValorUnitario(50)
    ->setMtoValorVenta(50)
    ->setMtoBaseIgv(50)
    ->setPorcentajeIgv(18)
    ->setIgv(9)
    ->setTipAfeIgv('10')
    ->setTotalImpuestos(9)
    ->setMtoPrecioUnitario(59);

$invoice->setDetails([$itemDescuento, $item2])
    ->setLegends([
        (new Legend())
            ->setCode('1000')
            ->setValue('SON SETENTA Y SIETE CON 88/100 SOLES')
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
