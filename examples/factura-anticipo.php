<?php

declare(strict_types=1);

use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Charge;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
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
    ->setFechaEmision(new DateTime())
    ->setFormaPago(new FormaPagoContado())
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
    ->setMtoOperGravadas(100) // Sumatoria de Valor Venta (detalles) menos descuentos globales (anticipo): 200 - 100
    ->setMtoIGV(18)
    ->setValorVenta(200) // sumatoria de valor venta (detalle)
    ->setTotalImpuestos(18)
    ->setSubTotal(236) // ValorVenta + (sumatoria de valor venta detalle) * 18% (IGV)
    ->setMtoImpVenta(136) // subTotal - Anticipos: 236 - 100
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

/**@var $res BillResult*/
$cdr = $res->getCdrResponse();

$util->writeCdr($invoice, $res->getCdrZip());
$util->showResponse($invoice, $cdr);