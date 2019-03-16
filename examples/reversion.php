<?php

use Greenter\Model\Voided\Reversion;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();
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
    ->setFecGeneracion(new \DateTime('-3days'))
    ->setFecComunicacion(new \DateTime('-1days'))
    ->setCompany($util->shared->getCompany())
    ->setDetails([$detial1, $detial2]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::RETENCION_BETA);

$res = $see->send($reversion);
$util->writeXml($reversion, $see->getFactory()->getLastXml());

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
$util->writeCdr($reversion, $res->getCdrZip());

$util->showResponse($reversion, $cdr);