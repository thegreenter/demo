<?php

use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$detial1 = new VoidedDetail();
$detial1->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('02132132')
    ->setDesMotivoBaja('ERROR DE SISTEMA');

$detial2 = new VoidedDetail();
$detial2->setTipoDoc('07')
    ->setSerie('FC01')
    ->setCorrelativo('222')
    ->setDesMotivoBaja('ERROR DE RUC');

$voided = new Voided();
$voided->setCorrelativo('00111')
    ->setFecComunicacion(new DateTime())
    ->setFecGeneracion(new DateTime())
    ->setCompany(Util::getCompany())
    ->setDetails([$detial1, $detial2]);

// Envio a SUNAT.
$see = new \Greenter\See();
$see->setService(SunatEndpoints::FE_BETA);
$see->setCertificate(file_get_contents(__DIR__.'/../resources/cert.pem'));
$see->setCredentials('20000000001MODDATOS', 'moddatos');

$res = $see->send($voided);
Util::writeXml($voided, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\SummaryResult*/
    $ticket = $res->getTicket();

    $status = new \Greenter\Ws\Services\ExtService();
    $client = new Greenter\Ws\Services\SoapClient();
    $client->setCredentials('20000000001MODDATOS', 'moddatos');
    $client->setService(SunatEndpoints::FE_BETA);
    $status->setClient($client);
    $result = $status->getStatus($ticket);
    if ($result->isSuccess()) {
        $cdr = $result->getCdrResponse();
        Util::writeCdr($voided, $result->getCdrZip());

        echo '<h2>Respuesta SUNAT:</h2><br>';
        echo '<b>ID:</b> ' . $cdr->getId().'<br>';
        echo '<b>CODE:</b> ' . $cdr->getCode().'<br>';
        echo '<b>DESCRIPTION:</b> ' . $cdr->getDescription().'<br>';
    } else {
        var_dump($result->getError());
    }
} else {
    var_dump($res->getError());
}