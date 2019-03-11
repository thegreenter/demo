<?php


use Greenter\Model\Retention\Exchange;
use Greenter\Model\Retention\Payment;
use Greenter\Model\Retention\Retention;
use Greenter\Model\Retention\RetentionDetail;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$retention = new Retention();
$retention
    ->setSerie('R001')
    ->setCorrelativo('123')
    ->setFechaEmision(new \DateTime())
    ->setCompany($util->shared->getCompany())
    ->setProveedor($util->shared->getClient())
    ->setObservacion('NOTA /><!-- HI -->')
    ->setImpRetenido(10)
    ->setImpPagado(200)
    ->setRegimen('01')
    ->setTasa(3);

$pay = new Payment();
$pay->setMoneda('PEN')
    ->setFecha(new \DateTime())
    ->setImporte(100);

$cambio = new Exchange();
$cambio->setFecha(new \DateTime())
    ->setFactor(1)
    ->setMonedaObj('PEN')
    ->setMonedaRef('PEN');

$detail = new RetentionDetail();
$detail->setTipoDoc('01')
    ->setNumDoc('F001-1')
    ->setFechaEmision(new \DateTime())
    ->setFechaRetencion(new \DateTime())
    ->setMoneda('PEN')
    ->setImpTotal(210)
    ->setImpPagar(200)
    ->setImpRetenido(10)
    ->setPagos([$pay])
    ->setTipoCambio($cambio);

$retention->setDetails([$detail]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::RETENCION_BETA);

$res = $see->send($retention);
$util->writeXml($retention, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\BillResult*/
    $cdr = $res->getCdrResponse();
    $util->writeCdr($retention, $res->getCdrZip());

    $util->showResponse($retention, $cdr);
} else {
    echo $util->getErrorResponse($res->getError());
}
