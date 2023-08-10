<?php

declare(strict_types=1);

use Greenter\Model\Response\SummaryResult;
use Greenter\Model\Sale\Document;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Model\Summary\SummaryPerception;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

// Summary

$detiail1 = new SummaryDetail();
$detiail1->setTipoDoc('03')
    ->setSerieNro('B001-1')
    ->setEstado('3')
    ->setClienteTipo('1')
    ->setClienteNro('00000000')
    ->setTotal(129.555)
    ->setMtoOperGravadas(20)
    ->setMtoOperInafectas(24.4)
    ->setMtoOperExoneradas(50)
    ->setMtoOperExportacion(10.555)
    ->setMtoOtrosCargos(21)
    ->setMtoIGV(3.6);

$detiail2 = new SummaryDetail();
$detiail2->setTipoDoc('07')
    ->setSerieNro('B001-4')
    ->setDocReferencia((new Document())
        ->setTipoDoc('03')
        ->setNroDoc('0001-122'))
    ->setEstado('1')
    ->setClienteTipo('1')
    ->setClienteNro('00000000')
    ->setTotal(200)
    ->setMtoOperGravadas(40)
    ->setMtoOperExoneradas(30)
    ->setMtoOperInafectas(120)
    ->setMtoIGV(7.2)
    ->setMtoISC(2.8);

$detiail3 = new SummaryDetail();
$detiail3->setTipoDoc('03')
    ->setSerieNro('B001-2')
    ->setEstado('1')
    ->setClienteTipo('1')
    ->setClienteNro('00000000')
    ->setPercepcion((new SummaryPerception())
        ->setCodReg('01')
        ->setTasa(2.00)
        ->setMtoBase(119.00)
        ->setMto(2.38)
        ->setMtoTotal(121.38))
    ->setTotal(119)
    ->setMtoOperGravadas(20)
    ->setMtoOperInafectas(24.4)
    ->setMtoOperExoneradas(50)
    ->setMtoOtrosCargos(21)
    ->setMtoIGV(3.6);

$sum = new Summary();
// Fecha Generacion menor que Fecha Resumen
$sum->setFecGeneracion(new DateTime('-3days'))
    ->setFecResumen(new DateTime('-1days'))
    ->setCorrelativo('001')
    ->setCompany($util->shared->getCompany())
    ->setDetails([$detiail1, $detiail2, $detiail3]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::FE_BETA);

$res = $see->send($sum);
$util->writeXml($sum, $see->getFactory()->getLastXml());

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
$util->writeCdr($sum, $res->getCdrZip());

$util->showResponse($sum, $cdr);
