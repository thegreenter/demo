<?php

use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$detiail1 = new SummaryDetail();
$detiail1->setTipoDoc('03')
    ->setSerie('B001')
    ->setDocInicio('1')
    ->setDocFin('4')
    ->setTotal(100)
    ->setMtoOperGravadas(20.555)
    ->setMtoOperInafectas(24.4)
    ->setMtoOperExoneradas(50)
    ->setMtoOtrosCargos(21)
    ->setMtoIGV(3.6);

$detiail2 = new SummaryDetail();
$detiail2->setTipoDoc('07')
    ->setSerie('BB01')
    ->setDocInicio('4')
    ->setDocFin('8')
    ->setTotal(200)
    ->setMtoOperGravadas(40)
    ->setMtoOperExoneradas(30)
    ->setMtoOperInafectas(120)
    ->setMtoIGV(7.2)
    ->setMtoISC(2.8);

$sum = new Summary();
$sum->setFecGeneracion(new DateTime())
    ->setFecResumen(new DateTime())
    ->setCorrelativo('001')
    ->setCompany(Util::getCompany())
    ->setDetails([$detiail1, $detiail2]);

// Envio a SUNAT.
$see = new \Greenter\See();
$see->setService(SunatEndpoints::FE_BETA);
$see->setCertificate(file_get_contents(__DIR__.'/../resources/cert.pem'));
$see->setCredentials('20000000001MODDATOS', 'moddatos');

$res = $see->send($sum);
Util::writeXml($sum, $see->getFactory()->getLastXml());

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
        Util::writeCdr($sum, $result->getCdrZip());

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
