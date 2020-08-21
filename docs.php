<?php

$items = [
    [
        'file' => 'examples/factura.php',
        'title' => 'Factura',
    ],
    [
        'file' => 'examples/factura-contingencia.php',
        'title' => 'Factura',
        'tag' => 'CONTINGENCIA',
        'description' => 'Factura física emitida en situación de contingencia',
    ],
    [
        'file' => 'examples/boleta.php',
        'title' => 'Boleta de Venta',
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
        'file' => 'examples/factura-rel-guia-remision.php',
        'title' => 'Factura con guía remisión',
        'description' => 'Factura relacionada a una guía de remisión',
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
        'file' => 'examples/nota-credito.php',
        'title' => 'Nota de Crédito'
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
        'title' => 'Guía de Remisión',
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