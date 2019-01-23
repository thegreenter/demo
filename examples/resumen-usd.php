<?php

use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$sum = $util->getSummary();
$sum->setCorrelativo('211')
    ->setMoneda('USD')
    ->setFecGeneracion(new \DateTime('-3days'))
    ->setFecResumen(new \DateTime('-1days'));

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($sum);
$util->writeXml($sum, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\SummaryResult*/
    $ticket = $res->getTicket();
    echo 'Ticket :<strong>' . $ticket .'</strong>';

    $result = $see->getStatus($ticket);
    if ($result->isSuccess()) {
        $cdr = $result->getCdrResponse();
        $util->writeCdr($sum, $result->getCdrZip());

        $util->showResponse($sum, $cdr);
    } else {
        echo $util->getErrorResponse($result->getError());;
    }
} else {
    echo $util->getErrorResponse($res->getError());
}
