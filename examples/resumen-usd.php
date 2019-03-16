<?php

use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$detiail = new SummaryDetail();
$detiail->setTipoDoc('03')
    ->setSerieNro('B001-1')
    ->setEstado('3')
    ->setClienteTipo('1')
    ->setClienteNro('00000000')
    ->setTotal(100)
    ->setMtoOperGravadas(20.555)
    ->setMtoOperInafectas(24.4)
    ->setMtoOperExoneradas(50)
    ->setMtoOperExportacion(10)
    ->setMtoOtrosCargos(21)
    ->setMtoIGV(3.6);

$sum = new Summary();
$sum->setFecGeneracion(new \DateTime('-3days'))
    ->setFecResumen(new \DateTime('-1days'))
    ->setCorrelativo('211')
    ->setMoneda('USD')
    ->setCompany($util->shared->getCompany())
    ->setDetails([$detiail]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($sum);
$util->writeXml($sum, $see->getFactory()->getLastXml());

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
$util->writeCdr($sum, $res->getCdrZip());

$util->showResponse($sum, $cdr);