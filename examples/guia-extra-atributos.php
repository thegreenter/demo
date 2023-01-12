<?php

declare(strict_types=1);

use Greenter\Model\Client\Client;
use Greenter\Model\Despatch\AdditionalDoc;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Response\SummaryResult;
use Greenter\Model\Sale\DetailAttribute;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();;

$relDoc = (new AdditionalDoc())
    ->setTipo('49')
    ->setTipoDesc('Constancia de Depósito - IVAP (Ley 28211)')
    ->setNro('00000001');

$transp = new Transportist();
$transp->setTipoDoc('6')
    ->setNumDoc('20000000002')
    ->setRznSocial('TRANSPORTES S.A.C')
    ->setNroMtc('0001');

$envio = new Shipment();
$envio
    ->setCodTraslado('01') // Cat.20 - Venta
    ->setModTraslado('01') // Cat.18 - Transp. Publico
    ->setFecTraslado(new DateTime())
    ->setPesoTotal(12.5)
    ->setUndPesoTotal('KGM')
    ->setLlegada(new Direction('150101', 'AV LIMA'))
    ->setPartida(new Direction('150203', 'AV ITALIA'))
    ->setTransportista($transp);

$despatch = new Despatch();
$despatch->setVersion('2022')
    ->setTipoDoc('09')
    ->setSerie('T001')
    ->setCorrelativo('124')
    ->setFechaEmision(new DateTime())
    ->setCompany($util->getGRECompany())
    ->setDestinatario((new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000002')
        ->setRznSocial('EMPRESA DEST 1'))
    ->setAddDocs([$relDoc])
    ->setEnvio($envio);

$detail = new DespatchDetail();
$detail->setCantidad(2)
    ->setUnidad('ZZ')
    ->setDescripcion('PROD 1')
    ->setCodigo('PROD1')
    ->setCodProdSunat('50161509')
    ->setAtributos([
        (new DetailAttribute())
            ->setCode('7020')
            ->setName('Partida arancelaria')
            ->setValue('1701130000'),
        (new DetailAttribute())
        ->setCode('7022')
        ->setName('Indicador de bien normalizado')
        ->setValue('1')
    ]);

$despatch->setDetails([$detail]);

// Envio a SUNAT.
$api = $util->getSeeApi();
$res = $api->send($despatch);
$util->writeXml($despatch, $api->getLastXml());
if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());
    return;
}

/**@var $res SummaryResult*/
$ticket = $res->getTicket();
echo 'Ticket :<strong>' . $ticket .'</strong>';

$res = $api->getStatus($ticket);
if (!$res->isSuccess()) {
    echo $util->getErrorResponse($res->getError());
    return;
}

$cdr = $res->getCdrResponse();
$util->writeCdr($despatch, $res->getCdrZip());

$util->showResponse($despatch, $cdr);

