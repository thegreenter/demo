<?php

$items = [
    [
        'file' => 'examples/factura.php',
        'title' => 'Factura',
        'description' => 'Factura con item gravado y exonerado.'
    ],
    [
        'file' => 'examples/factura-credito.php',
        'title' => 'Factura - Pago Crédito',
        'description' => 'Factura con forma de pago Crédito.',
        'tag' => 'Nuevo'
    ],
    [
        'file' => 'examples/factura-beatose.php',
        'title' => 'Factura 2 <img alt="beatOSE" style="height: 18px" src="https://raw.githubusercontent.com/thegreenter/beatose/master/public/beat-ose.png"/>',
        'description' => 'Factura con observaciones enviada a <a href="https://github.com/thegreenter/beatose" target="_blank" style="color: blue">beatOSE</a>'
    ],
    [
        'file' => 'examples/factura-compleja.php',
        'title' => 'Factura 3',
        'description' => 'Factura con items gravado, exonerado, inafecto y gratuitos.'
    ],
    [
        'file' => 'examples/factura-ivap.php',
        'title' => 'Factura IVAP',
    ],
    [
        'file' => 'examples/factura-isc.php',
        'title' => 'Factura ISC',
    ],
    [
        'file' => 'examples/factura-detraccion.php',
        'title' => 'Factura con Detracción',
    ],
    [
        'file' => 'examples/factura-contingencia.php',
        'title' => 'Factura',
        'tag' => 'CONTINGENCIA',
        'description' => 'Factura física emitida en situación de contingencia',
    ],
    [
        'file' => 'examples/factura-inafecta.php',
        'title' => 'Factura Inafecta',
    ],
    [
        'file' => 'examples/factura-exonerado.php',
        'title' => 'Factura Exonerado',
    ],
    [
        'file' => 'examples/factura-gratuita.php',
        'title' => 'Factura Gratuita',
    ],
    [
        'file' => 'examples/factura-percepcion.php',
        'title' => 'Factura con Percepción',
    ],
    [
        'file' => 'examples/factura-retencion.php',
        'title' => 'Factura con Retencion',
    ],
    [
        'file' => 'examples/factura-exportacion.php',
        'title' => 'Factura Exportación',
    ],
    [
        'file' => 'examples/factura-anticipo.php',
        'title' => 'Factura con Anticipos',
    ],
    [
        'file' => 'examples/factura-icbper.php',
        'title' => 'Factura ICBPER',
        'description' => 'Factura con Impuesto a la bolsa de plástico',
    ],
    [
        'file' => 'examples/factura-icbper-gratuita.php',
        'title' => 'Factura ICBPER gratuita',
        'description' => 'Factura con bolsa de plástico gratuita + impuesto ICBPER',
    ],
    [
        'file' => 'examples/factura-rel-guia-remision.php',
        'title' => 'Factura con guía remisión',
        'description' => 'Factura relacionada a una guía de remisión',
    ],
    [
        'file' => 'examples/boleta.php',
        'title' => 'Boleta de Venta',
    ],
    [
        'file' => 'examples/boleta-icbper.php',
        'title' => 'Boleta ICBPER',
        'description' => 'Boleta con Impuesto a la bolsa de plástico',
    ],
    [
        'file' => 'examples/factura-descuento-global.php',
        'title' => 'Factura con Descuento Global'
    ],
    [
        'file' => 'examples/factura-descuento-linea.php',
        'title' => 'Factura con Descuento linea'
    ],
    [
        'file' => 'examples/factura-descuento-nobase.php',
        'title' => 'Factura con Descuentos',
        'description' => 'Factura con descuento global y lineal que no afecta la base imponible',
    ],
    [
        'file' => 'examples/nota-credito.php',
        'title' => 'Nota de Crédito',
        'description' => 'Nota de crédito de una Factura'
    ],
    [
        'file' => 'examples/nota-credito-boleta.php',
        'title' => 'Nota de Crédito',
        'description' => 'Nota de crédito de una Boleta de venta'
    ],
    [
        'file' => 'examples/nota-debito.php',
        'title' => 'Nota de Débito'
    ],
    [
        'file' => 'examples/resumen.php',
        'title' => 'Resumen Diario'
    ],
    [
        'file' => 'examples/resumen-contingencia.php',
        'title' => 'Resumen Diario',
        'tag' => 'CONTINGENCIA',
    ],
    [
        'file' => 'examples/resumen-usd.php',
        'title' => 'Resumen Diario (en Dólares)',
    ],
    [
        'file' => 'examples/comunicacion-baja.php',
        'title' => 'Comunicación de Baja',
    ],
    [
        'file' => 'examples/guia-remision.php',
        'title' => 'Guía de Remisión (v2)',
    ],
    [
        'file' => 'examples/guia-transportePrivado.php',
        'title' => 'Guía con transporte privado (v2)',
    ],
    [
        'file' => 'examples/guia-transporteM1L.php',
        'title' => 'Guía con transporte M1 y L (v2)',
    ],
    [
        'file' => 'examples/guia-extra-atributos.php',
        'title' => 'Guía con atributos (v2)',
    ],
    [
        'file' => 'examples/percepcion.php',
        'title' => 'Comprobante de Percepción',
    ],
    [
        'file' => 'examples/retencion.php',
        'title' => 'Comprobante de Retención',
    ],
    [
        'file' => 'examples/reversion.php',
        'title' => 'Resumen de Reversiones',
    ],
];

$pdfPaths = glob(__DIR__.'/examples/report/*.php');
$pdfPaths = array_map(function ($file) {
    $name = basename($file);
    $path = 'examples/report/' . $name;

    return ['name' => $name, 'path' => $path];
}, $pdfPaths);

header('Content-Type: application/json');

echo json_encode([
    'invoices' => $items,
    'reports'  => $pdfPaths,
]);