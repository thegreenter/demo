<?php

use Greenter\Model\Voided\Reversion;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$detial1 = new VoidedDetail();
$detial1->setTipoDoc('20')
    ->setSerie('R001')
    ->setCorrelativo('02132132')
    ->setDesMotivoBaja('ERROR DE SISTEMA');

$detial2 = new VoidedDetail();
$detial2->setTipoDoc('20')
    ->setSerie('R001')
    ->setCorrelativo('123')
    ->setDesMotivoBaja('ERROR DE RUC');

$reversion = new Reversion();
$reversion->setCorrelativo('001')
    ->setFecComunicacion(new DateTime())
    ->setFecGeneracion(new DateTime())
    ->setCompany(Util::getCompany())
    ->setDetails([$detial1, $detial2]);


// Envio a SUNAT.
$see = Util::getSee(SunatEndpoints::RETENCION_BETA);

$res = $see->send($reversion);
Util::writeXml($reversion, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\SummaryResult*/
    $ticket = $res->getTicket();

    $result = $see->getStatus($ticket);
    if ($result->isSuccess()) {
        $cdr = $result->getCdrResponse();
        Util::writeCdr($reversion, $result->getCdrZip());

        echo Util::getResponseFromCdr($cdr);
    } else {
        var_dump($result->getError());
    }
} else {
    var_dump($res->getError());
}