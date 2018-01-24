<?php

use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\DocumentInterface;
use Greenter\Model\Response\CdrResponse;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
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
            ->setUrbanizacion('-')
            ->setDireccion('AV NUEVA LUZ');

        $company = new Company();
        $company->setRuc('20000000001')
            ->setRazonSocial('GREENTER S.A.C.')
            ->setNombreComercial('IMM CORP')
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
        $see->setCertificate(file_get_contents(__DIR__ . '/../resources/cert.pem'));
        $see->setCredentials('20000000001MODDATOS', 'moddatos');
        $see->setCachePath(__DIR__ . '/../cache');

        return $see;
    }

    public static function getResponseFromCdr(CdrResponse $cdr)
    {
        $result = <<<HTML
        <h2>Respuesta SUNAT:</h2><br>
        <b>ID:</b> {$cdr->getId()}<br>
        <b>CODE:</b>{$cdr->getCode()}<br>
        <b>DESCRIPTION:</b>{$cdr->getDescription()}<br>
HTML;

        return $result;
    }

    public static function writeXml(DocumentInterface $document, $xml)
    {
        if (getenv('GREENTER_NO_FILES')) {
            return;
        }
        file_put_contents(__DIR__ . '/../files/' . $document->getName() . '.xml', $xml);
    }

    public static function writeCdr(DocumentInterface $document, $zip)
    {
        if (getenv('GREENTER_NO_FILES')) {
            return;
        }
        file_put_contents(__DIR__ . '/../files/R-' . $document->getName() . '.zip', $zip);
    }

    public static function getPdf(DocumentInterface $document)
    {
        $html = new HtmlReport('', [
            'cache' => __DIR__ . '/../cache',
            'strict_variables' => true,
        ]);

        $render = new PdfReport($html);
        $render->setOptions( [
            'no-outline',
            'viewport-size' => '1280x1024',
            'page-width' => '21cm',
            'page-height' => '29.7cm',
            'footer-html' => __DIR__.'/../resources/footer.html',
        ]);
        $binPath = self::getPathBin();
        if (file_exists($binPath)) {
            $render->setBinPath($binPath);
        }
        $hash = self::getHash($document);
        $params = self::getParametersPdf();
        $params['user']['footer'] = '<p style="font-size:7pt">Codigo Hash: '.$hash.'</p>';

        return $render->render($document, $params);
    }

    public static function generator($item, $count)
    {
        $items = [];

        for ($i = 0; $i < $count; $i++) {
            $items[] = $item;
        }

        return $items;
    }

    public static function showPdf($content, $filename)
    {
        self::writePdf($filename, $content);
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($content));

        echo $content;
    }

    private static function getHash(DocumentInterface $document)
    {
        $see = Util::getSee('');
        $xml = $see->getXmlSigned($document);

        $hash = (new \Greenter\Report\XmlUtils())->getHashSign($xml);

        return $hash;
    }

    private static function writePdf($filename, $content)
    {
        if (getenv('GREENTER_NO_FILES')) {
            return;
        }
        file_put_contents(__DIR__ . '/../files/'.$filename, $content);
    }

    private static function getParametersPdf()
    {
        $logo = file_get_contents(__DIR__.'/../resources/logo.png');

        return [
            'system' => [
                'logo' => $logo,
            ],
            'user' => [
                'resolucion' => '212321',
                'header' => 'Telf: <b>(056) 123375</b>',
                'extras' => [
                    ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'],
                    ['name' => 'VENDEDOR', 'value' => 'GITHUB SELLER'],
                ],
            ]
        ];
    }

    public static function getPathBin()
    {
        $path = __DIR__.'/../vendor/bin/wkhtmltopdf';
        if (self::isWindows()) {
            $path .= '.exe';
        }

        return $path;
    }

    public static function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }
}