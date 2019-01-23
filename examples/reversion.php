<?php

use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$reversion = $util->getReversion();
$reversion->setFecGeneracion(new \DateTime('-3days'));
$reversion->setFecComunicacion(new \DateTime('-1days'));

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::RETENCION_BETA);

$res = $see->send($reversion);
$util->writeXml($reversion, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\SummaryResult*/
    $ticket = $res->getTicket();
    echo 'Ticket :<strong>' . $ticket .'</strong>';

    $result = $see->getStatus($ticket);
    if ($result->isSuccess()) {
        $cdr = $result->getCdrResponse();
        $util->writeCdr($reversion, $result->getCdrZip());

        $util->showResponse($reversion, $cdr);
    } else {
        echo $util->getErrorResponse($result->getError());
    }
} else {
    echo $util->getErrorResponse($res->getError());
}