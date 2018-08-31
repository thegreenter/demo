<?php

use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

// Venta
$invoice = new Invoice();
$invoice
    ->setFecVencimiento(new DateTime())
    ->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('123')
    ->setFechaEmision(new DateTime())
    ->setTipoMoneda('PEN')
    ->setGuias([
        (new Document())
        ->setTipoDoc('09')
        ->setNroDoc('001-213')
    ])
    ->setClient($util->getClient())
    ->setMtoOperGravadas(200)
    ->setMtoOperExoneradas(0)
    ->setMtoOperInafectas(0)
    ->setMtoIGV(36)
    ->setMtoImpVenta(2236.43)
    ->setCompany($util->getCompany());

$item1 = new SaleDetail();
$item1->setCodProducto('C023')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PROD 1')
    ->setDescuento(1)
    ->setIgv(18)
    ->setTipAfeIgv('10')
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(56);

$item2 = new SaleDetail();
$item2->setCodProducto('C02')
    ->setCodProdSunat('P21')
    ->setUnidad('NIU')
    ->setCantidad(2)
    ->setDescripcion('PROD 1')
    ->setIgv(18)
    ->setTipAfeIgv('10')
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(56);

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON CIEN CON 00/100 SOLES');

$invoice->setDetails([$item1, $item2])
    ->setLegends([$legend]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

/** Si solo desea enviar un XML ya generado utilice esta función
$res = $see->sendForce($invoice, file_get_contents($ruta_XML));

Y a la vez si solo desea generar el XML y/o PDF (sin solicitar el CDR, útil para boletas) ejecute esta función
El hash del XML firmado siempre será el mismo si es que no modifican el documento.

try {
    $pdf = $util->getPdf($invoice);
    $util->writePdf($pdf, $tmp_invoice.'.pdf');
    $res = $see->genXML($invoice);
    $util->writeXml($invoice, $see->getFactory()->getLastXml());
} catch (Exception $e) {
    @$response_sunat['MESSAGE']	.= $e;
}
**/

$res = $see->send($invoice);
$util->writeXml($invoice, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\BillResult*/
    $cdr = $res->getCdrResponse();
    $util->writeCdr($invoice, $res->getCdrZip());

    echo $util->getResponseFromCdr($cdr);
} else {
    var_dump($res->getError());
}

