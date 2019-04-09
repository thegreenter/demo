<?php

use Greenter\Model\Voided\Voided;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$detial1 = new VoidedDetail();
$detial1->setTipoDoc('01')
    ->setSerie('F001')
    ->setCorrelativo('02132132')
    ->setDesMotivoBaja('ERROR EN CÁLCULOS');

$detial2 = new VoidedDetail();
$detial2->setTipoDoc('07')
    ->setSerie('FC01')
    ->setCorrelativo('222')
    ->setDesMotivoBaja('ERROR DE RUC');

$voided = new Voided();
$voided->setCorrelativo('00111')
    ->setFecGeneracion(new \DateTime('-3days'))
    ->setFecComunicacion(new \DateTime('-1days'))
    ->setCompany($util->shared->getCompany())
    ->setDetails([$detial1, $detial2]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($voided);
$util->writeXml($voided, $see->getFactory()->getLastXml());

if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());
    return;
}

/**@var $res \Greenter\Model\Response\SummaryResult*/
$ticket = $res->getTicket();
echo 'Ticket :<strong>' . $ticket .'</strong>';

$res = $see->getStatus($ticket);
if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());
    return;
}

$cdr = $res->getCdrResponse();
$util->writeCdr($voided, $res->getCdrZip());

$util->showResponse($voided, $cdr);