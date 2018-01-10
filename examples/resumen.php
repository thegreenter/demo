<?php

use Greenter\Model\Sale\Document;
use Greenter\Model\Summary\SummaryDetailV2;
use Greenter\Model\Summary\SummaryV2;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$detiail1 = new SummaryDetailV2();
$detiail1->setTipoDoc('03')
    ->setSerieNro('B001-1')
    ->setEstado('3')
    ->setClienteTipo('1')
    ->setClienteNro('00000000')
    ->setTotal(100)
    ->setMtoOperGravadas(20.555)
    ->setMtoOperInafectas(24.4)
    ->setMtoOperExoneradas(50)
    ->setMtoOtrosCargos(21)
    ->setMtoIGV(3.6);

$detiail2 = new SummaryDetailV2();
$detiail2->setTipoDoc('07')
    ->setSerieNro('B001-4')
    ->setDocReferencia((new Document())
        ->setTipoDoc('03')
        ->setNroDoc('0001-122'))
    ->setEstado('1')
    ->setClienteTipo('1')
    ->setClienteNro('00000000')
    ->setTotal(200)
    ->setMtoOperGravadas(40)
    ->setMtoOperExoneradas(30)
    ->setMtoOperInafectas(120)
    ->setMtoIGV(7.2)
    ->setMtoISC(2.8);

$sum = new SummaryV2();
$sum->setFecGeneracion(new DateTime())
    ->setFecResumen(new DateTime())
    ->setCorrelativo('001')
    ->setCompany(Util::getCompany())
    ->setDetails([$detiail1, $detiail2]);

// Envio a SUNAT.
$see = Util::getSee(SunatEndpoints::FE_BETA);

$res = $see->send($sum);
Util::writeXml($sum, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\SummaryResult*/
    $ticket = $res->getTicket();

    $result = $see->getStatus($ticket);
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
