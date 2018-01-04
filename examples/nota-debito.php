<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Note;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

// Cliente
$client = new Client();
$client->setTipoDoc('6')
    ->setNumDoc('20000000001')
    ->setRznSocial('EMPRESA 1');

$note = new Note();
$note
    ->setTipDocAfectado('01')
    ->setNumDocfectado('F001-111')
    ->setCodMotivo('02')
    ->setDesMotivo('AUMENTO EN EL VALOR')
    ->setTipoDoc('08')
    ->setSerie('FF01')
    ->setFechaEmision(new DateTime())
    ->setCorrelativo('123')
    ->setTipoMoneda('PEN')
    ->setClient($client)
    ->setMtoOperGravadas(200)
    ->setMtoOperExoneradas(0)
    ->setMtoOperInafectas(0)
    ->setMtoIGV(36)
    ->setMtoImpVenta(236)
    ->setCompany(Util::getCompany());

$detail1 = new SaleDetail();
$detail1->setCodProducto('C023')
    ->setCodUnidadMedida('NIU')
    ->setCtdUnidadItem(2)
    ->setDesItem('PROD 1')
    ->setMtoIgvItem(18)
    ->setTipAfeIgv('10')
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(56);

$detail2 = new SaleDetail();
$detail2->setCodProducto('C02')
    ->setCodUnidadMedida('NIU')
    ->setCtdUnidadItem(2)
    ->setDesItem('PROD 2')
    ->setMtoIgvItem(18)
    ->setTipAfeIgv('10')
    ->setMtoValorVenta(100)
    ->setMtoValorUnitario(50)
    ->setMtoPrecioUnitario(56);

$legend = new Legend();
$legend->setCode('1000')
    ->setValue('SON CIEN CON 00/100 SOLES');

$note->setDetails([$detail1, $detail2])
    ->setLegends([$legend]);

// Envio a SUNAT.
$see = new \Greenter\See();
$see->setService(SunatEndpoints::FE_BETA);
$see->setCertificate(file_get_contents(__DIR__.'/../resources/cert.pem'));
$see->setCredentials('20000000001MODDATOS', 'moddatos');

$res = $see->send($note);
Util::writeXml($note, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\BillResult*/
    $cdr = $res->getCdrResponse();
    Util::writeCdr($note, $res->getCdrZip());

    echo '<h2>Respuesta SUNAT:</h2><br>';
    echo '<b>ID:</b> ' . $cdr->getId().'<br>';
    echo '<b>CODE:</b> ' . $cdr->getCode().'<br>';
    echo '<b>DESCRIPTION:</b> ' . $cdr->getDescription().'<br>';
} else {
    var_dump($res->getError());
}
