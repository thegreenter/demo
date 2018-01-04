<?php

use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\DocumentInterface;

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

    public static function writeXml(DocumentInterface $document, $xml)
    {
        file_put_contents(__DIR__.'/data/'.$document->getName().'.xml', $xml);
    }

    public static function writeCdr(DocumentInterface $document, $zip)
    {
        file_put_contents(__DIR__.'/data/R-'.$document->getName().'.zip', $zip);
    }
}