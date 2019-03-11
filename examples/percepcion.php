<?php

use Greenter\Model\Perception\Perception;
use Greenter\Model\Perception\PerceptionDetail;
use Greenter\Model\Retention\Exchange;
use Greenter\Model\Retention\Payment;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$perception = new Perception();
$perception
    ->setSerie('P001')
    ->setCorrelativo('123')
    ->setFechaEmision(new \DateTime())
    ->setObservacion('NOTA PRUEBA />')
    ->setCompany($util->shared->getCompany())
    ->setProveedor($util->shared->getClient())
    ->setImpPercibido(10)
    ->setImpCobrado(210)
    ->setRegimen('01')
    ->setTasa(2);

$pay = new Payment();
$pay->setMoneda('PEN')
    ->setFecha(new \DateTime())
    ->setImporte(100);

$cambio = new Exchange();
$cambio->setFecha(new \DateTime())
    ->setFactor(1)
    ->setMonedaObj('PEN')
    ->setMonedaRef('PEN');

$detail = new PerceptionDetail();
$detail->setTipoDoc('01')
    ->setNumDoc('F001-1')
    ->setFechaEmision(new \DateTime())
    ->setFechaPercepcion(new \DateTime())
    ->setMoneda('PEN')
    ->setImpTotal(200)
    ->setImpCobrar(210)
    ->setImpPercibido(10)
    ->setCobros([$pay])
    ->setTipoCambio($cambio);

$perception->setDetails([$detail]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::RETENCION_BETA);

$res = $see->send($perception);
$util->writeXml($perception, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\BillResult*/
    $cdr = $res->getCdrResponse();
    $util->writeCdr($perception, $res->getCdrZip());

    $util->showResponse($perception, $cdr);
} else {
    echo $util->getErrorResponse($res->getError());
}

