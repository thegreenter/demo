<?php

use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Despatch\Despatch;
use Greenter\Model\Despatch\DespatchDetail;
use Greenter\Model\Despatch\Direction;
use Greenter\Model\Despatch\Shipment;
use Greenter\Model\Despatch\Transportist;
use Greenter\Model\Sale\Document;
use Greenter\Ws\Services\SunatEndpoints;

require __DIR__ . '/../vendor/autoload.php';

// Emisor
$address = new Address();
$address->setUbigueo('150101')
    ->setDepartamento('LIMA')
    ->setProvincia('LIMA')
    ->setDistrito('LIMA')
    ->setUrbanizacion('NONE')
    ->setDireccion('AV LS');

$company = new Company();
$company->setRuc('20000000001')
    ->setRazonSocial('EMPRESA SAC')
    ->setNombreComercial('EMPRESA')
    ->setAddress($address);

$baja = new Document();
$baja->setTipoDoc('09')
    ->setNroDoc('T001-00001');

$rel = new Document();
$rel->setTipoDoc('02') // Tipo: Numero de Orden de Entrega
->setNroDoc('213123');

$transp = new Transportist();
$transp->setTipoDoc('6')
    ->setNumDoc('20000000002')
    ->setRznSocial('TRANSPORTES S.A.C')
    ->setPlaca('ABI-453')
    ->setChoferTipoDoc('1')
    ->setChoferDoc('40003344');

$envio = new Shipment();
$envio->setModTraslado('01')
    ->setCodTraslado('01')
    ->setDesTraslado('VENTA')
    ->setFecTraslado(new \DateTime())
    ->setCodPuerto('123')
    ->setIndTransbordo(false)
    ->setPesoTotal(12.5)
    ->setUndPesoTotal('KGM')
    ->setNumBultos(2)
    ->setNumContenedor('XD-2232')
    ->setLlegada(new Direction('150101', 'AV LIMA'))
    ->setPartida(new Direction('150203', 'AV ITALIA'))
    ->setTransportista($transp);

$despatch = new Despatch();
$despatch->setTipoDoc('09')
    ->setSerie('T001')
    ->setCorrelativo('123')
    ->setFechaEmision(new \DateTime())
    ->setCompany($company)
    ->setDestinatario((new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000002')
        ->setRznSocial('EMPRESA (<!-- --> />) 1'))
    ->setTercero((new Client())
        ->setTipoDoc('6')
        ->setNumDoc('20000000003')
        ->setRznSocial('EMPRESA SA'))
    ->setObservacion('NOTA GUIA')
    ->setDocBaja($baja)
    ->setRelDoc($rel)
    ->setEnvio($envio);

$detail = new DespatchDetail();
$detail->setCantidad(2)
    ->setUnidad('ZZ')
    ->setDescripcion('PROD 1')
    ->setCodigo('PROD1');

$despatch->setDetails([$detail]);

// Envio a SUNAT.
$see = new \Greenter\See();
$see->setService(SunatEndpoints::GUIA_BETA);
$see->setCertificate(file_get_contents(__DIR__.'/../resources/cert.pem'));
$see->setCredentials('20000000001MODDATOS', 'moddatos');

$res = $see->send($despatch);

if ($res->isSuccess()) {
    /**@var $res \Greenter\Model\Response\BillResult*/
    $cdr = $res->getCdrResponse();

    echo '<h2>Respuesta SUNAT:</h2><br>';
    echo '<b>ID:</b> ' . $cdr->getId().'<br>';
    echo '<b>CODE:</b> ' . $cdr->getCode().'<br>';
    echo '<b>DESCRIPTION:</b> ' . $cdr->getDescription().'<br>';

    // Descomentar para guardar el xml firmado y el CDR de respuesta.
    //    file_put_contents('xml-signed.xml', $see->getFactory()->getLastXml());
    //    file_put_contents('cdr.zip', $res->getCdrZip());
} else {
    var_dump($res->getError());
}


