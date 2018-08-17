<?php

use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$reversion = $util->getReversion();

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::RETENCION_BETA);

$res = $see->send($reversion);
$util->writeXml($reversion, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\SummaryResult*/
    $ticket = $res->getTicket();
    echo 'Ticket :<strong>' . $ticket .'</strong>';

    $result = $see->getStatus($ticket);
    if ($result->isSuccess() && in_array($result->getCode(), ['0', '99'])) {
        $cdr = $result->getCdrResponse();
        $util->writeCdr($reversion, $result->getCdrZip());

        echo $util->getResponseFromCdr($cdr);
    } else {
        var_dump($result->getError());
    }
} else {
    var_dump($res->getError());
}