<?php


use Greenter\Model\Client\Client;
use Greenter\Model\Retention\Exchange;
use Greenter\Model\Retention\Payment;
use Greenter\Model\Retention\Retention;
use Greenter\Model\Retention\RetentionDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$client = new Client();
$client->setTipoDoc('6')
    ->setNumDoc('20000000001')
    ->setRznSocial('EMPRESA 1');

$retention = new Retention();
$retention
    ->setSerie('R001')
    ->setCorrelativo('123')
    ->setFechaEmision(new \DateTime())
    ->setCompany(Util::getCompany())
    ->setProveedor($client)
    ->setObservacion('NOTA /><!-- HI -->')
    ->setImpRetenido(10)
    ->setImpPagado(210)
    ->setRegimen('01')
    ->setTasa(3);

$pay = new Payment();
$pay->setMoneda('PEN')
    ->setFecha(new \DateTime())
    ->setImporte(100);

$cambio = new Exchange();
$cambio->setFecha(new \DateTime())
    ->setFactor(1)
    ->setMonedaObj('PEN')
    ->setMonedaRef('PEN');

$detail = new RetentionDetail();
$detail->setTipoDoc('01')
    ->setNumDoc('F001-1')
    ->setFechaEmision(new \DateTime())
    ->setFechaRetencion(new \DateTime())
    ->setMoneda('PEN')
    ->setImpTotal(200)
    ->setImpPagar(200)
    ->setImpRetenido(5)
    ->setPagos([$pay])
    ->setTipoCambio($cambio);

$retention->setDetails([$detail]);

// Envio a SUNAT.
$see = Util::getSee(SunatEndpoints::RETENCION_BETA);

$res = $see->send($retention);
Util::writeXml($retention, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\BillResult*/
    $cdr = $res->getCdrResponse();
    Util::writeCdr($retention, $res->getCdrZip());

    echo '<h2>Respuesta SUNAT:</h2><br>';
    echo '<b>ID:</b> ' . $cdr->getId().'<br>';
    echo '<b>CODE:</b> ' . $cdr->getCode().'<br>';
    echo '<b>DESCRIPTION:</b> ' . $cdr->getDescription().'<br>';
} else {
    var_dump($res->getError());
}
