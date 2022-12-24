<?php

declare(strict_types=1);

use Greenter\Model\Client\Client;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Driver;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Vehicle;
use Greenter\Model\Response\CdrResponse;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

$util = Util::getInstance();

$vehiculoSecundario = (new Vehicle())
    ->setPlaca('ABI456');

$vehiculoPrincipal = (new Vehicle())
    ->setPlaca('ABC123')
    ->setNroCirculacion('111111')
    ->setCodEmisor('01')
    ->setNroAutorizacion('AAA')
    ->setSecundarios([$vehiculoSecundario]); // opcional

$chofer = (new Driver())
    ->setTipo('Principal')
    ->setTipoDoc('1')
    ->setNroDoc('44004400')
    ->setLicencia('0001122020')
    ->setNombres('ROBERTO')
    ->setApellidos('RODRIGUEZ VALENCIA');

$envio = new Shipment();
$envio
    ->setCodTraslado('01') // Cat.20 - Venta
    ->setModTraslado('02') // Cat.18 - Transp. Privado
    ->setFecTraslado(new DateTime())
    ->setPesoTotal(12.5)
    ->setUndPesoTotal('KGM')
    ->setVehiculo($vehiculoPrincipal)
    ->setChoferes([$chofer])
    ->setLlegada(new Direction('150101', 'AV LIMA'))
    ->setPartida(new Direction('150203', 'AV ITALIA'));

$despatch = new Despatch();
$despatch->setVersion('2022')
    ->setTipoDoc('09')
    ->setSerie('T001')
    ->setCorrelativo('123')
    ->setFechaEmision(new DateTime())
    ->setCompany($util->shared->getCompany())
    ->setDestinatario((new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000002')
        ->setRznSocial('EMPRESA DEST 1'))
    ->setEnvio($envio);

$detail = new DespatchDetail();
$detail->setCantidad(2)
    ->setUnidad('ZZ')
    ->setDescripcion('PROD 1')
    ->setCodigo('PROD1')
    ->setCodProdSunat('10101508');

$despatch->setDetails([$detail]);

// Envio a SUNAT.
$see = $util->getSee(SunatEndpoints::GUIA_BETA);

$xml = $see->getXmlSigned($despatch);
$util->writeXml($despatch, $xml);

$cdr = (new CdrResponse())
    ->setCode('-')
    ->setDescription('XML valido')
    ->setNotes([]);
$util->showResponse($despatch, $cdr);
