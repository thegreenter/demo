<?php

use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\DocumentInterface;
use Greenter\See;

final class Util
{
    public static function getCompany()
    {
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

        return $company;
    }

    /**
     * @param string $endpoint
     * @return See
     */
    public static function getSee($endpoint)
    {
        $see = new See();
        $see->setService($endpoint);
        $see->setCertificate(file_get_contents(__DIR__.'/resources/cert.pem'));
        $see->setCredentials('20000000001MODDATOS', 'moddatos');
        $see->setCachePath(__DIR__.'/cache');

        return $see;
    }

    public static function writeXml(DocumentInterface $document, $xml)
    {
        if (getenv('NO_FILES')) {
            return;
        }
        file_put_contents(__DIR__ . '/files/' .$document->getName().'.xml', $xml);
    }

    public static function writeCdr(DocumentInterface $document, $zip)
    {
        if (getenv('NO_FILES')) {
            return;
        }
        file_put_contents(__DIR__ . '/files/R-' .$document->getName().'.zip', $zip);
    }
}