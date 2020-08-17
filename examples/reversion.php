<?php

declare(strict_types=1);

use Greenter\Model\Response\SummaryResult;
use Greenter\Model\Voided\Reversion;
use Greenter\Model\Voided\VoidedDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();
$item1 = new VoidedDetail();
$item1->setTipoDoc('20')
    ->setSerie('R001')
    ->setCorrelativo('122')
    ->setDesMotivoBaja('ERROR DE SISTEMA');

$item2 = new VoidedDetail();
$item2->setTipoDoc('40')
    ->setSerie('P001')
    ->setCorrelativo('111')
    ->setDesMotivoBaja('ERROR DE RUC');

$reversion = new Reversion();
$reversion->setCorrelativo('001')
    ->setFecGeneracion(new DateTime('-3days'))
    ->setFecComunicacion(new DateTime('-1days'))
    ->setCompany($util->shared->getCompany())
    ->setDetails([$item1, $item2]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::RETENCION_BETA);

$res = $see->send($reversion);
$util->writeXml($reversion, $see->getFactory()->getLastXml());

if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());
    return;
}

/**@var $res SummaryResult*/
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