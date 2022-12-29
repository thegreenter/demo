<?php

declare(strict_types=1);

use Greenter\Model\Client\Client;
use Greenter\Model\Despatch\AdditionalDoc;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Response\CdrResponse;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$rel = new AdditionalDoc();
$rel->setTipoDesc('Factura')
    ->setTipo('01') // Cat. 61 - Factura
    ->setNro('F001-222')
    ->setEmisor('20123456789');

$transp = new Transportist();
$transp->setTipoDoc('6')
    ->setNumDoc('20000000002')
    ->setRznSocial('TRANSPORTES S.A.C')
    ->setNroMtc('0001');

$envio = new Shipment();
$envio
    ->setCodTraslado('01') // Cat.20 - Venta
    ->setDesTraslado('VENTA')
    ->setModTraslado('01') // Cat.18 - Transp. Publico
    ->setFecTraslado(new DateTime())
    ->setPesoTotal(12.5)
    ->setUndPesoTotal('KGM')
//    ->setNumBultos(2) // Solo válido para importaciones
    ->setLlegada(new Direction('150101', 'AV LIMA'))
    ->setPartida(new Direction('150203', 'AV ITALIA'))
    ->setTransportista($transp);

$despatch = new Despatch();
$despatch->setVersion('2022')
    ->setTipoDoc('09')
    ->setSerie('T001')
    ->setCorrelativo('121')
    ->setFechaEmision(new DateTime())
    ->setCompany($util->shared->getCompany())
    ->setDestinatario((new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000002')
        ->setRznSocial('EMPRESA DEST 1'))
    ->setObservacion('NOTA GUIA')
    ->setAddDocs([$rel])
    ->setEnvio($envio);

$detail = new DespatchDetail();
$detail->setCantidad(2)
    ->setUnidad('ZZ')
    ->setDescripcion('PROD 1')
    ->setCodigo('PROD1')
    ->setCodProdSunat('10101508');

$despatch->setDetails([$detail]);

// Envio a SUNAT.
$see = $util->getSee('');

$xml = $see->getXmlSigned($despatch);
$util->writeXml($despatch, $xml);

$cdr = (new CdrResponse())
    ->setCode('-')
    ->setDescription('XML valido')
    ->setNotes([]);
$util->showResponse($despatch, $cdr);


