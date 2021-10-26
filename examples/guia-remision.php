<?php

declare(strict_types=1);

use Greenter\Model\Client\Client;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Response\BillResult;
use Greenter\Model\Sale\Document;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$rel = new Document();
$rel->setTipoDoc('02') // Cat. 21 - Numero de Orden de Entrega
->setNroDoc('213123');

$transp = new Transportist();
$transp->setTipoDoc('6')
    ->setNumDoc('20000000002')
    ->setRznSocial('TRANSPORTES S.A.C')
    ->setPlaca('ABI-453')
    ->setChoferTipoDoc('1')
    ->setChoferDoc('40003344');

$envio = new Shipment();
$envio
    ->setCodTraslado('01') // Cat.20
    ->setDesTraslado('VENTA')
    ->setModTraslado('01') // Cat.18
    ->setFecTraslado(new DateTime())
    ->setCodPuerto('123')
    ->setIndTransbordo(false)
    ->setPesoTotal(12.5)
    ->setUndPesoTotal('KGM')
//    ->setNumBultos(2) // Solo válido para importaciones
    ->setNumContenedor('XD-2232')
    ->setLlegada(new Direction('150101', 'AV LIMA'))
    ->setPartida(new Direction('150203', 'AV ITALIA'))
    ->setTransportista($transp);

$despatch = new Despatch();
$despatch->setTipoDoc('09')
    ->setSerie('T001')
    ->setCorrelativo('123')
    ->setFechaEmision(new DateTime())
    ->setCompany($util->shared->getCompany())
    ->setDestinatario((new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000002')
        ->setRznSocial('EMPRESA (<!-- --> />) 1'))
    ->setTercero((new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000003')
        ->setRznSocial('EMPRESA SA'))
    ->setObservacion('NOTA GUIA')
    ->setRelDoc($rel)
    ->setEnvio($envio);

$detail = new DespatchDetail();
$detail->setCantidad(2)
    ->setUnidad('ZZ')
    ->setDescripcion('PROD 1')
    ->setCodigo('PROD1')
    ->setCodProdSunat('P001');

$despatch->setDetails([$detail]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::GUIA_BETA);

$res = $see->send($despatch);
$util->writeXml($despatch, $see->getFactory()->getLastXml());

if ($res->isSuccess()) {
    /**@var $res BillResult*/
    $cdr = $res->getCdrResponse();
    $util->writeCdr($despatch, $res->getCdrZip());

    $util->showResponse($despatch, $cdr);
} else {
    echo $util->getErrorResponse($res->getError());
}


