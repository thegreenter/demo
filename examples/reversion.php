<?php

use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$reversion = $util->getReversion();

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::RETENCION_BETA);

$res = $see->send($reversion);
Util::writeXml($reversion, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\SummaryResult*/
    $ticket = $res->getTicket();

    $result = $see->getStatus($ticket);
    if ($result->isSuccess()) {
        $cdr = $result->getCdrResponse();
        Util::writeCdr($reversion, $result->getCdrZip());

        echo $util->getResponseFromCdr($cdr);
    } else {
        var_dump($result->getError());
    }
} else {
    var_dump($res->getError());
}