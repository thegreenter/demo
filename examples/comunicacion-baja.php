<?php

use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$voided = $util->getVoided();
$voided->setFecGeneracion(new \DateTime('-3days'));
$voided->setFecComunicacion(new \DateTime('-1days'));

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($voided);
$util->writeXml($voided, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\SummaryResult*/
    $ticket = $res->getTicket();
    echo 'Ticket :<strong>' . $ticket .'</strong>';

    $result = $see->getStatus($ticket);
    if ($result->isSuccess()) {
        $cdr = $result->getCdrResponse();
        $util->writeCdr($voided, $result->getCdrZip());

        $util->showResponse($voided, $cdr);
    } else {
        echo $util->getErrorResponse($result->getError());
    }
} else {
    echo $util->getErrorResponse($res->getError());
}